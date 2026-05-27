@extends('layouts.app')

@section('title', __('Admin Login'))

@section('body')
    <div class="auth-wrap">
        <div class="auth-card">
            <h1>{{ __('Admin Login') }}</h1>
            <p class="sub">{{ __('Sign in to the admin panel.') }}</p>

            @include('partials.flash')

            <form method="POST" action="{{ route('admin.login') }}">
                @csrf
                <div class="form-group">
                    <label for="email">{{ __('Email') }}</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus>
                    @error('email') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="password">{{ __('Password') }}</label>
                    <input id="password" type="password" name="password" class="form-control" required>
                    @error('password') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <button type="submit" class="btn btn-primary btn-block">{{ __('Sign in') }}</button>
            </form>
        </div>
    </div>
@endsection
