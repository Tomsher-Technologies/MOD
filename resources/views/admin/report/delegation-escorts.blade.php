<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>{{ __db('delegations_escort_report') }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #000;
            direction: rtl;
            text-align: right;
        }

        table {
            direction: rtl;
            text-align: right;
        }
       
        h1, h2, h3 {
            margin: 4px 0;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
            font-size: 11px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            vertical-align: top;
            text-align: left;
        }
        th {
            background: #eee;
        }
        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        .header-table {
            width: 100%;
            margin-bottom: 10px;
        }
        .header-table td {
            border: none;
            text-align: center;
        }
        .header-table img {
            height: 60px;
        }
        .info-table td {
            border: none;
            padding: 3px 6px;
        }
        .no-border {
            border: none !important;
        }
        .text-center {
            text-align: center;
        }
        .text-bold {
            font-weight: bold;
        }
        .text-red {
            color: #c00;
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <table class="header-table">
        <tr>
            <td style="width:20%; text-align:left;">
                <img src="{{ public_path(getAdminEventPDFLogo()) }}" width="150" alt="{{ getCurrentEventName() }}">
            </td>
            
            <td style="width:60%; text-align:center;">
                <h2>{{ __db('united_arab_emirates') }}</h2>
                <h3>{{ __db('ministry_of_defense') }}</h3>
                <h3 class="text-red">{{ __db('delegations_escort_report') }}</h3>
            </td>
            <td style="width:20%; text-align:right;">
                <img src="{{ public_path('assets/img/md-logo.svg') }}" width="150" alt="{{ env('APP_NAME') }}">
            </td>
        </tr>
    </table>
    <hr>
    @php
        $assignedHotels = [];
    @endphp

    {{-- Escorts + Accommodation --}}
    <div class="section">
        <table class="info-table">
            @foreach ($delegation->escorts as $escort)
                <tr>
                    <td class="">
                        @php
                            $roomEscort = $escort->currentRoomAssignment ?? null;
                            $assignedHotels[] = $roomEscort?->hotel_id ?? null;
                        @endphp
                        <div>
                            <span class="text-bold">{{ __db('accommodation') }}:</span>
                            {{ $roomEscort?->room_number }}-{{ $roomEscort?->hotel?->hotel_name ?? '' }}
                        </div>
                    </td>
                    <td>
                        <div>
                            <span class="text-bold">{{ __db('escort') }}:</span>
                            {{ $escort->name }}-{{ $escort->code }}
                            &nbsp;&nbsp;
                            <span class="text-bold">{{ __db('mobile') }}:</span>
                            {{ $escort->phone_number }}
                        </div>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

    {{-- Delegation Info --}}
    <div class="section">
        <table class="info-table">
            <tr>
                <td>
                    <div><span class="text-bold">{{ __db('country') }}:</span> {{ $delegation->country?->name ?? '-' }}</div>
                    <div><span class="text-bold">{{ __db('invitation_status') }}:</span> {{ $delegation->invitationStatus?->value ?? '-' }}</div>
                </td>
                <td>
                    <div><span class="text-bold">{{ __db('invitation_from') }}:</span> {{ $delegation->invitationFrom?->value ?? '-' }}</div>
                    <div><span class="text-bold">{{ __db('participation_status') }}:</span> {{ $delegation->participationStatus?->value ?? '-' }}</div>
                </td>
            </tr>
        </table>
    </div>

    @php
        $teamHead = '';
    @endphp
    {{-- Arrival Details --}}
    <div class="section">
        <h3>{{ __db('arrival_details') }}</h3>
        <table>
            <thead>
                <tr>
                    <th>{{ __db('sl_no') }}</th>
                    <th>{{ __db('delegations') }}</th>
                    <th>{{ __db('position') }}</th>
                    <th>{{ __db('room') }}</th>
                    <th>{{ __db('flight_name') }}</th>
                    <th>{{ __db('flight_number') }}</th>
                    <th>{{ __db('airport') }}</th>
                    <th>{{ __db('date') }}</th>
                    <th>{{ __db('time') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($delegation->delegates as $key => $delegate)
                    @php
                        $delegateRoom = $delegate->currentRoomAssignment ?? null;
                        $assignedHotels[] = $delegateRoom?->hotel_id ?? null;

                        $arrival = $delegate->delegateTransports->where('type', 'arrival')->first();

                        if($delegate->team_head == 1){
                            $departure = $delegate->delegateTransports->where('type', 'departure')->first();
                    
                            $teamHead.= '<tr>
                                <td class="border-2 border-black p-2">'.($departure?->flight_name).'</td>
                                <td class="border-2 border-black p-2">'.($departure?->flight_no).'</td>
                                <td class="border-2 border-black p-2">'.($departure?->airport?->value) .'</td>
                                <td class="border-2 border-black p-2">'. date('d-m-Y', strtotime($departure?->date_time)) .'</td>
                                <td class="border-2 border-black p-2">'.date('H:i', strtotime($departure?->date_time)).'</td>
                            </tr>';
                        }
                    @endphp
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td class="@if($delegate->team_head) text-red text-bold @endif">
                            {{ $delegate->getTranslation('title').' '.$delegate->getTranslation('name') }}
                        </td>
                        <td>
                            @php
                                $relation = '';
                                if($delegate->relationship){
                                    $relation = $delegate->relationship?->value .' '. __db('of') .' '. $delegate->parent?->getTranslation('name');
                                }
                            @endphp
                            {{ $delegate->internalRanking?->value ?? $relation }}
                        </td>
                        <td>{{ $delegateRoom ? $delegateRoom?->room_number .' - '. $delegateRoom?->hotel?->hotel_name : 'Not Required'}}</td>
                        <td> 
                            {{ $arrival?->flight_name ?? '-' }}
                        </td>
                        <td>{{ $arrival?->flight_no ?? '-' }}</td>
                        <td>{{ $arrival?->airport?->value ?? '-' }}</td>
                        <td>{{ $arrival?->date_time ? date('d-m-Y', strtotime($arrival?->date_time)) : '-' }}</td>
                        <td>{{ $arrival?->date_time ? date('H:i', strtotime($arrival?->date_time)) : '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center">{{ __db('no_record_found') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Departure Head of Delegation --}}
    <div class="section">
        <h3>{{  __db('departure_details_of_head_of_delegation') }}</h3>
        <table>
            <thead>
                <tr>
                    <th>{{ __db('flight_name') }}</th>
                    <th>{{ __db('flight_number') }}</th>
                    <th>{{ __db('airport') }}</th>
                    <th>{{ __db('date') }}</th>
                    <th>{{ __db('time') }}</th>
                </tr>
            </thead>
            <tbody>{!! $teamHead !!}</tbody>
        </table>
    </div>

    {{-- Hotel Details --}}
    @php
        $assignedHotels = array_filter(array_unique($assignedHotels));
        $hotelDetails = getAccommodationDetails($assignedHotels);
    @endphp

     @foreach($hotelDetails as $key => $hotel)
        @php
            $acc_con = '';
        @endphp
        <div class="section">
            <table class="info-table">
                <tr>
                    <td>
                        <div><span class="text-bold">{{ __db('hotel') }}:</span> {{ $hotel->hotel_name }}</div>
                        @foreach($hotel->contacts as $k => $con)
                            <div><span class="text-bold">{{ __db('res'.$k) }}:</span> {{ $con->name }}</div>
                            @php
                                $acc_con .= '<div><b>'. __db('mobile').':</b> <span>'.$con->phone.'</span></div>';
                            @endphp
                        @endforeach
                    </td>
                    <td>
                        <div><span class="text-bold">{{ __db('hotel_number') }}:</span> {{ $hotel->contact_number }}</div>
                        {!! $acc_con !!}
                    </td>
                </tr>
            </table>
        </div>
    @endforeach

    {{-- Drivers --}}
    <div class="section">
        <h3>{{ __db('drivers') }}</h3>
        <table>
            <thead>
                <tr>
                    <th>{{ __db('sl_no') }}</th>
                    <th>{{ __db('name') }}</th>
                    <th>{{ __db('phone') }}</th>
                    <th>{{ __db('vehicle') . " " . __db('type') }}</th>
                    <th>{{ __db('car_number') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($delegation->drivers as $keyDriver => $rowDriver)
                    <tr>
                        <td>{{ $keyDriver+1 }}</td>
                        <td>{{ $rowDriver->name ?? '-' }}</td>
                        <td>{{ $rowDriver->phone_number ?? '-' }}</td>
                        <td>{{ $rowDriver->car_type ?? '-' }}</td>
                        <td>{{ $rowDriver->car_number ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center">{{ __db('no_record_found') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Interviews --}}
    <div class="section">
        <h3>{{ __db('interviews') }}</h3>
        <table>
            <thead>
                <tr>
                    <th>{{ __db('sl_no') }}</th>
                    <th>{{ __db('interview_request_with') }}</th>
                    <th>{{ __db('date') }}</th>
                    <th>{{ __db('time') }}</th>
                    <th>{{ __db('notes') }}</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $interviewData = $delegation->interviews ?? collect();
                    $interviewMembers = '';
                @endphp 
                @forelse ( $interviewData as $in => $row)
                    @php
                        if (!empty($row->other_member_id) && $row->otherMember) {
                            $otherMemberName = $row->otherMember->name ?? '';
                            $otherMemberId = $row->otherMember->getTranslation('name') ?? $row->other_member_id;
                            if ($otherMemberId) {
                                $with = 'Other Member: '.$otherMemberId;
                            }
                        } else {
                            $with = 'Delegation ID : ' .$row->interviewWithDelegation->code ?? '';
                        }

                        $names = $row->interviewMembers
                            ->map(fn($member) => '<span class="block">' . e($member->name ?? '') . '</span>')
                            ->implode('');

                        $interviewMembers =  $with . $names;
                    @endphp
                    <tr>
                        <td>{{ $in + 1 }}</td>
                        <td>
                            {!! $interviewMembers !!}
                        </td>
                        <td>{{ date('d-m-Y', strtotime($row->date_time)) }}</td>
                        <td>{{ date('H:i', strtotime($row->date_time)) }}</td>
                        <td>Airshow venue</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center">{{ __db('no_record_found') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
