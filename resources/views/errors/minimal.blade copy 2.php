<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Something Went Wrong')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Tailwind CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    {{-- Animate.css (optional for fade-in/bounce effects) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>
<body class="bg-gradient-to-tr from-gray-900 via-gray-800 to-black text-white min-h-screen flex flex-col justify-center items-center px-4">

    {{-- SVG Graphic --}}
    <div class="w-48 mb-6 animate__animated animate__fadeInDown">
        <svg viewBox="0 0 24 24" fill="none" class="w-full h-full text-red-500">
            <path fill="currentColor" d="M12 0C5.372 0 0 5.373 0 12s5.372 12 12 12 12-5.373 12-12S18.628 0 12 0zm0 22C6.486 22 2 17.514 2 12S6.486 2 12 2s10 4.486 10 10-4.486 10-10 10z"/>
            <path fill="currentColor" d="M13 6h-2v7h2zm0 8h-2v2h2z"/>
        </svg>
    </div>

    {{-- Error Code --}}
    <h1 class="text-7xl font-extrabold animate__animated animate__bounceIn text-red-500">
        @yield('code', 'Oops!')
    </h1>

    {{-- Message --}}
    <p class="text-lg mt-4 text-gray-300 animate__animated animate__fadeInUp">
        @yield('message', 'Something went wrong. But don’t worry — you can go back home.')
    </p>

    {{-- Go Home Button --}}
    <a href="{{ url('/') }}" class="mt-6 px-6 py-2 bg-blue-600 hover:bg-blue-700 transition-all duration-200 rounded-lg text-white font-semibold shadow-lg animate__animated animate__fadeInUp animate__delay-1s">
        ← Back to Home
    </a>

    {{-- Footer --}}
    <footer class="absolute bottom-4 text-sm text-gray-500">
        &copy; {{ date('Y') }} YourApp. All rights reserved.
    </footer>

</body>
</html>
