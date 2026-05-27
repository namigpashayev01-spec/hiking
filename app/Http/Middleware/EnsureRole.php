<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Restrict a route to users with the given role.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = Auth::user();

        if (! $user) {
            // Guests are sent to the matching login screen.
            return redirect()->route($role === 'admin' ? 'admin.login' : 'company.login');
        }

        if ($user->role !== $role) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route($role === 'admin' ? 'admin.login' : 'company.login')
                ->withErrors(['email' => __('You are not authorized to access this area.')]);
        }

        return $next($request);
    }
}
