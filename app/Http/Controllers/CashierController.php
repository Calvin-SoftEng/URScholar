<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

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
}
