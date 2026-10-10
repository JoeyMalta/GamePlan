<!DOCTYPE html>
<html lang="en" class="m-0 p-0">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Gameplan')</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>

<body class="m-0 flex min-h-svh flex-col bg-white p-0 font-sans text-zinc-900">

@include('partials.navbar')

<main class="flex min-h-0 flex-1">
    @yield('content')
</main>

</body>
</html>
