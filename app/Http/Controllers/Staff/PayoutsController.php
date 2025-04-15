<?php

namespace App\Http\Controllers\Staff;
use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Batch;
use App\Models\Disbursement;
use App\Models\Payout;
use App\Models\Campus; // Added Campus model
use App\Models\PayoutSchedule;
use App\Models\Scholarship;
use Inertia\Inertia;
use Illuminate\Http\Request;

class PayoutsController extends Controller
{
    public function payouts_index()
    {
        $scholarships = Scholarship::all();

        // Get payouts with date information
        $payouts = Payout::all();

        // Get batches with school year and campus information
        $batches = Batch::with(['school_year', 'campus'])->get();

        // Get all disbursements to track claims
        $disbursements = Disbursement::all();

        // Get scheduled payout dates
        $payout_schedule = PayoutSchedule::all();

        $academic_years = AcademicYear::all();
        $campuses = Campus::all();

        return Inertia::render('Staff/Payouts/Payout_Records', [
            'scholarships' => $scholarships,
            'payouts' => $payouts,
            'batches' => $batches,
            'disbursements' => $disbursements,
            'payout_schedule' => $payout_schedule,
            'academic_years' => $academic_years,
            'campuses' => $campuses,
        ]);
    }

    public function student_payouts($scholarshipId, $batchId)
    {
        $scholarship = Scholarship::findOrFail($scholarshipId);

        $batch = Batch::where('id', $batchId)
            ->where('scholarship_id', $scholarship->id)
            ->with('school_year')
            ->orderBy('batch_no', 'desc')
            ->firstOrFail(); // Use firstOrFail to handle cases where batch doesn't exist

        $payout = Payout::where('scholarship_id', $scholarship->id)
            ->where('school_year_id', $batch->school_year_id)
            ->where('semester', $batch->semester)
            ->where('campus_id', $batch->campus_id)
            ->first();


        // Optimize query to reduce N+1 problem
        $disbursements = Disbursement::where('payout_id', $payout->id)
            ->where('batch_id', $batchId)
            ->with([
                'scholar' => function ($subQuery) {
                    $subQuery->with(['course', 'campus']);
                }
            ])
            ->get();


        // Count total claimed disbursements
        $totalClaimed = Disbursement::where('payout_id', $payout->id)
            ->where('batch_id', $batchId)
            ->where('status', 'claimed') // Assuming 'claimed' is the status for claimed disbursements
            ->count();

        return Inertia::render('Staff/Payouts/Payouts', [
            'scholarship' => $scholarship,
            'batch' => $batch,
            'disbursements' => $disbursements,
            'payout' => $payout,
            'totalClaimed' => $totalClaimed, // Pass the total claimed count to the view
        ]);
    }

    public function payouts_list()
    {
        return Inertia::render('Staff/Payouts/Payouts');
    }
}
