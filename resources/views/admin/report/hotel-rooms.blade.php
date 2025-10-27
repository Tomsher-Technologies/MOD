@extends('layouts.admin_account', ['title' => __db('reports')])

@section('content')
    <div>
        <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
            <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('hotel_rooms_vacancies_report') }}</h2>
        
        </div>

        <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
            

            <form class="w-[65%] me-4"  method="GET">
                        
                <div class="flex relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-3 text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                    </div>

                    <input type="search" id="default-search" name="search" value="{{ request('search') }}"
                        class="block w-[75%] p-2.5 !ps-10 text-secondary-light text-sm !border-[#d1d5db] rounded-lg "
                        placeholder="{{ __db('search_by_name') }}" />


                    <button type="submit" class="!text-[#5D471D] mr-2  end-[3px] bottom-[3px] !bg-[#E6D7A2] hover:bg-yellow-400 focus:ring-4 focus:outline-none focus:ring-yellow-200 font-medium rounded-lg text-sm px-4 py-2">{{ __db('search') }}</button>

                    <a href="{{ route('hotel-rooms') }}"
                        class=" end-[80px]  bottom-[3px] mr-2 border !border-[#B68A35] !text-[#B68A35] font-medium rounded-lg text-sm px-4 py-2 ">{{ __db('reset') }}</a>
                        
                </div>
            </form>

            <div class="flex gap-3 ms-auto">
                @directCanany(['export_hotel_room_vacancies'])
                    <form action="{{ route('hotel-rooms.bulk-exportPdf') }}" method="POST" style="display:inline;">
                        @csrf
                        @foreach (request()->except('limit', 'page') as $key => $value)
                            @if (is_array($value))
                                @foreach ($value as $subKey => $subValue)
                                    <input type="hidden" name="{{ $key }}[{{ $subKey }}]"
                                        value="{{ $subValue }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <button type="submit" class="!text-[#5D471D]  !bg-[#E6D7A2] hover:bg-yellow-400 rounded-lg py-2 px-3">
                            {{ __db('export_pdf') }}
                        </button>
                    </form>
                @enddirectCanany
                <x-back-btn class="" back-url="{{ route('reports.index') }}" />
            </div>
        </div>

        <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6" dir="ltr">
            {{-- <div style=" border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">

                    <div style="width: auto;">
                        <img src="{{ getLogo() }}" alt="{{ env('APP_NAME') }}"
                            style="height: auto; width: 150px;">

                    </div>
                    <div style="text-align: center; width: 50%;">
                        <div style="font-size: 20px; font-weight: bold;">{{ __db('united_arab_emirates') }}</div>
                        <div style="font-size: 20px; font-weight: bold; margin-top: 5px;">{{ __db('ministry_of_defense') }}</div>
                        <div style="font-size: 20px; font-weight: bold; color: #cc0000; margin-top: 5px;">{{ __db('hotel_rooms_vacancies_report') }}</div>
                    </div>
                    <div style=" width: auto; text-align: right;">
                        <img src="{{ getAdminEventLogo() }}" alt="{{ getCurrentEventName() }}"
                            style=" width: 150px; height: auto;">
                    </div>
                </div>
                <div style="text-align: right; font-size: 0.9em; margin-top:10px;">{{ date('d-m-Y H:i A') }}</div>

            </div> --}}

            <div style="font-family: Arial, sans-serif; display: flex; flex-direction: column; gap: 20px; align-items: center;">
                @foreach ($accommodations as $hotel)
                    <div style="width: 100%; margin-bottom:20px;">
                        <table style="border-collapse: collapse; border-spacing: 0; width: 100%; font-weight: bold; background-color: #d9d9d9; border: 2px solid #000;">
                            <tr>
                                <td style="padding: 6px;text-align: center; border: 2px solid #000; width: 75%;">
                                    {{ $hotel->hotel_name }}
                                </td>
                                <td style="padding: 6px; border: 2px solid #000; width: 25%;text-align: center;">
                                    {{ __db('hotel_name') }}
                                </td>
                            </tr>
                        </table>

                        <div style="display: flex; gap: 5%; margin-top: 10px;">
                            
                            @php
                                $rooms = $hotel->rooms;
                                $totalRooms = $rooms->sum('total_rooms');
                                $totalAssigned = $rooms->sum('assigned_rooms');
                                $totalAvailable = $rooms->sum('available_rooms');
                                $accommodatedHtml = '';
                                $totalHtml = '';
                            @endphp

                            <!-- Remaining Rooms Table -->
                            <table style="border: 2px solid black; width: 30%; background-color: white; border-collapse: collapse;">
                                <tbody style="font-size: 12px; text-align: center;">
                                    <tr style="font-weight: bold; background-color: #f0f0f0;">
                                        <td style="border: 2px solid black; padding: 8px;">{{ __db('total_remaining_rooms') }}</td>
                                        <td style="border: 2px solid black; padding: 8px;">{{ $totalAvailable }}</td>
                                    </tr>
                                    @foreach ($rooms as $room)
                                        <tr>
                                            <td style="border: 2px solid black; padding: 8px;">
                                                {{ $room->roomType?->value ?? 'Unknown' }}
                                            </td>
                                            <td style="border: 2px solid black; padding: 8px;">
                                                {{ $room->available_rooms }}
                                            </td>
                                        </tr>
                                        @php
                                            $accommodatedHtml .= '<tr>
                                                                    <td style="border: 2px solid black; padding: 8px;">
                                                                        '. ($room->roomType?->value ?? 'Unknown') .'
                                                                    </td>
                                                                    <td style="border: 2px solid black; padding: 8px;">
                                                                        '. $room->assigned_rooms .'
                                                                    </td>
                                                                </tr>';
                                            $totalHtml .= '<tr>
                                                                <td style="border: 2px solid black; padding: 8px;">
                                                                    '.($room->roomType?->value ?? 'Unknown') .'
                                                                </td>
                                                                <td style="border: 2px solid black; padding: 8px;">
                                                                    '.  $room->total_rooms .'
                                                                </td>
                                                            </tr>';
                                        @endphp
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Accommodated Rooms Table -->
                            <table style="border: 2px solid black; width: 30%; background-color: white; border-collapse: collapse;">
                                <tbody style="font-size: 12px;text-align: center;">
                                    <tr style="font-weight: bold; background-color: #f0f0f0;">
                                        <td style="border: 2px solid black; padding: 8px;">{{ __db('total_accommodations') }}</td>
                                        <td style="border: 2px solid black; padding: 8px;">{{ $totalAssigned }}</td>
                                    </tr>
                                    {!! $accommodatedHtml !!}
                                </tbody>
                            </table>

                            <!-- Total Rooms Table -->
                            <table style="border: 2px solid black; width: 30%; background-color: white; border-collapse: collapse;">
                                <tbody style="font-size: 12px;text-align: center;">
                                    <tr style="font-weight: bold; background-color: #f0f0f0;">
                                        <td style="border: 2px solid black; padding: 8px;">{{ __db('total_rooms') }}</td>
                                        <td style="border: 2px solid black; padding: 8px;">{{ $totalRooms }}</td>
                                    </tr>
                                    {!! $totalHtml !!}
                                </tbody>
                            </table>

                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
@endsection