<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="minimum-scale=1, initial-scale=1, width=device-width, shrink-to-fit=no" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    {{-- <script src="{{ mix('js/app.js') }}" defer></script> --}}

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    <style>
        body {
            background-color: black;
            color: white;
        }

        #app {
            background-color: black;
            color: white;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        *::-webkit-scrollbar {
            width: 0.5em;
            height: 0.5rem
        }
        *::-webkit-scrollbar-track {
            /* -webkit-box-shadow: inset 0 0 6px rgba(255,255,255,0.3); */
        }
        *::-webkit-scrollbar-thumb {
            background-color: rgba(205,27,84,0.2);
            outline: 1px solid slategrey;
        }
    </style>
</head>
<body>
    @yield('app')
</body>
</html>