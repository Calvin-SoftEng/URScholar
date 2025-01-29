<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    public function dashboard() {
        return Inertia::render('Super_Admin/Dashboard');
    }
}
