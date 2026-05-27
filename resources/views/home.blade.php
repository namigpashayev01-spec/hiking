@extends('layouts.app')

@section('title', __('Home'))

@section('body')
    <section class="hero">
        <div class="container">
            <h1>{{ __('Find your next hiking adventure') }}</h1>
            <p>{{ __('Discover guided hiking tours across Azerbaijan from trusted companies.') }}</p>
            <form class="search-form" method="GET" action="{{ route('home') }}">
                <input type="text" name="q" value="{{ $search }}" placeholder="{{ __('Search tours...') }}">
                <button type="submit" class="btn btn-primary">{{ __('Search') }}</button>
            </form>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section__head">
                <h2>{{ $search !== '' ? __('Search') . ': "' . $search . '"' : __('Latest Tours') }}</h2>
                <span class="muted">{{ $tours->total() }} {{ __('Tours') }}</span>
            </div>

            @if ($tours->count() === 0)
                <div class="empty">
                    <div class="icon">🧭</div>
                    <p>{{ $search !== '' ? __('No tours match your search.') : __('No tours found.') }}</p>
                </div>
            @else
                <div class="grid">
                    @foreach ($tours as $tour)
                        <article class="card">
                            <a href="{{ route('tours.show', $tour) }}">
                                @if ($tour->imageUrl())
                                    <img src="{{ $tour->imageUrl() }}" alt="{{ $tour->title }}" class="card__img">
                                @else
                                    <div class="card__img card__img--placeholder">🏔️</div>
                                @endif
                            </a>
                            <div class="card__body">
                                <h3 class="card__title"><a href="{{ route('tours.show', $tour) }}">{{ $tour->title }}</a></h3>
                                <div class="card__company">🏢 {{ $tour->user->name }}</div>
                                <p class="card__desc">{{ \Illuminate\Support\Str::limit($tour->description, 110) }}</p>
                                <div class="card__foot">
                                    <span class="price">{{ number_format($tour->price, 0) }} ₼ <small>/ {{ __('per person') }}</small></span>
                                    <a href="{{ route('tours.show', $tour) }}" class="btn btn-outline btn-sm">{{ __('View details') }}</a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="container" style="padding:0;">
                    {{ $tours->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
