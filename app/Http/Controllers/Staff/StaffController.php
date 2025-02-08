<?php

namespace App\Http\Controllers\Staff;

use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function dashboard() {
        return Inertia::render('Staff/Dashboard');
    }
}
