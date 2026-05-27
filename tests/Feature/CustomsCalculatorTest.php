<?php

namespace Tests\Feature;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CustomsCalculatorTest extends TestCase
{
    private function validInput(): array
    {
        return [
            'autoType' => '0',
            'engineType' => '0',
            'engine' => 1300,
            'commerceType' => '0',
            'issueDate' => '2020-01-01',
            'price' => 15000,
        ];
    }

    /** The exact response shape documented in CalcAutoDuty.pdf. */
    private function documentedResponse(): array
    {
        return [
            'code' => 200,
            'data' => [
                'usdCourse' => '1.7',
                'autoDuty' => [
                    'duties' => [
                        ['code' => '20', 'name' => 'Import customs duty', 'value' => 1547],
                        ['code' => '32', 'name' => 'VAT', 'value' => 824],
                        ['code' => '30', 'name' => 'Excise tax', 'value' => 390],
                    ],
                    'total' => ['code' => '0', 'name' => 'Total customs payments', 'value' => 2886.4],
                ],
            ],
            'exception' => null,
        ];
    }

    public function test_calculator_page_loads(): void
    {
        $this->get(route('calculator.index'))
            ->assertOk()
            ->assertSee(__('Vehicle details'));
    }

    public function test_successful_calculation_renders_duties_and_total(): void
    {
        Http::fake([
            '*calcAutoDuty*' => Http::response($this->documentedResponse(), 200),
        ]);

        $response = $this->post(route('calculator.calculate'), $this->validInput());

        $response->assertOk()
            ->assertSee('Import customs duty')
            ->assertSee('1,547.00')
            ->assertSee('2,886.40')   // total
            ->assertSee('1.7');       // usd course

        // Verify the outgoing request matches the documented contract.
        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'calcAutoDuty')
                && $request->method() === 'POST'
                && $request['issueDate'] === '01.01.2020'   // reformatted to dd.MM.yyyy
                && $request['engine'] === 1300
                && $request->hasHeader('requestSource', '1');
        });
    }

    public function test_api_error_code_is_shown_to_user(): void
    {
        Http::fake([
            '*calcAutoDuty*' => Http::response(['code' => 404, 'data' => null, 'exception' => null], 200),
        ]);

        $this->withSession(['locale' => 'en'])
            ->post(route('calculator.calculate'), $this->validInput())
            ->assertOk()
            ->assertSee(__('Data not found.'));
    }

    public function test_connection_failure_is_handled_gracefully(): void
    {
        Http::fake(function () {
            throw new ConnectionException('Connection refused');
        });

        $this->withSession(['locale' => 'en'])
            ->post(route('calculator.calculate'), $this->validInput())
            ->assertOk()
            ->assertSee(__('Could not reach the customs service. Please check your connection or try again later.'));
    }

    public function test_unreachable_endpoint_shows_unavailable_message(): void
    {
        // Mirrors what the live DGK host currently returns: HTTP 404, empty body.
        Http::fake([
            '*calcAutoDuty*' => Http::response('', 404),
        ]);

        $this->post(route('calculator.calculate'), $this->validInput())
            ->assertOk()
            ->assertSee('HTTP 404');
    }

    public function test_validation_rejects_bad_input(): void
    {
        $this->post(route('calculator.calculate'), [
            'autoType' => '9',      // invalid
            'engineType' => '',     // required
            'engine' => 'abc',      // not integer
            'commerceType' => '',   // required
            'issueDate' => '',      // required
            'price' => -5,          // below min
        ])->assertSessionHasErrors(['autoType', 'engineType', 'engine', 'commerceType', 'issueDate', 'price']);
    }
}
