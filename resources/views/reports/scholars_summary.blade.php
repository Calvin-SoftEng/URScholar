<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scholarship Report</title>
    <style>
        {!! file_get_contents(public_path('css/tailwind.min.css')) !!}
    </style>
</head>
<body class="text-gray-900">

    <!-- Header Section -->
    <div class="text-center mb-6">
        <h1 class="font-bold text-xl">Annex 2 - TES Continuing Form 4</h1>
        <h2 class="text-red-600 font-bold text-lg">INSERT HEI LETTERHEAD</h2>
        <p>Republic of the Philippines</p>
        <p class="text-red-500 font-bold">(Name of the HEI)</p>
        <p class="text-red-500 font-bold">(Address of the HEI)</p>
    </div>

    <p class="mt-4 right-0">Date: ________________________</p>

    <!-- Title -->
    <h2 class="text-lg font-bold text-center mb-4">
        CERTIFICATION OF GRADUATED GRANTEES
    </h2>

    <!-- Certification Content -->
    <p class="text-justify mb-4">
        This is to certify that the total number of Continuing TES grantees by campus as shown below, 
        are qualified to avail of the Tertiary Education Subsidy (TES) program under R.A. No. 10931 also 
        known as Universal Access to Quality Tertiary Education (UAQTE) for the 
        <span class="text-red-500 font-semibold">(1st or 2nd semester)</span> of Academic Year __________.
    </p>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full border border-gray-700">
            <thead class="bg-gray-300">
                <tr>
                    <th class="border border-gray-700 px-4 py-2">Name of Campus</th>
                    <th class="border border-gray-700 px-4 py-2">Number of TES Grantees</th>
                    <th class="border border-gray-700 px-4 py-2">Number of TES Grantees with TES3-a</th>
                    <th class="border border-gray-700 px-4 py-2">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border border-gray-700 px-4 py-2">Campus A</td>
                    <td class="border border-gray-700 px-4 py-2"></td>
                    <td class="border border-gray-700 px-4 py-2"></td>
                    <td class="border border-gray-700 px-4 py-2"></td>
                </tr>
                <tr>
                    <td class="border border-gray-700 px-4 py-2">Campus B</td>
                    <td class="border border-gray-700 px-4 py-2"></td>
                    <td class="border border-gray-700 px-4 py-2"></td>
                    <td class="border border-gray-700 px-4 py-2"></td>
                </tr>
                <tr>
                    <td class="border border-gray-700 px-4 py-2 italic">(Insert more rows for additional Campus)</td>
                    <td class="border border-gray-700 px-4 py-2"></td>
                    <td class="border border-gray-700 px-4 py-2"></td>
                    <td class="border border-gray-700 px-4 py-2"></td>
                </tr>
                <tr class="bg-gray-200 font-bold">
                    <td class="border border-gray-700 px-4 py-2">Total</td>
                    <td class="border border-gray-700 px-4 py-2"></td>
                    <td class="border border-gray-700 px-4 py-2"></td>
                    <td class="border border-gray-700 px-4 py-2"></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Additional Notes -->
    <p class="mt-4">
        This further certifies that the student’s information indicated in 
        <span class="text-red-500 font-semibold">Annex 2 - TES Continuing Form 2</span> is accurate and complete.
    </p>

    <p class="mt-4 font-bold">
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
