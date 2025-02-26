<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Scholar;
use Illuminate\Support\Facades\Hash;

class CashierController extends Controller
{
    public function dashboard()
    {
        return Inertia::render('Cashier/Dashboard');
    }

    public function scholarships()
    {
        return Inertia::render('Cashier/Scholarships/Active_Scholarships');
    }

    public function payout_batches()
    {
        return Inertia::render('Cashier/Scholarships/Payout_Batches');
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
        $scholar = Scholar::where('urscholar_id', $request->id)->first();

        if (!$scholar) {
            return back()->withErrors(['message' => 'Invalid QR Code']);
        }

        // Debugging - Show received QR code data
        logger()->info("Received QR data:", $request->all());

        // Recreate QR data for hash checking
        $qrData = json_encode([
            'id' => $scholar->urscholar_id,
            'name' => $scholar->first_name . ' ' . $scholar->last_name,
            'timestamp' => $request->timestamp,
        ], JSON_UNESCAPED_SLASHES);

        if (!Hash::check($qrData, $scholar->qr_code)) {
            return back()->withErrors(['message' => 'QR Code verification failed']);
        }

        return back()->with('message', 'QR Code verified successfully');
    }
    
}
