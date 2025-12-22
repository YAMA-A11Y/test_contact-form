<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FashionablyLate</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>

<body>
    @unless(View::hasSection('no-header'))
        <header class="header">
            <h1 class="header__logo">FashionablyLate</h1>
            @yield('header-right')
        </header>
    @endunless
    
    <main>
        @yield('content')
    </main>
</body>

</html>