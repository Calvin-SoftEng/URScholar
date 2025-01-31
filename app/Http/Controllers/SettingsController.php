<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use Illuminate\Http\Request;

class SettingsController extends Controller
{

    public function index()
    {
        return Inertia::render('Super_Admin/Settings/Settings_Sponsor');
    }

    public function adding()
    {
        return Inertia::render('Super_Admin/Settings/Adding_Students');
    }


}
