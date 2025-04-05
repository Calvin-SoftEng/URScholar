<?php

namespace App\Http\Controllers;
use App\Models\Scholarship;
use App\Models\Batch;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function ScholarSummaryReport(Scholarship $scholarship, Batch $batch)
    {
           // Fetch real scholars
        $scholars = $batch->scholars;

        // Dummy fallback if no scholars exist
        if ($scholars->isEmpty()) {
            $scholars = collect([
                (object)[
                    'name' => 'Juan Dela Cruz',
                    'email' => 'juan@example.com',
                    'status' => 'Active'
                ],
                (object)[
                    'name' => 'Maria Santos',
                    'email' => 'maria@example.com',
                    'status' => 'Graduated'
                ],
                (object)[
                    'name' => 'Pedro Reyes',
                    'email' => 'pedro@example.com',
                    'status' => 'Inactive'
                ]
            ]);
        }

        $pdf = PDF::loadView('reports.scholars_summary', [
            'scholarship' => $scholarship,
            'batch' => $batch,
            'scholars' => $scholars
        ]);

        return $pdf->stream("scholarship-report-batch-{$batch->batch_no}.pdf");
    }

    public function EnrolledSummaryReport(Scholarship $scholarship, Batch $batch)
    {
           // Fetch real scholars
        $scholars = $batch->scholars;

        // Dummy fallback if no scholars exist
        if ($scholars->isEmpty()) {
            $scholars = collect([
                (object)[
                    'name' => 'Juan Dela Cruz',
                    'email' => 'juan@example.com',
                    'status' => 'Active'
                ],
                (object)[
                    'name' => 'Maria Santos',
                    'email' => 'maria@example.com',
                    'status' => 'Graduated'
                ],
                (object)[
                    'name' => 'Pedro Reyes',
                    'email' => 'pedro@example.com',
                    'status' => 'Inactive'
                ]
            ]);
        }

        $pdf = PDF::loadView('reports.enrolled-report', [
            'scholarship' => $scholarship,
            'batch' => $batch,
            'scholars' => $scholars
        ]);

        return $pdf->stream("scholarship-report-batch-{$batch->batch_no}.pdf");
    }

    public function GraduateSummaryReport(Scholarship $scholarship, Batch $batch)
    {
           // Fetch real scholars
        $scholars = $batch->scholars;

        // Dummy fallback if no scholars exist
        if ($scholars->isEmpty()) {
            $scholars = collect([
                (object)[
                    'name' => 'Juan Dela Cruz',
                    'email' => 'juan@example.com',
                    'status' => 'Active'
                ],
                (object)[
                    'name' => 'Maria Santos',
                    'email' => 'maria@example.com',
                    'status' => 'Graduated'
                ],
                (object)[
                    'name' => 'Pedro Reyes',
                    'email' => 'pedro@example.com',
                    'status' => 'Inactive'
                ]
            ]);
        }

        $pdf = PDF::loadView('reports.graduates-report', [
            'scholarship' => $scholarship,
            'batch' => $batch,
            'scholars' => $scholars
        ]);

        return $pdf->stream("scholarship-report-batch-{$batch->batch_no}.pdf");
    }

    public function PayrollReport(Scholarship $scholarship, Batch $batch)
    {
           // Fetch real scholars
        $scholars = $batch->scholars;

        // Dummy fallback if no scholars exist
        if ($scholars->isEmpty()) {
            $scholars = collect([
                (object)[
                    'name' => 'Juan Dela Cruz',
                    'email' => 'juan@example.com',
                    'status' => 'Active'
                ],
                (object)[
                    'name' => 'Maria Santos',
                    'email' => 'maria@example.com',
                    'status' => 'Graduated'
                ],
                (object)[
                    'name' => 'Pedro Reyes',
                    'email' => 'pedro@example.com',
                    'status' => 'Inactive'
                ]
            ]);
        }

        $pdf = PDF::loadView('reports.payroll', [
            'scholarship' => $scholarship,
            'batch' => $batch,
            'scholars' => $scholars
        ]);

        return $pdf->stream("scholarship-report-batch-{$batch->batch_no}.pdf");
    }
}
