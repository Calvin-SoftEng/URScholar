<?php

namespace App\Http\Controllers;
use App\Models\Scholarship;
use App\Models\Batch;
use App\Models\Grantees;
use App\Models\Campus;
use App\Models\Scholar;
use App\Models\SchoolYear;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function EnrolleesSummaryReport(Request $request, Scholarship $scholarship)
    {
        // Get parameters from request
        $batchIds = $request->input('batch_ids', []);
        $campusIds = $request->input('campus_ids', []);
        $schoolYearId = $request->input('school_year_id');
        $semester = $request->input('semester');

        // Query batches with the given IDs that belong to the scholarship
        $batches = Batch::whereIn('id', $batchIds)
            ->where('scholarship_id', $scholarship->id)
            ->get();

        // Aggregate data for enrolled students by batch and campus
        $enrolledSummary = [];
        $totalEnrolled = 0;

        foreach ($batches as $batch) {
            // Get scholars for this batch who have student_status = "Enrolled"
            // And filter by the selected campuses
            $enrolledScholars = $batch->scholars()
                ->whereIn('campus_id', $campusIds)
                ->where('student_status', 'Enrolled')
                ->get();

            if ($enrolledScholars->isNotEmpty()) {
                // Group scholars by campus
                $campusGroups = $enrolledScholars->groupBy('campus_id');

                foreach ($campusGroups as $campusId => $scholars) {
                    $campus = Campus::find($campusId);

                    // Add to summary
                    if (!isset($enrolledSummary[$campus->name])) {
                        $enrolledSummary[$campus->name] = [
                            'total' => 0,
                            'batches' => []
                        ];
                    }

                    $enrolledSummary[$campus->name]['batches'][$batch->batch_no] = $scholars->count();
                    $enrolledSummary[$campus->name]['total'] += $scholars->count();
                    $totalEnrolled += $scholars->count();
                }
            }
        }

        // Load view with data
        $pdf = PDF::loadView('reports.enrollees-summary', [
            'scholarship' => $scholarship,
            'schoolYear' => SchoolYear::find($schoolYearId),
            'semester' => $semester,
            'enrolledSummary' => $enrolledSummary,
            'totalEnrolled' => $totalEnrolled
        ]);

        return $pdf->stream("enrollees-summary-{$scholarship->name}.pdf");
    }

    public function EnrolledListReport(Scholarship $scholarship, Batch $batch)
    {
        // Get the campus for this batch
        $campus = $batch->campus;

        // Get all grantees/scholars for this batch
        $grantees = Grantees::where('batch_id', $batch->id)
            ->where('scholarship_id', $scholarship->id)
            ->with('scholar.course')
            ->get();

        // Map to scholars
        $scholars = $grantees->map(function ($grantee) {
            return $grantee->scholar;
        })->filter()->values();

        // Fallback for testing/demo purposes
        if ($scholars->isEmpty()) {
            $scholars = collect([
                (object) [
                    'urscholar_id' => 'SCH-' . rand(10000, 99999),
                    'last_name' => 'Dela Cruz',
                    'first_name' => 'Juan',
                    'middle_name' => 'Santos',
                    'sex' => 'Male',
                    'year_level' => 3,
                    'course' => (object) ['name' => 'BS Computer Science'],
                    'student_status' => 'Enrolled',
                    'grant' => null
                ],
                (object) [
                    'urscholar_id' => 'SCH-' . rand(10000, 99999),
                    'last_name' => 'Santos',
                    'first_name' => 'Maria',
                    'middle_name' => 'Reyes',
                    'sex' => 'Female',
                    'year_level' => 2,
                    'course' => (object) ['name' => 'BS Accountancy'],
                    'student_status' => 'Enrolled',
                    'grant' => 'TES3-a'
                ],
                (object) [
                    'urscholar_id' => 'SCH-' . rand(10000, 99999),
                    'last_name' => 'Reyes',
                    'first_name' => 'Pedro',
                    'middle_name' => 'Garcia',
                    'sex' => 'Male',
                    'year_level' => 4,
                    'course' => (object) ['name' => 'BS Civil Engineering'],
                    'student_status' => 'Enrolled',
                    'grant' => 'TES3-a'
                ]
            ]);
        }

        // Generate PDF
        $pdf = PDF::loadView('reports.enrolled_list', [
            'scholarship' => $scholarship,
            'batch' => $batch,
            'scholars' => $scholars,
        ]);

        // Set paper size and orientation
        $pdf->setPaper([0, 0, 612, 936], 'landscape');

        // Stream the PDF
        return $pdf->stream("scholarship-report-batch-{$batch->batch_no}.pdf");
    }

    public function GraduateSummaryReport(Scholarship $scholarship, Batch $batch)
    {
        // Get all campuses
        $campuses = Campus::all();

        // Fetch scholars for this batch with graduated status
        $scholars = Scholar::where('student_status', 'Graduated')
            ->whereHas('grantees', function ($query) use ($batch) {
                $query->where('batch_id', $batch->id);
            })
            ->get();

        // If no scholars found, use dummy data
        if ($scholars->isEmpty()) {
            $scholars = collect([
                (object) [
                    'id' => 1,
                    'campus_id' => 1,
                    'student_number' => 'S12345',
                    'last_name' => 'Dela Cruz',
                    'first_name' => 'Juan',
                    'middle_name' => 'Santos',
                    'student_status' => 'Graduated'
                ],
                (object) [
                    'id' => 2,
                    'campus_id' => 2,
                    'student_number' => 'S67890',
                    'last_name' => 'Santos',
                    'first_name' => 'Maria',
                    'middle_name' => 'Reyes',
                    'student_status' => 'Graduated'
                ],
                (object) [
                    'id' => 3,
                    'campus_id' => 1,
                    'student_number' => 'S54321',
                    'last_name' => 'Reyes',
                    'first_name' => 'Pedro',
                    'middle_name' => 'Garcia',
                    'student_status' => 'Graduated'
                ]
            ]);
        }

        $pdf = PDF::loadView('reports.graduates-report', [
            'scholarship' => $scholarship,
            'batch' => $batch,
            'scholars' => $scholars,
            'campuses' => $campuses
        ]);

        return $pdf->stream("graduates-report-batch-{$batch->batch_no}.pdf");
    }

    public function PayrollReport(Scholarship $scholarship, Batch $batch)
    {
        // Fetch real scholars
        $scholars = $batch->scholars;

        // Dummy fallback if no scholars exist
        if ($scholars->isEmpty()) {
            $scholars = collect([
                (object) [
                    'name' => 'Juan Dela Cruz',
                    'email' => 'juan@example.com',
                    'status' => 'Active'
                ],
                (object) [
                    'name' => 'Maria Santos',
                    'email' => 'maria@example.com',
                    'status' => 'Graduated'
                ],
                (object) [
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

        // Set paper size and orientation
        $pdf->setPaper([0, 0, 612, 936], 'landscape');

        return $pdf->stream("scholarship-report-batch-{$batch->batch_no}.pdf");
    }
}
