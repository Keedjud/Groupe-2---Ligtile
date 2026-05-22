<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard — CTS HUG</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @vite(['resources/js/dashboard/app.js'])
</head>
<body>
    <div id="app"></div>
</body>
</html>
