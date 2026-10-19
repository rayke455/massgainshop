<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@lang('Dashboard temporarily unavailable')</title>
    <style>
        body { margin: 0; padding: 24px; min-height: 90vh; display: grid; place-items: center; background: #f5f7fc; color: #17213a; font-family: system-ui, sans-serif; }
        main { width: min(100%, 460px); box-sizing: border-box; padding: 32px; border-radius: 24px; background: white; box-shadow: 0 16px 60px #17213a12; }
        h1 { font-size: 26px; line-height: 1.25; } p { line-height: 1.7; color: #526078; }
        a { display: inline-block; margin: 8px 12px 0 0; color: #1d4ed8; } .retry { padding: 12px 20px; border-radius: 10px; background: #1d4ed8; color: white; text-decoration: none; }
    </style>
</head>
<body><main>
    <h1>@lang('Your dashboard is temporarily unavailable')</h1>
    <p>@lang('We could not load your account details. Please try again in a moment.')</p>
    <a class="retry" href="{{ route('user.dashboard') }}">@lang('Try again')</a>
    <a href="{{ url('/') }}">@lang('Back to home')</a>
</main></body>
</html>
