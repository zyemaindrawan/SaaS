<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = Filament::auth()?->user() ?? $request->user();

        if (! $user || ! $user->is_admin) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
