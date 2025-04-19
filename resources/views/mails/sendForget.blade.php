<!DOCTYPE html>
<html>

<head>
    <title>Password Reset Request – URScholar</title>
</head>

<body>
    <h1 style="color: #2c3e50;">{{ $mailData['title'] }}</h1>

    <p style="font-size: 16px; color: #34495e;">
        {!! nl2br(e($mailData['body'])) !!}
    </p>

    <hr>

    <p style="font-size: 14px; color: #7f8c8d;">
        **Important Reminder:** <br>
        If you did not request a password reset, please ignore this email. For security reasons, your account password remains unchanged unless you initiate the reset process.
        <br><br>
        If you encounter any issues or need further assistance, please contact our support team at [Insert Support Email] or visit the FAQ section on the portal.
    </p>

    <p style="font-weight: bold; color: #2c3e50;">Thank you,</p>
    <p style="font-weight: bold; color: #2c3e50;">The Scholarship Team</p>
</body>

</html>
