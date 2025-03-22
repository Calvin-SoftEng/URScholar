<?php

namespace App\Http\Controllers\Sponsor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SponsorController extends Controller
{
    public function sponsor_dashboard()
    {
        return Inertia::render('Sponsor/Dashboard');
    }

}
