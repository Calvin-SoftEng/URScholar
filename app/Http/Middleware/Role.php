<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $usertype): Response
    {
        // Check if the user is authenticated
        if (!$request->user()) {
            return redirect('login');  // Redirect to login page if user is not authenticated
        }

        // Check if the user's email is verified
        if (!$request->user()->hasVerifiedEmail()) {
            return redirect()->route('student.verify-account');  // Redirect to verification page if email is not verified
        }

        // Check if the user's type matches the required usertype
        if ($request->user()->usertype !== $usertype) {
            return redirect('dashboard');  // Redirect to the dashboard if the usertype doesn't match
        }

        return $next($request);  // Proceed with the request if all checks pass
    }
}
