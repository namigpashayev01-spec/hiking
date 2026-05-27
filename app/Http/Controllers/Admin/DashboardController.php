<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'tours_total' => Tour::count(),
            'tours_pending' => Tour::where('status', Tour::STATUS_PENDING)->count(),
            'tours_approved' => Tour::where('status', Tour::STATUS_APPROVED)->count(),
            'companies_total' => User::where('role', User::ROLE_COMPANY)->count(),
            'companies_pending' => User::where('role', User::ROLE_COMPANY)
                ->where('status', User::STATUS_PENDING)->count(),
        ];

        $pendingTours = Tour::with('user')
            ->where('status', Tour::STATUS_PENDING)
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'pendingTours'));
    }
}
