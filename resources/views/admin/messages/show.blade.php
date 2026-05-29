@extends('layouts.dashboard', ['panelLabel' => __('Admin Panel'), 'logoutRoute' => route('admin.logout')])

@section('title', $message->subject)

@section('sidebar')
    @include('partials.admin-sidebar')
@endsection

@section('content')
    <div class="page-head">
        <h1 style="font-size:22px;">{{ $message->subject }}</h1>
        <a href="{{ route('admin.messages.index') }}" class="btn btn-outline btn-sm">← {{ __('Back') }}</a>
    </div>

    @include('partials.flash')

    <div class="panel" style="padding: 24px;">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;margin-bottom:22px;">
            <div>
                <div class="muted" style="font-size:12px;text-transform:uppercase;letter-spacing:.05em;">{{ __('From') }}</div>
                <div style="font-weight:600;">{{ $message->name }}</div>
            </div>
            <div>
                <div class="muted" style="font-size:12px;text-transform:uppercase;letter-spacing:.05em;">{{ __('Email') }}</div>
                <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
            </div>
            @if ($message->phone)
                <div>
                    <div class="muted" style="font-size:12px;text-transform:uppercase;letter-spacing:.05em;">{{ __('Phone') }}</div>
                    <a href="tel:{{ preg_replace('/\s+/', '', $message->phone) }}">{{ $message->phone }}</a>
                </div>
            @endif
            <div>
                <div class="muted" style="font-size:12px;text-transform:uppercase;letter-spacing:.05em;">{{ __('Received') }}</div>
                <div>{{ $message->created_at->format('d.m.Y H:i') }}</div>
            </div>
        </div>

        <hr style="border:none;border-top:1px solid var(--line);margin:0 0 22px;">

        <div class="muted" style="font-size:12px;text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px;">{{ __('Message') }}</div>
        <div style="white-space:pre-wrap;line-height:1.7;color:var(--ink);">{{ $message->message }}</div>

        <hr style="border:none;border-top:1px solid var(--line);margin:24px 0;">

        <div class="table-actions">
            <a href="mailto:{{ $message->email }}?subject=Re: {{ urlencode($message->subject) }}" class="btn btn-primary btn-sm">
                ✉️ {{ __('Reply via email') }}
            </a>
            <form method="POST" action="{{ route('admin.messages.toggleRead', $message) }}" class="inline-form">
                @csrf
                <button class="btn btn-outline btn-sm">
                    {{ $message->is_read ? __('Mark as unread') : __('Mark as read') }}
                </button>
            </form>
            <form method="POST" action="{{ route('admin.messages.destroy', $message) }}" class="inline-form"
                  onsubmit="return confirm(@json(__('Are you sure?')));">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-sm">{{ __('Delete') }}</button>
            </form>
        </div>
    </div>
@endsection
