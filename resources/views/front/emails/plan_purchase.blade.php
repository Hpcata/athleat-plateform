<!-- resources/views/emails/plan_purchase.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank you for your plan purchase!</title>
</head>
<body>
    <h2>Hey {{ $user->name }},</h2>
    <p>We are excited to inform you that your <b>{{ $planName }}</b> has been successfully activated!</p>
    <p>Your plan is now active and ready to use. Please <a href="{{ $profileLandingPage }}">check your account</a> for the details and enjoy the benefits.</p>
    <p>If you have any questions or need assistance, feel free to reach out to our support team.</p>
    <p>Thank you for choosing us.</p>
    <p>Best regards, <br> Kerry & The Team at <a href="https://athleat.com">ATHLEAT.com</a></p>
</body>
</html>
