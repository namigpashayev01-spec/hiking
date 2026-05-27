<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TourController extends Controller
{
    public function index()
    {
        $tours = Auth::user()->tours()->latest()->paginate(10);

        return view('company.tours.index', compact('tours'));
    }

    public function create()
    {
        return view('company.tours.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateTour($request, true);

        $data['user_id'] = Auth::id();
        $data['slug'] = $this->uniqueSlug($data['title']);
        $data['status'] = Tour::STATUS_PENDING;
        $data['image'] = $this->storeImage($request);

        Tour::create($data);

        return redirect()->route('company.tours.index')
            ->with('success', __('Tour created and submitted for approval.'));
    }

    public function edit(Tour $tour)
    {
        $this->authorizeTour($tour);

        return view('company.tours.edit', compact('tour'));
    }

    public function update(Request $request, Tour $tour)
    {
        $this->authorizeTour($tour);

        $data = $this->validateTour($request, false);

        if ($newImage = $this->storeImage($request)) {
            $this->deleteImage($tour->image);
            $data['image'] = $newImage;
        }

        // Any edit goes back to the moderation queue.
        $data['status'] = Tour::STATUS_PENDING;
        $data['rejection_reason'] = null;

        $tour->update($data);

        return redirect()->route('company.tours.index')
            ->with('success', __('Tour updated and submitted for approval.'));
    }

    public function destroy(Tour $tour)
    {
        $this->authorizeTour($tour);

        $this->deleteImage($tour->image);
        $tour->delete();

        return redirect()->route('company.tours.index')
            ->with('success', __('Tour deleted.'));
    }

    /**
     * @return array<string, mixed>
     */
    private function validateTour(Request $request, bool $imageRequired): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'content' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'image' => [$imageRequired ? 'required' : 'nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:4096'],
        ]);
    }

    private function authorizeTour(Tour $tour): void
    {
        abort_unless($tour->user_id === Auth::id(), 403);
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'tour';
        $slug = $base;
        $i = 1;

        while (Tour::where('slug', $slug)->exists()) {
            $slug = $base.'-'.(++$i);
        }

        return $slug;
    }

    private function storeImage(Request $request): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        $file = $request->file('image');
        $name = Str::random(20).'.'.$file->getClientOriginalExtension();
        $file->move(public_path('uploads/tours'), $name);

        return $name;
    }

    private function deleteImage(?string $image): void
    {
        if ($image && is_file(public_path('uploads/tours/'.$image))) {
            @unlink(public_path('uploads/tours/'.$image));
        }
    }
}
