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
            /* direction: rtl; */
            /* margin: 20px; */
            text-align: right;
        }
    * { box-sizing: border-box; }

    </style>
</head>
<body style="margin: 0px; padding: 0px; font-size: 10px;">
    <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6" >
        <div style=" border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px;">
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
                <tr>
                    <td style="width: 25%; text-align: left; vertical-align: middle;">
                        <img src="{{ public_path('assets/img/md-logo.svg') }}" 
                            alt="{{ env('APP_NAME') }}" 
                            style="width: 150px; height: auto;">
                    </td>

                    <td style="width: 50%; text-align: center; vertical-align: middle;">
                        <div style="font-size: 14px; font-weight: bold;">
                            {{ __db('united_arab_emirates') }}
                        </div>
                        <div style="font-size: 14px; font-weight: bold; margin-top: 5px;">
                            {{ __db('ministry_of_defense') }}
                        </div>
                        <div style="font-size: 14px; font-weight: bold; color: #cc0000; margin-top: 5px;">
                            {{ __db('delegations_escort_report') }}
                        </div>
                    </td>

                    <td style="width: 25%; text-align: right; vertical-align: middle;">
                        <img src="{{ asset(getAdminEventPDFLogo()) }}" 
                            alt="{{ getCurrentEventName() }}" 
                            style="width: 150px; height: auto;">
                    </td>
                </tr>
            </table>

            <div style="text-align: right; font-size: 0.9em; margin-top:10px;">{{ date('d-m-Y H:i A') }}</div>

        </div>

        @php
            $assignedHotels = [];
            $escortAccHtml = $escortContactHtml = $escortDetailsHtml = '';
        @endphp

        <table style="width:100%; border-collapse: collapse; margin-bottom: 10px;">
            <tbody>
                @foreach ($delegation->escorts as $key => $escort)
                    @php
                        $roomEscort = $escort->currentRoomAssignment ?? null;
                        $assignedHotels[] = $roomEscort?->hotel_id ?? null;
                    @endphp

                    <tr>
                        <td style="padding: 6px; border: 0px;">
                            {{ $roomEscort?->room_number }} - {{ $roomEscort?->hotel?->hotel_name }} <strong> : {{ __db('accommodation') }}</strong>
                        </td>
                        <td style="padding: 6px; border: 0px;">
                            {{ $escort?->phone_number }} <strong> : {{ __db('mobile') }} </strong>
                        </td>
                        <td style="padding: 6px; border: 0px;text-align: right;">
                            {{ $escort?->internalRanking?->value }} {{ $escort?->name }} ({{ $escort?->military_number }})  @if($key == 0) <strong> : {{ __db('escort') }}</strong> @else <strong style="margin-right: 25px;"></strong> @endif
                                
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table style="width:100%; border-collapse: collapse; margin-bottom: 8px;">
            <tbody>
                <tr>
                        <td style="width:40%; padding: 6px; border: 0px;">
                            <div style="margin-bottom: 5px;">
                                {{ $delegation->invitationFrom?->value ?? '-' }}
                                <strong> : {{ __db('invitation_from') }}</strong>
                            </div>
                           
                        </td>
                        <td style="width:15%; padding: 6px; border: 0px;">
                            
                        </td>

                        <td style="width:55%; padding: 6px; border: 0px;text-align: right;">
                            <div style="margin-bottom: 5px;">
                                {{ $delegation->country?->name ?? '-' }}
                                <strong> : {{ __db('country') }}</strong>
                            </div>
                            
                        </td>
                    </tr>
                    <tr>
                        <td style="width:40%; padding: 6px; border: 0px;">
                          
                            <div style="margin-bottom: 5px;">
                                {{ $delegation->participationStatus?->value ?? '-' }}
                                <strong> : {{ __db('participation_status') }}</strong>
                            </div>
                        </td>
                        <td style="width:15%; padding: 6px; border: 0px;">
                            
                        </td>

                        <td style="width:55%; padding: 6px; border: 0px;text-align: right;">
                           
                            <div style="margin-bottom: 5px; display: flex; justify-content: flex-end;">
                                <span>{{ $delegation->invitationStatus?->value ?? '-' }}</span>
                                <strong style="width: 22%;"> : {{ __db('invitation_status') }}</strong>
                            </div>
                        </td>
                    </tr>
            </tbody>
        </table>

        @php
            $teamHead = '';
        @endphp

        <h2 class="font-bold text-center mb-3" style="font-size: 14px;">{{ __db('arrival_details') }}</h2>
        
        <table style="width: 100%; border-collapse: collapse;border: 1px solid #000;">
            <thead>
                <tr style="background-color: #d9d9d9;">
                    <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('time') }}</th>
                    <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('date') }}</th>
                    <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('airport') }}</th>
                    <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('flight_number') }}</th>
                    <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('flight_name') }}</th>
                    <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('room') }}</th>
                    <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('position') }}</th>
                    <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('delegations') }}</th>
                    <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('sl_no') }}</th>
                </tr>
            </thead>
            <tbody  style="text-align: center;">
                @forelse ($delegation->delegates as $key => $delegate)
                    @php
                        $delegateRoom = $delegate->currentRoomAssignment ?? null;
                        $assignedHotels[] = $delegateRoom?->hotel_id ?? null;
                        if($delegate->team_head == 1){
                            $departure = $delegate->delegateTransports->where('type', 'departure')->first();
                    
                            $teamHead.= '<tr style="border: 1px solid #000;">                                    
                                <td style="padding: 8px; border: 1px solid #000; text-align: center;">'.date('H:i', strtotime($departure?->date_time)).'</td>
                                <td style="padding: 8px; border: 1px solid #000; text-align: center;">'. date('d-m-Y', strtotime($departure?->date_time)) .'</td>
                                <td style="padding: 8px; border: 1px solid #000; text-align: center;">'.($departure?->airport?->value ?? ucwords($departure?->mode)) .'</td>
                                <td style="padding: 8px; border: 1px solid #000; text-align: center;">'.($departure?->flight_no).'</td>
                                <td style="padding: 8px; border: 1px solid #000; text-align: center;">'.($departure?->flight_name).'</td>
                            </tr>';
                        }
                    @endphp
                    <tr>
                        @php
                            $relation = '';
                            if($delegate->relationship){
                                $relation = $delegate->relationship?->value .' '. __db('of') .' '. $delegate->parent?->getTranslation('name');
                            }
                            $arrival = $delegate->delegateTransports->where('type', 'arrival')->first();
                        @endphp
                        <td style="padding: 8px; border: 1px solid #000; text-align: center;">{{ $arrival?->date_time ? date('H:i', strtotime($arrival?->date_time)) : '-' }}</td>
                        <td style="padding: 8px; border: 1px solid #000; text-align: center;">{{ $arrival?->date_time ? date('d-m-Y', strtotime($arrival?->date_time)) : '-' }}</td>
                        <td style="padding: 8px; border: 1px solid #000; text-align: center;">{{ $arrival?->airport?->value ?? ucwords($arrival?->mode) }}</td>
                        <td style="padding: 8px; border: 1px solid #000; text-align: center;">{{ $arrival?->flight_no ?? '-' }}</td>
                        <td style="padding: 8px; border: 1px solid #000; text-align: center;">
                            {{ $arrival?->flight_name ?? '-' }}
                        </td>
                        <td style="padding: 8px; border: 1px solid #000; text-align: center;">
                            {{ $delegateRoom ? $delegateRoom?->room_number .' - '. $delegateRoom?->hotel?->hotel_name : __db('not_required') }}
                        </td>
                        <td style="padding: 8px; border: 1px solid #000; text-align: center; @if($delegate->team_head === true) color: red; @endif ">
                            {{ $delegate->internalRanking?->value ?? $relation }}
                        </td>
                        <td style="padding: 8px; border: 1px solid #000; text-align: center; @if($delegate->team_head === true) color: red; @endif  font-bold">
                            {{ $delegate->getTranslation('title').' '.$delegate->getTranslation('name') }}
                        </td>
                        <td style="padding: 8px; border: 1px solid #000; text-align: center;">{{ $key + 1 }}</td>
                    </tr>
                @empty
                    <tr class="border-t">
                        <td colspan="9" style="padding: 8px; border: 1px solid #000; text-center">
                            {{ __db('no_record_found') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <h2 class="text-md font-bold mb-3" style="margin-top: 30px;font-size: 14px;">{{  __db('departure_details_of_head_of_delegation') }}</h2>
        
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #d9d9d9;">
                    <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('time') }}</th>
                    <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('date') }}</th>
                    <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('airport') }}
                    </th>
                    <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('flight_number') }}</th>
                    <th style="padding: 8px; border: 1px solid #000; text-align: center;"> {{ __db('flight_name') }}</th>
                </tr>
            </thead>
            <tbody  style="text-align: center;">
                @if($teamHead)
                    {!! $teamHead !!}
                @else
                    <tr class="border-t">
                        <td colspan="5" style="padding: 8px; border: 1px solid #000; text-center">
                            {{ __db('no_record_found') }}
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; gap: 20px; margin-top: 20px;">

            @php
                $assignedHotels = array_filter(array_unique($assignedHotels));
                $hotelDetails = getAccommodationDetails($assignedHotels);
            @endphp

            <table style="width:100%; border-collapse: collapse; margin-bottom: 12px;">
                <tbody>
                    @foreach($hotelDetails as $key => $hotel)
                        @php
                            $acc_con = $acc_name = '';
                            foreach($hotel->contacts as $k => $con){
                                $acc_con .= '<div style="margin-bottom: 5px; display: flex;">
                                                <span>'.$con->phone.'</span>
                                                <strong style="width: 30%;"> : '. __db('mobile').'</strong>
                                            </div>';
                                $acc_name .= '<div style="margin-bottom: 5px; display: flex; justify-content: flex-end;">
                                                <span>'.$con->name.'</span>
                                                <strong style="width: 22%;"> : '. __db('res'.$k).'</strong>
                                            </div>';
                            }
                        @endphp

                        <tr>
                            <!-- Left side (hotel numbers + contacts) -->
                            <td style="width:30%; vertical-align: top; padding: 6px;">
                                <div style="margin-bottom: 5px; display: flex;">
                                    <span>{{ $hotel->contact_number }}</span>
                                    <strong style="width: 30%;"> : {{ __db('hotel_number') }}</strong>
                                </div>
                                {!! $acc_con !!}
                            </td>

                            <!-- Right side (hotel name + responsible persons) -->
                            <td style="width:25%; text-align: right; vertical-align: top; padding: 6px;">
                                <div style="margin-bottom: 5px; display: flex; justify-content: flex-end;">
                                    <span>{{ $hotel->hotel_name }}</span>
                                    <strong style="width: 22%;"> : {{ __db('hotel') }}</strong>
                                </div>
                                {!! $acc_name !!}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-1 gap-x-0 md:gap-x-8 gap-y-8">
            <section>
                <h2 class="text-md font-bold mb-3 text-end" style="font-size: 14px;" >{{ __db('drivers') }}</h2>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #d9d9d9;">
                            <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('car_number') }}</th>
                            <th style="padding: 8px; border: 1px solid #000; text-align: center;"> {{  __db('vehicle') . " " . __db('type') }}</th>
                            <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('phone') }}</th>
                            <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('name') }}</th>
                            <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('sl_no') }}</th>
                        </tr>
                    </thead>
                    <tbody  style="text-align: center;">
                        @forelse ($delegation->drivers as $keyDriver => $rowDriver)
                            <tr>
                                <td  style="padding: 8px; border: 1px solid #000; text-align: center;">{{ $rowDriver->car_number ?? '-' }}</td>
                                <td  style="padding: 8px; border: 1px solid #000; text-align: center;">{{ $rowDriver->car_type ?? '-' }}</td>
                                <td  style="padding: 8px; border: 1px solid #000; text-align: center;">{{ $rowDriver->phone_number ?? '-' }}</td>
                                <td  style="padding: 8px; border: 1px solid #000; text-align: center;">
                                    {{ $rowDriver->name ?? '-' }}
                                </td>
                                <td  style="padding: 8px; border: 1px solid #000; text-align: center;">{{ $keyDriver + 1 }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="padding: 8px; border: 1px solid #000;text-align: center;">
                                    {{ __db('no_record_found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </section>

            <section>
                <h2 class="text-md font-bold mb-3 text-end" style="font-size: 14px;">{{ __db('interviews') }}</h2>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr  style="background-color: #d9d9d9;">
                            <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('notes') }}
                            </th>
                            <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('time') }}</th>
                            <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('date') }}
                            </th>
                            <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('interview_request_with') }}</th>
                            <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('sl_no') }}</th>
                        </tr>
                    </thead>
                    <tbody style="text-align: center;">
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
                                <td  style="padding: 8px; border: 1px solid #000; text-align: center;">-</td>
                                <td  style="padding: 8px; border: 1px solid #000; text-align: center;">{{ date('H:i', strtotime($row->date_time)) }}</td>
                                <td  style="padding: 8px; border: 1px solid #000; text-align: center;">{{ date('d-m-Y', strtotime($row->date_time)) }}</td>
                                <td  style="padding: 8px; border: 1px solid #000; text-align: center;">{!! $interviewMembers !!}</td>
                                <td  style="padding: 8px; border: 1px solid #000; text-align: center;">{{ $in + 1 }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="padding: 8px; border: 1px solid #000;text-align: center;">
                                    {{ __db('no_record_found') }}
                                </td>
                            </tr>
                        @endforelse
                        
                    </tbody>
                </table>
            </section>
        </div>
    </div>
        
</body>
</html>
