<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;

final class CancelImpersonationController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        if (session()->has('ORIGINAL-USER-ID')) {
            $originalUserId = session()->pull('ORIGINAL-USER-ID');
            session()->forget('X-USER-ID');

            $originalUser = User::find($originalUserId);
            if ($originalUser) {
                auth()->login($originalUser);
            }
        }

        return redirect()->back();
    }
}
