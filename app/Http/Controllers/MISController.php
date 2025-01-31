<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class MISController extends Controller
{
    public function dashboard() {
        return Inertia::render('MIS/Dashboard');
    }
}
