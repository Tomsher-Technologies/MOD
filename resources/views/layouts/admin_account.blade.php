<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $title ?? env('APP_NAME') }}</title>

    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">

    <link rel="icon" href="{{ asset('assets/img/favicon.ico') }}" type="image/x-icon">

    <script src="{{ asset('assets/js/ajax-form-handler.js') }}"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @yield('style')
    <style>
        .tox-promotion {
            display: none !important;
        }
        
        :fullscreen #fullDiv table#arrivals-table thead tr,
        :fullscreen #fullDiv table#departures-table thead tr,
        :fullscreen #fullDiv1 table#arrivals-table thead tr,
        :fullscreen #fullDiv1 table#departures-table thead tr {
            font-size: 20px !important;
        }
        
        :fullscreen #fullDiv table#arrivals-table tbody tr,
        :fullscreen #fullDiv table#departures-table tbody tr,
        :fullscreen #fullDiv1 table#arrivals-table tbody tr,
        :fullscreen #fullDiv1 table#departures-table tbody tr {
            font-size: 18px !important;
        }
        
        /* For webkit browsers */
        ::-webkit-full-screen #fullDiv table#arrivals-table thead tr,
        ::-webkit-full-screen #fullDiv table#departures-table thead tr,
        ::-webkit-full-screen #fullDiv1 table#arrivals-table thead tr,
        ::-webkit-full-screen #fullDiv1 table#departures-table thead tr {
            font-size: 20px !important;
        }
        
        ::-webkit-full-screen #fullDiv table#arrivals-table tbody tr,
        ::-webkit-full-screen #fullDiv table#departures-table tbody tr,
        ::-webkit-full-screen #fullDiv1 table#arrivals-table tbody tr,
        ::-webkit-full-screen #fullDiv1 table#departures-table tbody tr {
            font-size: 18px !important;
        }
        
        /* For mozilla browsers */
        :-moz-full-screen #fullDiv table#arrivals-table thead tr,
        :-moz-full-screen #fullDiv table#departures-table thead tr,
        :-moz-full-screen #fullDiv1 table#arrivals-table thead tr,
        :-moz-full-screen #fullDiv1 table#departures-table thead tr {
            font-size: 20px !important;
        }
        
        :-moz-full-screen #fullDiv table#arrivals-table tbody tr,
        :-moz-full-screen #fullDiv table#departures-table tbody tr,
        :-moz-full-screen #fullDiv1 table#arrivals-table tbody tr,
        :-moz-full-screen #fullDiv1 table#departures-table tbody tr {
            font-size: 18px !important;
        }
        
        .fullscreen-table thead tr {
            font-size: 20px !important;
        }
        
        .fullscreen-table tbody tr {
            font-size: 18px !important;
        }
    </style>
</head>

