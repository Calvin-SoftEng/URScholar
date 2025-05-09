<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\PortalBranding;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    public function create(): Response
    {
        $branding = PortalBranding::where('status', 'Active')->first();

        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
            'branding' => $branding
        ]);
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();

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
                return $user->hasVerifiedEmail()
                    ? redirect()->route('student.dashboard')
                    : redirect()->route('student.verify-account');
            default:
                return redirect()->intended('welcome');
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
