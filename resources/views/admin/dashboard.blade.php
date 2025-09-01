@extends('layouts.admin_account', ['title' => __db('dashboard')])

@section('content')
    <div class="">
        <!-- Overview boxes -->
        <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
            <h2 class="font-semibold mb-0 !text-[16px]">{{ __db('dashboard') }}</h2>
        </div>
        <div class="grid grid-cols-12 gap-3 text-sm">
            <div
                class=" shadow-none border !border-[#e6d7a2] rounded-lg h-full bg-gradient-to-r from-cyan-600/10 bg-[#F2ECCF] col-span-3">
                <div class="card-body p-5">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="font-medium text-neutral-900  mb-1">{{ __db('total') }} {{ __db('delegates') }}</p>
                            <h6 class="mb-0 ">{{ $data['totalDelegates'] ?? 0 }}</h6>
                        </div>
                        <div class="w-[50px] h-[50px] bg-[#B68A35] rounded-full flex justify-center items-center">
                            <svg class="text-white" width="36" height="30" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <!-- card end -->
            <div
                class=" shadow-none border !border-[#e6d7a2]  rounded-lg h-full bg-gradient-to-r from-purple-600/10 bg-[#F2ECCF] col-span-3">
                <div class="card-body p-5">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="font-medium text-neutral-900  mb-1">{{ __db('total') }} {{ __db('escorts') }} {{ __db('assigned') }} </p>
                            <h6 class="mb-0 ">{{ $data['totalEscortsAssigned'] ?? 0 }}</h6>
                        </div>
                        <div class="w-[50px] h-[50px] bg-[#B68A35] rounded-full flex justify-center items-center">
                            <svg class="text-white" width="36" height="30" viewBox="0 0 29 29" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M20.5249 24.7773H8.55908C3.77275 24.7773 2.57617 23.6107 2.57617 18.944V9.61068C2.57617 4.94401 3.77275 3.77734 8.55908 3.77734H20.5249C25.3112 3.77734 26.5078 4.94401 26.5078 9.61068V18.944C26.5078 23.6107 25.3112 24.7773 20.5249 24.7773Z"
                                    stroke="#fff" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M16.9355 9.61084H22.9185" stroke="#fff" stroke-width="1.75" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M18.1328 14.2773H22.9191" stroke="#fff" stroke-width="1.75" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M20.5254 18.9438H22.9186" stroke="#fff" stroke-width="1.75" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M10.3553 13.4489C11.5514 13.4489 12.5211 12.5035 12.5211 11.3373C12.5211 10.171 11.5514 9.22559 10.3553 9.22559C9.15912 9.22559 8.18945 10.171 8.18945 11.3373C8.18945 12.5035 9.15912 13.4489 10.3553 13.4489Z"
                                    stroke="#fff" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M14.5421 19.3289C14.3746 17.6372 12.9985 16.3072 11.2635 16.1556C10.6652 16.0972 10.0549 16.0972 9.44465 16.1556C7.70961 16.3189 6.33354 17.6372 6.16602 19.3289"
                                    stroke="#fff" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <!-- card end -->
            <div class="shadow-none border !border-[#e6d7a2] rounded-lg h-full bg-gradient-to-r from-blue-600/10 bg-[#F2ECCF] col-span-3">
                <div class="card-body p-5">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="font-medium text-neutral-900  mb-1">{{ __db('total') }} {{ __db('drivers') }} {{ __db('assigned') }}</p>
                            <h6 class="mb-0 ">{{ $data['totalDriversAssigned'] ?? 0 }}</h6>
                        </div>
                        <div class="w-[50px] h-[50px] bg-[#B68A35] rounded-full flex justify-center items-center">
                            <svg class="text-white" width="36" height="30" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-car-front-icon lucide-car-front">
                                <path d="m21 8-2 2-1.5-3.7A2 2 0 0 0 15.646 5H8.4a2 2 0 0 0-1.903 1.257L5 10 3 8" />
                                <path d="M7 14h.01" />
                                <path d="M17 14h.01" />
                                <rect width="18" height="8" x="3" y="10" rx="2" />
                                <path d="M5 18v2" />
                                <path d="M19 18v2" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <!-- card end -->
            <div
                class=" shadow-none border !border-[#e6d7a2] rounded-lg h-full bg-gradient-to-r from-success-600/10 bg-[#F2ECCF] col-span-3">
                <div class="card-body p-5">
                    <div class="flex  items-center justify-between gap-3">
                        <div>
                            <p class="font-medium text-neutral-900  mb-1">{{ __db('total') }} {{ __db('hotels') }}</p>
                            <h6 class="mb-0 ">{{ $data['totalHotels'] ?? 0 }}</h6>
                        </div>
                        <div class="w-[50px] h-[50px] bg-[#B68A35] rounded-full flex justify-center items-center">
                            <svg class="text-white" width="36" height="30" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <!-- card end -->
        </div>
        <!-- Notification -->
        <div class="grid grid-cols-12 xl:grid-cols-12 gap-3 mt-6">
            <div class="col-span-8 sm:col-span-8 xl:col-span-8">
                <div class="bg-white h-full rounded-lg border-0 p-4">
                    <div class="border-b border-neutral-200 pb-4 mb-4">
                        <h6 class="text-sm xl:text-xl font-medium mb-0">{{ __db('delegations_by_division') }}</h6>
                    </div>
                    <div id="pieChart"></div>
                </div>
            </div>
            <div class="col-span-4 sm:col-span-4 xl:col-span-4">
                <div class="bg-white h-full rounded-lg border-0 p-4">
                    <div class="border-b border-neutral-200 pb-4 mb-4">
                        <h6 class="text-sm xl:text-xl font-medium mb-0">{{ __db('delegation_assignments') }}</h6>
                    </div>
                    <div id="columnChart" class=""></div>
                </div>
            </div>
            <div class="col-span-5 sm:col-span-5 xl:col-span-5">
                <div class="bg-white h-full rounded-lg border-0 p-4">
                    <div class="border-b border-neutral-200 pb-4 mb-4">
                        <h6 class="text-sm xl:text-xl font-medium mb-0"> {{ __db('arrival_status') }}</h6>
                    </div>
                    <div id="userOverviewDonutChart" class="apexcharts-tooltip-z-none"></div>
                </div>
            </div>

            <div class="col-span-7 sm:col-span-7 xl:col-span-7">
               <div class="bg-white h-full rounded-lg border-0 p-6">
                  <div class="mb-4 flex items-center justify-start gap-2">
                        <h6 class="text-xl font-medium mb-0"> {{ __db('members_arrivals_and_departures') }}</h6>
                        <span
                           class="bg-red-100 text-red-700 text-sm font-medium px-3 py-1 rounded-full flex items-center gap-1">
                           <span class="h-2 w-2 rounded-full bg-red-500 animate-ping"></span>
                           {{ __db('today') }}
                        </span>

                  </div>
                  <table class="table-auto mb-0  !border-[#F9F7ED] w-full">
                        <thead>
                           <tr class="text-[10px]">
                              <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                    {{ __db('airport_land_sea') }}</th>
                              <th scope="col"
                                    class="p-3 !bg-[#B68A35] text-white border !border-[#cbac71] text-center">
                                    {{ __db('arrivals') }}</th>
                              <th scope="col"
                                    class="p-3 !bg-[#B68A35] text-white border !border-[#cbac71] text-centertext-center">
                                    {{ __db('departures') }}</th>
                           </tr>
                        </thead>
                        <tbody>
                           @forelse($data['arr_dep_summary'] as $row)
                                 <tr class="text-[10px] align-[middle]">
                                    <td class="px-4 py-2 border border-gray-200">{{ $row->transport_point }}</td>
                                    <td class="px-4 py-2 border border-gray-200 text-center">{{ $row->arrival_count }}</td>
                                    <td class="px-4 py-2 border border-gray-200 text-center">{{ $row->departure_count }}</td>
                                 </tr>
                           @empty
                                 <tr>
                                    <td colspan="3" class="px-4 py-2 border text-center">{{ __db('no_record_found') }}</td>
                                 </tr>
                           @endforelse
                        </tbody>
                  </table>

               </div>
            </div>
        </div>


        <!-- Invitation Status -->
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-3 mt-6">
            {{-- <div class="xl:col-span-6 2xl:col-span-6">
                <div class="bg-white h-full rounded-lg border-0 p-6">
                    <div class="mb-4 flex items-center justify-between">
                        <h6 class="!text-[16px] font-medium mb-0"> Invitation Status</h6>
                    </div>

                    <div class="overflow-x-auto w-full">
                        <table class="table-auto mb-0  !border-[#F9F7ED] w-full max-h-full h-[400px]">
                            <thead>

                                <tr class="text-[10px]">
                                    <th scope="col"
                                        class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                        Division</th>
                                    <th scope="col"
                                        class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                        Navy</th>
                                    <th scope="col"
                                        class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                        Army</th>
                                    <th scope="col"
                                        class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                        Air Force </th>
                                    <th scope="col"
                                        class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                        Total</th>


                                </tr>


                            </thead>
                            <tbody>
                                <tr class=" align-[middle] text-[10px]">


                                    <td class="px-4 py-2 border border-gray-200">Waiting</td>
                                    <td class="px-4 py-2 border border-gray-200">10</td>
                                    <td class="px-4 py-2 border border-gray-200">5</td>
                                    <td class="px-4 py-2 border border-gray-200">0</td>
                                    <td class="px-4 py-2 border border-gray-200">15</td>
                                </tr>
                                <tr class="  align-[middle] text-[10px]">


                                    <td class="px-4 py-2 border border-gray-200">Accepted </td>
                                    <td class="px-4 py-2 border border-gray-200">5</td>
                                    <td class="px-4 py-2 border border-gray-200">10</td>
                                    <td class="px-4 py-2 border border-gray-200">20</td>
                                    <td class="px-4 py-2 border border-gray-200">35</td>

                                </tr>
                                <tr class="  align-[middle] text-[10px]">


                                    <td class="px-4 py-2 border border-gray-200">Accepted with secretary </td>
                                    <td class="px-4 py-2 border border-gray-200">0</td>
                                    <td class="px-4 py-2 border border-gray-200">2</td>
                                    <td class="px-4 py-2 border border-gray-200">0</td>
                                    <td class="px-4 py-2 border border-gray-200">2</td>

                                </tr>
                                <tr class=" text-[10px] align-[middle]">


                                    <td class="px-4 py-2 border border-gray-200">Accepted with acting person </td>
                                    <td class="px-4 py-2 border border-gray-200">0</td>
                                    <td class="px-4 py-2 border border-gray-200">5</td>
                                    <td class="px-4 py-2 border border-gray-200">5</td>
                                    <td class="px-4 py-2 border border-gray-200">5</td>

                                </tr>


                                <tr class=" text-[10px] align-[middle]">


                                    <td class="px-4 py-2 border border-gray-200">Rejected </td>
                                    <td class="px-4 py-2 border border-gray-200">3</td>
                                    <td class="px-4 py-2 border border-gray-200">3</td>
                                    <td class="px-4 py-2 border border-gray-200">5</td>
                                    <td class="px-4 py-2 border border-gray-200">11</td>
                                </tr>


                                <tr
                                    class=" text-[10px] align-[middle] bg-[#FFF9E4] font-medium text-[#B68A35] text-[20px]">


                                    <td class="px-4 py-2 border border-gray-200">Total </td>
                                    <td class="px-4 py-2 border border-gray-200">18</td>
                                    <td class="px-4 py-2 border border-gray-200">20</td>
                                    <td class="px-4 py-2 border border-gray-200">30</td>
                                    <td class="px-4 py-2 border border-gray-200">68</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div> --}}

            <div class="xl:col-span-12 2xl:col-span-12">
                <div class="bg-white h-full rounded-lg border-0 p-6">
                    <div class="mb-4 flex items-center justify-between">
                        <h6 class="!text-[16px] font-medium mb-0">{{ __db('delegates_invitation_status') }}</h6>

                    </div>
                    <div id="InvitationStatus"></div>

                </div>
            </div>


        </div>


        {{-- <!-- Participation status -->
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-3 mt-6">

            <div class="xl:col-span-6 2xl:col-span-6">
                <div class="bg-white h-full rounded-lg border-0 p-6">
                    <div class="mb-4 flex items-center justify-between">
                        <h6 class="!text-[16px] font-medium mb-0"> Participation Status</h6>
                    </div>

                    <div class="overflow-x-auto w-full">
                        <table class="table-auto mb-0  !border-[#F9F7ED] w-full h-[400px]">
                            <thead>

                                <tr class="text-[10px]">
                                    <th scope="col"
                                        class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                        Division</th>
                                    <th scope="col"
                                        class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                        Navy</th>
                                    <th scope="col"
                                        class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                        Army </th>
                                    <th scope="col"
                                        class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                        Air Force </th>
                                    <th scope="col"
                                        class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                        Total</th>

                                </tr>


                            </thead>
                            <tbody>


                                <tr class=" text-[10px] align-[middle]">

                                    <td class="px-4 py-2 border border-gray-200"> Not Yet</td>
                                    <td class="px-4 py-2 border border-gray-200">10</td>
                                    <td class="px-4 py-2 border border-gray-200">5</td>
                                    <td class="px-4 py-2 border border-gray-200">0</td>
                                    <td class="px-4 py-2 border border-gray-200">15</td>

                                </tr>
                                <tr class=" text-[10px] align-[middle]">


                                    <td class="px-4 py-2 border border-gray-200">Partially arrived</td>
                                    <td class="px-4 py-2 border border-gray-200">5</td>
                                    <td class="px-4 py-2 border border-gray-200">10</td>
                                    <td class="px-4 py-2 border border-gray-200">20</td>
                                    <td class="px-4 py-2 border border-gray-200">35</td>

                                </tr>
                                <tr class=" text-[10px] align-[middle]">


                                    <td class="px-4 py-2 border border-gray-200">Arrived</td>
                                    <td class="px-4 py-2 border border-gray-200">0</td>
                                    <td class="px-4 py-2 border border-gray-200">2</td>
                                    <td class="px-4 py-2 border border-gray-200">0</td>
                                    <td class="px-4 py-2 border border-gray-200">2</td>

                                </tr>
                                <tr class=" text-[10px] align-[middle]">


                                    <td class="px-4 py-2 border border-gray-200">Partially Departed</td>
                                    <td class="px-4 py-2 border border-gray-200">0</td>
                                    <td class="px-4 py-2 border border-gray-200">0</td>
                                    <td class="px-4 py-2 border border-gray-200">5</td>
                                    <td class="px-4 py-2 border border-gray-200">5</td>

                                </tr>


                                <tr class=" text-[10px] align-[middle] ">


                                    <td class="px-4 py-2 border border-gray-200">Departed</td>
                                    <td class="px-4 py-2 border border-gray-200">3</td>
                                    <td class="px-4 py-2 border border-gray-200">3</td>
                                    <td class="px-4 py-2 border border-gray-200">5</td>
                                    <td class="px-4 py-2 border border-gray-200">11</td>

                                </tr>




                                <tr
                                    class=" text-[10px] align-[middle] bg-[#FFF9E4] font-medium text-[#B68A35] text-[20px]">


                                    <td class="px-4 py-2 border border-gray-200">Total</td>
                                    <td class="px-4 py-2 border border-gray-200">18</td>
                                    <td class="px-4 py-2 border border-gray-200">20</td>
                                    <td class="px-4 py-2 border border-gray-200">30</td>
                                    <td class="px-4 py-2 border border-gray-200">68</td>

                                </tr>

                            </tbody>
                        </table>
                    </div>



                </div>
            </div>

            <div class="xl:col-span-6 2xl:col-span-6">
                <div class="bg-white h-full rounded-lg border-0 p-6">
                    <div class="mb-4 flex items-center justify-between">
                        <h6 class="!text-[16px] font-medium mb-0">Delegates participation status</h6>

                    </div>
                    <div id="ParticipationStatus"></div>

                </div>
            </div>

        </div>

        <!-- Accepted Delegates by Continents -->
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-3 mt-6">

            <div class="xl:col-span-6 2xl:col-span-6">
                <div class="bg-white h-full rounded-lg border-0 p-6">
                    <div class="mb-4 flex items-center justify-between">
                        <h6 class="!text-[16px] font-medium mb-0"> Accepted Delegates by Continents</h6>
                    </div>

                    <div class="overflow-x-auto w-full">

                        <table class="table-auto mb-0  !border-[#F9F7ED] w-full h-[400px]">
                            <thead>

                                <tr class="text-[10px]">
                                    <th scope="col"
                                        class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                        Division</th>
                                    <th scope="col"
                                        class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                        Navy</th>
                                    <th scope="col"
                                        class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                        Army </th>
                                    <th scope="col"
                                        class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                        Air Force </th>
                                    <th scope="col"
                                        class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                        Total</th>

                                </tr>


                            </thead>
                            <tbody>
                                <tr class=" text-[10px] align-[middle]">


                                    <td class="px-4 py-2 border border-gray-200">Asia</td>
                                    <td class="px-4 py-2 border border-gray-200">1</td>
                                    <td class="px-4 py-2 border border-gray-200">1</td>
                                    <td class="px-4 py-2 border border-gray-200">1</td>
                                    <td class="px-4 py-2 border border-gray-200">3</td>

                                </tr>
                                <tr class=" text-[10px] align-[middle]">


                                    <td class="px-4 py-2 border border-gray-200">Africa</td>
                                    <td class="px-4 py-2 border border-gray-200">0</td>
                                    <td class="px-4 py-2 border border-gray-200">1</td>
                                    <td class="px-4 py-2 border border-gray-200">3</td>
                                    <td class="px-4 py-2 border border-gray-200">4</td>

                                </tr>
                                <tr class=" text-[10px] align-[middle]">


                                    <td class="px-4 py-2 border border-gray-200">Europe</td>
                                    <td class="px-4 py-2 border border-gray-200">0</td>
                                    <td class="px-4 py-2 border border-gray-200">2</td>
                                    <td class="px-4 py-2 border border-gray-200">5</td>
                                    <td class="px-4 py-2 border border-gray-200">7</td>

                                </tr>
                                <tr class=" text-[10px] align-[middle]">


                                    <td class="px-4 py-2 border border-gray-200">America</td>
                                    <td class="px-4 py-2 border border-gray-200">0</td>
                                    <td class="px-4 py-2 border border-gray-200">1</td>
                                    <td class="px-4 py-2 border border-gray-200">1</td>
                                    <td class="px-4 py-2 border border-gray-200">2</td>

                                </tr>

                                <tr class=" text-[10px] align-[middle]">


                                    <td class="px-4 py-2 border border-gray-200">Gcc Countries</td>
                                    <td class="px-4 py-2 border border-gray-200">3</td>
                                    <td class="px-4 py-2 border border-gray-200">3</td>
                                    <td class="px-4 py-2 border border-gray-200">5</td>
                                    <td class="px-4 py-2 border border-gray-200">11</td>

                                </tr>


                                <tr class=" text-[10px] align-[middle]">


                                    <td class="px-4 py-2 border border-gray-200">Arab Countries</td>
                                    <td class="px-4 py-2 border border-gray-200">0</td>
                                    <td class="px-4 py-2 border border-gray-200">3</td>
                                    <td class="px-4 py-2 border border-gray-200">5</td>
                                    <td class="px-4 py-2 border border-gray-200">8</td>

                                </tr>

                                <tr
                                    class=" text-[10px] align-[middle] bg-[#FFF9E4] font-medium text-[#B68A35] text-[20px]">


                                    <td class="px-4 py-2 border border-gray-200">Total</td>
                                    <td class="px-4 py-2 border border-gray-200">5</td>
                                    <td class="px-4 py-2 border border-gray-200">10</td>
                                    <td class="px-4 py-2 border border-gray-200">20</td>
                                    <td class="px-4 py-2 border border-gray-200">35</td>

                                </tr>

                            </tbody>
                        </table>
                    </div>


                </div>
            </div>

            <div class="xl:col-span-6 2xl:col-span-6">
                <div class="bg-white h-full rounded-lg border-0 p-6">
                    <div class="mb-4 flex items-center justify-between">
                        <h6 class="!text-[16px] font-medium mb-0">Total number of accepted invitations
                            by Continents.</h6>

                    </div>
                    <div id="AcceptedContinents"></div>

                </div>
            </div>


        </div>

        

        <!-- Upcoming Arrivals-->
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-6">
            <div class="xl:col-span-12">
                <div class="bg-white h-full rounded-lg border-0 p-6">
                    <div class=" mb-4 flex items-center justify-start gap-2">
                        <h6 class="!text-[16px] font-medium mb-0"> Upcoming Arrivals </h6>

                        <span
                            class="bg-red-100 text-red-700 text-sm font-medium px-3 py-1 rounded-full flex items-center gap-1">
                            <span class="h-2 w-2 rounded-full bg-red-500 animate-ping"></span>
                            Today
                        </span>

                    </div>

                    <table class="table-auto mb-0 !border-[#F9F7ED] w-full">
                        <thead>

                            <tr class="text-[10px]">
                                <th scope="col"
                                    class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                    Sl.No</th>
                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">Delegation
                                </th>
                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">Continent</th>
                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">Country</th>
                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">Delegates</th>
                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">Escort</th>
                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">Driver</th>
                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">To Airport
                                </th>
                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">Time</th>
                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">Flight Number
                                </th>
                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">Flight Name
                                </th>
                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">Action</th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-[10px] align-middle  align-center">
                                <td class="px-4 py-2 border border-gray-200">1</td>
                                <td class="px-4 py-2 border border-gray-200]">

                                    <a href="#">
                                        DA25-001
                                    </a>

                                </td>
                                <td class="px-4 py-2 border border-gray-200">Asia</td>
                                <td class="px-4 py-2 border border-gray-200">Japan</td>
                                <td class="px-4 py-2 border border-gray-200">

                                    <span class="block">Sara Al-Suwaidi</span>
                                    <span class="block">Dalia Al-Hassan</span>

                                </td>
                                <td class="px-4 py-2 border border-gray-200">
                                    <span class="block">
                                        <a href="#" class="" data-modal-target=""
                                            data-modal-toggle="">DR023</a>

                                    </span>
                                    <span class="block">DR030
                                    </span>
                                </td>
                                <td class="px-4 py-2 border border-gray-200">
                                    <span class="block">
                                        <a class="" href="#" data-modal-target=""
                                            data-modal-toggle="">EC001</a>
                                    </span>
                                    <span class="block">EC008</span>
                                </td>

                                <td class="px-4 py-2 border border-gray-200">Dubai International</td>
                                <td class="px-4 py-2 border border-gray-200">08:45 AM</td>
                                <td class="px-4 py-2 border border-gray-200">JL786</td>
                                <td class="px-4 py-2 border border-gray-200">Japan Airlines</td>


                                <td class="px-4 py-2 border border-gray-200">
                                    <div class="flex items-center gap-5">
                                        <a href="#"
                                            class="w-10 h-10  text-primary-600 dark:text-primary-400 rounded-full inline-flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 16 12" fill="none">
                                                <path
                                                    d="M6.73242 5.98193C6.73242 6.37976 6.89046 6.76129 7.17176 7.04259C7.45307 7.3239 7.8346 7.48193 8.23242 7.48193C8.63025 7.48193 9.01178 7.3239 9.29308 7.04259C9.57439 6.76129 9.73242 6.37976 9.73242 5.98193C9.73242 5.58411 9.57439 5.20258 9.29308 4.92127C9.01178 4.63997 8.63025 4.48193 8.23242 4.48193C7.8346 4.48193 7.45307 4.63997 7.17176 4.92127C6.89046 5.20258 6.73242 5.58411 6.73242 5.98193Z"
                                                    stroke="#7C5E24" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                </path>
                                                <path
                                                    d="M14.9824 5.98193C13.1824 8.98193 10.9324 10.4819 8.23242 10.4819C5.53242 10.4819 3.28242 8.98193 1.48242 5.98193C3.28242 2.98193 5.53242 1.48193 8.23242 1.48193C10.9324 1.48193 13.1824 2.98193 14.9824 5.98193Z"
                                                    stroke="#7C5E24" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                </path>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr class="text-[10px] align-middle">
                                <td class="px-4 py-2 border border-gray-200">2</td>
                                <td class="px-4 py-2 border border-gray-200">DA25-002</td>
                                <td class="px-4 py-2 border border-gray-200">Europe</td>
                                <td class="px-4 py-2 border border-gray-200">Germany</td>
                                <td class="px-4 py-2 border border-gray-200">Rachel Lewis</td>
                                <td class="px-4 py-2 border border-gray-200">DR031</td>
                                <td class="px-4 py-2 border border-gray-200">EC011</td>

                                <td class="px-4 py-2 border border-gray-200">Sharjah Airport</td>
                                <td class="px-4 py-2 border border-gray-200">09:30 PM</td>
                                <td class="px-4 py-2 border border-gray-200">LH789</td>
                                <td class="px-4 py-2 border border-gray-200">Lufthansa</td>

                                <td class="px-4 py-3 border border-gray-200">
                                    <div class="flex items-center gap-5">
                                        <a href="#"
                                            class="w-10 h-10  text-primary-600 dark:text-primary-400 rounded-full inline-flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 16 12" fill="none">
                                                <path
                                                    d="M6.73242 5.98193C6.73242 6.37976 6.89046 6.76129 7.17176 7.04259C7.45307 7.3239 7.8346 7.48193 8.23242 7.48193C8.63025 7.48193 9.01178 7.3239 9.29308 7.04259C9.57439 6.76129 9.73242 6.37976 9.73242 5.98193C9.73242 5.58411 9.57439 5.20258 9.29308 4.92127C9.01178 4.63997 8.63025 4.48193 8.23242 4.48193C7.8346 4.48193 7.45307 4.63997 7.17176 4.92127C6.89046 5.20258 6.73242 5.58411 6.73242 5.98193Z"
                                                    stroke="#7C5E24" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                </path>
                                                <path
                                                    d="M14.9824 5.98193C13.1824 8.98193 10.9324 10.4819 8.23242 10.4819C5.53242 10.4819 3.28242 8.98193 1.48242 5.98193C3.28242 2.98193 5.53242 1.48193 8.23242 1.48193C10.9324 1.48193 13.1824 2.98193 14.9824 5.98193Z"
                                                    stroke="#7C5E24" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                </path>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr class=" text-[10px] align-middle">
                                <td class="px-4 py-2 border border-gray-200">3</td>
                                <td class="px-4 py-2 border border-gray-200">DA25-003</td>
                                <td class="px-4 py-2 border border-gray-200">Africa</td>
                                <td class="px-4 py-2 border border-gray-200">Nigeria</td>
                                <td class="px-4 py-2 border border-gray-200">Alex Morgan</td>
                                <td class="px-4 py-2 border border-gray-200">DR040</td>
                                <td class="px-4 py-2 border border-gray-200">EC012</td>

                                <td class="px-4 py-2 border border-gray-200">Abu Dhabi Airport</td>
                                <td class="px-4 py-2 border border-gray-200">11:15 AM</td>
                                <td class="px-4 py-2 border border-gray-200">NG452</td>
                                <td class="px-4 py-2 border border-gray-200">Air Nigeria</td>

                                <td class="px-4 py-2 border border-gray-200"> <a href="#"
                                        class="w-10 h-10  text-primary-600 dark:text-primary-400 rounded-full inline-flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 16 12" fill="none">
                                            <path
                                                d="M6.73242 5.98193C6.73242 6.37976 6.89046 6.76129 7.17176 7.04259C7.45307 7.3239 7.8346 7.48193 8.23242 7.48193C8.63025 7.48193 9.01178 7.3239 9.29308 7.04259C9.57439 6.76129 9.73242 6.37976 9.73242 5.98193C9.73242 5.58411 9.57439 5.20258 9.29308 4.92127C9.01178 4.63997 8.63025 4.48193 8.23242 4.48193C7.8346 4.48193 7.45307 4.63997 7.17176 4.92127C6.89046 5.20258 6.73242 5.58411 6.73242 5.98193Z"
                                                stroke="#7C5E24" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round">
                                            </path>
                                            <path
                                                d="M14.9824 5.98193C13.1824 8.98193 10.9324 10.4819 8.23242 10.4819C5.53242 10.4819 3.28242 8.98193 1.48242 5.98193C3.28242 2.98193 5.53242 1.48193 8.23242 1.48193C10.9324 1.48193 13.1824 2.98193 14.9824 5.98193Z"
                                                stroke="#7C5E24" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round">
                                            </path>
                                        </svg>
                                    </a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <!-- Upcoming Arrivals-->
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-6">
            <div class="xl:col-span-12">
                <div class="bg-white h-full rounded-lg border-0 p-6">
                    <div class=" mb-4 flex items-center justify-start gap-2">
                        <h6 class="!text-[16px] font-medium mb-0"> Upcoming Departures</h6>

                        <span
                            class="bg-red-100 text-red-700 text-sm font-medium px-3 py-1 rounded-full flex items-center gap-1">
                            <span class="h-2 w-2 rounded-full bg-red-500 animate-ping"></span>
                            Today
                        </span>


                    </div>


                    <table class="table-auto mb-0 !border-[#F9F7ED] w-full">
                        <thead>
                            <tr class="text-[10px]">
                                <th scope="col"
                                    class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                    Sl.No</th>
                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">Delegation
                                </th>
                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">Continent</th>
                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">Country</th>
                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">Delegates</th>
                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">Escort</th>
                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">Driver</th>

                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">From Airport
                                </th>
                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">Time</th>
                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">Flight Number
                                </th>
                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">Flight Name
                                </th>
                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-[10px] align-middle  !text-[#000] align-center">
                                <td class="px-4 py-2 border border-gray-200">1</td>
                                <td class="px-4 py-2 border border-gray-200">
                                    <a href="#">
                                        DA25-004
                                    </a>
                                </td>
                                <td class="px-4 py-2 border border-gray-200">North America</td>
                                <td class="px-4 py-2 border border-gray-200">USA</td>
                                <td class="px-4 py-2 border border-gray-200">
                                    <span class="block">Kevin Wilson</span>
                                    <span class="block">David Johnson</span>
                                </td>
                                <td class="px-4 py-2 border border-gray-200">
                                    <span class="block">
                                        <a href="#" class="" data-modal-target=""
                                            data-modal-toggle="">DR023</a>

                                    </span>
                                    <span class="block">DR030
                                    </span>
                                </td>
                                <td class="px-4 py-2 border border-gray-200">
                                    <span class="block">
                                        <a class="" href="#" data-modal-target=""
                                            data-modal-toggle="">EC001</a>
                                    </span>
                                    <span class="block">EC008</span>
                                </td>
                                <td class="px-4 py-2 border border-gray-200">Dubai International</td>
                                <!-- swapped -->

                                <!-- swapped -->
                                <td class="px-4 py-2 border border-gray-200">2025-06-26 06:15</td>
                                <td class="px-4 py-2 border border-gray-200">EK202</td>
                                <td class="px-4 py-2 border border-gray-200">Emirates</td>

                                <td class="px-4 py-2 border border-gray-200">
                                    <div class="flex items-center gap-5">
                                        <a href="#"
                                            class="w-10 h-10  text-primary-600 dark:text-primary-400 rounded-full inline-flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 16 12" fill="none">
                                                <path
                                                    d="M6.73242 5.98193C6.73242 6.37976 6.89046 6.76129 7.17176 7.04259C7.45307 7.3239 7.8346 7.48193 8.23242 7.48193C8.63025 7.48193 9.01178 7.3239 9.29308 7.04259C9.57439 6.76129 9.73242 6.37976 9.73242 5.98193C9.73242 5.58411 9.57439 5.20258 9.29308 4.92127C9.01178 4.63997 8.63025 4.48193 8.23242 4.48193C7.8346 4.48193 7.45307 4.63997 7.17176 4.92127C6.89046 5.20258 6.73242 5.58411 6.73242 5.98193Z"
                                                    stroke="#7C5E24" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                </path>
                                                <path
                                                    d="M14.9824 5.98193C13.1824 8.98193 10.9324 10.4819 8.23242 10.4819C5.53242 10.4819 3.28242 8.98193 1.48242 5.98193C3.28242 2.98193 5.53242 1.48193 8.23242 1.48193C10.9324 1.48193 13.1824 2.98193 14.9824 5.98193Z"
                                                    stroke="#7C5E24" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                </path>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr class="text-[10px] align-middle">
                                <td class="px-4 py-2 border border-gray-200">2</td>
                                <td class="px-4 py-2 border border-gray-200">DA25-005</td>
                                <td class="px-4 py-2 border border-gray-200">Oceania</td>
                                <td class="px-4 py-2 border border-gray-200">Australia</td>
                                <td class="px-4 py-2 border border-gray-200">Chris Anderson</td>
                                <td class="px-4 py-2 border border-gray-200">DR018</td>
                                <td class="px-4 py-2 border border-gray-200">EC021</td>
                                <td class="px-4 py-2 border border-gray-200">Abu Dhabi Airport</td>
                                <!-- swapped -->

                                <!-- swapped -->
                                <td class="px-4 py-2 border border-gray-200">2025-06-26 13:00</td>
                                <td class="px-4 py-2 border border-gray-200">EY451</td>
                                <td class="px-4 py-2 border border-gray-200">Etihad Airways</td>

                                <td class="px-4 py-3 border border-gray-200">
                                    <div class="flex items-center gap-5">
                                        <a href="#"
                                            class="w-10 h-10  text-primary-600 dark:text-primary-400 rounded-full inline-flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 16 12" fill="none">
                                                <path
                                                    d="M6.73242 5.98193C6.73242 6.37976 6.89046 6.76129 7.17176 7.04259C7.45307 7.3239 7.8346 7.48193 8.23242 7.48193C8.63025 7.48193 9.01178 7.3239 9.29308 7.04259C9.57439 6.76129 9.73242 6.37976 9.73242 5.98193C9.73242 5.58411 9.57439 5.20258 9.29308 4.92127C9.01178 4.63997 8.63025 4.48193 8.23242 4.48193C7.8346 4.48193 7.45307 4.63997 7.17176 4.92127C6.89046 5.20258 6.73242 5.58411 6.73242 5.98193Z"
                                                    stroke="#7C5E24" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                </path>
                                                <path
                                                    d="M14.9824 5.98193C13.1824 8.98193 10.9324 10.4819 8.23242 10.4819C5.53242 10.4819 3.28242 8.98193 1.48242 5.98193C3.28242 2.98193 5.53242 1.48193 8.23242 1.48193C10.9324 1.48193 13.1824 2.98193 14.9824 5.98193Z"
                                                    stroke="#7C5E24" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                </path>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr class="text-[10px] align-middle">
                                <td class="px-4 py-2 border border-gray-200">3</td>
                                <td class="px-4 py-2 border border-gray-200">DA25-006</td>
                                <td class="px-4 py-2 border border-gray-200">South America</td>
                                <td class="px-4 py-2 border border-gray-200">Brazil</td>
                                <td class="px-4 py-2 border border-gray-200">Nicole Harris</td>
                                <td class="px-4 py-2 border border-gray-200">DR015</td>
                                <td class="px-4 py-2 border border-gray-200">EC022</td>
                                <td class="px-4 py-2 border border-gray-200">Sharjah Airport</td>

                                <!-- swapped -->

                                <!-- swapped -->
                                <td class="px-4 py-2 border border-gray-200">2025-06-26 21:45</td>
                                <td class="px-4 py-2 border border-gray-200">AZ982</td>
                                <td class="px-4 py-2 border border-gray-200">Azul Airlines</td>

                                <td class="px-4 py-3 border border-gray-200">
                                    <a href="#"
                                        class="w-10 h-10  text-primary-600 dark:text-primary-400 rounded-full inline-flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 16 12" fill="none">
                                            <path
                                                d="M6.73242 5.98193C6.73242 6.37976 6.89046 6.76129 7.17176 7.04259C7.45307 7.3239 7.8346 7.48193 8.23242 7.48193C8.63025 7.48193 9.01178 7.3239 9.29308 7.04259C9.57439 6.76129 9.73242 6.37976 9.73242 5.98193C9.73242 5.58411 9.57439 5.20258 9.29308 4.92127C9.01178 4.63997 8.63025 4.48193 8.23242 4.48193C7.8346 4.48193 7.45307 4.63997 7.17176 4.92127C6.89046 5.20258 6.73242 5.58411 6.73242 5.98193Z"
                                                stroke="#7C5E24" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round">
                                            </path>
                                            <path
                                                d="M14.9824 5.98193C13.1824 8.98193 10.9324 10.4819 8.23242 10.4819C5.53242 10.4819 3.28242 8.98193 1.48242 5.98193C3.28242 2.98193 5.53242 1.48193 8.23242 1.48193C10.9324 1.48193 13.1824 2.98193 14.9824 5.98193Z"
                                                stroke="#7C5E24" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round">
                                            </path>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div> --}}

      @php
         $baseColor = '#B68A35';
         $labelsCount = count($data['delegatesByDivision']['labels']);
         $colors = [];

         $spread = 30; // +/- percentage from base color

         for ($i = 0; $i < $labelsCount; $i++) {
            // Alternate dark/light slices
            $position = ($i % 2 == 0) ? -1 : 1; // even = darker, odd = lighter
            $step = ceil($i / 2); // step away from base
            $percent = $position * ($spread * $step / max(1, ceil($labelsCount / 2)));
            
            $colors[] = shadeColor($baseColor, $percent);
         }
      @endphp
    </div>
@endsection

@section('style')
<style>
   #container {
      height: 400px;
   }

   .highcharts-figure,
   .highcharts-data-table table {
      min-width: 310px;
      max-width: 800px;
      margin: 1em auto;
   }

   .highcharts-data-table table {
      font-family: Verdana, sans-serif;
      border-collapse: collapse;
      border: 1px solid var(--highcharts-neutral-color-10, #e6e6e6);
      margin: 10px auto;
      text-align: center;
      width: 100%;
      max-width: 500px;
   }

   .highcharts-data-table caption {
      padding: 1em 0;
      font-size: 1.2em;
      color: var(--highcharts-neutral-color-60, #666);
   }

   .highcharts-data-table th {
      font-weight: 600;
      padding: 0.5em;
   }

   .highcharts-data-table td,
   .highcharts-data-table th,
   .highcharts-data-table caption {
      padding: 0.5em;
   }

   .highcharts-data-table thead tr,
   .highcharts-data-table tbody tr:nth-child(even) {
      background: var(--highcharts-neutral-color-3, #f7f7f7);
   }

   .highcharts-description {
      margin: 0.3rem 10px;
   }

      </style>
@endsection
@section('script')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            Highcharts.chart('InvitationStatus', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: '',
                    align: 'left'
                },
                xAxis: {
                    categories: ['Navy', 'Army', 'Air Force'],
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: ''
                    },
                    stackLabels: {
                        enabled: true
                    }
                },
                legend: {
                    align: 'left',
                    x: 0,
                    verticalAlign: 'bottom',
                    y: 10,
                    floating: false,
                    backgroundColor: 'var(--highcharts-background-color, #ffffff)',
                    borderColor: 'var(--highcharts-neutral-color-20, #cccccc)',
                    borderWidth: 0,
                    shadow: false
                },
                tooltip: {
                    headerFormat: '<b>{category}</b><br/>',
                    pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
                },
                plotOptions: {
                    column: {
                        stacking: 'normal',
                        dataLabels: {
                            enabled: true
                        }
                    }
                },
                series: [{
                        name: 'Waiting',
                        data: [10, 5, 0],
                        color: '#7c5e24'
                    }, {
                        name: 'Accepted',
                        data: [5, 10, 20],
                        color: '#b68a35'
                    }, {
                        name: 'Accepted secretary',
                        data: [0, 2, 0],
                        color: '#d7bc6d'
                    }, {
                        name: 'Accepted acting person',
                        data: [0, 0, ],
                        color: '#f0da8b'
                    }, {
                        name: 'Rejected',
                        data: [3, 3, 5],
                        color: '#e6d7a2'
                    }

                ],

            });



            // Highcharts.chart('ParticipationStatus', {
            //     chart: {
            //         type: 'column'
            //     },
            //     title: {
            //         text: '',
            //         align: 'left'
            //     },
            //     xAxis: {
            //         categories: ['Navy', 'Army', 'Air Force'],
            //     },
            //     yAxis: {
            //         min: 0,
            //         title: {
            //             text: ''
            //         },
            //         stackLabels: {
            //             enabled: true
            //         }
            //     },
            //     legend: {
            //         align: 'left',
            //         x: 0,
            //         verticalAlign: 'bottom',
            //         y: 10,
            //         floating: false,
            //         backgroundColor: 'var(--highcharts-background-color, #ffffff)',
            //         borderColor: 'var(--highcharts-neutral-color-20, #cccccc)',
            //         borderWidth: 0,
            //         shadow: false
            //     },
            //     tooltip: {
            //         headerFormat: '<b>{category}</b><br/>',
            //         pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
            //     },
            //     plotOptions: {
            //         column: {
            //             stacking: 'normal',
            //             dataLabels: {
            //                 enabled: true
            //             }
            //         }
            //     },
            //     series: [{
            //             name: 'Not Yet',
            //             data: [10, 5, 0],
            //             color: '#7c5e24'
            //         }, {
            //             name: 'Partially arrived',
            //             data: [5, 10, 20],
            //             color: '#b68a35'
            //         }, {
            //             name: 'Arrived',
            //             data: [0, 2, 0],
            //             color: '#d7bc6d'
            //         }, {
            //             name: 'Partially departed ',
            //             data: [0, 0, 5],
            //             color: '#f0da8b'
            //         }, {
            //             name: 'Departed',
            //             data: [3, 3, 5],
            //             color: '#e6d7a2'
            //         }

            //     ],


            // });


            // Highcharts.chart('AcceptedContinents', {
            //     chart: {
            //         type: 'column'
            //     },
            //     title: {
            //         text: '',
            //         align: 'left'
            //     },
            //     xAxis: {
            //         categories: ['Navy', 'Army', 'Air Force'],
            //     },
            //     yAxis: {
            //         min: 0,
            //         title: {
            //             text: ''
            //         },
            //         stackLabels: {
            //             enabled: true
            //         }
            //     },
            //     legend: {
            //         align: 'left',
            //         x: 0,
            //         verticalAlign: 'bottom',
            //         y: 10,
            //         floating: false,
            //         backgroundColor: 'var(--highcharts-background-color, #ffffff)',
            //         borderColor: 'var(--highcharts-neutral-color-20, #cccccc)',
            //         borderWidth: 0,
            //         shadow: false
            //     },
            //     tooltip: {
            //         headerFormat: '<b>{category}</b><br/>',
            //         pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
            //     },
            //     plotOptions: {
            //         column: {
            //             stacking: 'normal',
            //             dataLabels: {
            //                 enabled: true
            //             }
            //         }
            //     },
            //     series: [{
            //             name: 'Asia',
            //             data: [1, 1, 1],
            //             color: '#7c5e24'
            //         }, {
            //             name: 'Africa',
            //             data: [0, 1, 3],
            //             color: '#b68a35'
            //         }, {
            //             name: 'Europe',
            //             data: [0, 2, 5],
            //             color: '#d7bc6d'
            //         }, {
            //             name: 'America',
            //             data: [1, 0, 1],
            //             color: '#d7bc6d'
            //         }, {
            //             name: 'Gcc Countries	',
            //             data: [3, 3, 5],
            //             color: '#f0da8b'
            //         }, {
            //             name: 'Arab Countries',
            //             data: [0, 3, 5],
            //             color: '#e6d7a2'
            //         }

            //     ],

            // });

            var labels = @json($data['delegatesByDivision']['labels'] ?? []);
            var series = @json($data['delegatesByDivision']['series'] ?? []);
            var colors = @json($colors ?? []);
            // Prepare data array for Highcharts
            var chartData = labels.map(function(label, i) {
               return {
                     name: label,
                     y: series[i],
                     color: colors[i] || '#B68A35' // fallback color
               };
            });

            Highcharts.chart('pieChart', {
               chart: { type: 'pie', height: 400 },
               credits: { enabled: false },
               title: { text: null },
               tooltip: {
                     pointFormat: '{point.name}: <b>{point.actual}</b>',
                     formatter: function() {
                        // Show original count in tooltip
                        var original = @json($data['delegatesByDivision']['series'] ?? [])[this.point.index];
                        return '<b>' + this.point.name + '</b>: ' + original;
                     }
               },
               plotOptions: {
                     pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        showInLegend: true,
                        dataLabels: {
                           enabled: true,
                           style: {
                              fontSize: '9px',  // adjust the size as needed
                              fontWeight: 'bold', // optional
                              color: '#000'       // optional
                           },
                           formatter: function() {
                                 var original = @json($data['delegatesByDivision']['series'] ?? [])[this.point.index];
                                 return this.point.name + '('+ original +') ' ;
                           }
                        },
                        borderWidth: 3,
                        borderColor: '#ffffff'
                     }
               },
               legend: {
                  layout: 'horizontal',
                  align: 'center',
                  verticalAlign: 'bottom',
                  itemMarginBottom: 0,
                  itemStyle: {
                     fontSize: '9px', // change to your desired size
                     fontWeight: 'normal',
                     color: '#333'
                  },
                  navigation: { enabled: true } 
               },
               series: [{
                     name: "{{ __db('delegates') }}",
                     colorByPoint: true,
                     data: chartData
               }]
            });

            var delegationData = @json($data['delegation_assignments']);

            Highcharts.chart('columnChart', {
               chart: {
                     type: 'column',
                     height: 400
               },
               credits: { enabled: false },
               title: {
                     text: ''
               },
               xAxis: {
                     categories: ['{{ __db('escorts') }}', '{{ __db('drivers') }}', '{{ __db('hotels') }}'],
                     crosshair: true
               },
               yAxis: {
                     min: 0,
                     title: {
                        text: '{{ __db('count') }}'
                     }
               },
               
               tooltip: {
                     shared: true,
                     useHTML: true,
                     headerFormat: '<b>{point.key}</b><br/>',
                     pointFormat: '{series.name}: {point.y}<br/>'
               },
               plotOptions: {
                     column: {
                        borderRadius: 4,
                        pointPadding: 0.2,
                        borderWidth: 0
                     }
               },
               colors: ['#e6d7a2', '#B68A35'],
               legend: {
                  enabled: true,
                  align: 'center', 
                  verticalAlign: 'bottom', 
                  itemStyle: {
                        fontSize: '10px', 
                        fontWeight: 'normal',
                        color: '#333333'
                  }
               },
               series: [{
                     name: '{{ __db('not_assigned') }}',
                     data: [
                        delegationData.notAssignedEscorts,
                        delegationData.notAssignedDrivers,
                        delegationData.notAssignedHotels
                     ]
               }, {
                     name: '{{ __db('assigned') }}',
                     data: [
                        delegationData.assignedEscorts,
                        delegationData.assignedDrivers,
                        delegationData.assignedHotels
                     ]
               }]
            });


            Highcharts.chart('userOverviewDonutChart', {
               chart: {
                  type: 'pie',
                  height: 400
               },
               credits: { enabled: false },
               title: {
                  text: ''
               },
               plotOptions: {
                  pie: {
                        innerSize: '50%', 
                        showInLegend: true,
                        dataLabels: {
                           enabled: true,
                           style: {
                              fontSize: '9px',  
                              fontWeight: 'bold', 
                              color: '#000'       
                           },
                           format: '{point.name}: {point.percentage:.1f}%'
                        }
                  }
               },
               legend: {
                  layout: 'horizontal',
                  align: 'center',
                  verticalAlign: 'bottom',
                  itemMarginBottom: 0,
                  itemStyle: {
                     fontSize: '9px', // change to your desired size
                     fontWeight: 'normal',
                     color: '#333'
                  },
                  navigation: { enabled: true } 
               },
               colors: ['#B68A35', '#D7BC6D', '#E6D7A2'],
               series: [{
                  name: 'Delegates',
                  data: [
                        { name: '{{ __db('arrived') }}', y: {{ $data['arrival_status']['arrived'] }} },
                        { name: '{{ __db('not_yet_arrived') }}', y: {{ $data['arrival_status']['not_yet_arrived'] }} },
                        { name: '{{ __db('departed') }}', y: {{ $data['arrival_status']['departed'] }} }
                  ]
               }]
            });
        });
    </script>

@endsection
