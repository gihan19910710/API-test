<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reminder</title>
</head>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 20px;
        background-color: #f4f4f4;
    }
    .container {
        max-width: 600px;
        margin: auto;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    p {
        margin: 0 0 10px;
    }
</style>

<body>

    <div class="container">
        <h1>Reminder</h1>
        <p><strong>Outlet Name:</strong> {{ $outletName }}</p>
        <p><strong>Outlet Address:</strong> {{ $outletAddress }}</p>
        <p><strong>Assignee:</strong> {{ $assignee }}</p>
        <p><strong>Mobile:</strong> {{ $mobileNumber }}</p>
        <p><strong>Note:</strong> {{ $note }}</p>
    </div>

    {{-- <p>Outlet Name : {{ $outletName}}</p>
    <p>Outlet Address : {{ $outletAddress}}</p>
    <p>Assignee : {{ $assignee}}</p>
    <p>Mobile : {{ $mobileNumber}}</p>
    <p>Note : {{ $note}}</p> --}}
</body>
</html>

