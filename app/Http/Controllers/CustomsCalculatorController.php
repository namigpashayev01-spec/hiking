<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CustomsCalculatorController extends Controller
{
    public function index()
    {
        return view('calculator.index', [
            'result' => null,
            'apiError' => null,
            'input' => [],
        ]);
    }

    public function calculate(Request $request)
    {
        $data = $request->validate([
            'autoType' => ['required', 'in:0,1'],
            'engineType' => ['required', 'in:0,1,2,3,4,5'],
            'engine' => ['required', 'integer', 'min:0', 'max:999999'],
            'commerceType' => ['required', 'in:0,1'],
            'issueDate' => ['required', 'date'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999999'],
        ]);

        // DGK expects the issue date as dd.MM.yyyy.
        $payload = [
            'autoType' => (string) $data['autoType'],
            'engineType' => (string) $data['engineType'],
            'engine' => (int) $data['engine'],
            'commerceType' => (string) $data['commerceType'],
            'issueDate' => Carbon::parse($data['issueDate'])->format('d.m.Y'),
            'price' => (float) $data['price'],
        ];

        $lang = in_array(app()->getLocale(), ['az', 'en', 'ru'], true) ? app()->getLocale() : 'az';

        $result = null;
        $apiError = null;

        try {
            $response = Http::timeout(config('services.customs.timeout', 30))
                ->acceptJson()
                ->withHeaders([
                    'lang' => $lang,
                    'requestSource' => 1,
                ])
                ->post(config('services.customs.calc_auto_duty_url'), $payload);

            $body = $response->json();

            if (is_array($body) && (int) ($body['code'] ?? 0) === 200 && isset($body['data'])) {
                // Success — duty data returned.
                $result = $body['data'];
            } elseif (is_array($body) && isset($body['code'])) {
                // The API answered with its own error envelope (code + exception).
                $apiError = data_get($body, 'exception.message')
                    ?? data_get($body, 'exception')
                    ?? $this->codeMessage($body['code']);

                if (! is_string($apiError)) {
                    $apiError = $this->codeMessage($body['code']);
                }
            } else {
                // No valid JSON envelope (e.g. an HTTP 404/5xx with empty body):
                // the endpoint is unreachable or has moved.
                $apiError = __('The customs service is currently unavailable (HTTP :status). The API endpoint may have moved — verify CUSTOMS_CALC_AUTO_DUTY_URL.', [
                    'status' => $response->status(),
                ]);
            }
        } catch (\Throwable $e) {
            report($e);
            $apiError = __('Could not reach the customs service. Please check your connection or try again later.');
        }

        return view('calculator.index', [
            'result' => $result,
            'apiError' => $apiError,
            'input' => $request->all(),
        ]);
    }

    private function codeMessage(int|string|null $code): string
    {
        return match ((int) $code) {
            401 => __('Authorization data is not valid.'),
            404 => __('Data not found.'),
            400 => __('Invalid request.'),
            500 => __('Unknown error on the customs service.'),
            default => __('The customs service returned an error.'),
        };
    }
}
