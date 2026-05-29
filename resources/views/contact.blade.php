@extends('layouts.app')

@section('title', __('Contact'))

@push('head')
<style>
    .contact-hero {
        background: linear-gradient(135deg, var(--green-dark), var(--green));
        color: #fff;
        padding: 56px 0 40px;
    }
    .contact-hero h1 { color: #fff; font-size: 34px; margin-bottom: 8px; }
    .contact-hero p { color: #d8efe2; font-size: 17px; max-width: 620px; margin: 0; }

    .contact-grid {
        display: grid;
        grid-template-columns: 1fr 1.4fr;
        gap: 28px;
        margin-top: -40px;
        position: relative;
        z-index: 2;
        margin-bottom: 50px;
    }
    @media (max-width: 860px) { .contact-grid { grid-template-columns: 1fr; } }

    .contact-info, .contact-form-wrap {
        background: var(--white);
        border: 1px solid var(--line);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 28px;
    }
    .contact-info h2 { font-size: 20px; margin-bottom: 6px; }
    .contact-info > p { color: var(--muted); margin-bottom: 20px; font-size: 14px; }

    .info-item {
        display: flex;
        gap: 14px;
        padding: 14px 0;
        border-bottom: 1px solid var(--line);
    }
    .info-item:last-child { border-bottom: none; }
    .info-icon {
        width: 40px; height: 40px;
        background: var(--green-light);
        color: var(--green-dark);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .info-label { font-size: 12px; color: var(--muted); text-transform: uppercase; letter-spacing: .05em; margin-bottom: 2px; }
    .info-value { font-weight: 600; color: var(--ink); }
    .info-value a { color: var(--ink); }
    .info-value a:hover { color: var(--green-dark); }

    .contact-form-wrap h2 { font-size: 20px; margin-bottom: 6px; }
    .contact-form-wrap > p { color: var(--muted); margin-bottom: 20px; font-size: 14px; }

    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    @media (max-width: 600px) { .form-row { grid-template-columns: 1fr; } }

    .honeypot { position: absolute; left: -10000px; width: 1px; height: 1px; overflow: hidden; }
</style>
@endpush

@section('body')
<section class="contact-hero">
    <div class="container">
        <h1>{{ __('Get in touch') }}</h1>
        <p>{{ __('Have a question about a tour or want to list your company? Send us a message and we will reply within 1–2 business days.') }}</p>
    </div>
</section>

<div class="container">
    <div class="contact-grid">
        <aside class="contact-info">
            <h2>{{ __('Contact information') }}</h2>
            <p>{{ __('Reach out directly or use the form.') }}</p>

            <div class="info-item">
                <div class="info-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                </div>
                <div>
                    <div class="info-label">{{ __('Email') }}</div>
                    <div class="info-value"><a href="mailto:{{ config('services.contact.to') }}">{{ config('services.contact.to') }}</a></div>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.37 1.9.72 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.35 1.85.59 2.81.72a2 2 0 0 1 1.72 2z"/>
                    </svg>
                </div>
                <div>
                    <div class="info-label">{{ __('Phone') }}</div>
                    <div class="info-value"><a href="tel:{{ preg_replace('/\s+/', '', config('services.contact.phone')) }}">{{ config('services.contact.phone') }}</a></div>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                </div>
                <div>
                    <div class="info-label">{{ __('Address') }}</div>
                    <div class="info-value">{{ config('services.contact.address') }}</div>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                </div>
                <div>
                    <div class="info-label">{{ __('Working hours') }}</div>
                    <div class="info-value">{{ __('Mon–Fri, 09:00 – 18:00') }}</div>
                </div>
            </div>
        </aside>

        <div class="contact-form-wrap">
            <h2>{{ __('Send a message') }}</h2>
            <p>{{ __('Fill in the form and we will get back to you.') }}</p>

            @include('partials.flash')

            <form method="POST" action="{{ route('contact.send') }}" novalidate>
                @csrf

                <div class="honeypot" aria-hidden="true">
                    <label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="name">{{ __('Full name') }}</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-control" required maxlength="120">
                        @error('name') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label for="email">{{ __('Email') }}</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" required maxlength="150">
                        @error('email') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">{{ __('Phone') }} <span class="muted" style="font-weight:400;font-size:12px;">({{ __('optional') }})</span></label>
                        <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" class="form-control" maxlength="40">
                        @error('phone') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label for="subject">{{ __('Subject') }}</label>
                        <input id="subject" type="text" name="subject" value="{{ old('subject') }}" class="form-control" required maxlength="150">
                        @error('subject') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="message">{{ __('Message') }}</label>
                    <textarea id="message" name="message" rows="6" class="form-control" required maxlength="3000">{{ old('message') }}</textarea>
                    @error('message') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-primary">{{ __('Send message') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
