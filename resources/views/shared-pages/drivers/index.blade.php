<div class="dashboard-main-body ">
    <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
        <h2 class="font-semibold mb-0 !text-[22px] ">Drivers</h2>
    </div>


    <!-- Drivers -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
        <div class="xl:col-span-12 h-full">
            <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">

                <div class=" mb-4 flex items-center justify-between gap-3">
                    <form class="w-[50%] me-4" action="#">
                        <div class="relative">

                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-3 text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                            </div>
                            <input type="search" id="default-search"
                                class="block w-full p-2.5 !ps-10 text-secondary-light text-sm !border-[#d1d5db] rounded-lg "
                                placeholder="Search by Military Number, Name, Mobile Number" required />
                            <button type="submit"
                                class="!text-[#5D471D] absolute end-[3px] bottom-[3px] !bg-[#E6D7A2] hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Search</button>

                        </div>
                    </form>

                    <div class="text-center">
                        <button
                            class="text-white flex items-center gap-1 !bg-[#B68A35] hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-sm rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                            type="button" data-drawer-target="drawer-example" data-drawer-show="drawer-example"
                            aria-controls="drawer-example">
                            <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-width="1.5"
                                    d="M18.796 4H5.204a1 1 0 0 0-.753 1.659l5.302 6.058a1 1 0 0 1 .247.659v4.874a.5.5 0 0 0 .2.4l3 2.25a.5.5 0 0 0 .8-.4v-7.124a1 1 0 0 1 .247-.659l5.302-6.059c.566-.646.106-1.658-.753-1.658Z" />
                            </svg>


                            <span> Filter</span>

                        </button>
                    </div>
                </div>
                <table class="table-auto mb-0 !border-[#F9F7ED] w-full">
                    <thead>
                        <tr>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Military Number</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Title</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Name</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Mobile Number</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Vehicle Type</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Plate Number</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Capacity</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Assigned Delegation</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class=" text-sm align-[middle]">
                            <td class="px-4 py-3 border border-gray-200">MIL-1024</td>
                            <td class="px-4 py-3 border border-gray-200">Captain</td>
                            <td class="px-4 py-3 border border-gray-200">Saeed Al Kaabi</td>
                            <td class="px-4 py-3 text-end border border-gray-200" dir="ltr">+971 55 789 3210</td>
                            <td class="px-4 py-3 border border-gray-200">SUV</td>
                            <td class="px-4 py-3 border border-gray-200">DXB 4567</td>
                            <td class="px-4 py-3 border border-gray-200">5</td>
                            <td class="px-4 py-3 !text-[#B68A35] border border-gray-200 "><a
                                    href="delegate-details.html">DA25-001</a></td>
                            <td class="px-4 py-2 border border-gray-200">
                                <div class="flex align-center gap-4">

                                    <a href="drivers-edit.html">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 512 512">
                                            <path
                                                d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z"
                                                fill="#B68A35" />
                                        </svg>
                                    </a>
                                    <a href="#" data-modal-target="deleteModal" data-modal-toggle="deleteModal">
                                        <svg class="w-5.5 h-5.5 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="#B68A35" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="1.5"
                                                d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z">
                                            </path>
                                        </svg>
                                    </a>
                                    <a href="assign-escorts.html"
                                        class="!bg-[#E6D7A2] !text-[#5D471D] px-3 text-sm flex items-center gap-2 py-1 text-sm rounded-lg me-auto">
                                        <svg class="w-5 h-5 !text-[#5D471D]" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="2"
                                                d="M16 12h4m-2 2v-4M4 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>

                                        <span> Assign</span>

                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr class="bg-[#f2eccf] text-sm align-[middle]">
                            <td class="px-4 py-3 border border-gray-200">MIL-2548</td>
                            <td class="px-4 py-3 border border-gray-200">Mr</td>
                            <td class="px-4 py-3 border border-gray-200">Mohammed Al Obaidi</td>
                            <td class="px-4 py-3 text-end border border-gray-200" dir="ltr">+971 50 112 3344</td>
                            <td class="px-4 py-3 border border-gray-200">Sedan</td>
                            <td class="px-4 py-3 border border-gray-200">AUH 2345</td>
                            <td class="px-4 py-3 border border-gray-200">4</td>
                            <td class="px-4 py-3 !text-[#B68A35] border border-gray-200 "><a
                                    href="delegate-details.html">DA25-002</a></td>
                            <td class="px-4 py-2 border border-gray-200">
                                <div class="flex align-center gap-4">

                                    <a href="drivers-edit.html">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 512 512">
                                            <path
                                                d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z"
                                                fill="#B68A35" />
                                        </svg>
                                    </a>
                                    <a href="#" data-modal-target="deleteModal"
                                        data-modal-toggle="deleteModal">
                                        <svg class="w-5.5 h-5.5 " aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="none" viewBox="0 0 24 24">
                                            <path stroke="#B68A35" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="1.5"
                                                d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z">
                                            </path>
                                        </svg>
                                    </a>
                                    <a href="#"
                                        class="!bg-[#E6D7A2] !text-[#5D471D] px-3 text-sm flex items-center gap-2 py-1 text-sm rounded-lg me-auto">
                                        <svg class="w-5 h-5 !text-[#5D471D]" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="2"
                                                d="M16 12h4m-2 2v-4M4 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>

                                        <span> Assign</span>

                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr class=" text-sm align-[middle]">
                            <td class="px-4 py-3 border border-gray-200">MIL-8789</td>
                            <td class="px-4 py-3 border border-gray-200">Ms</td>
                            <td class="px-4 py-3 border border-gray-200">Fatima Al Zahra</td>
                            <td class="px-4 py-3 text-end border border-gray-200" dir="ltr">+971 52 223 4455</td>
                            <td class="px-4 py-3 border border-gray-200">Hatchback</td>
                            <td class="px-4 py-3 border border-gray-200">SHJ 9876</td>
                            <td class="px-4 py-3 border border-gray-200">5</td>
                            <td class="px-4 py-3 !text-[#B68A35] border border-gray-200 "><a
                                    href="delegate-details.html">DA25-003</a></td>
                            <td class="px-4 py-2 border border-gray-200">
                                <div class="flex align-center gap-4">

                                    <a href="drivers-edit.html">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 512 512">
                                            <path
                                                d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z"
                                                fill="#B68A35" />
                                        </svg>
                                    </a>
                                    <a href="#" data-modal-target="deleteModal"
                                        data-modal-toggle="deleteModal">
                                        <svg class="w-5.5 h-5.5 " aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="none" viewBox="0 0 24 24">
                                            <path stroke="#B68A35" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="1.5"
                                                d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z">
                                            </path>
                                        </svg>
                                    </a>
                                    <a href="#"
                                        class="!bg-[#E6D7A2] !text-[#5D471D] px-3 text-sm flex items-center gap-2 py-1 text-sm rounded-lg me-auto">
                                        <svg class="w-5 h-5 !text-[#5D471D]" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="2"
                                                d="M16 12h4m-2 2v-4M4 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>

                                        <span> Assign</span>

                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr class=" text-sm align-[middle]">
                            <td class="px-4 py-3 border border-gray-200">MIL-0024</td>
                            <td class="px-4 py-3 border border-gray-200">Captain</td>
                            <td class="px-4 py-3 border border-gray-200">John Doe</td>
                            <td class="px-4 py-3 text-end border border-gray-200" dir="ltr">+971 58 667 8899</td>
                            <td class="px-4 py-3 border border-gray-200">Crossover</td>
                            <td class="px-4 py-3 border border-gray-200">RAK 1234</td>
                            <td class="px-4 py-3 border border-gray-200">4</td>
                            <td class="px-4 py-3 !text-[#B68A35] border border-gray-200 "><a
                                    href="delegate-details.html">DA25-004</a></td>
                            <td class="px-4 py-2 border border-gray-200">
                                <div class="flex align-center gap-4">

                                    <a href="drivers-edit.html">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 512 512">
                                            <path
                                                d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z"
                                                fill="#B68A35" />
                                        </svg>
                                    </a>
                                    <a href="#" data-modal-target="deleteModal"
                                        data-modal-toggle="deleteModal">
                                        <svg class="w-5.5 h-5.5 " aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="none" viewBox="0 0 24 24">
                                            <path stroke="#B68A35" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="1.5"
                                                d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z">
                                            </path>
                                        </svg>
                                    </a>
                                    <a href="#"
                                        class="!bg-[#E6D7A2] !text-[#5D471D] px-3 text-sm flex items-center gap-2 py-1 text-sm rounded-lg me-auto">
                                        <svg class="w-5 h-5 !text-[#5D471D]" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="2"
                                                d="M16 12h4m-2 2v-4M4 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>

                                        <span> Assign</span>

                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="mt-3 flex items-center justify-start gap-3 ">
                    <div class="h-5 w-5 bg-[#e6d7a2] rounded"></div>
                    <span class="text-gray-800 text-sm">Unassigned Drivers</span>
                </div>
            </div>
        </div>
    </div>
</div>
