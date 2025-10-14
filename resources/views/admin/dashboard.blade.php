


@extends('layouts.admin_account', ['title' => __db('dashboard')])

@section('content')
    <div class="">
        <!-- Overview boxes -->
        <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
            <h2 class="font-semibold mb-0 !text-[16px]">{{ __db('dashboard') }}</h2>
        </div>
      
      
      
      
      {{-- Your main view file, e.g., dashboard.blade.php --}}

<div class="grid grid-cols-1 gap-5 text-sm sm:grid-cols-2 xl:grid-cols-4">

    {{-- Card 1: Total Delegates --}}
    <x-dashboard-stat-card title="{{ __db('total') }} {{ __db('delegates') }}" value="{{ $data['totalDelegates'] ?? 0 }}" color="cyan">
        <svg class="h-7 w-7 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
        </svg>
    </x-dashboard-stat-card>

    {{-- Card 2: Escorts Assigned --}}
    <x-dashboard-stat-card title="{{ __db('total') }} {{ __db('escorts') }} {{ __db('assigned') }}" value="{{ $data['totalEscortsAssigned'] ?? 0 }}" color="purple">
        <svg class="h-7 w-7 text-white" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M20.5249 24.7773H8.55908C3.77275 24.7773 2.57617 23.6107 2.57617 18.944V9.61068C2.57617 4.94401 3.77275 3.77734 8.55908 3.77734H20.5249C25.3112 3.77734 26.5078 4.94401 26.5078 9.61068V18.944C26.5078 23.6107 25.3112 24.7773 20.5249 24.7773Z" stroke="#fff" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M16.9355 9.61084H22.9185" stroke="#fff" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M18.1328 14.2773H22.9191" stroke="#fff" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M20.5254 18.9438H22.9186" stroke="#fff" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M10.3553 13.4489C11.5514 13.4489 12.5211 12.5035 12.5211 11.3373C12.5211 10.171 11.5514 9.22559 10.3553 9.22559C9.15912 9.22559 8.18945 10.171 8.18945 11.3373C8.18945 12.5035 9.15912 13.4489 10.3553 13.4489Z" stroke="#fff" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M14.5421 19.3289C14.3746 17.6372 12.9985 16.3072 11.2635 16.1556C10.6652 16.0972 10.0549 16.0972 9.44465 16.1556C7.70961 16.3189 6.33354 17.6372 6.16602 19.3289" stroke="#fff" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </x-dashboard-stat-card>

    {{-- Card 3: Drivers Assigned --}}
    <x-dashboard-stat-card title="{{ __db('total') }} {{ __db('drivers') }} {{ __db('assigned') }}" value="{{ $data['totalDriversAssigned'] ?? 0 }}" color="blue">
        <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="m21 8-2 2-1.5-3.7A2 2 0 0 0 15.646 5H8.4a2 2 0 0 0-1.903 1.257L5 10 3 8" />
            <path d="M7 14h.01" />
            <path d="M17 14h.01" />
            <rect width="18" height="8" x="3" y="10" rx="2" />
            <path d="M5 18v2" />
            <path d="M19 18v2" />
        </svg>
    </x-dashboard-stat-card>

    {{-- Card 4: Total Hotels --}}
    <x-dashboard-stat-card title="{{ __db('total') }} {{ __db('hotels') }}" value="{{ $data['totalHotels'] ?? 0 }}" color="green">
        <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
        </svg>
    </x-dashboard-stat-card>

</div>


@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const animateValue = (obj, start, end, duration) => {
            let startTimestamp = null;
            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                const currentValue = Math.floor(progress * (end - start) + start);
                // Format number with commas
                obj.innerHTML = currentValue.toLocaleString();
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            };
            window.requestAnimationFrame(step);
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    const el = entry.target;
                    const targetValue = parseInt(el.dataset.value, 10);
                    animateValue(el, 0, targetValue, 1500); // Animate over 1.5 seconds
                    observer.unobserve(el); // Stop observing after animation
                }
            });
        }, {
            threshold: 0.5 // Trigger when 50% of the element is visible
        });

        document.querySelectorAll('.animate-number').forEach((el) => {
            observer.observe(el);
        });
    });
