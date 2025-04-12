<!-- resources/views/reports/enrolled-report.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scholarship Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .date {
            position: absolute;
            right: 20px;
            top: 20px;
        }

        h1,
        h2,
        h3 {
            color: #333;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .campus-section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }

        .footer {
            margin-top: 30px;
        }

        .signature-area {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }

        .signature-line {
            width: 45%;
            border-top: 1px solid #333;
            padding-top: 5px;
            text-align: center;
        }

        .seal {
            width: 100px;
            height: 100px;
            border: 1px solid #333;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-style: italic;
            font-size: 10px;
            text-align: center;
        }

        .scholar-info {
            margin-top: 5px;
            font-size: 12px;
        }

        .summary-table {
            margin-top: 20px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>HIGHER EDUCATION INSTITUTION LETTERHEAD</h2>
        <p>Republic of the Philippines</p>
        <p><strong>{{ $scholarship->name }}</strong></p>
        <p>Address of the HEI</p>
    </div>

    <div class="date">
        Date: {{ date('F d, Y') }}
    </div>

    <h2 style="text-align: center; margin-top: 50px;">CERTIFICATION OF ENROLLED GRANTEES</h2>

    <p>
        This is to certify that the total number of Continuing TES grantees by campus as shown below,
        are qualified to avail of the Tertiary Education Subsidy (TES) program under R.A. No. 10931 also
        known as Universal Access to Quality Tertiary Education (UAQTE) for the
        {{ $batch->school_year->semester ?? '1st' }} semester of Academic Year
        {{ $batch->school_year->year }}.
    </p>

    <!-- Summary Table -->
    <div class="summary-table">
        <table>
            <thead>
                <tr>
                    <th>Name of Campus</th>
                    <th>Number of TES Grantees</th>
                    <th>Number of TES Grantees with TES3-a</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalGrantees = 0;
                    $totalTES3a = 0;
                    $campus = $batch->campus;

                    // Count grantees for this batch's campus
                    $granteeCount = $scholars->count();
                    $tes3aCount = $scholars->where('grant', 'TES3-a')->count();
                    $totalGrantees += $granteeCount;
                    $totalTES3a += $tes3aCount;
                @endphp

                <tr>
                    <td>{{ $campus->name }}</td>
                    <td>{{ $granteeCount }}</td>
                    <td>{{ $tes3aCount }}</td>
                    <td>{{ $granteeCount + $tes3aCount }}</td>
                </tr>

                <tr style="font-weight: bold; background-color: #f2f2f2;">
                    <td>Total</td>
                    <td>{{ $totalGrantees }}</td>
                    <td>{{ $totalTES3a }}</td>
                    <td>{{ $totalGrantees + $totalTES3a }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <p>
        This further certifies that the student's information indicated in <strong>Annex 2 - TES Continuing Form
            2</strong> is accurate and complete.
    </p>

    <p style="font-weight: bold;">
        This certification is being issued in accordance with the CHED-UniFAST Memorandum Circular No. 01 Series of
        2022,
        Amended Tertiary Education Subsidy (TES) Guidelines of 2022.
    </p>

    <div class="page-break"></div>

    <!-- Detailed Scholar List -->
    <h2 style="text-align: center;">LIST OF SCHOLARS - BATCH {{ $batch->batch_no }}</h2>
    <h3>Campus: {{ $batch->campus->name }}</h3>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Scholar ID</th>
                <th>Name</th>
                <th>Sex</th>
                <th>Year Level</th>
                <th>Course</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($scholars as $index => $scholar)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $scholar->urscholar_id ?? 'N/A' }}</td>
                    <td>{{ $scholar->last_name ?? '' }}, {{ $scholar->first_name ?? '' }}
                        {{ $scholar->middle_name ? substr($scholar->middle_name, 0, 1) . '.' : '' }}
                        {{ $scholar->extname ?? '' }}</td>
                    <td>{{ $scholar->sex ?? 'N/A' }}</td>
                    <td>{{ $scholar->year_level ?? 'N/A' }}</td>
                    <td>{{ $scholar->course->name ?? 'N/A' }}</td>
                    <td>{{ $scholar->student_status ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Signature Section -->
    <div class="signature-area">
        <div style="position: relative;">
            <div class="seal">Official Dry Seal</div>
            <p><strong>(must be notarized)</strong></p>
        </div>

        <div style="text-align: right; width: 45%;">
            <p style="margin-bottom: 50px;"><strong>Certified by:</strong></p>
            <div class="signature-line">
                <p>Signature over Printed Name of the School Registrar</p>
            </div>

            <p style="margin-top: 40px; margin-bottom: 50px;"><strong>Approved by:</strong></p>
            <div class="signature-line">
                <p>Signature over Printed Name of the President of the HEI</p>
            </div>
        </div>
    </div>

</body>

</html>