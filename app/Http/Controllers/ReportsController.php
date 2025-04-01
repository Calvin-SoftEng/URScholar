<?php

namespace App\Http\Controllers;
use App\Models\Scholarship;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function generateReport($scholarshipId, $batchId)
    {
        // Dummy Data
        $scholarship = Scholarship::findOrFail($scholarshipId);
        $batch = $scholarship->batches()->findOrFail($batchId);

        $data = [
            'title' => 'Scholarship Report',
            'scholarship' => $scholarship->name,
            'batch' => $batch->name,
            'date' => now()->format('F j, Y'),
            'students' => [
                ['name' => 'John Doe', 'status' => 'Approved'],
                ['name' => 'Jane Smith', 'status' => 'Pending'],
                ['name' => 'Michael Brown', 'status' => 'Approved'],
            ],
        ];

        // Load View
        $pdf = Pdf::loadView('reports.scholarship_report', $data);

        return $pdf->download('scholarship_report.pdf');
    }
}
