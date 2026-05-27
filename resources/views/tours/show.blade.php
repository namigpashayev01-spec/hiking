@extends('layouts.app')

@section('title', $tour->title)

@section('body')
    <div class="detail-hero">
        <div class="container">
            <div style="padding-top:18px;">
                <a href="{{ route('home') }}" class="muted">← {{ __('Back') }}</a>
            </div>
            <div class="detail-grid">
                <div>
                    @if ($tour->imageUrl())
                        <img src="{{ $tour->imageUrl() }}" alt="{{ $tour->title }}" class="detail-img">
                    @else
                        <div class="detail-img detail-img--placeholder">🏔️</div>
                    @endif

                    <h1 style="margin-top:22px;">{{ $tour->title }}</h1>
                    <p class="muted">{{ $tour->description }}</p>

                    <hr style="border:none;border-top:1px solid var(--line);margin:22px 0;">

                    <h3>{{ __('Details') }}</h3>
                    <div class="content-body">
                        {!! $tour->content !!}
                    </div>
                </div>

                <aside class="detail-side">
                    <div class="price">{{ number_format($tour->price, 0) }} ₼</div>
                    <div class="muted">{{ __('per person') }}</div>
                    <hr>
                    <h4>{{ __('Company') }}</h4>
                    <p style="margin:0;font-weight:600;">🏢 {{ $tour->user->name }}</p>
                    @if ($tour->user->phone)
                        <p class="muted" style="margin:6px 0 0;">📞 {{ $tour->user->phone }}</p>
                    @endif
                    <p class="muted" style="margin:6px 0 0;">✉️ {{ $tour->user->email }}</p>
                </aside>
            </div>
        </div>
    </div>

    @if ($related->count())
        <section class="section">
            <div class="container">
                <div class="section__head"><h2>{{ __('Related tours') }}</h2></div>
                <div class="grid">
                    @foreach ($related as $item)
                        <article class="card">
                            <a href="{{ route('tours.show', $item) }}">
                                @if ($item->imageUrl())
                                    <img src="{{ $item->imageUrl() }}" alt="{{ $item->title }}" class="card__img">
                                @else
                                    <div class="card__img card__img--placeholder">🏔️</div>
                                @endif
                            </a>
                            <div class="card__body">
                                <h3 class="card__title"><a href="{{ route('tours.show', $item) }}">{{ $item->title }}</a></h3>
                                <div class="card__foot">
                                    <span class="price">{{ number_format($item->price, 0) }} ₼</span>
                                    <a href="{{ route('tours.show', $item) }}" class="btn btn-outline btn-sm">{{ __('View details') }}</a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
