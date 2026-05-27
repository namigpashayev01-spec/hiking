<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureCompanyApproved
{
    /**
     * Block tour management until the company account is approved by an admin.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && ! $user->isApproved()) {
            return redirect()
                ->route('company.dashboard')
                ->with('warning', __('Your company account is awaiting admin approval. You cannot add tours yet.'));
        }

        return $next($request);
    }
}
