@extends('layouts.app')

@section('title', __('Home'))

@push('head')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

@section('body')
<div class="hk-home">

    {{-- ============================ HERO ============================ --}}
    <header class="xhero">
        <div class="xhero__topo" aria-hidden="true">
            <svg viewBox="0 0 1440 800" preserveAspectRatio="xMidYMid slice" fill="none" stroke="currentColor" stroke-width="1.4">
                {{-- summit (top-right) --}}
                <g transform="rotate(-20 1190 150)">
                    <ellipse cx="1190" cy="150" rx="40"  ry="26"/>
                    <ellipse cx="1190" cy="150" rx="86"  ry="56"/>
                    <ellipse cx="1190" cy="150" rx="138" ry="90"/>
                    <ellipse cx="1190" cy="150" rx="196" ry="128"/>
                    <ellipse cx="1190" cy="150" rx="260" ry="170"/>
                    <ellipse cx="1190" cy="150" rx="330" ry="216"/>
                </g>
                {{-- valley (bottom-left) --}}
                <g transform="rotate(14 150 720)">
                    <ellipse cx="150" cy="720" rx="34"  ry="22"/>
                    <ellipse cx="150" cy="720" rx="74"  ry="48"/>
                    <ellipse cx="150" cy="720" rx="120" ry="78"/>
                    <ellipse cx="150" cy="720" rx="172" ry="112"/>
                </g>
                {{-- long contour waves --}}
                <path d="M-60 320 C 240 250, 520 410, 800 330 S 1280 250, 1520 350" opacity=".7"/>
                <path d="M-60 450 C 260 380, 540 540, 820 460 S 1300 380, 1520 480" opacity=".55"/>
                <path d="M-60 600 C 220 540, 560 680, 860 600 S 1320 520, 1520 620" opacity=".4"/>
                {{-- dashed trail with waypoints --}}
                <path d="M120 580 C 360 500, 500 660, 740 560 S 1080 470, 1340 540" stroke-dasharray="1 11" stroke-linecap="round" stroke-width="2.4" opacity=".9"/>
                <circle cx="120" cy="580" r="5" fill="currentColor" stroke="none"/>
                <circle cx="740" cy="560" r="5" fill="currentColor" stroke="none"/>
                <circle cx="1340" cy="540" r="5" fill="currentColor" stroke="none"/>
            </svg>
        </div>
        <div class="xhero__grain" aria-hidden="true"></div>

        <div class="container">
            <div class="xhero__inner">
                <span class="xhero__eyebrow">{{ __('Guided hiking tours in Azerbaijan') }}</span>

                <h1 class="xhero__title">
                    <span class="l1">{{ __('The mountains') }}</span>
                    <span class="l2">{{ __('are calling') }}</span>
                </h1>

                <p class="xhero__lead">{{ __('Discover guided hiking tours across Azerbaijan from trusted companies.') }}</p>

                <form class="xsearch" method="GET" action="{{ route('home') }}">
                    <div class="xsearch__field">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                            <circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/>
                        </svg>
                        <input type="text" name="q" value="{{ $search }}" placeholder="{{ __('Search tours...') }}">
                    </div>
                    <button type="submit">{{ __('Search') }}</button>
                </form>

                <div class="xhero__meta">
                    <div class="xstat"><b>{{ $tours->total() }}</b> <span>{{ __('tours') }}</span></div>
                    <span class="xmeta-div"></span>
                    <span class="xcoord">41.5°N · 47.8°E</span>
                </div>
            </div>
        </div>
    </header>

    {{-- ============================ TOURS ============================ --}}
    <section class="xtours">
        <div class="container">
            <div class="xtours__head">
                <div>
                    <span class="xtours__eyebrow">{{ __('Browse the collection') }}</span>
                    <h2 class="xtours__title">
                        @if ($search !== '')
                            {{ __('Search') }}: <em>“{{ $search }}”</em>
                        @else
                            {{ __('Latest Tours') }}
                        @endif
                    </h2>
                </div>
                <div class="xtours__count"><b>{{ $tours->total() }}</b> {{ __('Tours') }}</div>
            </div>

            @if ($tours->count() === 0)
                <div class="xempty">
                    <div class="xempty__icon">🧭</div>
                    <h3>{{ $search !== '' ? __('No tours match your search.') : __('No tours found.') }}</h3>
                    <p>{{ __('Discover guided hiking tours across Azerbaijan from trusted companies.') }}</p>
                </div>
            @else
                <div class="xgrid">
                    @foreach ($tours as $tour)
                        <article class="xcard" style="--i: {{ $loop->index }}">
                            <a class="xcard__media" href="{{ route('tours.show', $tour) }}">
                                @if ($tour->imageUrl())
                                    <img src="{{ $tour->imageUrl() }}" alt="{{ $tour->title }}" loading="lazy">
                                @else
                                    <div class="xcard__ph">🏔️</div>
                                @endif
                                <span class="xprice">{{ number_format($tour->price, 0) }} <small>₼</small></span>
                            </a>
                            <div class="xcard__body">
                                <div class="xcard__org">{{ $tour->user->name }}</div>
                                <h3 class="xcard__title"><a href="{{ route('tours.show', $tour) }}">{{ $tour->title }}</a></h3>
                                <p class="xcard__desc">{{ \Illuminate\Support\Str::limit($tour->description, 96) }}</p>
                                <div class="xcard__foot">
                                    <span class="xcard__per">{{ __('per person') }}</span>
                                    <a class="xcard__link" href="{{ route('tours.show', $tour) }}">
                                        {{ __('View details') }}
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M5 12h14M13 6l6 6-6 6"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{ $tours->links() }}
            @endif
        </div>
    </section>

</div>
@endsection
