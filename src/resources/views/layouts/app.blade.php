<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <title>coachtech_fleamarket</title>
    @yield('css')
</head>
<body>
    <header class="header">
    <div class="header__inner">
        <h1 class="header__logo">
            FashionablyLate
        </h1>
        @yield('button')
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>
</html>