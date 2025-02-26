<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Scholarship;
use App\Models\Batch;
use App\Models\Scholar;
use App\Models\Sponsor;
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

    public function student_payouts()
    {
        return Inertia::render('Cashier/Scholarships/Payouts');
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

        // Decode the scanned QR data
        $scannedData = json_decode($request->scanned_data, true);

        if (!$scannedData || !isset($scannedData['id'], $scannedData['timestamp'])) {
            return back()->withErrors(['message' => 'Invalid QR Code format']);
        }

        // Find the scholar using the ID from the scanned QR
        $scholar = Scholar::where('urscholar_id', $scannedData['id'])->first();

        if (!$scholar) {
            return back()->withErrors(['message' => 'Scholar not found']);
        }

        // Debugging - Show received QR code data
        logger()->info("Received QR data:", $scannedData);

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


        return back()->withErrors(['message' => 'Ano ka bionic']);
    }

}
