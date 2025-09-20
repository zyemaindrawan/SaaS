<?php
namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'avatar' => $request->user()->avatar,
                ] : null,
            ],
            'flash' => [
                'message' => fn () => $request->session()->get('message'),
                'type' => fn () => $request->session()->get('type', 'success'),
            ],
            'app' => [
                'name' => config('app.name'),
                'url' => config('app.url'),
            ],
        ]);
    }
}
