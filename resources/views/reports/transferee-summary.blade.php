<!-- reports/transferee-summary.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scholarship Transferee Summary Report</title>
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
            class="absolute left-5 top-0 w-20 h-24 object-contain" alt="University Seal">

        <div class="text-center">
            <h1 class="text-lg font-bold">Republic of the Philippines</h1>
            <h2 class="text-xl font-semibold">University of Rizal System</h2>
            <p class="text-sm">Scholarship and Financial Assistance Office</p>
        </div>

        <!-- Right Logo (Sponsor Logo) -->
        @php
            $imagePath = storage_path('app/public/sponsor/logo/' . $sponsor->logo);
            $imageData = base64_encode(file_get_contents($imagePath));
            $src = 'data:image/' . pathinfo($sponsor->logo, PATHINFO_EXTENSION) . ';base64,' . $imageData;
        @endphp
        <img src="{{ $src }}" class="absolute right-5 top-0 w-20 h-24 object-contain" alt="Sponsor Logo">

        <div class="absolute right-5 top-20 mt-4">
            <p class="text-right underline">Date: {{ date('F d, Y') }}</p>
        </div>
    </div>

    <!-- Title -->
    <h2 class="font-bold text-lg text-center mb-4 uppercase">Certification of Transferred Grantees</h2>
    <p class="text-center mb-4">{{ $scholarship->name }} - {{ $schoolYear->name }} ({{ $semester }})</p>

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
            @foreach($campusData as $data)
                <tr>
                    <td class="border border-gray-700 p-2">{{ $data['campus']->name }}</td>
                    <td class="border border-gray-700 p-2">{{ $data['graduated_count'] }}</td>
                    <td class="border border-gray-700 p-2">{{ $data['graduated_count'] }}</td>
                </tr>
            @endforeach

            <tr class="font-bold">
                <td class="border border-gray-700 p-2">Total</td>
                <td class="border border-gray-700 p-2">{{ $totalGraduates }}</td>
                <td class="border border-gray-700 p-2">{{ $totalGraduates }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Summary Statistics with Scholar Details -->
    <div class="mt-8 mb-4">
        <h3 class="font-bold">Transferee Details:</h3>
        <table class="w-full border border-gray-700 mb-4 text-center mt-2">
            <thead class="bg-gray-100 text-xs">
                <tr>
                    <th class="border border-gray-700 p-2">No.</th>
                    <th class="border border-gray-700 p-2">Student ID</th>
                    <th class="border border-gray-700 p-2">Last Name</th>
                    <th class="border border-gray-700 p-2">First Name</th>
                    <th class="border border-gray-700 p-2">Middle Name</th>
                    <th class="border border-gray-700 p-2">Current Campus</th>
                    <th class="border border-gray-700 p-2">Current Course</th>
                    <th class="border border-gray-700 p-2">Year Level</th>
                    <th class="border border-gray-700 p-2">Batch No.</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $counter = 1;
                @endphp
                @foreach($campusData as $data)
                    @foreach($batch->grantees()->whereHas('scholar', function ($query) use ($data) {
                        $query->where('campus_id', $data['campus']->id)
                            ->where('student_status', 'Transferred');
                    })->with(['scholar', 'scholar.course'])->get() as $grantee)
                        <tr class="text-xs">
                            <td class="border border-gray-700 p-2">{{ $counter++ }}</td>
                            <td class="border border-gray-700 p-2">{{ $grantee->scholar->student_number }}</td>
                            <td class="border border-gray-700 p-2">{{ $grantee->scholar->last_name }}</td>
                            <td class="border border-gray-700 p-2">{{ $grantee->scholar->first_name }}</td>
                            <td class="border border-gray-700 p-2">{{ $grantee->scholar->middle_name }}</td>
                            <td class="border border-gray-700 p-2">{{ $data['campus']->name }}</td>
                            <td class="border border-gray-700 p-2">{{ $grantee->scholar->course->name ?? 'N/A' }}</td>
                            <td class="border border-gray-700 p-2">{{ $grantee->scholar->year_level }}</td>
                            <td class="border border-gray-700 p-2">{{ $grantee->batch->batch_number }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Additional Cert Text -->
    <p class="mb-8">
        This further certifies that the student's information indicated in
        <span class="text-red-600 underline">{{ $scholarship->name }} Continuing Form </span> is accurate and complete.
    </p>


    <!-- Signatories -->
    <div class="flex justify-between mt-20">
        <div class="text-center w-64">
            <p class="mb-2">Certified by:</p>
            <div class="h-12 border-b border-gray-700"></div>
            <p class="text-red-600 font-semibold mt-1 text-sm">Signature over Printed Name of the School Registrar</p>
        </div>
        <div class="text-center w-64">
            <p class="mb-2">Approved by:</p>
            <div class="h-12 border-b border-gray-700"></div>
            <p class="text-red-600 font-semibold mt-1 text-sm">Signature over Printed Name of the President of the HEIs</p>
        </div>
    </div>

    <!-- Seal -->
    <div class="absolute bottom-16 left-16 text-sm italic text-gray-700 flex items-center gap-4">
        <div class="w-32 h-32 border border-gray-400 rounded-full flex items-center justify-center text-center text-xs">
            Official Dry Seal
        </div>
        <div class="text-red-600 font-semibold">(must be notarized)</div>
    </div>

</body>

</html>