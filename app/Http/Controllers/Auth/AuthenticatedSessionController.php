<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): Response
    {
        $redirect = $this->resolveRedirect($request->query('redirect'));

        if ($redirect) {
            $request->session()->put('url.intended', url($redirect));
        }

        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
            'redirect' => $redirect,
            'app' => [
                'name' => config('app.name'),
            ],
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        if ($redirect = $this->resolveRedirect($request->input('redirect'))) {
            $request->session()->put('url.intended', url($redirect));
        }

        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('templates.index', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    protected function resolveRedirect(?string $redirect): ?string
    {
        if (! $redirect) {
            return null;
        }

        $redirect = trim($redirect);

        if ($redirect === '') {
            return null;
        }

        $redirect = '/' . ltrim($redirect, '/');

        if (str_contains($redirect, '://') || str_contains($redirect, '\\')) {
            return null;
        }

        return $redirect;
    }
}
