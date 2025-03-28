<?php

namespace App\Http\Controllers\Staff;
use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Payout;
use App\Models\Scholarship;
use Inertia\Inertia;
use Illuminate\Http\Request;

class PayoutsController extends Controller
{
    public function payouts_index()
    {

        $scholarships = Scholarship::with('payouts')->get();
        $payouts = Payout::all();

        return Inertia::render('Staff/Payouts/Payout_Records', [
            'scholarships' => $scholarships,
            'payouts' => $payouts,
        ]);
    }

    public function payouts_list()
    {
        return Inertia::render('Staff/Payouts/Payouts');
    }
}
