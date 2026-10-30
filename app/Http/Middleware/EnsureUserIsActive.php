<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    /**
     * Bij ELK verzoek: is de ingelogde gebruiker intussen gedeactiveerd? Dan direct uitloggen.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ! $user->is_active) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();       // sessie weggooien
            $request->session()->regenerateToken();  // nieuw CSRF-token

            return redirect()->route('login')
                ->withErrors(['email' => 'This account has been deactivated by an administrator.']);
        }

        return $next($request);
    }
}
