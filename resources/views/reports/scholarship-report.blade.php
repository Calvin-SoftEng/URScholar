<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scholarship Report - Batch {{ $batch->batch_no }}</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            height: 100px;
        }

        .title {
            flex-grow: 1;
            text-align: center;
        }

        h1, h2 {
            margin: 5px 0;
        }

        .report-info {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .footer {
            margin-top: 20px;
            text-align: left;
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <div class="header">
        <!-- University Seal -->
        <img src="{{ public_path('assets/images/univ-seal.png') }}" alt="University Seal">

        <!-- Centered Title -->
        <div class="title">
            <h1>Republic of the Philippines</h1>
            <h1>University of Rizal System</h1>
            <h3>Scholarship and Financial Assistance Unit</h3>
        </div>

        <!-- CHED Logo -->
        <img src="{{ public_path('assets/images/CHED.png') }}" alt="CHED Logo">
    </div>

    <!-- Report Information -->
    <div class="report-info">
        <h2>Scholarship Report - Batch {{ $batch->batch_no }}</h2>
        <p><strong>School Year:</strong> {{ $batch->school_year }}</p>
        <p><strong>Semester:</strong> {{ $batch->semester }}</p>
    </div>

    <!-- Scholar Details Table -->
    <table>
        <thead>
            <tr>
                <th>Scholar Name</th>
                <th>Course</th>
                <th>Year Level</th>
                <th>Campus</th>
                <th>Grant</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($scholars as $scholar)
            <tr>
                <td>{{ $scholar->last_name }}, {{ $scholar->first_name }} {{ $scholar->middle_name }}</td>
                <td>{{ $scholar->course }}</td>
                <td>{{ $scholar->year_level }}</td>
                <td>{{ $scholar->campus }}</td>
                <td>{{ $scholar->grant }}</td>
                <td>{{ $scholar->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer Section -->
    <div class="footer">
        <p><strong>Total Scholars:</strong> {{ $scholars->count() }}</p>
        <p><strong>Generated on:</strong> {{ now()->format('F d, Y') }}</p>
    </div>

</body>
</html>
