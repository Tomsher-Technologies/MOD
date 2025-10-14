<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>{{ __db('arrival_hotels_report') }}</title>
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
    table {
        border-collapse: collapse;
        border-spacing: 0;   
        width: 100%;
    }

    </style>
</head>
<body style="margin: 0; font-size: 12px;">
    <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
        <div style="font-family: Arial, sans-serif;  gap: 20px; align-items: center;margin-top:3%;">
           @foreach($formattedGroups as $group)
                @php
                    $delegation = $group->delegation;
                    $delegates = $group->delegates;
                    $assignedHotels = [];
                    $escortAccHtml = $escortContactHtml = $escortDetailsHtml = '';
                @endphp

                 <table style="width:100%; border-collapse: collapse; margin-bottom: 5px;">
                    <tbody>
                        <tr>
                            <td style="width:40%; padding: 3px; border: 0px;">
                                <div style="">
                                    {{ $delegation?->country?->name ?? '-' }}                                    
                                    <strong> : {{ __db('country') }}</strong>
                                </div>
                            
                            </td>
                            <td style="width:15%; padding: 3px; border: 0px;">
                                
                            </td>

                            <td style="width:55%; padding: 3px; border: 0px;text-align: right;">
                                <div style="">
                                    {{ $delegation->invitationFrom?->value ?? '-' }}
                                    <strong> : {{ __db('invitation_from') }}</strong>
                                </div>
                                
                            </td>
                        </tr>
                        <tr>
                            <td style="width:40%; padding: 6px; border: 0px;">
                            
                                <div style="margin-bottom: 5px;">
                                    {{ $delegation?->note2 ?? '-' }}
                                    <strong> : {{ __db('note_2') }}</strong>
                                </div>
                            </td>
                            <td style="width:15%; padding: 6px; border: 0px;">
                                
                            </td>

                            <td style="width:55%; padding: 6px; border: 0px;text-align: right;">
                            
                                <div style="margin-bottom: 5px; display: flex; justify-content: flex-end;">
                                    <span>{{ $delegation?->note1 ?? '-' }}</span>
                                    <strong style="width: 22%;"> : {{ __db('note_1') }}</strong>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table style="width:100%; border-collapse: collapse; margin-bottom: 10px;">
                    <tbody>
                        @foreach ($delegation?->escorts as $key => $escort)
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
                                    {{ $escort?->internalRanking?->value }} {{ $escort?->name }} - {{ $escort?->military_number }}  @if($key == 0) <strong> : {{ __db('escort') }}</strong> @else <strong style="margin-right: 45px;"></strong> @endif
                                        
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <table style="width: 100%; border-collapse: collapse; border: 2px solid #000; border-bottom: 0;">
                    <thead>
                        <tr style="background-color: #d9d9d9;">
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('room') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('room_type') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('hotel') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('flight_number') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('time') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('departure_date') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('flight_number') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('time') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('arrival_date') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('position') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('delegations') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('sl_no') }}</th>
                        </tr>
                    </thead>
                    <tbody style="text-align: center;">
                         @php
                        $separator = (getActiveLanguage() === 'ar') ? ' / ' : ' . ';
                    @endphp
                        @forelse ($delegates as $key => $delegate)
                            @php
                                $delegateRoom = $delegate->currentRoomAssignment ?? null;
                                $assignedHotels[] = $delegateRoom?->hotel_id ?? null;
                                
                                $relation = '';
                                if($delegate->relationship){
                                    $relation = $delegate->relationship?->value .' '. __db('of') .' '. $delegate->parent?->getTranslation('name');
                                }
                                $arrival = $delegate->delegateTransports->where('type', 'arrival')->first();
                                $departure = $delegate->delegateTransports->where('type', 'departure')->first();
                            @endphp
                            <tr>
                                <td style="padding: 8px; border: 2px solid #000;">{{ $delegateRoom ? $delegateRoom?->room_number : '-' }}</td>
                                <td style="padding: 8px; border: 2px solid #000;">{{ $delegateRoom?->roomType?->roomType?->value }}</td>
                                <td style="padding: 8px; border: 2px solid #000;">
                                    {{ $delegateRoom?->hotel?->hotel_name ?? __db('not_required') }}
                                </td>
                                <td style="padding: 8px; border: 2px solid #000;">{{ $departure?->flight_no ?? '-' }}</td>
                                <td style="padding: 8px; border: 2px solid #000; @if($delegate->team_head === true) color: red; @endif">
                                    {{ $departure?->date_time ? date('H:i', strtotime($departure?->date_time)) : '-' }}
                                </td>
                                <td style="padding: 8px; border: 2px solid #000;">
                                    {{ $departure?->date_time ? date('d-m-Y', strtotime($departure?->date_time)) : '-' }}
                                </td>
                                <td style="padding: 8px; border: 2px solid #000;">{{ $arrival?->flight_no ?? '-' }}</td>
                                <td style="padding: 8px; border: 2px solid #000; @if($delegate->team_head === true) color: red; @endif">
                                    {{ $arrival?->date_time ? date('H:i', strtotime($arrival?->date_time)) : '-' }}
                                </td>
                                <td style="padding: 8px; border: 2px solid #000;">
                                    {{ $arrival?->date_time ? date('d-m-Y', strtotime($arrival?->date_time)) : '-' }}
                                </td>
                                <td style="padding: 8px; border: 2px solid #000;">{{ $delegate?->getTranslation('designation') ?? $relation }}</td>
                                <td style="padding: 8px; border: 2px solid #000; @if($delegate->team_head === true) color: red; @endif">
                                    <strong>
                                        {{ $delegate->getTranslation('title').''.$separator.' '.$delegate->getTranslation('name') }}
                                    </strong>
                                </td>
                                <td style="padding: 8px; border: 2px solid #000;">{{ $key + 1 }}</td>
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

                @if($delegation->drivers->isNotEmpty())
                    <div style="display: flex; justify-content: space-between; align-items: end; margin-bottom: 20px; gap: 20px; border: 2px solid #000; border-top: 0; padding: 8px;">
                        <table style="width:100%; border-collapse:collapse;">
                            @foreach($delegation?->drivers as $index => $driver)
                                <tr>
                                    <td style="padding:6px;text-align:right;width: 20%;">
                                        <span >{{ $driver->car_number }}</span>
                                        <strong> : {{ __db('car_number') }}</strong>
                                    </td>
                                    <td style="padding:6px;text-align:right;width: 20%;">
                                        <span >{{ $driver->car_type }}</span>
                                        <strong> : {{ __db('car_type') }}</strong>
                                    </td>
                                    <td style="padding:6px;text-align:right;width: 20%;">
                                        <span >{{ $driver->phone_number }}</span>
                                        <strong> : {{ __db('mobile') }}</strong>
                                    </td>
                                    <td style="padding:6px;text-align:right;width: 40%;">
                                        <span >{{ $driver?->getTranslation('name') }} {{ $driver?->getTranslation('title') }} - {{ $driver?->military_number }}</span>
                                        <strong> : {{ __db('driver') }}{{ $index+1 }}</strong>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                @else
                    <div style="margin-bottom: 20px; ">
                       
                    </div>
                @endif

                @if($loop->iteration % 20 == 0)
                    <!--CHUNKHTML-->
                @endif
            
            @endforeach
        </div>
    </div>
</body>
</html>
