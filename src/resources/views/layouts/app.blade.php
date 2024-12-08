<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=ABeeZee:ital@0;1&family=Inika:wght@400;700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <title>coachtech_fleamarket</title>
    @yield('css')
</head>
<body>
    <header class="header">
        <div class="header__inner">
            <div class="header__logo">
                <a href="/" class="header__logo--inner">
                    <img src="{{ asset('img/logo.svg') }}" alt="ロゴ">
                </a>
            </div>
            @yield('page-move')
        </div>
    </header>

    <main>
        @yield('content')
        @yield('js')
    </main>
</body>
</html>