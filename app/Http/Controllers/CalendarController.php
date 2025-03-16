<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function calendar() {
        return Inertia::render('Staff/Calendar/Calendar');
    }
}
