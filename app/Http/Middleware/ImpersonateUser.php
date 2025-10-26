<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

final class ImpersonateUser
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && Session::has('X-USER-ID') && auth()->user()->isAdmin()) {
            $userId = Session::get('X-USER-ID');
            $user = User::find($userId);

            if ($user) {
                Auth::login($user);
                Context::add('user', $user);
            }
        } else {
            Context::add('user', auth()->user());
        }

        return $next($request);
    }
}
