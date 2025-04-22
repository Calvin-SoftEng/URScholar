<?php

namespace App\Http\Controllers;
use App\Models\Scholarship;
use App\Models\Batch;
use App\Models\Grantees;
use App\Models\Disbursement;
use App\Models\Campus;
use App\Models\Sponsor;
use App\Models\Scholar;
use App\Models\SchoolYear;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function EnrolleesSummaryReport(Request $request, Scholarship $scholarship)
    {
        // Get parameters from request
        $batchIds = explode(',', $request->query('batch_ids'));
        $campusIds = explode(',', $request->query('campus_ids'));
        $schoolYearId = $request->query('school_year_id');
        $semester = $request->query('semester');


        // Query batches with the given IDs that belong to the scholarship
        $batches = Batch::whereIn('id', $batchIds)
            ->where('scholarship_id', $scholarship->id)
            ->get();

        $sponsor = Sponsor::find($scholarship->sponsor_id);

        // Aggregate data for enrolled students by batch and campus
        $enrolledSummary = [];
        $totalEnrolled = 0;

        // dd($batches);

        foreach ($batches as $batch) {
            $enrolledScholars = $batch->grantees()
                ->whereHas('scholar', function ($query) use ($campusIds) {
                    $query->whereIn('campus_id', $campusIds)
                        ->where('student_status', 'Enrolled');
                })
                ->with('scholar') // eager load to prevent N+1 problem
                ->with('school_year')
                ->get()
                ->pluck('scholar')
                ->filter(); // remove null in case of broken relationships

            if ($enrolledScholars->isNotEmpty()) {
                $campusGroups = $enrolledScholars->groupBy('campus_id');

                foreach ($campusGroups as $campusId => $scholars) {
                    $campus = Campus::find($campusId);

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
            'sponsor' => $sponsor,
            'enrolledSummary' => $enrolledSummary,
            'totalEnrolled' => $totalEnrolled
        ]);

        return $pdf->stream("enrollees-summary-{$scholarship->name}.pdf");
    }

    public function EnrolledListReport(Scholarship $scholarship, Request $request)
    {
        // Get parameters from request
        $batchIds = explode(',', $request->query('batch_ids'));
        $campusIds = explode(',', $request->query('campus_ids'));
        $schoolYearId = $request->query('school_year_id');
        $semester = $request->query('semester');

        // Get the school year
        $schoolYear = SchoolYear::find($schoolYearId);

        // Query batches with the given IDs that belong to the scholarship
        $batches = Batch::whereIn('id', $batchIds)
            ->where('scholarship_id', $scholarship->id)
            ->get();
        $sponsor = Sponsor::find($scholarship->sponsor_id);

        // Get campuses with the given IDs
        $campuses = Campus::whereIn('id', $campusIds)->get();

        // Prepare data for each campus
        $campusData = [];

        foreach ($campuses as $campus) {
            $campusScholars = collect();

            foreach ($batches as $batch) {
                $grantees = Grantees::where('batch_id', $batch->id)
                    ->where('scholarship_id', $scholarship->id)
                    ->with(['scholar.course', 'scholar.campus'])
                    ->get();

                // Map to scholars and filter by current campus
                $batchScholars = $grantees->map(function ($grantee) use ($campus, $batch) {
                    if ($grantee->scholar && $grantee->scholar->campus_id == $campus->id) {
                        // Add batch information to each scholar for reporting
                        $scholar = $grantee->scholar;
                        $scholar->batch_no = $batch->batch_no;
                        $scholar->campus_name = $scholar->campus ? $scholar->campus->name : 'N/A';
                        return $scholar;
                    }
                    return null;
                })->filter()->values();

                $campusScholars = $campusScholars->concat($batchScholars);
            }

            // // Fallback for testing/demo purposes
            // if ($campusScholars->isEmpty()) {
            //     foreach ($batches as $batch) {
            //         // Create 5 demo students for each batch in this campus
            //         for ($i = 0; $i < 5; $i++) {
            //             $faker = \Faker\Factory::create('en_PH');
            //             $campusScholars->push((object) [
            //                 'id' => $faker->unique()->randomNumber(6),
            //                 'urscholar_id' => 'SCH-' . rand(10000, 99999),
            //                 'student_number' => 'STUD-' . rand(10000, 99999),
            //                 'last_name' => strtoupper($faker->lastName),
            //                 'first_name' => strtoupper($faker->firstName),
            //                 'middle_name' => strtoupper($faker->lastName),
            //                 'sex' => $faker->randomElement(['Male', 'Female']),
            //                 'year_level' => rand(1, 4),
            //                 'course' => (object) ['name' => $faker->randomElement(['BS Computer Science', 'BS Information Technology', 'BS Accountancy', 'BS Civil Engineering', 'BS Psychology'])],
            //                 'student_status' => 'Enrolled',
            //                 'grant' => $faker->randomElement(['TES', 'TES3-a', null]),
            //                 'batch_no' => $batch->batch_no,
            //                 'campus_name' => $campus->name,
            //                 'campus_id' => $campus->id
            //             ]);
            //         }
            //     }
            // }

            $campusData[$campus->id] = [
                'campus' => $campus,
                'scholars' => $campusScholars
            ];
        }

        // Generate PDF with multiple pages
        $pdf = PDF::loadView('reports.enrolled_list', [
            'scholarship' => $scholarship,
            'batches' => $batches,
            'campusData' => $campusData,
            'sponsor' => $sponsor,
            'schoolYear' => $schoolYear,
            'semester' => $semester
        ]);

        // Set paper size and orientation
        $pdf->setPaper([0, 0, 612, 936], 'landscape');

        // Stream the PDF
        return $pdf->stream("scholarship-enrolled-report.pdf");
    }

    public function GraduateSummaryReport(Scholarship $scholarship, Request $request)
    {
        // Get parameters from request
        $batchIds = explode(',', $request->query('batch_ids'));
        $campusIds = explode(',', $request->query('campus_ids'));
        $schoolYearId = $request->query('school_year_id');
        $semester = $request->query('semester');

        // Get the school year
        $schoolYear = SchoolYear::find($schoolYearId);

        // Query batches with the given IDs that belong to the scholarship
        $batches = Batch::whereIn('id', $batchIds)
            ->where('scholarship_id', $scholarship->id)
            ->get();

        $sponsor = Sponsor::find($scholarship->sponsor_id);

        // dd($sponsor);

        // Prepare campus data structure
        $campusData = [];
        $totalGraduates = 0;

        foreach ($campusIds as $campusId) {
            $campus = Campus::find($campusId);
            if ($campus) {
                $graduatedCount = 0;

                foreach ($batches as $batch) {
                    $scholars = $batch->grantees()
                        ->whereHas('scholar', function ($query) use ($campusId) {
                            $query->where('campus_id', $campusId)
                                ->where('student_status', 'Graduated');
                        })
                        ->with('scholar')
                        ->get();

                    $graduatedCount += $scholars->count();
                }

                $campusData[] = [
                    'campus' => $campus,
                    'graduated_count' => $graduatedCount
                ];

                $totalGraduates += $graduatedCount;
            }
        }

        $pdf = PDF::loadView('reports.graduates-report', [
            'scholarship' => $scholarship,
            'schoolYear' => $schoolYear,
            'semester' => $semester,
            'sponsor' => $sponsor,
            'campusData' => $campusData,
            'totalGraduates' => $totalGraduates,
            'batch' => $batches->first() // Pass the first batch for backward compatibility
        ]);

        return $pdf->stream("graduates-report-{$scholarship->name}.pdf");
    }

    public function TransferredSummaryReport(Scholarship $scholarship, Request $request)
    {
        // Get parameters from request
        $batchIds = explode(',', $request->query('batch_ids'));
        $campusIds = explode(',', $request->query('campus_ids'));
        $schoolYearId = $request->query('school_year_id');
        $semester = $request->query('semester');

        // Get the school year
        $schoolYear = SchoolYear::find($schoolYearId);

        // Query batches with the given IDs that belong to the scholarship
        $batches = Batch::whereIn('id', $batchIds)
            ->where('scholarship_id', $scholarship->id)
            ->get();

        $sponsor = Sponsor::find($scholarship->sponsor_id);

        // Prepare campus data structure
        $campusData = [];
        $totalGraduates = 0;

        foreach ($campusIds as $campusId) {
            $campus = Campus::find($campusId);
            if ($campus) {
                $graduatedCount = 0;

                foreach ($batches as $batch) {
                    $scholars = $batch->grantees()
                        ->whereHas('scholar', function ($query) use ($campusId) {
                            $query->where('campus_id', $campusId)
                                ->where('student_status', 'Transferred');
                        })
                        ->with('scholar')
                        ->get();

                    $graduatedCount += $scholars->count();
                }

                $campusData[] = [
                    'campus' => $campus,
                    'graduated_count' => $graduatedCount
                ];

                $totalGraduates += $graduatedCount;
            }
        }

        $pdf = PDF::loadView('reports.transferee-summary', [
            'scholarship' => $scholarship,
            'schoolYear' => $schoolYear,
            'semester' => $semester,
            'sponsor' => $sponsor,
            'campusData' => $campusData,
            'totalGraduates' => $totalGraduates,
            'batch' => $batches->first() // Pass the first batch for backward compatibility
        ]);

        return $pdf->stream("transferee-summary-{$scholarship->name}.pdf");
    }

    public function PayrollReport(Scholarship $scholarship, Request $request)
    {
        // Get parameters from request
        $batchIds = $request->query('batch_ids', null);
        $campusIds = $request->query('campus_ids', null);
        $schoolYearId = $request->query('school_year_id', null);
        $semester = $request->query('semester', null);

        // Convert comma-separated strings to arrays
        if ($batchIds && is_string($batchIds)) {
            $batchIds = explode(',', $batchIds);
        }

        if ($campusIds && is_string($campusIds)) {
            $campusIds = explode(',', $campusIds);
        }

        // Get all selected batches
        $batches = Batch::whereIn('id', $batchIds)->get();

        $sponsor = Sponsor::find($scholarship->sponsor_id);

        // Initialize arrays for data collection
        $allScholars = [];
        $allDisbursementDates = [];

        foreach ($batches as $batch) {
            // Get disbursement data for this batch
            $data = $this->listPayoutDisbursements($scholarship, $batch, $campusIds);

            // Process each disbursement
            foreach ($data['disbursements'] as $disbursement) {
                $allDisbursementDates[] = [
                    'id' => $disbursement['disbursement_id'],
                    'date' => $disbursement['disbursement_date'],
                    'batch_id' => $batch->id,
                    'batch_name' => $batch->name
                ];

                foreach ($disbursement['scholars'] as $scholar) {
                    $scholarKey = $scholar['scholar_id'];

                    // Initialize scholar record if not exists
                    if (!isset($allScholars[$scholarKey])) {
                        $allScholars[$scholarKey] = [
                            'id' => $scholar['scholar_id'],
                            'first_name' => $scholar['first_name'],
                            'last_name' => $scholar['last_name'],
                            'middle_name' => $scholar['middle_name'],
                            'student_number' => $scholar['student_number'],
                            'course' => $scholar['course'],
                            'year_level' => $scholar['year_level'],
                            'campus' => $scholar['campus'],
                            'batch_name' => $batch->name,
                            'batch_id' => $batch->id,
                            'disbursements' => [],
                            'status' => $scholar['status'], // Default to claimed, will update if not claimed
                        ];
                    }

                    // Add this disbursement
                    // $allScholars[$scholarKey]['disbursements'][$disbursement['disbursement_id']] = [
                    //     'amount' => $scholar['amount'],
                    //     'status' => $scholar['status'],
                    //     'date_received' => $scholar['date_received']
                    // ];

                    // Calculate total received and update claimed status
                    if ($scholar['status'] == 'Disbursed') {
                        $allScholars[$scholarKey]['total_received'] += $scholar['amount'];
                    } else {
                        $allScholars[$scholarKey]['not_claimed_status'] = 'Not Claimed';
                    }
                }
            }
        }

        // Get the school year information
        $schoolYear = SchoolYear::find($schoolYearId);

        $pdf = PDF::loadView('reports.payroll', [
            'scholarship' => $scholarship,
            'batches' => $batches,
            'scholars' => array_values($allScholars),
            'disbursementDates' => $allDisbursementDates,
            'sponsor' => $sponsor,
            'schoolYear' => $schoolYear,
            'semester' => $semester
        ]);

        // Set paper size and orientation
        $pdf->setPaper([0, 0, 612, 936], 'landscape');

        return $pdf->stream("scholarship-payroll-{$scholarship->name}.pdf");
    }

    public function listPayoutDisbursements(Scholarship $scholarship, Batch $batch, $campusIds = null)
    {
        // If no campus IDs provided, get all scholars for this batch
        if (!$campusIds) {
            $campusIds = Grantees::where('batch_id', $batch->id)
                ->join('scholars', 'grantees.scholar_id', '=', 'scholars.id')
                ->distinct('scholars.campus_id')
                ->pluck('scholars.campus_id')
                ->toArray();
        }

        // Get all grantees and their scholars for this batch filtered by campus
        $grantees = $batch->grantees()
            ->whereHas('scholar', function ($query) use ($campusIds) {
                $query->whereIn('campus_id', $campusIds);
            })
            ->with('scholar.course', 'scholar.campus') // eager load to prevent N+1 problem
            ->with('school_year')
            ->get();

        // Get all disbursements for this batch
        $disbursements = Disbursement::where('batch_id', $batch->id)
            ->get();

        $sponsor = Sponsor::find($scholarship->sponsor_id);

        // Transform data for report
        $disbursementData = [];

        foreach ($disbursements as $disbursement) {
            $scholarData = [];

            foreach ($grantees as $grantee) {
                // Get scholar from grantee
                $scholar = $grantee->scholar;

                if ($scholar) {
                    // Check if this scholar received this disbursement
                    $scholarDisbursement = Disbursement::where('scholar_id', $scholar->id)
                        ->where('id', $disbursement->id)
                        ->first();

                    $scholarData[] = [
                        'scholar_id' => $scholar->id,
                        'first_name' => $scholar->first_name ?? 'Unknown Scholar',
                        'last_name' => $scholar->last_name,
                        'middle_name' => $scholar->middle_name,
                        'student_number' => $scholar->student_number ?? 'N/A',
                        'course' => $scholar->course->name ?? 'N/A',
                        'year_level' => $scholar->year_level ?? 'N/A',
                        'campus' => $scholar->campus->name ?? 'N/A',
                        'status' => $scholarDisbursement ? $scholarDisbursement->status : 'Pending',
                        'date_received' => $scholarDisbursement ? $scholarDisbursement->date_received : null
                    ];
                }
            }

            $disbursementData[] = [
                'disbursement_id' => $disbursement->id,
                'disbursement_date' => $disbursement->disbursement_date,
                'status' => $disbursement->status,
                'total_amount' => $disbursement->total_amount,
                'scholars' => $scholarData,
                'disbursed_count' => count(array_filter($scholarData, function ($s) {
                    return $s['status'] == 'Disbursed';
                })),
                'total_scholars' => count($scholarData)
            ];
        }

        return [
            'scholarship' => $scholarship,
            'batch' => $batch,
            'sponsor' => $sponsor,
            'disbursements' => $disbursementData
        ];
    }
}
