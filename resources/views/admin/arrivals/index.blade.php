@extends('layouts.admin_account', ['title' => __db('arrivals')])

@section('content')
    <div>
        <div class="flex items-center justify-between gap-12 mb-4">

            <input type="date"
                class="p-3 !w-[20%] text-secondary-light !border-[#d1d5db] rounded-lg w-full border text-sm">
            <form class="w-[75%]" action="{{ route('delegations.arrivalsIndex') }}" method="GET">
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-3 text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                    </div>
                    <input type="search" id="default-search" name="search_key" value="{{ request('search_key') }}"
                        class="block w-full p-2.5 !ps-10 text-secondary-light text-sm !border-[#d1d5db] rounded-lg "
                        placeholder="Search by Delegation ID, Escorts, Drivers, Flight Number, Flight Name" />
                    <button type="submit"
                        class="!text-[#5D471D] absolute end-[3px] bottom-[3px] !bg-[#E6D7A2] hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Search</button>
                </div>
            </form>
            <div class="text-center">
                <button
                    class="text-white !bg-[#B68A35] hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-sm rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                    type="button" data-drawer-target="drawer-example" data-drawer-show="drawer-example"
                    aria-controls="drawer-example">
                    Filter</button>
            </div>
        </div>
        <!-- Escorts -->
        <!-- Arrival Section -->
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-6 h-full " id="fullDiv">
            <div class="xl:col-span-12 h-full">
                <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">


                    <div class="flex items-center justify-between mb-5">

                        <h2 class="font-semibold mb-0 !text-[22px] mb-10 pb-4">Arrival</h2>

                        <div class="full-screen-logo flex items-center gap-8 hidden">
                            <img src="{{ asset('assets/img/logo.svg') }}" alt="">
                            <img src="{{ asset('assets/img/md-logo.svg') }}" class="light-logo" alt="Logo">
                        </div>


                        <a href="#" id="fullscreenToggleBtn"
                            class="px-4 flex items-center gap-4 py-2 text-sm font-medium text-center !text-[#B68A35] bg-white border !border-[#B68A35] rounded-lg focus:outline-none hover:bg-gray-100 hover:text-[#B68A35] focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-[#B68A35] dark:border-[#B68A35] dark:hover:text-white dark:hover:bg-[#B68A35]">
                            <span> Go Fullscreen</span>
                        </a>

                    </div>

                    <hr class="mx-6 border-neutral-200 h-5 ">
                    @php
                        $columns = [
                            [
                                'label' => 'Sl.No',
                                'render' => fn($row, $key) => $key +
                                    1 +
                                    ($arrivals->currentPage() - 1) * $arrivals->perPage(),
                            ],
                            ['label' => 'Delegation', 'render' => fn($row) => $row->delegate->delegation->code ?? '-'],
                            [
                                'label' => 'Continent',
                                'render' => fn($row) => $row->delegate->delegation->continent->value ?? '-',
                            ],
                            [
                                'label' => 'Country',
                                'render' => fn($row) => $row->delegate->delegation->country->value ?? '-',
                            ],
                            ['label' => 'Delegates', 'render' => fn($row) => $row->delegate->name_en ?? '-'],
                            ['label' => 'Escort', 'render' => fn($row) => $row->delegate->escort->name_en ?? '-'],
                            ['label' => 'Driver', 'render' => fn($row) => $row->delegate->driver->name_en ?? '-'],
                            ['label' => 'To Airport', 'render' => fn($row) => $row->airport->value ?? '-'],
                            [
                                'label' => 'Date & Time',
                                'render' => fn($row) => $row->date_time
                                    ? \Carbon\Carbon::parse($row->date_time)->format('Y-m-d h:i A')
                                    : '-',
                            ],
                            ['label' => 'Flight Number', 'render' => fn($row) => $row->flight_no ?? '-'],
                            ['label' => 'Flight Name', 'render' => fn($row) => $row->flight_name ?? '-'],
                            ['label' => 'Arrival Status', 'render' => fn($row) => $row->status->value ?? '-'],
                            [
                                'label' => 'Action',
                                'render' => function ($row) {
                                    return '<a href="#" class="edit-arrival-btn" 
                                    data-airport-id="' .
                                        e($row->airport_id) .
                                        '" 
                                    data-flight-no="' .
                                        e($row->flight_no) .
                                        '" 
                                    data-flight-name="' .
                                        e($row->flight_name) .
                                        '" 
                                    data-date-time="' .
                                        e(
                                            $row->date_time
                                                ? \Carbon\Carbon::parse($row->date_time)->format('Y-m-d\TH:i')
                                                : '',
                                        ) .
                                        '" 
                                    data-status-id="' .
                                        e($row->status_id) .
                                        '"
                                    data-arrival-id="' .
                                        e($row->id) .
                                        '"
                                    data-modal-target="ActionModal" data-modal-toggle="ActionModal"
                                    >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z" fill="#000"></path></svg>
                                </a>';
                                },
                            ],
                        ];
                    @endphp
                    <x-reusable-table :columns="$columns" :data="$arrivals" />
                </div>
            </div>
        </div>

    </div>

    <!-- Arrival Edit Modal -->
    <div id="ActionModal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative !w-[70%] mx-auto">
            <!-- Modal content -->
            <div class="bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 border-b rounded-t">
                    <h3 class="text-xl font-semibold text-gray-900">Edit Arrival</h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                        data-modal-hide="ActionModal" id="close-modal-btn">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>
                <!-- Modal body -->
                <form id="arrival-form" action="" data-ajax-form="true" method="POST" class="p-6 space-y-6">
                    @csrf
                    @method('POST')

                    <div id="flight-inputs-arrival" class="grid grid-cols-5 gap-5">
                        <div>
                            <label for="arrival-airport-id" class="form-label block mb-1 text-gray-700 font-medium">Arrival
                                Airport:</label>
                            <select id="arrival-airport-id" name="airport_id" class="p-3 rounded-lg w-full border text-sm">
                                <option value="">Select To Airport</option>
                                @foreach (getDropdown('airports')->options as $option)
                                    <option value="{{ $option->id }}">{{ $option->value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="arrival-flight-no" class="form-label block mb-1 text-gray-700 font-medium">Flight
                                No:</label>
                            <input type="text" id="arrival-flight-no" name="flight_no"
                                class="p-3 rounded-lg w-full border !border-[#d1d5db] text-sm"
                                placeholder="Enter Flight No">
                        </div>
                        <div>
                            <label for="arrival-flight-name" class="form-label block mb-1 text-gray-700 font-medium">Flight
                                Name:</label>
                            <input type="text" id="arrival-flight-name" name="flight_name"
                                class="p-3 rounded-lg w-full border !border-[#d1d5db] text-sm"
                                placeholder="Enter Flight Name">
                        </div>
                        <div>
                            <label for="arrival-date-time" class="form-label block mb-1 text-gray-700 font-medium">Date &
                                Time:</label>
                            <input type="datetime-local" id="arrival-date-time" name="date_time"
                                class="p-3 rounded-lg w-full border !border-[#d1d5db] text-sm">
                        </div>
                        <div>
                            <label for="arrival-status-id" class="form-label block mb-1 text-gray-700 font-medium">Arrival
                                Status:</label>
                            <select id="arrival-status-id" name="status_id" class="p-3 rounded-lg w-full border text-sm">
                                <option value="">Select Status</option>
                                @foreach (getDropdown('arrival_status')->options as $option)
                                    <option value="{{ $option->id }}">{{ $option->value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <input type="hidden" id="arrival-id" name="id" value="">
                    <button type="submit"
                        class="text-white flex items-center gap-1 !bg-[#B68A35] hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-sm rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        Update
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // const fullscreenDiv = document.getElementById('fullDiv');

        // $('#fullscreenToggleBtn').on('click', function() {
        //     const isInFullscreen =
        //         document.fullscreenElement ||
        //         document.webkitFullscreenElement ||
        //         document.mozFullScreenElement ||
        //         document.msFullscreenElement;

        //     if (!isInFullscreen) {
        //         // Enter fullscreen
        //         if (fullscreenDiv.requestFullscreen) {
        //             console.log("standard fullscreen");
        //             fullscreenDiv.requestFullscreen();
        //         } else if (fullscreenDiv.webkitRequestFullscreen) {
        //             console.log("webkit fullscreen");
        //             fullscreenDiv.webkitRequestFullscreen();
        //         } else if (fullscreenDiv.mozRequestFullScreen) {
        //             console.log("moz fullscreen");
        //             fullscreenDiv.mozRequestFullScreen();
        //         } else if (fullscreenDiv.msRequestFullscreen) {
        //             fullscreenDiv.msRequestFullscreen();
        //             console.log("ms fullscreen");
        //         }
        //     } else {
        //         // Exit fullscreen
        //         if (document.exitFullscreen) {
        //             document.exitFullscreen();
        //         } else if (document.webkitExitFullscreen) {
        //             document.webkitExitFullscreen();
        //         } else if (document.msExitFullscreen) {
        //             document.msExitFullscreen();
        //         }
        //     }
        // });

        // // Listen for fullscreen changes
        // $(document).on('fullscreenchange webkitfullscreenchange mozfullscreenchange MSFullscreenChange', function() {
        //     const isInFullscreen =
        //         document.fullscreenElement ||
        //         document.webkitIsFullScreen ||
        //         document.mozFullScreen ||
        //         document.webkitFullscreenElement ||
        //         document.mozFullScreenElement ||
        //         document.msFullscreenElement;

        //     if (isInFullscreen) {
        //         $('.hide-when-fullscreen').hide();
        //         $('#fullscreenToggleBtn').text('Exit Fullscreen');
        //     } else {
        //         $('.hide-when-fullscreen').show();
        //         $('#fullscreenToggleBtn').text('Go Fullscreen');
        //     }
        // });


        // // Listen for fullscreen changes
        // $(document).on('fullscreenchange webkitfullscreenchange mozfullscreenchange MSFullscreenChange', function() {
        //     const isInFullscreen =
        //         document.fullscreenElement ||
        //         document.webkitFullscreenElement ||
        //         document.mozFullScreenElement ||
        //         document.msFullscreenElement;

        //     if (isInFullscreen) {
        //         $('.hide-when-fullscreen').hide();
        //         $('.full-screen-logo').removeClass('hidden'); // SHOW during fullscreen
        //         $('#fullscreenToggleBtn').text('Exit Fullscreen');
        //     } else {
        //         $('.hide-when-fullscreen').show();
        //         $('.full-screen-logo').css('display', 'none'); // HIDE when not in fullscreen
        //         $('#fullscreenToggleBtn').text('Go Fullscreen');
        //     }
        // });



        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('ActionModal');
            const closeModalBtn = document.getElementById('close-modal-btn');

            const airportSelect = document.getElementById('arrival-airport-id');
            const flightNoInput = document.getElementById('arrival-flight-no');
            const flightNameInput = document.getElementById('arrival-flight-name');
            const dateTimeInput = document.getElementById('arrival-date-time');
            const statusSelect = document.getElementById('arrival-status-id');
            const arrivalIdInput = document.getElementById('arrival-id');


            // Function to show modal
            function showModal() {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden'; // prevent background scroll
            }

            // Function to hide modal
            function hideModal() {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }

            // Setup close button
            closeModalBtn.onclick = hideModal;

            // Close modal on clicking outside content
            modal.addEventListener('click', function(e) {
                if (e.target === modal) hideModal();
            });


            // Handle clicks on all edit buttons
            document.querySelectorAll('.edit-arrival-btn').forEach(button => {
                button.addEventListener('click', e => {
                    e.preventDefault();

                    // Read data attributes from clicked row
                    const airportId = button.getAttribute('data-airport-id');
                    const statusId = button.getAttribute('data-status-id');
                    const flightNo = button.getAttribute('data-flight-no');
                    const flightName = button.getAttribute('data-flight-name');
                    const dateTime = button.getAttribute('data-date-time');
                    const arrivalId = button.getAttribute('data-arrival-id');


                    // Set values for text inputs
                    flightNoInput.value = flightNo;
                    flightNameInput.value = flightName;
                    dateTimeInput.value = dateTime;
                    arrivalIdInput.value = arrivalId;

                    // Set selected option for airport dropdown
                    if (airportId) {
                        airportSelect.value = airportId;
                    }

                    // Set selected option for status dropdown
                    if (statusId) {
                        statusSelect.value = statusId;
                    }

                    // Open modal
                    showModal();
                });
            });
        });
    </script>
@endpush
