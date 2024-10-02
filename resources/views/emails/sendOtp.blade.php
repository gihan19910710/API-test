<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your OTP Code</title>
</head>
<body>
    <h1>Your OTP is: {{ $otp }}</h1>
    <p>Please use this code to verify your email address. The code is valid for a limited time.</p>
</body>
</html>
