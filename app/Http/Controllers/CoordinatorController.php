<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CoordinatorController extends Controller
{

    public function dashboard() {
        return Inertia::render('Coordinator/Dashboard');
    }
}
