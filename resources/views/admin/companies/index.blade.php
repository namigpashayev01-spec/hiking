@extends('layouts.dashboard', ['panelLabel' => __('Admin Panel'), 'logoutRoute' => route('admin.logout')])

@section('title', __('Companies'))

@section('sidebar')
    @include('partials.admin-sidebar')
@endsection

@section('content')
    <div class="page-head"><h1>{{ __('Companies') }}</h1></div>

    <div class="tabs">
        <a href="{{ route('admin.companies.index') }}" class="{{ ! $status ? 'active' : '' }}">{{ __('All') }}</a>
        <a href="{{ route('admin.companies.index', ['status' => 'pending']) }}" class="{{ $status === 'pending' ? 'active' : '' }}">{{ __('Pending') }}</a>
        <a href="{{ route('admin.companies.index', ['status' => 'approved']) }}" class="{{ $status === 'approved' ? 'active' : '' }}">{{ __('Approved') }}</a>
        <a href="{{ route('admin.companies.index', ['status' => 'rejected']) }}" class="{{ $status === 'rejected' ? 'active' : '' }}">{{ __('Rejected') }}</a>
    </div>

    @if ($companies->count() === 0)
        <div class="empty"><div class="icon">🏢</div><p>{{ __('No companies found.') }}</p></div>
    @else
        <div class="panel">
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('Company') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Phone') }}</th>
                        <th>{{ __('Tours') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Joined') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($companies as $company)
                        <tr>
                            <td><strong>{{ $company->name }}</strong></td>
                            <td class="muted">{{ $company->email }}</td>
                            <td class="muted">{{ $company->phone ?: '—' }}</td>
                            <td>{{ $company->tours_count }}</td>
                            <td><x-status-badge :status="$company->status" /></td>
                            <td class="muted">{{ $company->created_at->format('d.m.Y') }}</td>
                            <td>
                                <div class="table-actions">
                                    @if ($company->status !== 'approved')
                                        <form method="POST" action="{{ route('admin.companies.approve', $company) }}" class="inline-form">
                                            @csrf
                                            <button class="btn btn-success btn-sm">{{ __('Approve') }}</button>
                                        </form>
                                    @endif
                                    @if ($company->status !== 'rejected')
                                        <form method="POST" action="{{ route('admin.companies.reject', $company) }}" class="inline-form" onsubmit="return confirm('{{ __('Are you sure?') }}');">
                                            @csrf
                                            <button class="btn btn-danger btn-sm">{{ __('Reject') }}</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $companies->links() }}
    @endif
@endsection
