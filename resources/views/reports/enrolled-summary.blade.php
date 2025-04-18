{{-- 
<!DOCTYPE html>
<html lang="en">


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

</html> --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scholarship Summary Report</title>
    <style>
        {!! file_get_contents(public_path('css/tailwind.min.css')) !!}
        body {
            font-family: sans-serif;
        }
    </style>
</head>
<body class="text-gray-900 p-8">

    <!-- Header with logos -->
    <div class="flex justify-between items-center pb-4 mb-8">
        <!-- Left Logo -->
        <img src="{{ public_path('assets/images/univ-seal.png') }}"
             class="absolute left-5 top-0 w-20 h-24 object-contain"
             alt="Left Logo">
        
        <div class="text-center">
            <h1 class="text-lg font-bold">Republic of the Philippines</h1>
            <h2 class="text-xl font-semibold">University of Rizal System</h2>
            <p class="text-sm">Scholarship and Financial Assistance Office</p>
        </div>

        <!-- Right Logo -->
        {{-- <img src="{{ public_path('assets/images/CHED.png') }}"
             class="absolute right-5 top-0 w-20 h-20 object-contain"
             alt="Right Logo"> --}}

        <div class="absolute right-5 top-20 mt-4">
            <p class="text-right underline">Date: {{ date('F d, Y') }}</p>
        </div>
    </div>

    <!-- Title -->
    <h2 class="font-bold text-lg text-center mb-4 uppercase">Certification of Enrolled Grantees</h2>

    <!-- Paragraph -->
    <p class="mb-4 ">
        <span class="font-bold">TO WHOM IT MAY CONCERN:</span>
        <br />
        <p class="text-justify indent-5">This is to certify that the total number of Continuing {{ $scholarship->name }} grantees by campus as shown below, are qualified to avail of the {{ $scholarship->name }} for the 
        <span class="font-bold text-red-600"> {{ $batch->school_year->semester ?? '1st' }}</span> semester of Academic Year
            {{ $batch->school_year->year }}.</p>
    </p>

    <br>

    <!-- Table -->
    <table class="w-full border border-gray-700 mb-4 text-center">
        <thead class="bg-gray-100">
        <tr>
            <th class="border border-gray-700 p-2">Name of Campus</th>
            <th class="border border-gray-700 p-2">Number of {{ $scholarship->name }} Grantees</th>
            <th class="border border-gray-700 p-2">Total</th>
        </tr>
        </thead>
        <tbody>
            {{-- @php
                    $totalGrantees = 0;
                    $totalTES3a = 0;
                    $campus = $batch->campus;

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
                </tr> --}}
                @php
                    $totalGrantees = 0;
                    $totalTES3a = 0;
                    $campus = $batch->campus;

                    $granteeCount = $scholars->count();
                    $tes3aCount = $scholars->where('grant', 'TES3-a')->count();
                    $totalGrantees += $granteeCount;
                    $totalTES3a += $tes3aCount;
                @endphp
        <tr>
            <td class="border border-gray-700 p-2">{{ $campus->name }}</td>
            <td class="border border-gray-700 p-2">{{ $granteeCount }}</td>
            <td class="border border-gray-700 p-2">{{ $granteeCount }}</td>
        </tr>
        {{-- <tr>
            <td class="border border-gray-700 p-2">Campus B</td>
            <td class="border border-gray-700 p-2"></td>
            <td class="border border-gray-700 p-2"></td>
        </tr>
        <tr>
            <td class="border border-gray-700 p-2 italic text-gray-500">(Insert more rows for additional Campus)</td>
            <td class="border border-gray-700 p-2"></td>
            <td class="border border-gray-700 p-2"></td>
        </tr> --}}
        <tr class="font-bold">
            <td class="border border-gray-700 p-2">Total</td>
            <td class="border border-gray-700 p-2">{{ $totalGrantees }}</td>
            <td class="border border-gray-700 p-2">{{ $totalGrantees}}</td>
        </tr>
        </tbody>
    </table>

    <!-- Additional Cert Text -->
    <p class="mb-4">
        This further certifies that the student’s information indicated in 
        <span class="text-red-600 underline">{{ $scholarship->name }} Continuing Form </span> is accurate and complete.
    </p>

    {{-- <p class="font-bold mb-6">
        This certification is being issued in accordance with the CHED-UniFAST Memorandum Circular No. 01 Series of 2022, Amended Tertiary Education Subsidy (TES) Guidelines of 2022.
    </p> --}}

    <!-- Signatories -->
    <div class="absolute bottom-0 right-0 flex justify-between mt-12">
        <div class="text-center justify-center items-center">
        <p class="mb-2">Certified by:</p>
        <div class="h-12 border-b border-gray-700 w-64"></div>
        <p class="text-red-600 font-semibold mt-1 text-sm">Signature over Printed Name of the School Registrar</p>
        </div>
        <div>
        <p class="mb-2">Approved by:</p>
        <div class="h-12 border-b border-gray-700 w-64"></div>
        <p class="text-red-600 font-semibold mt-1 text-sm">Signature over Printed Name of the President of the HEIs</p>
        </div>
    </div>

    <!-- Seal -->
    <div class="absolute bottom-0 left-0 mt-12 text-sm italic text-gray-700 flex items-center gap-4">
        <div class="w-32 h-32 border border-gray-400 rounded-full flex items-center justify-center text-center text-xs">
        Official Dry Seal
        </div>
        <div class="text-red-600 font-semibold">(must be notarized)</div>
    </div>

</body>
</html>