<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scholarship Report</title>
    <title>Scholar Detail Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
        }
        .header h1 {
            margin-bottom: 5px;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
        }
        .scholarship-info {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .batch-info {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f0f8ff;
            border-radius: 5px;
        }
        .scholar-list {
            margin-top: 30px;
        }
        .campus-heading {
            background-color: #4a5568;
            color: white;
            padding: 8px 15px;
            margin-top: 30px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }
        td {
            padding: 8px;
            font-size: 11px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .scholar-details {
            margin-left: 20px;
            margin-bottom: 25px;
            border-left: 3px solid #4a5568;
            padding-left: 15px;
        }
        .scholar-header {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 5px;
            color: #4a5568;
        }
        .scholar-subheader {
            font-size: 12px;
            color: #666;
            margin-bottom: 10px;
        }
        .personal-info, .academic-info, .contact-info {
            margin-bottom: 15px;
        }
        .section-title {
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 5px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 3px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            font-size: 11px;
        }
        .info-item {
            margin-bottom: 5px;
        }
        .label {
            font-weight: bold;
            color: #666;
        }
        .page-break {
            page-break-after: always;
        }
        .summary-table {
            width: 70%;
            margin: 30px auto;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $scholarship->name }} - Scholar Detail Report</h1>
        <p>Batch #{{ $batch->batch_no }} | School Year: {{ $batch->schoolYear->school_year ?? 'N/A' }}</p>
        <p>Report Generated: {{ now()->format('F d, Y h:i A') }}</p>
    </div>

    <div class="scholarship-info">
        <h3>Scholarship Information</h3>
        <p><strong>Name:</strong> {{ $scholarship->name }}</p>
        <p><strong>Type:</strong> {{ $scholarship->scholarshipType }}</p>
        <p><strong>Status:</strong> {{ $scholarship->status }}</p>
        <p><strong>Duration:</strong> {{ $scholarship->date_start }} to {{ $scholarship->date_end }}</p>
    </div>

    <div class="batch-info">
        <h3>Batch Information</h3>
        <p><strong>Batch Number:</strong> {{ $batch->batch_no }}</p>
        <p><strong>School Year:</strong> {{ $batch->schoolYear->school_year ?? 'N/A' }}</p>
        <p><strong>Status:</strong> {{ $batch->status }}</p>
        <p><strong>Total Scholars:</strong> {{ $scholars->count() }}</p>
    </div>

    <div class="scholar-list">
        <h2>Scholars by Campus</h2>
        
        <!-- Group scholars by campus -->
        @php
            $scholarsByCampus = $scholars->groupBy('campus_id');
            $totalByStatus = [
                'Enrolled' => 0,
                'Unenrolled' => 0,
                'Graduated' => 0,
                'Dropped' => 0
            ];
        @endphp
        
        @foreach($campuses as $campus)
            @if(isset($scholarsByCampus[$campus->id]) && $scholarsByCanvas[$campus->id]->count() > 0)
                <div class="campus-heading">
                    {{ $campus->name }} - {{ $campus->location }}
                </div>
                
                @php
                    $campusScholars = $scholarsByCanvas[$campus->id];
                    
                    // Calculate status counts for this campus
                    $statusCounts = [
                        'Enrolled' => $campusScholars->where('student_status', 'Enrolled')->count(),
                        'Unenrolled' => $campusScholars->where('student_status', 'Unenrolled')->count(),
                        'Graduated' => $campusScholars->where('student_status', 'Graduated')->count(),
                        'Dropped' => $campusScholars->where('student_status', 'Dropped')->count()
                    ];
                    
                    // Add to totals
                    foreach($totalByStatus as $status => $count) {
                        $totalByStatus[$status] += $statusCounts[$status];
                    }
                @endphp
                
                <table>
                    <tr>
                        <th>Status</th>
                        <th>Count</th>
                        <th>Percentage</th>
                    </tr>
                    @foreach($statusCounts as $status => $count)
                        <tr>
                            <td>{{ $status }}</td>
                            <td>{{ $count }}</td>
                            <td>{{ $campusScholars->count() > 0 ? round(($count / $campusScholars->count()) * 100, 2) : 0 }}%</td>
                        </tr>
                    @endforeach
                    <tr style="font-weight: bold;">
                        <td>Total</td>
                        <td>{{ $campusScholars->count() }}</td>
                        <td>100%</td>
                    </tr>
                </table>
                
                @foreach($campusScholars as $scholar)
                    <div class="scholar-details">
                        <div class="scholar-header">
                            {{ $scholar->last_name }}, {{ $scholar->first_name }} {{ $scholar->middle_name }} {{ $scholar->extname ?? '' }}
                        </div>
                        <div class="scholar-subheader">
                            ID: {{ $scholar->student_number ?? $scholar->urscholar_id }} | Status: {{ $scholar->student_status }}
                        </div>
                        
                        <div class="personal-info">
                            <div class="section-title">Personal Information</div>
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="label">Gender:</span> {{ $scholar->sex }}
                                </div>
                                <div class="info-item">
                                    <span class="label">Birthdate:</span> {{ $scholar->birthdate->format('M d, Y') }}
                                </div>
                                <div class="info-item">
                                    <span class="label">PWD:</span> {{ $scholar->pwd_classification ?? 'N/A' }}
                                </div>
                                <div class="info-item">
                                    <span class="label">QR Code:</span> {{ $scholar->qr_code ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="academic-info">
                            <div class="section-title">Academic Information</div>
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="label">HEI:</span> {{ $scholar->hei_name }}
                                </div>
                                <div class="info-item">
                                    <span class="label">Course:</span> {{ $scholar->course->name ?? 'N/A' }}
                                </div>
                                <div class="info-item">
                                    <span class="label">Year Level:</span> {{ $scholar->year_level }}
                                </div>
                                <div class="info-item">
                                    <span class="label">Total Units:</span> {{ $scholar->total_units ?? 'N/A' }}
                                </div>
                                <div class="info-item">
                                    <span class="label">Award No:</span> {{ $scholar->award_no ?? 'N/A' }}
                                </div>
                                <div class="info-item">
                                    <span class="label">App No:</span> {{ $scholar->app_no ?? 'N/A' }}
                                </div>
                                <div class="info-item">
                                    <span class="label">Grant Amount:</span> {{ $scholar->grant ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="contact-info">
                            <div class="section-title">Contact Information</div>
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="label">Email:</span> {{ $scholar->email ?? 'N/A' }}
                                </div>
                                <div class="info-item">
                                    <span class="label">Address:</span> {{ $scholar->street }}, {{ $scholar->municipality }}, {{ $scholar->province }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if(!$loop->last)
                        <hr>
                    @endif
                @endforeach
                
                @if(!$loop->last)
                    <div class="page-break"></div>
                @endif
            @endif
        @endforeach
    </div>
    
    <div class="page-break"></div>
    
    <div class="header">
        <h1>Summary of Scholar Status</h1>
        <p>{{ $scholarship->name }} - Batch #{{ $batch->batch_no }}</p>
    </div>

    <!-- Additional Notes -->
    <p class="mt-4 indent-8">
        This further certifies that the student’s information indicated in 
        <span class="text-red-500 font-semibold">Annex 2 - TES Continuing Form 2</span> is accurate and complete.
    </p>

    <p class="mt-4 font-bold indent-8">
        This certification is being issued in accordance with the CHED-UniFAST Memorandum Circular No. 01 Series of 2022, 
        Amended Tertiary Education Subsidy (TES) Guidelines of 2022.
    </p>

    <!-- Signatures -->
    <div class="mt-6 justify-between">
        <!-- Notary Section -->
        <div class="flex justify-between items-center mt-6 absolute left-0 bottom-10 transform -translate-y-0">
            <div class="border border-gray-700 rounded-full w-24 h-24 flex items-center justify-center text-xs italic">
                Official Dry Seal
            </div>
            <p class="text-red-500 font-bold">(must be notarized)</p>
        </div>

        <div class="flex items-center mt-6 absolute right-0 bottom-10 transform -translate-y-0">
            <p class="mb-2 font-semibold text-center">Certified by:</p>
            <br>
            <p class="text-red-500 font-bold">Signature over Printed Name of the School Registrar</p>

            <p class="mt-4 mb-2 font-semibold text-center">Approved by:</p>
            <br>
            <p class="text-red-500 font-bold">Signature over Printed Name of the President of the HEIs</p>
        </div>
    </div>

</body>
</html>
