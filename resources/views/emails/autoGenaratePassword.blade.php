<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your Password</title>
</head>
<body>
    <h1>Your Password is: {{ $password }}</h1>
    <p>Do not share with other preson</p>
</body>
</html>
