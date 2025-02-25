<!DOCTYPE html>
<html>

<head>
    <title>ItsolutionStuff.com</title>
</head>

<body>
    <h1 style="color: #2c3e50;">{{ $mailData['title'] }}</h1>

    <p style="font-size: 16px; color: #34495e;">
        {!! nl2br(e($mailData['body'])) !!}
    </p>

    <hr>

    <p style="font-size: 14px; color: #7f8c8d;">
        **Important Reminder:** <br>
        Please ensure that you complete your scholarship application before the deadline. Missing any required documents may affect your eligibility.  
        <br>
        If you encounter any issues during the application process, please reach out to our support team at [Insert Support Email] or visit our FAQ section on the portal.
    </p>

    <p style="font-weight: bold; color: #2c3e50;">Thank you,</p>
    <p style="font-weight: bold; color: #2c3e50;">The Scholarship Team</p>
</body>

</html>