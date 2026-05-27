<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $tours = Tour::approved()
            ->with('user')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('home', compact('tours', 'search'));
    }

    public function show(Tour $tour)
    {
        abort_unless($tour->isApproved(), 404);

        $tour->load('user');

        $related = Tour::approved()
            ->where('id', '!=', $tour->id)
            ->latest()
            ->take(3)
            ->get();

        return view('tours.show', compact('tour', 'related'));
    }
}
