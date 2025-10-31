<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): Response
    {
        $redirect = $this->resolveRedirect($request->query('redirect'));

        if ($redirect) {
            $request->session()->put('url.intended', url($redirect));
        }

        return Inertia::render('Auth/Register', [
            'redirect' => $redirect,
            'app' => [
                'name' => config('app.name'),
            ],
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        if ($redirect = $this->resolveRedirect($request->input('redirect'))) {
            $request->session()->put('url.intended', url($redirect));
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'phone' => [
                'required',
                'string',
                'unique:'.User::class,
                'regex:/^(628|08)\d{8,11}$/',
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'phone.regex' => 'Nomor telepon tidak valid. Format: 08xxxxxx atau 628xxxxxx',
            'phone.unique' => 'Nomor telepon sudah terdaftar',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->intended(route('templates.index', absolute: false));
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
