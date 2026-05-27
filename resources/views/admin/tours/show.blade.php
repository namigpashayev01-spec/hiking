@extends('layouts.dashboard', ['panelLabel' => __('Admin Panel'), 'logoutRoute' => route('admin.logout')])

@section('title', $tour->title)

@section('sidebar')
    @include('partials.admin-sidebar')
@endsection

@section('content')
    <div class="page-head">
        <h1>{{ $tour->title }}</h1>
        <a href="{{ route('admin.tours.index') }}" class="btn btn-outline">← {{ __('Back') }}</a>
    </div>

    <div class="detail-grid" style="padding:0;">
        <div>
            @if ($tour->imageUrl())
                <img src="{{ $tour->imageUrl() }}" alt="" class="detail-img">
            @else
                <div class="detail-img detail-img--placeholder">🏔️</div>
            @endif

            <p class="muted" style="margin-top:16px;">{{ $tour->description }}</p>

            <h3>{{ __('Details') }}</h3>
            <div class="content-body">{!! $tour->content !!}</div>
        </div>

        <aside class="detail-side">
            <div class="row-between">
                <x-status-badge :status="$tour->status" />
                <span class="price">{{ number_format($tour->price, 0) }} ₼</span>
            </div>
            <hr>
            <h4>{{ __('Company') }}</h4>
            <p style="margin:0;font-weight:600;">🏢 {{ $tour->user->name }}</p>
            <p class="muted" style="margin:4px 0 0;">✉️ {{ $tour->user->email }}</p>
            @if ($tour->user->phone)
                <p class="muted" style="margin:4px 0 0;">📞 {{ $tour->user->phone }}</p>
            @endif

            @if ($tour->status === 'rejected' && $tour->rejection_reason)
                <hr>
                <div class="alert alert-danger" style="margin:0;">
                    <strong>{{ __('Rejection reason') }}:</strong> {{ $tour->rejection_reason }}
                </div>
            @endif

            <hr>
            @if ($tour->status !== 'approved')
                <form method="POST" action="{{ route('admin.tours.approve', $tour) }}" style="margin-bottom:12px;">
                    @csrf
                    <button class="btn btn-success btn-block">✅ {{ __('Approve') }}</button>
                </form>
            @endif

            @if ($tour->status !== 'rejected')
                <form method="POST" action="{{ route('admin.tours.reject', $tour) }}">
                    @csrf
                    <div class="form-group">
                        <label for="rejection_reason">{{ __('Reason (optional)') }}</label>
                        <textarea id="rejection_reason" name="rejection_reason" class="form-control" rows="2"></textarea>
                    </div>
                    <button class="btn btn-danger btn-block">{{ __('Reject') }}</button>
                </form>
            @endif
        </aside>
    </div>
@endsection
