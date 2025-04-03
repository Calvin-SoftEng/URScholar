<?php

namespace App\Http\Controllers;
use App\Models\Scholarship;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function ScholarSummaryReport(Scholarship $scholarship, Batch $batch)
    {
        $scholars = $batch->scholars;

        $pdf = PDF::loadView('reports.scholars_summary', [
            'scholarship' => $scholarship,
            'batch' => $batch,
            'scholars' => $scholars
        ]);

        return $pdf->stream("scholarship-report-batch-{$batch->batch_no}.pdf");
    }
}
