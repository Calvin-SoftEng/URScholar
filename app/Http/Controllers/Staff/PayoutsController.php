<?php

namespace App\Http\Controllers\Staff;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;

class PayoutsController extends Controller
{
    public function payouts_index()
    {
        return Inertia::render('Staff/Payouts/Payout_Records');
    }

    public function payouts_list()
    {
        return Inertia::render('Staff/Payouts/Payouts');
    }
}
