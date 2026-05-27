<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use Illuminate\Http\Request;

class TourController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $tours = Tour::with('user')
            ->when(in_array($status, [Tour::STATUS_PENDING, Tour::STATUS_APPROVED, Tour::STATUS_REJECTED], true),
                fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.tours.index', compact('tours', 'status'));
    }

    public function show(Tour $tour)
    {
        $tour->load('user');

        return view('admin.tours.show', compact('tour'));
    }

    public function approve(Tour $tour)
    {
        $tour->update([
            'status' => Tour::STATUS_APPROVED,
            'rejection_reason' => null,
        ]);

        return back()->with('success', __('Tour approved.'));
    }

    public function reject(Request $request, Tour $tour)
    {
        $data = $request->validate([
            'rejection_reason' => ['nullable', 'string', 'max:500'],
        ]);

        $tour->update([
            'status' => Tour::STATUS_REJECTED,
            'rejection_reason' => $data['rejection_reason'] ?? null,
        ]);

        return back()->with('success', __('Tour rejected.'));
    }
}
