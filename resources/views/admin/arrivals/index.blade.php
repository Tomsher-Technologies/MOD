@extends('layouts.admin_account', ['title' => __db('all_delegations')])

@section('content')
    <div>
        <div class="flex items-center justify-between gap-12 mb-4">

            <input type="date"
                class="p-3 !w-[20%] text-secondary-light !border-[#d1d5db] rounded-lg w-full border text-sm">
            <form class="w-[75%]" action="#">
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-3 text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                    </div>
                    <input type="search" id="default-search"
                        class="block w-full p-2.5 !ps-10 text-secondary-light text-sm !border-[#d1d5db] rounded-lg "
                        placeholder="Search by Delegation ID, Escorts, Drivers, Flight Number, Flight Name" required />
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
                            <img src="src/images/logo.svg" alt="">
                            <img src="src/images/md-logo.svg" class="light-logo" alt="Logo">
                        </div>


                        <a href="#" id="fullscreenToggleBtn"
                            class="px-4 flex items-center gap-4 py-2 text-sm font-medium text-center !text-[#B68A35] bg-white border !border-[#B68A35] rounded-lg focus:outline-none hover:bg-gray-100 hover:text-[#B68A35] focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-[#B68A35] dark:border-[#B68A35] dark:hover:text-white dark:hover:bg-[#B68A35]">


                            <span> Go Fullscreen</span>
                        </a>

                    </div>

                    <hr class="mx-6 border-neutral-200 h-5 ">
                    <table class="table-auto mb-0 !border-[#F9F7ED] w-full">
                        <thead>

                            <tr>
                                <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                    Sl.No
                                </th>
                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                    Delegation</th>
                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                    Continent</th>
                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">Country
                                </th>
                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                    Delegates</th>
                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">Escort
                                </th>
                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">Driver
                                </th>
                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">To
                                    Airport</th>
                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">Date &
                                    Time</th>
                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">Flight
                                    Number</th>
                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">Flight
                                    Name</th>
                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">Arrival
                                    Status</th>
                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="odd:bg-[#ffdddd] text-sm align-middle  !text-[#d00202] align-center">
                                <td class="px-4 py-2 border border-white">1</td>
                                <td class="px-4 py-2 border border-white]">

                                    <a href="arr-dep-delegates-view.html">
                                        DA25-001
                                    </a>

                                </td>
                                <td class="px-4 py-2 border border-white">Asia</td>
                                <td class="px-4 py-2 border border-white">Japan</td>
                                <td class="px-4 py-2 border border-white">

                                    <span class="block">Sara Al-Suwaidi</span>
                                    <span class="block">Dalia Al-Hassan</span>

                                </td>
                                <td class="px-4 py-2 border border-white">
                                    <span class="block">
                                        <a href="#" class="" data-modal-target="EscortModal"
                                            data-modal-toggle="EscortModal">DR023</a>

                                    </span>
                                    <span class="block">DR030
                                    </span>
                                </td>
                                <td class="px-4 py-2 border border-white">
                                    <span class="block">
                                        <a class="" href="#" data-modal-target="DriversModal"
                                            data-modal-toggle="DriversModal">EC001</a>
                                    </span>
                                    <span class="block">EC008</span>
                                </td>

                                <td class="px-4 py-2 border border-white">Dubai International</td>
                                <td class="px-4 py-2 border border-white">2025-06-25 08:45</td>
                                <td class="px-4 py-2 border border-white">JL786</td>
                                <td class="px-4 py-2 border border-white">Japan Airlines</td>
                                <td class="px-4 py-2 border border-white">To be Arrived</td>

                                <td class="px-4 py-3 border border-white">
                                    <div class="flex items-center gap-5">
                                        <a href="#" data-modal-target="ActionModal" data-modal-toggle="ActionModal">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 512 512">
                                                <path
                                                    d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z"
                                                    fill="#d00202"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr class="text-sm align-middle">
                                <td class="px-4 py-2 border border-gray-200">2</td>
                                <td class="px-4 py-2 border border-gray-200">DA25-002</td>
                                <td class="px-4 py-2 border border-gray-200">Europe</td>
                                <td class="px-4 py-2 border border-gray-200">Germany</td>
                                <td class="px-4 py-2 border border-gray-200">Rachel Lewis</td>
                                <td class="px-4 py-2 border border-gray-200">DR031</td>
                                <td class="px-4 py-2 border border-gray-200">EC011</td>

                                <td class="px-4 py-2 border border-gray-200">Sharjah Airport</td>
                                <td class="px-4 py-2 border border-gray-200">2025-06-25 09:30</td>
                                <td class="px-4 py-2 border border-gray-200">LH789</td>
                                <td class="px-4 py-2 border border-gray-200">Lufthansa</td>
                                <td class="px-4 py-2 border border-gray-200">To be Arrived </td>
                                <td class="px-4 py-3 border border-gray-200">
                                    <div class="flex items-center gap-5">
                                        <a href="#" data-modal-target="ActionModal"
                                            data-modal-toggle="ActionModal">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 512 512">
                                                <path
                                                    d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z"
                                                    fill="#000"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr class="odd:bg-[#acf3bc] !text-[#09720a]  text-sm align-middle">
                                <td class="px-4 py-2 border border-gray-200">3</td>
                                <td class="px-4 py-2 border border-white">DA25-003</td>
                                <td class="px-4 py-2 border border-white">Africa</td>
                                <td class="px-4 py-2 border border-white">Nigeria</td>
                                <td class="px-4 py-2 border border-white">Alex Morgan</td>
                                <td class="px-4 py-2 border border-white">DR040</td>
                                <td class="px-4 py-2 border border-white">EC012</td>

                                <td class="px-4 py-2 border border-white">Abu Dhabi Airport</td>
                                <td class="px-4 py-2 border border-white">2025-06-25 11:15</td>
                                <td class="px-4 py-2 border border-white">NG452</td>
                                <td class="px-4 py-2 border border-white">Air Nigeria</td>
                                <td class="px-4 py-2 border border-white">Arrived</td>
                                <td class="px-4 py-2 border border-white">-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
