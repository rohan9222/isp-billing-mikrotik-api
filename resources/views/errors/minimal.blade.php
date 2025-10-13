<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title')</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #1f2937, #111827, #000000);
      color: #ffffff;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
      overflow: hidden;
    }

    .icon {
      width: 120px;
      margin-bottom: 30px;
      animation: fadeInDown 1s ease-out;
    }

    .icon svg {
      width: 100%;
      height: auto;
      fill: #ef4444;
    }

    h1 {
      font-size: 64px;
      font-weight: 900;
      color: #ef4444;
      animation: bounceIn 1s ease-out;
      margin: 0;
    }

    p {
      font-size: 18px;
      color: #d1d5db;
      margin-top: 20px;
      animation: fadeInUp 1.2s ease-out;
      max-width: 500px;
    }

    a.button {
      margin-top: 30px;
      padding: 12px 24px;
      background-color: #2563eb;
      color: white;
      border: none;
      border-radius: 8px;
      font-weight: 600;
      text-decoration: none;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      transition: background-color 0.3s ease;
      animation: fadeInUp 1.5s ease-out;
      display: inline-block;
    }

    a.button:hover {
      background-color: #1d4ed8;
    }

    footer {
      position: absolute;
      bottom: 20px;
      font-size: 14px;
      color: #9ca3af;
      animation: fadeIn 2s ease-out;
    }

    /* Animations */
    @keyframes fadeInDown {
      from { opacity: 0; transform: translateY(-40px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(40px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    @keyframes bounceIn {
      0% { opacity: 0; transform: scale(0.3); }
      50% { opacity: 1; transform: scale(1.05); }
      70% { transform: scale(0.9); }
      100% { transform: scale(1); }
    }

    @media (max-width: 600px) {
      h1 {
        font-size: 48px;
      }

      p {
        font-size: 16px;
      }

      .icon {
        width: 80px;
      }
    }
  </style>
</head>
<body>

  <div class="icon">
    <svg viewBox="0 0 24 24">
      <path d="M12 0C5.372 0 0 5.373 0 12s5.372 12 12 12 12-5.373 12-12S18.628 0 12 0zm0 22C6.486 22 2 17.514 2 12S6.486 2 12 2s10 4.486 10 10-4.486 10-10 10z"/>
      <path d="M13 6h-2v7h2zm0 8h-2v2h2z"/>
    </svg>
  </div>

  <h1>Oops! @yield('code') Error</h1>
  <p>@yield('message')</p>
  <a href="/" class="button">← Back to Home</a>

  <footer>&copy; 2025 {{ config('app.name', 'Friends Communications Ltd') }}. All rights reserved.</footer>

</body>
</html>
