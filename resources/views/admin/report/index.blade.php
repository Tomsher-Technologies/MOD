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
                                'name' => 'Delegations Head Arrival Report',
                                'url' => route('reports-delegations')
                            ],
                            ['slug' => 'view_delegations_head_departure','name' => 'Delegations Head Departure Report','url' => route('reports-delegations')],
                            ['slug' => 'view_delegations_cars','name' => 'Delegations Cars Report', 'url' => route('reports-delegations')],
                            ['slug' => 'view_delegations_escort','name' => 'Delegations Escort Report','url' => route('reports-delegations')],
                            ['slug' => 'view_delegations_members','name' => 'Delegations Members Report','url' => route('reports-delegations')],
                            ['slug' => 'view_delegations_without_escort','name' => 'Delegations Without Escort Report','url' => route('reports-delegations')],
                            ['slug' => 'view_departures_report','name' => 'Departures Report','url' => route('reports-delegations')],
                            ['slug' => 'view_drivers_report','name' => 'Drivers Report','url' => route('reports-delegations')],
                            ['slug' => 'view_escorts_report','name' => 'Escorts Report','url' => route('reports-delegations')],
                            ['slug' => 'view_hotel_room_vacancies','name' => 'Hotel Room Vacancies Report','url' => route('reports-delegations')],
                            ['slug' => 'view_hotel_delegations','name' => 'Hotel Delegations Report','url' => route('reports-delegations')],
                            ['slug' => 'view_interviews_reports','name' => 'Interviews Report','url' => route('reports-delegations')],
                            ['slug' => 'view_vip','name' => 'VIP Report','url' => route('reports-delegations')],
                            ['slug' => 'view_wives','name' => 'Wives Report','url' => route('reports-delegations')],
                            ['slug' => 'view_arrivals_report','name' => 'Arrivals Report','url' => route('reports-delegations')],
                            ['slug' => 'view_arrival_hotels','name' => 'Arrival Hotels Report','url' => route('reports-delegations')],
                            ['slug' => 'view_delegation_head_invitations','name' => 'Delegation Head Invitations Report','url' => route('reports-delegations')]
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

