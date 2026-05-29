@extends('layouts.dashboard', ['panelLabel' => __('Admin Panel'), 'logoutRoute' => route('admin.logout')])

@section('title', __('Messages'))

@section('sidebar')
    @include('partials.admin-sidebar')
@endsection

@section('content')
    <div class="page-head">
        <h1>{{ __('Messages') }}</h1>
        <span class="muted">{{ __('Contact form submissions from the website.') }}</span>
    </div>

    <div class="tabs">
        <a href="{{ route('admin.messages.index') }}" class="{{ ! $filter ? 'active' : '' }}">{{ __('All') }}</a>
        <a href="{{ route('admin.messages.index', ['filter' => 'unread']) }}" class="{{ $filter === 'unread' ? 'active' : '' }}">{{ __('Unread') }}</a>
        <a href="{{ route('admin.messages.index', ['filter' => 'read']) }}" class="{{ $filter === 'read' ? 'active' : '' }}">{{ __('Read') }}</a>
    </div>

    @if ($messages->count() === 0)
        <div class="empty">
            <div class="icon">✉️</div>
            <p>{{ __('No messages yet.') }}</p>
        </div>
    @else
        <div class="panel">
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('From') }}</th>
                        <th>{{ __('Subject') }}</th>
                        <th>{{ __('Received') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($messages as $msg)
                        <tr style="{{ $msg->is_read ? '' : 'font-weight: 600;' }}">
                            <td>
                                @if ($msg->is_read)
                                    <span class="badge badge-approved">{{ __('Read') }}</span>
                                @else
                                    <span class="badge badge-pending">{{ __('Unread') }}</span>
                                @endif
                            </td>
                            <td>
                                <div>{{ $msg->name }}</div>
                                <div class="muted" style="font-size:13px;font-weight:400;">{{ $msg->email }}</div>
                            </td>
                            <td>{{ \Illuminate\Support\Str::limit($msg->subject, 60) }}</td>
                            <td class="muted" style="font-weight:400;">{{ $msg->created_at->format('d.m.Y H:i') }}</td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('admin.messages.show', $msg) }}" class="btn btn-outline btn-sm">{{ __('View') }}</a>
                                    <form method="POST" action="{{ route('admin.messages.destroy', $msg) }}" class="inline-form"
                                          onsubmit="return confirm(@json(__('Are you sure?')));">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">{{ __('Delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $messages->links() }}
    @endif
@endsection
