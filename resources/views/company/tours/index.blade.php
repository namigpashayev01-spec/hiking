@extends('layouts.dashboard', ['panelLabel' => __('Company Panel'), 'logoutRoute' => route('company.logout')])

@section('title', __('My Tours'))

@section('sidebar')
    @include('partials.company-sidebar')
@endsection

@section('content')
    <div class="page-head">
        <h1>{{ __('My Tours') }}</h1>
        @if (auth()->user()->isApproved())
            <a href="{{ route('company.tours.create') }}" class="btn btn-primary">➕ {{ __('Add Tour') }}</a>
        @endif
    </div>

    @unless (auth()->user()->isApproved())
        <div class="alert alert-warning">⏳ {{ __('Your company account is awaiting admin approval. You cannot add tours yet.') }}</div>
    @endunless

    @if ($tours->count() === 0)
        <div class="empty">
            <div class="icon">🧭</div>
            <p>{{ __('No tours yet.') }}</p>
            @if (auth()->user()->isApproved())
                <a href="{{ route('company.tours.create') }}" class="btn btn-primary mt-2">{{ __('Add your first tour') }}</a>
            @endif
        </div>
    @else
        <div class="panel">
            <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        <th>{{ __('Title') }}</th>
                        <th>{{ __('Price') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Created') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tours as $tour)
                        <tr>
                            <td>
                                @if ($tour->imageUrl())
                                    <img src="{{ $tour->imageUrl() }}" alt="" class="thumb">
                                @else
                                    <div class="thumb" style="display:flex;align-items:center;justify-content:center;background:var(--green-light);">🏔️</div>
                                @endif
                            </td>
                            <td>
                                {{ $tour->title }}
                                @if ($tour->isApproved())
                                    <div><a href="{{ route('tours.show', $tour) }}" class="muted" style="font-size:13px;" target="_blank">{{ __('View') }} ↗</a></div>
                                @endif
                            </td>
                            <td>{{ number_format($tour->price, 0) }} ₼</td>
                            <td><x-status-badge :status="$tour->status" /></td>
                            <td class="muted">{{ $tour->created_at->format('d.m.Y') }}</td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('company.tours.edit', $tour) }}" class="btn btn-outline btn-sm">{{ __('Edit') }}</a>
                                    <form method="POST" action="{{ route('company.tours.destroy', $tour) }}" class="inline-form" onsubmit="return confirm('{{ __('Are you sure you want to delete this tour?') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">{{ __('Delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $tours->links() }}
    @endif
@endsection
