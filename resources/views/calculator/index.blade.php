@extends('layouts.app')

@section('title', __('Customs Duty Calculator'))

@php
    $autoTypes = [
        '0' => __('Passenger car'),
        '1' => __('Bus'),
    ];
    $engineTypes = [
        '0' => __('Petrol'),
        '1' => __('Diesel'),
        '2' => __('Gas'),
        '3' => __('Hybrid Petrol'),
        '4' => __('Hybrid Diesel'),
        '5' => __('Electric'),
    ];
    $commerceTypes = [
        '0' => __('Regular import (duty applies)'),
        '1' => __('Free Trade Agreement country (no import duty)'),
    ];
    $val = fn ($k, $d = '') => old($k, $input[$k] ?? $d);
@endphp

@section('body')
    <section class="hero" style="padding:40px 0;">
        <div class="container">
            <h1>🚗 {{ __('Customs Duty Calculator') }}</h1>
            <p class="mb-0">{{ __('Calculate customs duties for importing a light passenger vehicle, based on State Customs Committee (DGK) data.') }}</p>
        </div>
    </section>

    <section class="section">
        <div class="container">
            @include('partials.flash')

            <div class="calc-layout">
                {{-- Form --}}
                <div class="panel" style="padding:24px;">
                    <h2 style="font-size:20px;margin-bottom:18px;">{{ __('Vehicle details') }}</h2>
                    <form method="POST" action="{{ route('calculator.calculate') }}">
                        @csrf

                        <div class="form-group">
                            <label for="autoType">{{ __('Vehicle type') }} *</label>
                            <select id="autoType" name="autoType" class="form-control" required>
                                @foreach ($autoTypes as $k => $label)
                                    <option value="{{ $k }}" @selected($val('autoType') === $k)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="engineType">{{ __('Engine type') }} *</label>
                            <select id="engineType" name="engineType" class="form-control" required>
                                @foreach ($engineTypes as $k => $label)
                                    <option value="{{ $k }}" @selected($val('engineType') === $k)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="engine">{{ __('Engine volume') }} (sm³) *</label>
                            <input id="engine" type="number" name="engine" min="0" max="999999" value="{{ $val('engine') }}" class="form-control" placeholder="1300" required>
                        </div>

                        <div class="form-group">
                            <label for="price">{{ __('Vehicle price') }} (USD) *</label>
                            <div class="input-group-prefix">
                                <span class="addon">$</span>
                                <input id="price" type="number" name="price" step="0.01" min="0" value="{{ $val('price') }}" class="form-control" placeholder="15000" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="issueDate">{{ __('Manufacture date') }} *</label>
                            <input id="issueDate" type="date" name="issueDate" value="{{ $val('issueDate') }}" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="commerceType">{{ __('Commerce type') }} *</label>
                            <select id="commerceType" name="commerceType" class="form-control" required>
                                @foreach ($commerceTypes as $k => $label)
                                    <option value="{{ $k }}" @selected($val('commerceType') === $k)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">{{ __('Calculate') }}</button>
                    </form>
                </div>

                {{-- Result --}}
                <div>
                    @if ($apiError)
                        <div class="alert alert-danger">⚠️ {{ $apiError }}</div>
                    @endif

                    @if ($result)
                        @php
                            $duties = data_get($result, 'autoDuty.duties', []);
                            $total = data_get($result, 'autoDuty.total');
                            $usdCourse = data_get($result, 'usdCourse');
                        @endphp
                        <div class="panel" style="padding:24px;">
                            <div class="row-between" style="margin-bottom:16px;">
                                <h2 style="font-size:20px;margin:0;">{{ __('Calculation result') }}</h2>
                                @if ($usdCourse)
                                    <span class="badge badge-approved">1 USD = {{ $usdCourse }} ₼</span>
                                @endif
                            </div>

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('Duty') }}</th>
                                        <th style="text-align:right;">{{ __('Amount') }} (₼)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($duties as $duty)
                                        <tr>
                                            <td>{{ data_get($duty, 'name') }}</td>
                                            <td style="text-align:right;">{{ number_format((float) data_get($duty, 'value'), 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="2" class="muted">{{ __('No duties returned.') }}</td></tr>
                                    @endforelse
                                </tbody>
                                @if ($total)
                                    <tfoot>
                                        <tr style="border-top:2px solid var(--line);">
                                            <th style="font-size:15px;">{{ data_get($total, 'name', __('Total customs payments')) }}</th>
                                            <th style="text-align:right;font-size:18px;color:var(--green-dark);">{{ number_format((float) data_get($total, 'value'), 2) }} ₼</th>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    @elseif (! $apiError)
                        <div class="empty" style="height:100%;display:flex;flex-direction:column;justify-content:center;">
                            <div class="icon">🧮</div>
                            <p>{{ __('Fill in the vehicle details and press Calculate to see the customs duties.') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <p class="muted mt-2" style="font-size:13px;">
                {{ __('Source: State Customs Committee of the Republic of Azerbaijan (e.customs.gov.az). Results are indicative.') }}
            </p>
        </div>
    </section>
@endsection
