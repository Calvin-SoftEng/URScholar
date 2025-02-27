<?php

namespace App\Http\Controllers;

use App\Models\Payout;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Scholarship;
use App\Models\Batch;
use App\Models\Scholar;
use App\Models\Sponsor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CashierController extends Controller
{
    public function dashboard()
    {
        return Inertia::render('Cashier/Dashboard');
    }

    public function scholarships()
    {
        $scholarships = Scholarship::all();
        $sponsors = Sponsor::all();

        return Inertia::render('Cashier/Scholarships/Active_Scholarships', [
            'scholarships' => $scholarships,
            'sponsors' => $sponsors
        ]);
    }

    public function payout_batches(Scholarship $scholarship)
    {
        $batches = Batch::where('scholarship_id', $scholarship->id)
            ->with([
                'scholars' => function ($query) {
                    $query->orderBy('last_name')
                        ->orderBy('first_name');
                }
            ])
            ->orderBy('batch_no', 'desc')
            ->get();

        return Inertia::render('Cashier/Scholarships/Payout_Batches', [
            'scholarship' => $scholarship,
            'batches' => $batches
        ]);
    }

    public function student_payouts($scholarshipId, $batchId)
    {
        $scholarship = Scholarship::findOrFail($scholarshipId);

        $batch = Batch::where('id', $batchId)
            ->where('scholarship_id', $scholarship->id)
            ->orderBy('batch_no', 'desc')
            ->first();

        // Fetch payouts using the Payout model
        $payouts = Payout::where('scholarship_id', $scholarshipId)
            ->where('batch_id', $batchId)
            ->with(['scholar']) // Assuming you have these relationships defined
            ->get();


        return Inertia::render('Cashier/Scholarships/Payouts', [
            'scholarship' => $scholarship,
            'batch' => $batch,
            'payouts' => $payouts
        ]);
    }

    public function payouts()
    {
        return Inertia::render('Cashier/Scholarships/Payouts');
    }


    public function verify_qr(Request $request)
    {
        $request->validate([
            'scanned_data' => 'required|string',
        ]);

        try {
            // Decode the scanned QR data
            $scannedData = json_decode($request->scanned_data, true);

            // Log the received QR data for debugging
            logger()->info("Received QR data:", $scannedData ?? ['error' => 'Invalid JSON']);

            if (!$scannedData || !isset($scannedData['id'], $scannedData['timestamp'])) {
                return back()->withErrors(['message' => 'Invalid QR Code format']);
            }

            // Find the scholar using the ID from the scanned QR
            $scholar = Scholar::where('urscholar_id', $scannedData['id'])->first();

            if (!$scholar) {
                return back()->withErrors(['message' => 'Scholar not found']);
            }

            // Recreate QR data for hash checking
            $qrData = json_encode([
                'id' => $scholar->urscholar_id,
                'name' => $scholar->first_name . ' ' . $scholar->last_name,
                'timestamp' => $scannedData['timestamp'],
            ], JSON_UNESCAPED_SLASHES);

            // Verify the QR Code
            if (!Hash::check($qrData, $scholar->qr_code)) {
                return back()->withErrors(['message' => 'QR Code verification failed']);
            }

            // QR is valid, now check if the scholar has a pending payout
            $payout = Payout::where('scholar_id', $scholar->id)
                ->where('status', 'Pending')
                ->first();

            if (!$payout) {
                return back()->withErrors(['message' => 'No pending payout found for this scholar']);
            }

            // Update the payout status
            $payout->status = 'Claimed';
            $payout->claimed_at = now();
            $payout->claimed_by = Auth::user()->id; // Assuming the cashier is logged in
            //dd($payout);
            $payout->save();

            return back()->with('flash', [
                'type' => 'success',
                'message' => 'Grant successfully claimed for Scholar: ' . $scholar->first_name . ' ' . $scholar->last_name
            ]);

        } catch (\Exception $e) {
            logger()->error('QR verification error: ' . $e->getMessage());
            return back()->withErrors(['message' => 'Error processing QR code: ' . $e->getMessage()]);
        }
    }
}

