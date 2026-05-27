@extends('layouts.dashboard', ['panelLabel' => __('Admin Panel'), 'logoutRoute' => route('admin.logout')])

@section('title', __('Tours'))

@section('sidebar')
    @include('partials.admin-sidebar')
@endsection

@section('content')
    <div class="page-head"><h1>{{ __('Tours') }}</h1></div>

    <div class="tabs">
        <a href="{{ route('admin.tours.index') }}" class="{{ ! $status ? 'active' : '' }}">{{ __('All') }}</a>
        <a href="{{ route('admin.tours.index', ['status' => 'pending']) }}" class="{{ $status === 'pending' ? 'active' : '' }}">{{ __('Pending') }}</a>
        <a href="{{ route('admin.tours.index', ['status' => 'approved']) }}" class="{{ $status === 'approved' ? 'active' : '' }}">{{ __('Approved') }}</a>
        <a href="{{ route('admin.tours.index', ['status' => 'rejected']) }}" class="{{ $status === 'rejected' ? 'active' : '' }}">{{ __('Rejected') }}</a>
    </div>

    @if ($tours->count() === 0)
        <div class="empty"><div class="icon">🧭</div><p>{{ __('No tours found.') }}</p></div>
    @else
        <div class="panel">
            <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        <th>{{ __('Title') }}</th>
                        <th>{{ __('Company') }}</th>
                        <th>{{ __('Price') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tours as $tour)
                        <tr>
                            <td>
                                @if ($tour->imageUrl())
                                    <img src="{{ $tour->imageUrl() }}" alt="" class="thumb">
                                @else
                                    <div class="thumb" style="display:flex;align-items:center;justify-content:center;background:var(--green-light);">🏔️</div>
                                @endif
                            </td>
                            <td>{{ $tour->title }}</td>
                            <td class="muted">{{ $tour->user->name }}</td>
                            <td>{{ number_format($tour->price, 0) }} ₼</td>
                            <td><x-status-badge :status="$tour->status" /></td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('admin.tours.show', $tour) }}" class="btn btn-outline btn-sm">{{ __('View') }}</a>
                                    @if ($tour->status !== 'approved')
                                        <form method="POST" action="{{ route('admin.tours.approve', $tour) }}" class="inline-form">
                                            @csrf
                                            <button class="btn btn-success btn-sm">{{ __('Approve') }}</button>
                                        </form>
                                    @endif
                                    @if ($tour->status !== 'rejected')
                                        <form method="POST" action="{{ route('admin.tours.reject', $tour) }}" class="inline-form" onsubmit="return askReason(this);">
                                            @csrf
                                            <input type="hidden" name="rejection_reason">
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

        {{ $tours->links() }}
    @endif
@endsection

@push('scripts')
<script>
    function askReason(form) {
        var reason = prompt(@json(__('Rejection reason')));
        if (reason === null) return false;
        form.rejection_reason.value = reason;
        return true;
    }
</script>
@endpush
