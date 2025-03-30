<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.0.0/dist/tailwind.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <meta charset="utf-8">
    <title>Scholarship Report - Batch {{ $batch->batch_no }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
        }

        .header .logo {
            width: 80px;
            height: auto;
        }

        .header .logo-small {
            width: 60px;
            height: auto;
        }

        .header .logo-ched {
            width: 60px;
            height: auto;
        }

        .header .title {
            text-align: center;
            flex-grow: 1;
            font-size: 1.5em;
            font-weight: bold;
        }

        h2, p {
            text-align: center;
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
        <!-- First logo (University Seal) -->
        <img src="../public/assets/images/univ-seal.png" class="W-[75px] h-[100px]" alt="University Seal">
        
        <!-- Centered Title -->
        <div class="title">
            <h1>Republic of the Philippines</h1>
            <h1>University of Rizal System</h1>
        </div>
    
        <!-- Second logo (CHED) -->
        <img src="../public/assets/images/CHED.png" class="logo-ched" alt="CHED Logo">
    </div>
    
    <div>
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
