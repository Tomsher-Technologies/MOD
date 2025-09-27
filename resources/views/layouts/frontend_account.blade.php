<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $title ?? env('APP_NAME') }}</title>

    <!-- Custom CSS for Public Sans -->
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel="icon" href="{{ asset('assets/img/favicon.ico') }}" type="image/x-icon">

    <!-- Importing Public Sans font -->
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS and other assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @yield('style')
</head>

<body dir="rtl" class="font-public-sans">

    @include('frontend.includes.header')

    @php
        $currentPath = Request::path();
    @endphp

    @if ($currentPath !== '/')
        <style>
            body {
                padding-top: 8rem;
            }
        </style>
    @endif

    <div class="min-h-[85vh]">
        @yield('content')
    </div>

    @include('frontend.includes.footer')

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            toastr.options = {
                closeButton: true,
                progressBar: true,
                timeOut: "5000",
                extendedTimeOut: "1000",
                positionClass: "toast-top-right",
                showDuration: "300",
                hideDuration: "1000",
                showMethod: "fadeIn",
                hideMethod: "fadeOut"
            };

            @if (session('success'))
                toastr.success("{{ session('success') }}");
            @endif

            @if (session('error'))
                toastr.error("{{ session('error') }}");
            @endif

            @if (session('info'))
                toastr.info("{{ session('info') }}");
            @endif

            @if (session('warning'))
                toastr.warning("{{ session('warning') }}");
            @endif
        });
    </script>

    @yield('script')
</body>

</html>
