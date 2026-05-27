@extends('layouts.dashboard', ['panelLabel' => __('Company Panel'), 'logoutRoute' => route('company.logout')])

@section('title', __('Dashboard'))

@section('sidebar')
    @include('partials.company-sidebar')
@endsection

@section('content')
    <div class="page-head">
        <h1>{{ __('Welcome back') }}, {{ auth()->user()->name }}</h1>
        @if (auth()->user()->isApproved())
            <a href="{{ route('company.tours.create') }}" class="btn btn-primary">➕ {{ __('Add Tour') }}</a>
        @endif
    </div>

    @unless (auth()->user()->isApproved())
        <div class="alert alert-warning">
            ⏳ {{ __('Your company account is awaiting admin approval. You cannot add tours yet.') }}
        </div>
    @endunless

    <div class="stats">
        <div class="stat"><div class="num">{{ $stats['total'] }}</div><div class="label">{{ __('Total tours') }}</div></div>
        <div class="stat"><div class="num">{{ $stats['approved'] }}</div><div class="label">{{ __('Approved tours') }}</div></div>
        <div class="stat"><div class="num">{{ $stats['pending'] }}</div><div class="label">{{ __('Pending tours') }}</div></div>
        <div class="stat"><div class="num">{{ $stats['rejected'] }}</div><div class="label">{{ __('Rejected tours') }}</div></div>
    </div>

    <div class="section__head"><h2>{{ __('Recent tours') }}</h2></div>

    @if ($recentTours->count() === 0)
        <div class="empty">
            <div class="icon">🧭</div>
            <p>{{ __('No tours yet.') }}</p>
            @if (auth()->user()->isApproved())
                <a href="{{ route('company.tours.create') }}" class="btn btn-primary mt-2">{{ __('Add your first tour') }}</a>
            @endif
        </div>
    @else
        <div class="panel">
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('Title') }}</th>
                        <th>{{ __('Price') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Created') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentTours as $tour)
                        <tr>
                            <td>{{ $tour->title }}</td>
                            <td>{{ number_format($tour->price, 0) }} ₼</td>
                            <td><x-status-badge :status="$tour->status" /></td>
                            <td class="muted">{{ $tour->created_at->format('d.m.Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
