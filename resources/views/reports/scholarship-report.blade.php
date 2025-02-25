<!-- resources/views/pdfs/scholarship-report.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Scholarship Report - Batch {{ $batch->batch_no }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            max-width: 100px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('path-to-logo.png') }}" alt="Logo" class="logo">
        <img src="{{ public_path('path-to-logo.png') }}" alt="Logo" class="logo">
        <h1>University of Rizal System</h1>
        <h2>Scholarship Report - Batch {{ $batch->batch_no }}</h2>
        <p>School Year: {{ $batch->school_year }}</p>
        <p>Semester: {{ $batch->semester }}</p>
    </div>

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

    <div class="footer">
        <p>Total Scholars: {{ $scholars->count() }}</p>
        <p>Generated on: {{ now()->format('F d, Y') }}</p>
    </div>
</body>
</html>