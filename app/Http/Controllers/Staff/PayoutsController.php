<?php

namespace App\Http\Controllers\Staff;
use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Batch;
use App\Models\Disbursement;
use App\Models\Payout;
use App\Models\PayoutSchedule;
use App\Models\Scholarship;
use Inertia\Inertia;
use Illuminate\Http\Request;

class PayoutsController extends Controller
{
    public function payouts_index()
    {

        $scholarships = Scholarship::all();
        $payouts = Payout::all();
        $disbursements = Disbursement::all();
        $payout_schedule = PayoutSchedule::all();
        $batches = Batch::with('school_year')->get();
        $academic_years = AcademicYear::all();

        return Inertia::render('Staff/Payouts/Payout_Records', [
            'scholarships' => $scholarships,
            'payouts' => $payouts,
            'batches' => $batches,
            'disbursements' => $disbursements,
            'payout_schedule' => $payout_schedule,
            'academic_years' => $academic_years,
        ]);
    }

    public function payouts_list()
    {
        return Inertia::render('Staff/Payouts/Payouts');
    }
}
