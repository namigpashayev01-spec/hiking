<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    public function switch(Request $request, string $locale)
    {
        if (in_array($locale, ['az', 'en'], true)) {
            Session::put('locale', $locale);
        }

        return redirect()->back();
    }
}
