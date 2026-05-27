@extends('layouts.dashboard', ['panelLabel' => __('Admin Panel'), 'logoutRoute' => route('admin.logout')])

@section('title', __('Dashboard'))

@section('sidebar')
    @include('partials.admin-sidebar')
@endsection

@section('content')
    <div class="page-head">
        <h1>{{ __('Admin Panel') }}</h1>
        <span class="muted">{{ __('Manage hiking tours and company approvals.') }}</span>
    </div>

    <div class="stats">
        <div class="stat"><div class="num">{{ $stats['tours_total'] }}</div><div class="label">{{ __('Total tours') }}</div></div>
        <div class="stat"><div class="num">{{ $stats['tours_pending'] }}</div><div class="label">{{ __('Pending tours') }}</div></div>
        <div class="stat"><div class="num">{{ $stats['companies_total'] }}</div><div class="label">{{ __('Total companies') }}</div></div>
        <div class="stat"><div class="num">{{ $stats['companies_pending'] }}</div><div class="label">{{ __('Pending companies') }}</div></div>
    </div>

    <div class="section__head">
        <h2>{{ __('Tours awaiting approval') }}</h2>
        <a href="{{ route('admin.tours.index', ['status' => 'pending']) }}" class="muted">{{ __('All') }} →</a>
    </div>

    @if ($pendingTours->count() === 0)
        <div class="empty"><div class="icon">✅</div><p>{{ __('No tours to review right now.') }}</p></div>
    @else
        <div class="panel">
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('Title') }}</th>
                        <th>{{ __('Company') }}</th>
                        <th>{{ __('Price') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pendingTours as $tour)
                        <tr>
                            <td>{{ $tour->title }}</td>
                            <td class="muted">{{ $tour->user->name }}</td>
                            <td>{{ number_format($tour->price, 0) }} ₼</td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('admin.tours.show', $tour) }}" class="btn btn-outline btn-sm">{{ __('View') }}</a>
                                    <form method="POST" action="{{ route('admin.tours.approve', $tour) }}" class="inline-form">
                                        @csrf
                                        <button class="btn btn-success btn-sm">{{ __('Approve') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
