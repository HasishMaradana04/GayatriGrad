<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectUnauthorizedFilamentUser
{
    public function handle(Request $request, Closure $next): Response
    {
        $guard = Filament::auth();

        if (! $guard->check()) {
            return $next($request);
        }

        /** @var Model $user */
        $user = $guard->user();
        $panel = Filament::getCurrentPanel();

        $canAccessPanel = $user instanceof FilamentUser
            ? $user->canAccessPanel($panel)
            : app()->environment('local');

        if ($canAccessPanel) {
            return $next($request);
        }

        $guard->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(Filament::getLoginUrl())
            ->with('status', 'Your account does not have access to the admin panel. Please sign in with an authorized admin account.');
    }
}
