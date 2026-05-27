<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', __('Dashboard')) — {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('head')
</head>
<body>
    <nav class="navbar">
        <div class="navbar__inner">
            <a href="{{ route('home') }}" class="brand"><span class="logo">🏔️</span> {{ config('app.name') }}</a>
            <div class="nav-links">
                <span class="badge badge-approved">{{ $panelLabel ?? __('Dashboard') }}</span>
                <a href="{{ route('home') }}" class="nav-link">{{ __('Back to site') }}</a>
                @include('partials.lang-switch')
                <form method="POST" action="{{ $logoutRoute ?? route('company.logout') }}" class="inline-form">
                    @csrf
                    <button type="submit" class="btn btn-outline btn-sm">{{ __('Logout') }}</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="layout">
        <aside class="sidebar">
            <div class="sidebar__title">{{ $panelLabel ?? __('Menu') }}</div>
            @yield('sidebar')
        </aside>
        <main class="main">
            @include('partials.flash')
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
