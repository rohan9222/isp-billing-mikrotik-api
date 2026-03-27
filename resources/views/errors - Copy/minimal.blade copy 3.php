<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>System Error Detected!</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Optional: Emergency Alert Sound -->
    <audio id="alertSound" autoplay>
        <source src="https://www.myinstants.com/media/sounds/alarm.mp3" type="audio/mp3">
    </audio>

    <style>
        body {
            background-color: #0a0a0a;
            color: #ff4d4d;
            font-family: 'Courier New', monospace;
            overflow: hidden;
        }

        .glitch-text {
            font-size: 3.5rem;
            font-weight: bold;
            position: relative;
            color: #ff4d4d;
            animation: flicker 1.5s infinite alternate;
        }

        .glitch-text::before,
        .glitch-text::after {
            content: attr(data-text);
            position: absolute;
            width: 100%;
            left: 0;
            top: 0;
            color: #ff0000;
            background: black;
            overflow: hidden;
        }

        .glitch-text::before {
            left: 2px;
            text-shadow: -2px 0 red;
            animation: glitch-anim 2s infinite linear alternate-reverse;
        }

        .glitch-text::after {
            left: -2px;
            text-shadow: -2px 0 cyan;
            animation: glitch-anim2 1.5s infinite linear alternate-reverse;
        }

        @keyframes flicker {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        @keyframes glitch-anim {
            0% { clip: rect(0, 900px, 0, 0); }
            20% { clip: rect(0, 900px, 25px, 0); }
            40% { clip: rect(0, 900px, 15px, 0); }
            60% { clip: rect(0, 900px, 35px, 0); }
            80% { clip: rect(0, 900px, 10px, 0); }
            100% { clip: rect(0, 900px, 30px, 0); }
        }

        @keyframes glitch-anim2 {
            0% { clip: rect(0, 900px, 35px, 0); }
            20% { clip: rect(0, 900px, 15px, 0); }
            40% { clip: rect(0, 900px, 25px, 0); }
            60% { clip: rect(0, 900px, 5px, 0); }
            80% { clip: rect(0, 900px, 30px, 0); }
            100% { clip: rect(0, 900px, 20px, 0); }
        }

        .system-box {
            background: #1a0000;
            border: 2px solid red;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 25px red;
        }

        .danger-line {
            border-top: 4px dashed red;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .cursor-blink {
            display: inline-block;
            width: 10px;
            height: 20px;
            background-color: red;
            animation: blink 1s step-start infinite;
        }

        @keyframes blink {
            50% { opacity: 0; }
        }
    </style>
</head>
<body onload="document.getElementById('alertSound').play()">

<div class="container text-center">
    <div class="row justify-content-center mt-5">
        <div class="col-lg-10">
            <div class="glitch-text mb-4" data-text="SYSTEM ERROR">SYSTEM ERROR</div>
            <div class="system-box text-start">
                <p><strong class="text-danger">[CRITICAL FAILURE]</strong> - A fatal exception has occurred in the server core.</p>
                <p>Error Code: <span class="text-warning">@yield('code')</span></p>
                <p>Message: <span class="text-warning">@yield('message')</span></p>
                <hr class="danger-line">
                <p>Initiating system rollback in <span id="countdown">10</span>s<span class="cursor-blink"></span></p>
            </div>
            <a href="{{ url('/') }}" class="btn btn-outline-danger mt-4">Abort Mission & Go Home</a>
        </div>
    </div>
</div>

<script>
    let timer = 10;
    const countDown = document.getElementById('countdown');
    const interval = setInterval(() => {
        timer--;
        countDown.textContent = timer;
        if (timer <= 0) {
            clearInterval(interval);
            window.location.href = "{{ url('/') }}";
        }
    }, 1000);
</script>
</body>
</html>
