<?php

// app/Http/Middleware/Role.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Role
{
    public function handle(Request $request, Closure $next, ...$userTypes): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        // Handle comma-separated user types
        $allowedTypes = collect($userTypes)
            ->flatMap(fn($type) => explode(',', $type))
            ->toArray();

        if (!in_array($user->usertype, $allowedTypes)) {
            // Redirect based on user type
            switch ($user->usertype) {
                case 'system_admin':
                    return redirect()->route('sa.portal_branding');
                case 'super_admin':
                    return redirect()->route('staff.dashboard');
                case 'coordinator':
                    return redirect()->route('staff.dashboard');
                case 'sponsor':
                    return redirect()->route('sponsor.dashboard');
                case 'head_cashier':
                    return redirect()->route('cashier.dashboard');
                case 'cashier':
                    return redirect()->route('cashier.dashboard');
                case 'student':
                    return $request->$user->hasVerifiedEmail()
                        ? redirect()->route('student.dashboard')
                        : redirect()->route('student.verify-account');
                default:
                    return redirect('dashboard');
            }
        }

        // For students, always check email verification
        // if ($user->usertype === 'student' && !$request->$user->hasVerifiedEmail()) {
        //     return redirect()->route('student.verify-account');
        // }

        return $next($request);
    }
}