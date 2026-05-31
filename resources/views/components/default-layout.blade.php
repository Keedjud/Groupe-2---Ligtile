<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        @isset($description)
        <meta name="description" content="{{ $description }}" />
        @endisset
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        @isset($title)
        <title>{{ $title }} - {{ config('app.name') }}</title>
        @else
        <title>{{ config('app.name') }}</title>
        @endisset

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body>
        <main>{{ $slot }}</main>
    </body>
</html>
