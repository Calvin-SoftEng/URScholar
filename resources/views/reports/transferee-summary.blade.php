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
            class="absolute left-5 top-0 w-20 h-24 object-contain" alt="Left Logo">

        <div class="text-center">
            <h1 class="text-lg font-bold">Republic of the Philippines</h1>
            <h2 class="text-xl font-semibold">University of Rizal System</h2>
            <p class="text-sm">Scholarship and Financial Assistance Office</p>
        </div>

        <!-- Right Logo -->
        @php
            $imagePath = storage_path('app/public/sponsor/logo/' . $sponsor->logo);
            $imageData = base64_encode(file_get_contents($imagePath));
            $src = 'data:image/' . pathinfo($sponsor->logo, PATHINFO_EXTENSION) . ';base64,' . $imageData;
        @endphp


        <div class="absolute right-5 top-20 mt-4">
            <p class="text-right underline">Date: {{ date('F d, Y') }}</p>
        </div>
    </div>

    <!-- Title -->
    <h2 class="font-bold text-lg text-center mb-4 uppercase">Certification of Transferred Grantees</h2>

    <br>

    <!-- Table -->
    <table class="w-full border border-gray-700 mb-4 text-center">
        <thead class="bg-gray-100">
            <tr>
                <th class="border border-gray-700 p-2">Name of Campus</th>
                <th class="border border-gray-700 p-2">Number of {{ $scholarship->name }} Transferee</th>
                <th class="border border-gray-700 p-2">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($enrolledSummary as $campusName => $data)
                <tr>
                    <td class="border border-gray-700 p-2">{{ $campusName }}</td>
                    <td class="border border-gray-700 p-2">{{ $data['total'] }}</td>
                    <td class="border border-gray-700 p-2">{{ $data['total'] }}</td>
                </tr>
            @endforeach

            <tr class="font-bold">
                <td class="border border-gray-700 p-2">Total</td>
                <td class="border border-gray-700 p-2">{{ $totalEnrolled }}</td>
                <td class="border border-gray-700 p-2">{{ $totalEnrolled }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Summary Statistics -->
    <div class="mt-8 mb-4">
        <h3 class="font-bold">Summary:</h3>
        <table class="w-full border border-gray-700 mb-4 text-center mt-2">
        <!-- Table -->
        <table class="w-full border border-gray-700 mb-4 text-center">
            <thead class="bg-gray-100 text-xs">
                <tr>
                    <th class="border border-gray-700 p-2">Batch No.</th>
                    <th class="border border-gray-700 p-2">Student ID</th>
                    <th class="border border-gray-700 p-2">Last Name</th>
                    <th class="border border-gray-700 p-2">First Name</th>
                    <th class="border border-gray-700 p-2">Middle Name</th>
                    <th class="border border-gray-700 p-2">Current Campus</th>
                    <th class="border border-gray-700 p-2">Current Course</th>
                    <th class="border border-gray-700 p-2">Year Level</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalEnrolled = 0;
                    $groupedScholars = $scholars->groupBy(['campus_name', 'batch_no']);
                @endphp

                @foreach($groupedScholars as $campusName => $batches)
                            @foreach($batches as $batchNo => $batchScholars)
                                        @php
                                            $count = $batchScholars->count();
                                            $totalEnrolled += $count;
                                        @endphp
                                        <tr class="text-sm">
                                            <td class="border border-gray-700 p-2">{{ $campusName }}</td>
                                            <td class="border border-gray-700 p-2">{{ $batchNo }}</td>
                                            <td class="border border-gray-700 p-2">{{ $count }}</td>
                                        </tr>
                            @endforeach
                @foreach($data['scholars'] as $index => $scholar)
                    <tr class="text-sm">
                        <td class="border border-gray-700 p-2">{{ $index + 1 }}</td>
                        <td class="border border-gray-700 p-2">{{ $scholar->student_number }}</td>
                        <td class="border border-gray-700 p-2">{{ $scholar->last_name }}</td>
                        <td class="border border-gray-700 p-2">{{ $scholar->first_name }}</td>
                        <td class="border border-gray-700 p-2">{{ $scholar->middle_name }}</td>
                        <td class="border border-gray-700 p-2">{{ $scholar->course->name ?? 'N/A' }}</td>
                        <td class="border border-gray-700 p-2">{{ $scholar->year_level }}</td>
                        <td class="border border-gray-700 p-2">{{ $scholar->batch_no }}</td>
                    </tr>
                @endforeach

                <tr class="font-bold bg-gray-100 text-sm">
                    <td class="border border-gray-700 p-2" colspan="2">TOTAL</td>
                    <td class="border border-gray-700 p-2">{{ $totalEnrolled }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Additional Cert Text -->
    <p class="mb-4">
        This further certifies that the student's information indicated in
        <span class="text-red-600 underline">{{ $scholarship->name }} Continuing Form </span> is accurate and complete.
    </p>


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
            <p class="text-red-600 font-semibold mt-1 text-sm">Signature over Printed Name of the President of the HEIs
            </p>
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