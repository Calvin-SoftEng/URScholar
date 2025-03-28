<?php

namespace App\Http\Controllers\Staff;
use App\Http\Controllers\Controller;
use App\Models\Scholarship;
use Inertia\Inertia;
use Illuminate\Http\Request;

class PayoutsController extends Controller
{
    public function payouts_index()
    {
        $scholarships = Scholarship::with([
            'payouts' => function ($query) {
                $query->where('status', 'Pending')
                    ->orWhere('status', 'Inactive');
            }
        ])->get()->map(function ($scholarship) {
            // Separate recent (pending) and historical (inactive) payouts
            $scholarship->recentPayouts = $scholarship->payouts->where('status', 'Pending');
            $scholarship->payoutHistory = $scholarship->payouts->where('status', 'Inactive');

            return $scholarship;
        });

        return Inertia::render('Staff/Payouts/Payout_Records', [
            'scholarships' => $scholarships,
        ]);
    }

    public function payouts_list()
    {
        return Inertia::render('Staff/Payouts/Payouts');
    }
}