<body class="bg-[#f2f2f2]" dir="rtl">

    @include('admin.includes.sidebar')

    <main class="dashboard-main">

        @include('admin.includes.header')

        <div class="dashboard-main-body ">
            @yield('content')
        </div>

        <div id="default-modal" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow-sm ">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t  border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900 ">
                            Alert
                        </h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-hide="default-modal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-4 md:p-5 space-y-4">
                        <p class="text-base leading-relaxed text-black">
                            The system is shutting down now.
                        </p>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center justify-end p-4 md:p-5 border-t border-gray-200 rounded-b ">
                        <button data-modal-hide="default-modal" type="button"
                            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400  dark:hover:text-white dark:hover:bg-gray-700">OK</button>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.partials._confirmation-modal')
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const fullscreenDiv = document.getElementById('fullDiv');


            $('#fullscreenToggleBtn').on('click', function() {
                const isInFullscreen =
                    document.fullscreenElement ||
                    document.webkitFullscreenElement ||
                    document.mozFullScreenElement ||
                    document.msFullscreenElement;

                if (!isInFullscreen) {
                    // Enter fullscreen
                    if (fullscreenDiv.requestFullscreen) {
                        fullscreenDiv.requestFullscreen();
                    } else if (fullscreenDiv.webkitRequestFullscreen) {
                        fullscreenDiv.webkitRequestFullscreen();
                    } else if (fullscreenDiv.msRequestFullscreen) {
                        fullscreenDiv.msRequestFullscreen();
                    }
                } else {
                    // Exit fullscreen
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    } else if (document.webkitExitFullscreen) {
                        document.webkitExitFullscreen();
                    } else if (document.msExitFullscreen) {
                        document.msExitFullscreen();
                    }
                }
            });

            // Listen for fullscreen changes
            $(document).on('fullscreenchange webkitfullscreenchange mozfullscreenchange MSFullscreenChange',
                function() {
                    const isInFullscreen =
                        document.fullscreenElement ||
                        document.webkitFullscreenElement ||
                        document.mozFullScreenElement ||
                        document.msFullscreenElement;

                    if (isInFullscreen) {
                        $('.hide-when-fullscreen').hide();
                        $('#fullscreenToggleBtn').text('Exit Fullscreen');
                    } else {
                        $('.hide-when-fullscreen').show();
                        $('#fullscreenToggleBtn').text('Go Fullscreen');
                    }
                });


            // Listen for fullscreen changes
            $(document).on('fullscreenchange webkitfullscreenchange mozfullscreenchange MSFullscreenChange',
                function() {
                    const isInFullscreen =
                        document.fullscreenElement ||
                        document.webkitFullscreenElement ||
                        document.mozFullScreenElement ||
                        document.msFullscreenElement;

                    if (isInFullscreen) {
                        $('.hide-when-fullscreen').hide();
                        $('.full-screen-logo').css('display', 'flex'); // SHOW during fullscreen
                        $('#fullscreenToggleBtn').text('Exit Fullscreen');
                        // Add class to increase font size for arrivals table
                        $('#fullDiv table').addClass('fullscreen-table');
                    } else {
                        $('.hide-when-fullscreen').show();
                        $('.full-screen-logo').css('display', 'none'); // HIDE when not in fullscreen
                        $('#fullscreenToggleBtn').text('Go Fullscreen');
                        // Remove class to reset font size
                        $('#fullDiv table').removeClass('fullscreen-table');
                    }
                });

            const fullscreenDiv1 = document.getElementById('fullDiv1');

            $('#fullscreenToggleBtn1').on('click', function() {
                const isInFullscreen =
                    document.fullscreenElement ||
                    document.webkitFullscreenElement ||
                    document.mozFullScreenElement ||
                    document.msFullscreenElement;

                if (!isInFullscreen) {
                    // Enter fullscreen
                    if (fullscreenDiv1.requestFullscreen) {
                        fullscreenDiv1.requestFullscreen();
                    } else if (fullscreenDiv1.webkitRequestFullscreen) {
                        fullscreenDiv1.webkitRequestFullscreen();
                    } else if (fullscreenDiv1.msRequestFullscreen) {
                        fullscreenDiv1.msRequestFullscreen();
                    }
                } else {
                    // Exit fullscreen
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    } else if (document.webkitExitFullscreen) {
                        document.webkitExitFullscreen();
                    } else if (document.msExitFullscreen) {
                        document.msExitFullscreen();
                    }
                }
            });

            // Listen for fullscreen changes
            $(document).on('fullscreenchange webkitfullscreenchange mozfullscreenchange MSFullscreenChange',
                function() {
                    const isInFullscreen =
                        document.fullscreenElement ||
                        document.webkitFullscreenElement ||
                        document.mozFullScreenElement ||
                        document.msFullscreenElement;

                    if (isInFullscreen) {
                        $('.hide-when-fullscreen').hide();
                        $('#fullscreenToggleBtn1').text('Exit Fullscreen');
                    } else {
                        $('.hide-when-fullscreen').show();
                        $('#fullscreenToggleBtn1').text('Go Fullscreen');
                    }
                });


            // Listen for fullscreen changes
            $(document).on('fullscreenchange webkitfullscreenchange mozfullscreenchange MSFullscreenChange',
                function() {
                    const isInFullscreen =
                        document.fullscreenElement ||
                        document.webkitFullscreenElement ||
                        document.mozFullScreenElement ||
                        document.msFullscreenElement;

                    if (isInFullscreen) {
                        $('.hide-when-fullscreen').hide();
                        $('.full-screen-logo').css('display', 'flex'); // SHOW during fullscreen
                        $('#fullscreenToggleBtn1').text('Exit Fullscreen');
                        // Add class to increase font size for departures table
                        $('#fullDiv1 table').addClass('fullscreen-table');
                    } else {
                        $('.hide-when-fullscreen').show();
                        $('.full-screen-logo').css('display', 'none'); // HIDE when not in fullscreen
                        $('#fullscreenToggleBtn1').text('Go Fullscreen');
                        // Remove class to reset font size
                        $('#fullDiv1 table').removeClass('fullscreen-table');
                    }
                });



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
    @stack('scripts')

</body>

</html>
