<a href="{{ route('company.dashboard') }}" class="{{ request()->routeIs('company.dashboard') ? 'active' : '' }}">📊 {{ __('Dashboard') }}</a>
<a href="{{ route('company.tours.index') }}" class="{{ request()->routeIs('company.tours.index') ? 'active' : '' }}">🧭 {{ __('My Tours') }}</a>
@if (auth()->user()->isApproved())
    <a href="{{ route('company.tours.create') }}" class="{{ request()->routeIs('company.tours.create') ? 'active' : '' }}">➕ {{ __('Add Tour') }}</a>
@endif
