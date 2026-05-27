@extends('layouts.dashboard', ['panelLabel' => __('Company Panel'), 'logoutRoute' => route('company.logout')])

@section('title', __('Edit Tour'))

@section('sidebar')
    @include('partials.company-sidebar')
@endsection

@section('content')
    <div class="page-head"><h1>{{ __('Edit Tour') }}</h1></div>

    @if ($tour->status === \App\Models\Tour::STATUS_REJECTED && $tour->rejection_reason)
        <div class="alert alert-danger">
            <strong>{{ __('This tour was rejected') }}:</strong> {{ $tour->rejection_reason }}
        </div>
    @endif

    <div class="panel" style="padding:24px; max-width:760px;">
        <form method="POST" action="{{ route('company.tours.update', $tour) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('company.tours.form', ['tour' => $tour])
        </form>
    </div>
@endsection
