<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'total' => $user->tours()->count(),
            'approved' => $user->tours()->where('status', Tour::STATUS_APPROVED)->count(),
            'pending' => $user->tours()->where('status', Tour::STATUS_PENDING)->count(),
            'rejected' => $user->tours()->where('status', Tour::STATUS_REJECTED)->count(),
        ];

        $recentTours = $user->tours()->latest()->take(5)->get();

        return view('company.dashboard', compact('stats', 'recentTours'));
    }
}
