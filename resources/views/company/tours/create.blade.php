@extends('layouts.dashboard', ['panelLabel' => __('Company Panel'), 'logoutRoute' => route('company.logout')])

@section('title', __('New Tour'))

@section('sidebar')
    @include('partials.company-sidebar')
@endsection

@section('content')
    <div class="page-head"><h1>{{ __('New Tour') }}</h1></div>

    <div class="panel" style="padding:24px; max-width:760px;">
        <form method="POST" action="{{ route('company.tours.store') }}" enctype="multipart/form-data">
            @csrf
            @include('company.tours.form', ['tour' => null])
        </form>
    </div>
@endsection
