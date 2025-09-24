<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank you for your plan purchase!</title>
</head>
<body>
    <h2>Hey {{ $user->name }},</h2>
    <p>We are excited to inform you that your <b>{{ $planName }}</b> has been successfully purchased!</p>

    <p>Thank you for choosing us.</p>
    <p>Best regards, <br> Kerry & The Team at <a href="https://athleat.com">ATHLEAT.com</a></p>
</body>
</html>