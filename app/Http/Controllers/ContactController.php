<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact');
    }

    public function send(Request $request)
    {
        if ($request->filled('website')) {
            return redirect()->route('contact')->with('success', __('Your message has been sent. We will get back to you soon.'));
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:40'],
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        ContactMessage::create([
            ...$data,
            'ip' => $request->ip(),
        ]);

        return redirect()->route('contact')
            ->with('success', __('Your message has been sent. We will get back to you soon.'));
    }
}
