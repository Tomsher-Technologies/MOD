<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <meta name="robots" content="noindex, nofollow">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $title || env('APP_NAME') }}</title>

    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">

    <link rel="icon" href="{{ asset('assets/img/favicon.ico') }}" type="image/x-icon">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @yield('style')
    <style>
.select2-container--open .select2-dropdown {
  z-index: 9999 !important;
}

    </style>
</head>

<body class="bg-[#FFFCEE]" dir="rtl">
    @yield('content')
    
    <script>

        
    </script>
    @yield('script')


</body>

</html>