</script>
@endpush






        <!-- Notification -->
        <div class="grid grid-cols-12 xl:grid-cols-12 gap-3 mt-6">
            <div class="col-span-8 sm:col-span-8 xl:col-span-8">
                <div class="bg-white h-full rounded-lg border-0 p-4">
                    <div class="border-b border-neutral-200 pb-4 mb-4">
                        <h6 class="text-sm xl:text-xl font-medium mb-0">
                            <a href="{{ route("admin.dashboard.tables",["table" => "divisions"]) }}">
                                {{ __db('delegations_by_division') }}
                            </a>
                        </h6>
                    </div>
                    <div id="pieChart"></div>
                </div>
            </div>
            <div class="col-span-4 sm:col-span-4 xl:col-span-4">
                <div class="bg-white h-full rounded-lg border-0 p-4">
                    <div class="border-b border-neutral-200 pb-4 mb-4">
                        <h6 class="text-sm xl:text-xl font-medium mb-0">
                            <a href="{{ route("admin.dashboard.tables",["table" => "assignments"]) }}">
                                {{ __db('delegation_assignments') }}
                            </a>
                        </h6>
                    </div>
                    <div id="columnChart" class=""></div>
                </div>
            </div>
            <div class="col-span-5 sm:col-span-5 xl:col-span-5">
                <div class="bg-white h-full rounded-lg border-0 p-4">
                    <div class="border-b border-neutral-200 pb-4 mb-4">
                        <h6 class="text-sm xl:text-xl font-medium mb-0"> 
                            <a href="{{ route("admin.dashboard.tables",["table" => "arrival"]) }}">
                                {{ __db('arrival_status') }}
                            </a>
                        </h6>
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
                        <tr class="text-[13px]">
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
                            @forelse($data['arr_dep_summary'] as $drow)
                                    <tr class="text-[12px] align-[middle]">
                                        <td class="px-2 py-2 border border-gray-200">{{ $drow->transport_point }}</td>
                                        <td class="px-2 py-2 border border-gray-200 text-center">{{ $drow->arrival_count }}</td>
                                        <td class="px-2 py-2 border border-gray-200 text-center">{{ $drow->departure_count }}</td>
                                    </tr>
                            @empty
                                    <tr>
                                        <td colspan="3" class="px-2 py-2 border text-center">{{ __db('no_record_found') }}</td>
                                    </tr>
                            @endforelse

                            @if($data['arr_dep_summary']->isNotEmpty())
                                <tr class="align-[middle] bg-[#FFF9E4] font-medium text-[#B68A35] text-[13px]">
                                    <td class="text-start px-4 py-2 border border-gray-200">{{ __db('total') }}</td>
                                    <td class="text-center px-4 py-2 border border-gray-200">
                                        {{ $data['arr_dep_summary']->sum('arrival_count') }}
                                    </td>
                                    <td class="text-center px-4 py-2 border border-gray-200">
                                        {{ $data['arr_dep_summary']->sum('departure_count') }}
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>

               </div>
            </div>
        </div>


        <!-- Invitation Status -->
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-3 mt-6">
        
            <div class="xl:col-span-12 2xl:col-span-12">
                <div class="bg-white h-full rounded-lg border-0 p-6">
                    <div class="mb-4 flex items-center justify-between">
                        <a href="{{ route("admin.dashboard.tables",["table" => "invitations"]) }}">
                            <h6 class="!text-[16px] font-medium mb-0">{{ __db('delegates_invitation_status') }}</h6>
                        </a>
                    </div>
                    <div id="InvitationStatus"></div>

                </div>
            </div>
        </div>


        <!-- Participation status -->
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-3 mt-6">
            <div class="xl:col-span-12 2xl:col-span-12">
                <div class="bg-white h-full rounded-lg border-0 p-6">
                    <div class="mb-4 flex items-center justify-between">
                        <a href="{{ route("admin.dashboard.tables",["table" => "participations"]) }}">
                            <h6 class="!text-[16px] font-medium mb-0">{{ __db('delegates_by_participation_status') }}</h6>
                        </a>
                    </div>
                    <div id="ParticipationStatus"></div>
                </div>
            </div>
        </div>

        <!-- Accepted Delegates by Continents -->
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-3 mt-6">
            <div class="xl:col-span-12 2xl:col-span-12">
                <div class="bg-white h-full rounded-lg border-0 p-6">
                    <div class="mb-4 flex items-center justify-between">
                        <a href="{{ route("admin.dashboard.tables",["table" => "continents"]) }}">
                            <h6 class="!text-[16px] font-medium mb-0">{{ __db('accepted_invitations_continents') }}</h6>
                        </a>
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
                        <h6 class="!text-[16px] font-medium mb-0"> {{ __db('upcoming_arrivals') }} </h6>
                        <span
                            class="bg-red-100 text-red-700 text-sm font-medium px-3 py-1 rounded-full flex items-center gap-1">
                            <span class="h-2 w-2 rounded-full bg-red-500 animate-ping"></span>
                            {{ __db('today') }}
                        </span>
                        <button onclick="printSection('print_area_arrival')"  class=" no-print btn text-sm !bg-[#5c451d] flex items-center text-white rounded-lg py-1 px-2">
                            <svg class="ml-1 w-4 h-4 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 9V3h12v6M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2m-12 0h12v3H6v-3Z" />
                            </svg>

                            {{ __db('print') }}
                        </button>
                    </div>

                    <table class="table-auto mb-0 !border-[#F9F7ED] w-full" id="print_area_arrival">
                        <thead>

                            <tr class="text-[13px]">
                                <th scope="col" class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                    {{ __db('sl_no') }}
                                </th>
                                <th class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                    {{ __db('delegation') }}
                                </th>
                                <th class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                    {{ __db('continent') }}
                                </th>
                                <th class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                    {{ __db('country') }}
                                </th>
                                <th class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                    {{ __db('delegates') }}
                                </th>
                                <th class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                    {{ __db('escort') }}
                                </th>
                                <th class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                    {{ __db('driver') }}
                                </th>
                                <th class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                    {{ __db('airport') }}
                                </th>
                                <th class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                    {{ __db('time') }}
                                </th>
                                <th class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                    {{ __db('flight_number') }}
                                </th>
                                <th class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                    {{ __db('flight_name') }}
                                </th>
                                <th class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71] no-print">
                                    {{ __db('action') }}
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data['upcomming_arrivals'] as $akey => $row)
                                <tr class="text-[12px] align-middle  align-center">
                                    <td class="text-center px-2 py-2 border border-gray-200">{{ $akey + 1 }}</td>
                                    <td class="text-center px-2 py-2 border border-gray-200]">

                                        <a href="{{ route('delegations.show', $row->delegate->delegation_id) }}">
                                            {{ $row->delegate->delegation->code ?? '-' }}
                                        </a>

                                    </td>
                                    <td class="text-center px-2 py-2 border border-gray-200">
                                        {{ $row->delegate->delegation->continent->value ?? '-' }}
                                    </td>
                                    <td class="text-center px-2 py-2 border border-gray-200">
                                        {{ $row->delegate->delegation->country->value ?? '-' }}
                                    </td>
                                    <td class="text-center px-2 py-2 border border-gray-200">
                                        <span class="block">{{ $row->delegate->name_en ?? '-' }}</span>
                                    </td>
                                    <td class="text-center px-2 py-2 border border-gray-200">
                                        @if($row->delegate->delegation->escorts->isNotEmpty())
                                            @foreach ($row->delegate->delegation->escorts as $escort)
                                                <span class="">{{ $escort->code }}</span><br>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td class="text-center px-2 py-2 border border-gray-200">
                                        @if($row->delegate->delegation->drivers->isNotEmpty())
                                            @foreach ($row->delegate->delegation->drivers as $driver)
                                                <span class="">{{ $driver->code }}</span><br>
                                            @endforeach
                                        @endif
                                    </td>

                                    <td class="text-center px-2 py-2 border border-gray-200">
                                        {{ $row->airport->value ?? '-' }}
                                    </td>
                                    <td class="text-center px-2 py-2 border border-gray-200">
                                        {{ $row->date_time ? \Carbon\Carbon::parse($row->date_time)->format('h:i A') : '-' }}
                                    </td>
                                    <td class="text-center px-2 py-2 border border-gray-200">{{ $row->flight_no ?? '-' }}</td>
                                    <td class="text-center px-2 py-2 border border-gray-200">{{ $row->flight_name ?? '-' }}</td>


                                    <td class="text-center px-2 py-2 border border-gray-200 no-print">
                                        <div class="flex items-center gap-5">
                                            <a href="{{ route('delegations.show', $row->delegate->delegation_id) }}"
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
                            @empty
                                <tr class="odd:bg-[#F9F7ED] text-sm align-[middle]">
                                    <td colspan="12" class="px-2 py-2 border text-center">
                                        {{ __db('no_data_found') }}
                                    </td>
                                </tr>
                            @endforelse
                            
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
                        <h6 class="!text-[16px] font-medium mb-0"> {{ __db('upcoming_departures') }}</h6>
                        <span  class="bg-red-100 text-red-700 text-sm font-medium px-3 py-1 rounded-full flex items-center gap-1">
                            <span class="h-2 w-2 rounded-full bg-red-500 animate-ping"></span>
                            {{ __db('today') }}
                        </span>
                        <button onclick="printSection('print_area_departure')"  class=" no-print btn text-sm !bg-[#5c451d] flex items-center text-white rounded-lg py-1 px-2">
                            <svg class="ml-1 w-4 h-4 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 9V3h12v6M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2m-12 0h12v3H6v-3Z" />
                            </svg>

                            {{ __db('print') }}
                        </button>
                    </div>

                    <table class="table-auto mb-0 !border-[#F9F7ED] w-full" id="print_area_departure">
                        <thead>
                            <tr class="text-[13px]">
                                <th scope="col" class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                    {{ __db('sl_no') }}
                                </th>
                                <th class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                    {{ __db('delegation') }}
                                </th>
                                <th class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                    {{ __db('continent') }}
                                </th>
                                <th class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                    {{ __db('country') }}
                                </th>
                                <th class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                    {{ __db('delegates') }}
                                </th>
                                <th class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                    {{ __db('escort') }}
                                </th>
                                <th class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                    {{ __db('driver') }}
                                </th>
                                <th class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                    {{ __db('airport') }}
                                </th>
                                <th class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                    {{ __db('time') }}
                                </th>
                                <th class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                    {{ __db('flight_number') }}
                                </th>
                                <th class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                    {{ __db('flight_name') }}
                                </th>
                                <th class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71] no-print">
                                    {{ __db('action') }}
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data['upcomming_departures'] as $dkey => $drow)
                                <tr class="text-[12px] align-middle  align-center">
                                    <td class="text-center px-2 py-2 border border-gray-200">{{ $dkey + 1 }}</td>
                                    <td class="text-center px-2 py-2 border border-gray-200]">
                                        <a href="{{ route('delegations.show', $drow->delegate->delegation_id) }}">
                                            {{ $drow->delegate->delegation->code ?? '-' }}
                                        </a>
                                    </td>
                                    <td class="text-center px-2 py-2 border border-gray-200">
                                        {{ $drow->delegate->delegation->continent->value ?? '-' }}
                                    </td>
                                    <td class="text-center px-2 py-2 border border-gray-200">
                                        {{ $drow->delegate->delegation->country->value ?? '-' }}
                                    </td>
                                    <td class="text-center px-2 py-2 border border-gray-200">
                                        <span class="block">{{ $drow->delegate->name_en ?? '-' }}</span>
                                    </td>
                                    <td class="text-center px-2 py-2 border border-gray-200">
                                        @if($drow->delegate->delegation->escorts->isNotEmpty())
                                            @foreach ($drow->delegate->delegation->escorts as $escort)
                                                <span class="">{{ $escort->code }}</span><br>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td class="text-center px-2 py-2 border border-gray-200">
                                        @if($drow->delegate->delegation->drivers->isNotEmpty())
                                            @foreach ($drow->delegate->delegation->drivers as $driver)
                                                <span class="">{{ $driver->code }}</span> <br>
                                            @endforeach
                                        @endif
                                    </td>

                                    <td class="text-center px-2 py-2 border border-gray-200">
                                        {{ $drow->airport->value ?? '-' }}
                                    </td>
                                    <td class="text-center px-2 py-2 border border-gray-200">
                                        {{ $drow->date_time ? \Carbon\Carbon::parse($drow->date_time)->format('h:i A') : '-' }}
                                    </td>
                                    <td class="text-center px-2 py-2 border border-gray-200">{{ $drow->flight_no ?? '-' }}</td>
                                    <td class="text-center px-2 py-2 border border-gray-200">{{ $drow->flight_name ?? '-' }}</td>
                                    <td class="text-center px-2 py-2 border border-gray-200  no-print">
                                        <div class="flex items-center gap-5">
                                            <a href="{{ route('delegations.show', $drow->delegate->delegation_id) }}"
                                                class="w-10 h-10  text-primary-600 dark:text-primary-400 rounded-full inline-flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    viewBox="0 0 16 12" fill="none">
                                                    <path d="M6.73242 5.98193C6.73242 6.37976 6.89046 6.76129 7.17176 7.04259C7.45307 7.3239 7.8346 7.48193 8.23242 7.48193C8.63025 7.48193 9.01178 7.3239 9.29308 7.04259C9.57439 6.76129 9.73242 6.37976 9.73242 5.98193C9.73242 5.58411 9.57439 5.20258 9.29308 4.92127C9.01178 4.63997 8.63025 4.48193 8.23242 4.48193C7.8346 4.48193 7.45307 4.63997 7.17176 4.92127C6.89046 5.20258 6.73242 5.58411 6.73242 5.98193Z" stroke="#7C5E24" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
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
                            @empty
                                <tr class="odd:bg-[#F9F7ED] text-sm align-[middle]">
                                    <td colspan="12" class="px-2 py-2 border text-center">
                                        {{ __db('no_data_found') }}
                                    </td>
                                </tr>
                            @endforelse
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @php
         $colors = [
    '#FFB3BA', '#FFDFBA',  '#BAFFC9', '#BAE1FF',
    '#FFD6A5', '#FDFFB6', '#CAFFBF', '#9BF6FF', '#A0C4FF',
    '#BDB2FF', '#FFC6FF', '#D0F4DE', '#FFADAD', '#FFDAC1',
    '#E2F0CB', '#C7CEEA', '#F1C0E8', '#C2F0FC', '#FFF5BA',
    '#F8C8DC', '#C1E1C1', '#E0BBE4', '#D5F4E6', '#FFB7B2',
    '#B5EAD7', '#E2F0D9', '#F1E3DD', '#D4A5A5', '#C9BBCF'
];
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
                credits: { enabled: false },
                title: {
                    text: '',
                    align: 'left'
                },
                xAxis: {
                    categories: @json($data['delegatesByInvitationStatus']['categories']),
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
                    },
                    series: {
                        cursor: 'pointer',
                        point: {
                            events: {
                                click: function () {
                                    window.location.href = '{{ route("admin.dashboard.tables",["table" => "invitations"]) }}';
                                }
                            }
                        }
                    }
                },
                series: @json($data['delegatesByInvitationStatus']['series'])

            });

            Highcharts.chart('ParticipationStatus', {
                chart: {
                    type: 'column'
                },
                credits: { enabled: false },
                title: {
                    text: '',
                    align: 'left'
                },
                xAxis: {
                    categories: @json($data['delegatesByParticipationStatus']['categories']),
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
                    },
                    series: {
                        cursor: 'pointer',
                        point: {
                            events: {
                                click: function () {
                                    window.location.href = '{{ route("admin.dashboard.tables",["table" => "participations"]) }}';
                                }
                            }
                        }
                    }
                },
                series: @json($data['delegatesByParticipationStatus']['series'])


            });


            Highcharts.chart('AcceptedContinents', {
                chart: {
                    type: 'column'
                },
                credits: { enabled: false },
                title: {
                    text: '',
                    align: 'left'
                },
                xAxis: {
                    categories: @json( $data['invitationByContinents']['categories']),
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
                    },
                    series: {
                        cursor: 'pointer',
                        point: {
                            events: {
                                click: function () {
                                    window.location.href = '{{ route("admin.dashboard.tables",["table" => "continents"]) }}';
                                }
                            }
                        }
                    }
                },
                series: @json( $data['invitationByContinents']['series'])

            });

            var labels = @json($data['delegatesByDivision']['labels'] ?? []);
            var series = @json($data['delegatesByDivision']['series'] ?? []);
            var colors = @json($colors ?? []);
           
            var chartData = labels.map(function(label, i) {
               return {
                     name: label,
                     y: series[i],
                     color: colors[i % colors.length] // fallback color
               };
            });

            Highcharts.chart('pieChart', {
               chart: { type: 'pie', height: 400 },
               credits: { enabled: false },
               title: { text: null },
               tooltip: {
                     pointFormat: '{point.name}: <b>{point.actual}</b>',
                     formatter: function() {
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
                              fontSize: '9px',  
                              fontWeight: 'bold', 
                              color: '#000' 
                           },
                           formatter: function() {
                                 var original = @json($data['delegatesByDivision']['series'] ?? [])[this.point.index];
                                 return this.point.name + '('+ original +') ' ;
                           }
                        },
                        borderWidth: 3,
                        borderColor: '#ffffff'
                    },
                    series: {
                        cursor: 'pointer',
                        point: {
                            events: {
                                click: function () {
                                    window.location.href = '{{ route("admin.dashboard.tables",["table" => "divisions"]) }}';
                                }
                            }
                        }
                    }
               },
               legend: {
                  layout: 'horizontal',
                  align: 'center',
                  verticalAlign: 'bottom',
                  itemMarginBottom: 0,
                  itemStyle: {
                     fontSize: '9px', 
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
                     },
                     series: {
                        cursor: 'pointer',
                        point: {
                            events: {
                                click: function () {
                                    window.location.href = '{{ route("admin.dashboard.tables",["table" => "assignments"]) }}';
                                }
                            }
                        }
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
                    },
                    series: {
                        cursor: 'pointer',
                        point: {
                            events: {
                                click: function () {
                                    window.location.href = '{{ route("admin.dashboard.tables",["table" => "arrival"]) }}';
                                }
                            }
                        }
                    }
                },
               legend: {
                  layout: 'horizontal',
                  align: 'center',
                  verticalAlign: 'bottom',
                  itemMarginBottom: 0,
                  itemStyle: {
                     fontSize: '9px', 
                     fontWeight: 'normal',
                     color: '#333'
                  },
                  navigation: { enabled: true } 
               },
               colors: ['#FFE5BA', '#C8FFD4', '#FFBAF2'],
               series: [{
                  name: '{{ __db('delegates') }}',
                  data: [
                        { name: '{{ __db('to_be_arrived') }}', y: {{ $data['arrival_status']['to_be_arrived'] }} },
                        { name: '{{ __db('arrived') }}', y: {{ $data['arrival_status']['arrived'] }} },
                        
                        { name: '{{ __db('departed') }}', y: {{ $data['arrival_status']['departed'] }} }
                  ]
               }]
            });
        });

        function printSection(divId, chartId = null) {
            const contentDiv = document.getElementById(divId);
            const clonedContent = contentDiv.cloneNode(true);

            if (chartId) {
                const chart = Highcharts.charts.find(c => c && c.renderTo.id === chartId);
                if (chart) {
                    const svg = chart.getSVG({
                        exporting: {
                            sourceWidth: chart.chartWidth,
                            sourceHeight: chart.chartHeight
                        }
                    });

                    const chartContainer = clonedContent.querySelector(`#${chartId}`);
                    if (chartContainer) {
                        chartContainer.innerHTML = `
                            <div style="width:100%; max-width:${chart.chartWidth}px;">
                                ${svg}
                            </div>
                        `;
                        const svgEl = chartContainer.querySelector('svg');
                        svgEl.setAttribute('width', '100%');
                        svgEl.setAttribute('height', 'auto');
                    }
                }
            }

            const printWindow = window.open('', 'PRINT', 'height=800,width=1200');
            printWindow.document.write('<html><head><title>' + document.title + '</title>');

            printWindow.document.write('<style>' +
                '@media print {' +
                '.no-print { display: none !important; }' +
                'table { border-collapse: collapse !important; width: 100%; }' +
                'th, td { border: 1px solid #cbac71 !important; padding: 0.5rem !important; }' +
                'th, td { text-align: center !important; }' +
                'td:first-child,th:first-child { text-align: left !important; }' +
                'th {color: #cbac71 !important; }' +
                'svg { max-width: 85%; height: auto; }' +
                '}' +
                '</style>'
            );

            printWindow.document.write('</head><body>');
            printWindow.document.write(clonedContent.outerHTML);
            printWindow.document.write('</body></html>');

            printWindow.document.close();
            printWindow.focus();

            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 500);
        }
    </script>

@endsection
