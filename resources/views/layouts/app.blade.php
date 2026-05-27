<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name')) — {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('head')
</head>
<body>
    <nav class="navbar">
        <div class="navbar__inner">
            <a href="{{ route('home') }}" class="brand"><span class="logo">🏔️</span> {{ config('app.name') }}</a>
            <div class="nav-links">
                <a href="{{ route('home') }}" class="nav-link">{{ __('Home') }}</a>

                @auth
                    @if (auth()->user()->isCompany())
                        <a href="{{ route('company.dashboard') }}" class="nav-link">{{ __('Dashboard') }}</a>
                        <form method="POST" action="{{ route('company.logout') }}" class="inline-form">
                            @csrf
                            <button type="submit" class="btn btn-outline btn-sm">{{ __('Logout') }}</button>
                        </form>
                    @elseif (auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="nav-link">{{ __('Admin Panel') }}</a>
                    @endif
                @else
                    <a href="{{ route('company.login') }}" class="nav-link">{{ __('Company Login') }}</a>
                    <a href="{{ route('admin.login') }}" class="nav-link">{{ __('Admin') }}</a>
                @endauth

                @include('partials.lang-switch')
            </div>
        </div>
    </nav>

    @yield('body')

    <footer class="footer">
        <div class="container row-between">
            <div>© {{ date('Y') }} {{ config('app.name') }} — {{ __('Discover guided hiking tours across Azerbaijan from trusted companies.') }}</div>
            <div><a href="{{ route('company.login') }}">{{ __('Company Login') }}</a></div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
