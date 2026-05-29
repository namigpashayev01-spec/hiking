<a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">📊 {{ __('Dashboard') }}</a>
<a href="{{ route('admin.tours.index') }}" class="{{ request()->routeIs('admin.tours.*') ? 'active' : '' }}">🧭 {{ __('Tours') }}</a>
<a href="{{ route('admin.companies.index') }}" class="{{ request()->routeIs('admin.companies.*') ? 'active' : '' }}">🏢 {{ __('Companies') }}</a>
<a href="{{ route('admin.messages.index') }}" class="{{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
    ✉️ {{ __('Messages') }}
    @if (($unreadMessagesCount ?? 0) > 0)
        <span class="sidebar-badge">{{ $unreadMessagesCount }}</span>
    @endif
</a>
