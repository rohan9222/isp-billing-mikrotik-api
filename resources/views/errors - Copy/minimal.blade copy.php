<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>500 - Server Error</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #0d1117;
            color: #c9d1d9;
            font-family: 'Courier New', Courier, monospace;
            padding-top: 10vh;
        }

        .glitch {
            font-size: 3rem;
            font-weight: bold;
            position: relative;
            color: #c9d1d9;
        }

        .glitch::before,
        .glitch::after {
            content: attr(data-text);
            position: absolute;
            left: 0;
            width: 100%;
            overflow: hidden;
        }

        .glitch::before {
            animation: glitchTop 2s infinite linear alternate-reverse;
            color: #ff005a;
            top: -2px;
        }

        .glitch::after {
            animation: glitchBottom 1.5s infinite linear alternate-reverse;
            color: #00ffff;
            top: 2px;
        }

        @keyframes glitchTop {
            0% { clip: rect(0, 9999px, 0, 0); }
            20% { clip: rect(0, 9999px, 5px, 0); }
            40% { clip: rect(0, 9999px, 0, 0); }
            60% { clip: rect(0, 9999px, 3px, 0); }
            80% { clip: rect(0, 9999px, 0, 0); }
            100% { clip: rect(0, 9999px, 4px, 0); }
        }

        @keyframes glitchBottom {
            0% { clip: rect(0, 9999px, 0, 0); }
            20% { clip: rect(5px, 9999px, 9999px, 0); }
            40% { clip: rect(0, 9999px, 0, 0); }
            60% { clip: rect(3px, 9999px, 9999px, 0); }
            80% { clip: rect(0, 9999px, 0, 0); }
            100% { clip: rect(4px, 9999px, 9999px, 0); }
        }

        .terminal-box {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 20px;
            border-left: 5px solid #ff005a;
            box-shadow: 0 0 10px rgba(255, 0, 90, 0.3);
        }

        .btn-outline-light:hover {
            background-color: #ffffff10;
        }

        .cursor-blink {
            display: inline-block;
            width: 10px;
            height: 20px;
            background-color: #c9d1d9;
            animation: blink 1s infinite;
        }

        @keyframes blink {
            0%, 50% { opacity: 1; }
            50.01%, 100% { opacity: 0; }
        }
    </style>
</head>
<body>
<div class="container text-center">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="glitch mb-3" data-text="500 ERROR">@yield('code')</div>
            <div class="terminal-box text-start">
                <p><span class="text-danger">[FATAL]</span> Internal Server Error has occurred.</p>
                <p>Something went wrong on our end. Please try again later.</p>
                <p>System reboot in <span id="countdown">10</span>s<span class="cursor-blink"></span></p>
            </div>
            <a href="{{ url('/') }}" class="btn btn-outline-light mt-4">Return to Home</a>
        </div>
    </div>
</div>

{{-- <script>
    let counter = 10;
    const el = document.getElementById('countdown');
    const interval = setInterval(() => {
        counter--;
        if (counter <= 0) {
            clearInterval(interval);
            window.location.href = "{{ url('/') }}";
        } else {
            el.textContent = counter;
        }
    }, 1000);
</script> --}}
</body>
</html>
