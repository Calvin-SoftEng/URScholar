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
    <div class="flex justify-between items-center border-b pb-4 mb-6">
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
        @php
            $imagePath = storage_path('app/public/sponsor/logo/' . $sponsor->logo);
            $imageData = base64_encode(file_get_contents($imagePath));
            $src = 'data:image/' . pathinfo($sponsor->logo, PATHINFO_EXTENSION) . ';base64,' . $imageData;
        @endphp

    </div>


    <!-- Report Title -->
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold uppercase underline">Scholarship Summary Report</h2>
        <p class="text-sm mt-2">Academic Year: <span class="font-semibold">2024–2025</span></p>
        <p class="text-sm">Semester: <span class="font-semibold">1st Semester</span></p>
    </div>

    <!-- Scholars Table -->
    <div class="overflow-x-auto">
        <table class="w-full border border-gray-700 text-sm">
            <thead class="bg-gray-200 text-gray-900">
                <tr>
                    <th class="border px-4 py-2 text-left">#</th>
                    <th class="border px-4 py-2 text-left">Student ID</th>
                    <th class="border px-4 py-2 text-left">Name</th>
                    <th class="border px-4 py-2 text-left">Course</th>
                    <th class="border px-4 py-2 text-left">Year Level</th>
                    <th class="border px-4 py-2 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                {{-- @foreach($scholars as $index => $scholar)
                <tr class="odd:bg-white even:bg-gray-100">
                    <td class="border px-4 py-2">{{ $index + 1 }}</td>
                    <td class="border px-4 py-2">{{ $scholar->student_id }}</td>
                    <td class="border px-4 py-2">{{ $scholar->name }}</td>
                    <td class="border px-4 py-2">{{ $scholar->course }}</td>
                    <td class="border px-4 py-2">{{ $scholar->year_level }}</td>
                    <td class="border px-4 py-2">{{ ucfirst($scholar->status) }}</td>
                </tr>
                @endforeach --}}
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <div class="mt-10 text-sm">
        <p>Generated on: {{ \Carbon\Carbon::now()->format('F d, Y') }}</p>
        <p>Prepared by: <span class="font-semibold">Scholarship Administrator</span></p>
    </div>

</body>
</html>

