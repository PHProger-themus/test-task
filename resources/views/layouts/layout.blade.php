<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <link href="{{ \Illuminate\Support\Facades\URL::asset('css/style.css') }}" rel="stylesheet" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script src="{{ \Illuminate\Support\Facades\URL::asset('js/script.js') }}"></script>

</head>
<body class="antialiased" data-token="{{ csrf_token() }}">
    <div class="container{{ \Illuminate\Support\Facades\Auth::check() ? " dashboard" : "" }}">
        @if(\Illuminate\Support\Facades\Auth::check())
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <input type="submit" class="button" value="Выход">
            </form>
        @endif
        @yield('content')
    </div>
</body>
</html>
