<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class SponsorController extends Controller
{
    public function sponsor_dashboard()
    {
        return Inertia::render('Sponsor/Dashboard');
    }

}
