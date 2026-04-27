<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="description" content="{{ config('app.description') }}" />

        @vite(['resources/js/app.ts', 'resources/css/app.css'])
    </head>
    <body>
        @inertia
    </body>
</html>