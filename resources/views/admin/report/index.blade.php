@extends('layouts.admin_account', ['title' => __db('reports')])

@section('content')
    <div>
    <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
        <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('reports') }}</h2>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-6 h-full">
        <div class="xl:col-span-12 h-full">
            <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @php
                        $reports = [
                            [
                                'slug' => 'view_delegations_head_arrival',
                                'name' => __db('delegations_heads_arrival'),
                                'url' => route('report.heads-arrivals')
                            ],
                            ['slug' => 'view_delegations_head_departure','name' => __db('delegations_heads_departure'),'url' => route('report.heads-departure')],

                            ['slug' => 'view_delegations_cars','name' => __db('delegations_cars_report'), 'url' => route('report.delegation-cars')],

                            ['slug' => 'view_delegations_escort','name' =>  __db('delegations_escort_report'),'url' => route('reports-delegations')],

                            ['slug' => 'view_delegations_members','name' => __db('delegations_members_report'),'url' => route('report.delegation-members')],

                            ['slug' => 'view_delegations_without_escort','name' => __db('delegations_without_escort_report'),'url' => route('report.without-escorts')],

                            ['slug' => 'view_departures_report','name' => __db('departures_report'),'url' => route('report.delegation-departures')],

                            ['slug' => 'view_drivers_report','name' => __db('drivers_report'),'url' => route('report.drivers')],

                            ['slug' => 'view_escorts_report','name' => __db('escorts_report'),'url' => route('report.escorts')],

                            ['slug' => 'view_hotel_room_vacancies','name' => __db('hotel_rooms_vacancies_report'),'url' => route('hotel-rooms')],

                            ['slug' => 'view_hotel_delegations','name' => __db('hotel_delegations_report'),'url' => route('reports-delegations')],

                            ['slug' => 'view_interviews_reports','name' => __db('interviews_report'),'url' => route('reports-delegations')],

                            ['slug' => 'view_vip','name' => __db('vip_report'),'url' => route('report.vip')],

                            ['slug' => 'view_wives','name' => __db('wives_report'),'url' => route('report.wives')],

                            ['slug' => 'view_arrivals_report','name' => __db('arrivals_report'),'url' => route('report.delegation-arrivals')],

                            ['slug' => 'view_arrival_hotels','name' => __db('arrival_hotels_report'),'url' => route('reports-delegations')],

                            ['slug' => 'view_delegation_head_invitations','name' => __db('delegation_head_invitations_report'),'url' => route('report.heads-invitations')]
                        ];

                        $gradientPastels = [
                            'bg-gradient-to-r from-pink-200/50 via-yellow-200/50 to-green-200/50',
                            'bg-gradient-to-r from-blue-200/50 via-purple-200/50 to-pink-200/50',
                            'bg-gradient-to-r from-rose-200/50 via-indigo-200/50 to-yellow-200/50',
                            'bg-gradient-to-r from-green-200/50 via-teal-200/50 to-blue-200/50',
                            'bg-gradient-to-r from-orange-200/50 via-pink-200/50 to-purple-200/50',
                        ];

                        $permissions = Auth::user()->getPermissionsViaRoles()->pluck('name')->toArray();
                        
                    @endphp


                    @foreach ($reports as $index => $report)
                        @if (in_array($report['slug'], $permissions))
                            <a href="{{ $report['url'] }}" class="block rounded-2xl {{ $gradientPastels[$index % count($gradientPastels)] }} backdrop-blur-lg border border-white/20 shadow-lg hover:shadow-2xl transition-all duration-300 p-6 text-center hover:scale-105">
                                    <h2 class="text-gray-900 text-lg font-semibold">{{ $report['name'] }}</h2>
                                </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
    <script>
       
    </script>

@endsection

