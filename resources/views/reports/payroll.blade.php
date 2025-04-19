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
    <h2 class="font-bold text-lg text-center mb-4 uppercase">Summary of Payroll for {{ $scholarship->name }}</h2>
    <!-- Paragraph -->
    <p class="mb-4 ">
        <br />
        <p class="text-justify indent-5">This is to certify that the total number of {{ $scholarship->name }} grantees as shown below, have successfully claimed their {{ $scholarship->name }} grant
        <span class="font-bold text-red-600">{{ $semester ?? '1st' }}</span> semester of Academic Year
            {{ $schoolYear ? $schoolYear->year : 'Current' }}.</p>
    </p>
    <br>

    <!-- Table -->
    <table class="w-full border border-gray-700 mb-4 text-center">
        <thead class="bg-gray-100 text-xs">
        <tr>
            <th class="border border-gray-700 p-2">Student ID</th>
            <th class="border border-gray-700 p-2">Last Name</th>
            <th class="border border-gray-700 p-2">First Name</th>
            <th class="border border-gray-700 p-2">Middle Name</th>
            <th class="border border-gray-700 p-2">Campus</th>
            <th class="border border-gray-700 p-2">Course</th>
            <th class="border border-gray-700 p-2">Year Level</th>
            <th class="border border-gray-700 p-2">Batch Name</th>
            <th class="border border-gray-700 p-2">Amount Received</th>
            <th class="border border-gray-700 p-2">Status</th>
        </tr>
        </thead>
        <tbody>
        @foreach($scholars as $scholar)
            <tr class="text-sm">
                <td class="border border-gray-700 p-2">{{ $scholar['student_number'] }}</td>
                <td class="border border-gray-700 p-2">{{ $scholar['last_name'] }}</td>
                <td class="border border-gray-700 p-2">{{ $scholar['first_name'] }}</td>
                <td class="border border-gray-700 p-2">{{ $scholar['middle_name'] }}</td>
                <td class="border border-gray-700 p-2">{{ $scholar['campus'] }}</td>
                <td class="border border-gray-700 p-2">{{ $scholar['course'] }}</td>
                <td class="border border-gray-700 p-2">{{ $scholar['year_level'] }}</td>
                <td class="border border-gray-700 p-2">{{ $scholar['batch_name'] ?? $scholar['batch_id'] }}</td>
                <td class="border border-gray-700 p-2">{{ number_format($scholar['total_received'], 2) }}</td>
                <td class="border border-gray-700 p-2">{{ $scholar['not_claimed_status'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <!-- Signatories -->
    <div class="absolute bottom-0 right-0 flex justify-between mt-12">
        <div class="text-center justify-center items-center">
        <p class="mb-2">Certified by:</p>
        <div class="h-12 border-b border-gray-700 w-64"></div>
        <p class="text-red-600 font-semibold mt-1 text-sm">Signature over Printed Name of the School Registrar</p>
        </div>
        <div class="text-center justify-center items-center">
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