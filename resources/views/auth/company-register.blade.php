@extends('layouts.app')

@section('title', __('Register'))

@section('body')
    <div class="auth-wrap">
        <div class="auth-card">
            <h1>{{ __('Register your company') }}</h1>
            <p class="sub">{{ __('Start listing your hiking tours.') }}</p>

            @include('partials.flash')

            <form method="POST" action="{{ route('company.register') }}">
                @csrf
                <div class="form-group">
                    <label for="name">{{ __('Company name') }}</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-control" required autofocus>
                    @error('name') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="email">{{ __('Email') }}</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                    @error('email') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="phone">{{ __('Phone') }}</label>
                    <input id="phone" type="text" name="phone" value="{{ old('phone') }}" class="form-control">
                    @error('phone') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="password">{{ __('Password') }}</label>
                    <input id="password" type="password" name="password" class="form-control" required>
                    @error('password') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="password_confirmation">{{ __('Confirm Password') }}</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">{{ __('Create account') }}</button>
            </form>

            <div class="auth-foot">
                {{ __('Already have an account?') }} <a href="{{ route('company.login') }}">{{ __('Sign in') }}</a>
            </div>
        </div>
    </div>
@endsection
