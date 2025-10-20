<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>{{ __db('departures_report') }}</title>
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
     th, td {
            padding: 5px;
            text-align: center;
        }

        /* Direction handling */
        [lang="ar"], .rtl {
            direction: rtl;
            text-align: right;
            unicode-bidi: isolate;
        }

        [lang="en"], .ltr {
            direction: ltr;
            text-align: left;
            unicode-bidi: isolate;
        }

        /* Optional background for headers */
        thead th {
            background: #d3d3d3;
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

                 <table style="width:100%; border-collapse:collapse;">
                    <tbody>
                        <tr>
                            
                            <td style="padding:6px; border:0;">
                                <span class="ltr" style="display:inline-block; width:100%;">
                                    <span style="float:left;">
                                        {{ $delegation?->country?->name ?? '-' }}
                                    </span>
                                    <strong>: {{ __db('country') }}</strong>
                                </span>
                            </td>
                            
                            <td style="padding:6px; border:0;">
                                <span class="ltr" style="display:inline-block; width:100%;">
                                    <span style="float:left;">
                                        
                                    </span>
                                    <strong></strong>
                                </span>
                            </td>
                            
                            <td style="padding:6px; border:0; text-align:right;">
                                <span class="ltr" style="display:inline-block; width:100%;">
                                    <span style="float:left;">
                                        {{ $delegation->invitationFrom?->value ?? '-' }}
                                    </span>
                                    <strong>: {{ __db('invitation_from') }}</strong>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:6px; border:0;">
                            
                            </td>
                            <td  style="padding:6px; border:0;">
                                
                            </td>

                            <td  style="padding:6px; border:0; text-align:right;">
                                <span class="ltr" style="display:inline-block; width:100%;">
                                    <span style="float:left;">
                                        {{ $delegation?->note2 ?? '-' }}
                                    </span>
                                    <strong>: {{ __db('note') }}</strong>
                                </span>
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
                               
                                <td colspan="5" style="padding:6px; border:0;">
                                    <span class="ltr" style="display:inline-block; width:100%;">
                                        <span style="float:left;">
                                            {{ $roomEscort?->room_number ?? ' ' }} - {{ $roomEscort?->hotel?->hotel_name ?? ' ' }} 
                                        </span>
                                        <strong>: {{ __db('accommodation') }}</strong>
                                    </span>
                                </td>
                               
                                <td colspan="1" style="padding:6px; border:0;">
                                    <span class="ltr">
                                        {{ $escort?->phone_number ?? ' - ' }} <strong> : {{ __db('mobile') }}</strong>
                                    </span>
                                </td>
                                
                                <td colspan="5" style="padding:6px; border:0; text-align:right;">
                                    <span class="ltr" style="display:inline-block; width:100%;">
                                        <span style="float:left;">
                                            @if (getActiveLanguage() == 'en')
                                                {{ $escort?->military_number ?? ' ' }} &nbsp;-&nbsp; {{ $escort?->internalRanking?->value ?? ' ' }}&nbsp;{{ $escort?->name ?? ' ' }}
                                            @else
                                                {{ $escort?->internalRanking?->value ?? ' ' }}&nbsp;{{ $escort?->name ?? ' ' }}&nbsp;-&nbsp; {{ $escort?->military_number ?? ' ' }}
                                               
                                            @endif
                                        </span>

                                        <strong> : {{ __db('escort') }}</strong> 
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <table style="width: 100%; border-collapse: collapse; border: 2px solid #000; border-bottom: 0;">
                    <thead class="rtl">
                        <tr style="background-color: #d9d9d9;">
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('room') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('room_type') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('hotel') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('time') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('date') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('flight_number') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('flight_name') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('airport') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('position') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('delegations') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('sl_no') }}</th>
                        </tr>
                    </thead>
                    <tbody style="text-align: center;"  class="rtl">
                        @forelse ($delegates as $key => $delegate)
                            @php
                                $delegateRoom = $delegate->currentRoomAssignment ?? null;
                                $assignedHotels[] = $delegateRoom?->hotel_id ?? null;
                                
                                $relation = '';
                                if($delegate->relationship){
                                    $relation = $delegate->relationship?->value .' '. __db('of') .' '. $delegate->parent?->getTranslation('name');
                                }
                                $departure = $delegate->delegateTransports->where('type', 'departure')->first();
                            @endphp
                            <tr>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">{{ $delegateRoom ? $delegateRoom?->room_number : '-' }}</td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">{{ $delegateRoom?->roomType?->roomType?->value }}</td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                    {{ $delegateRoom?->hotel?->hotel_name ?? '-' }}
                                </td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center; @if($delegate->team_head === true) color: red; @endif">
                                    {{ $departure?->date_time ? date('H:i', strtotime($departure?->date_time)) : '-' }}
                                </td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                    {{ $departure?->date_time ? date('d-m-Y', strtotime($departure?->date_time)) : '-' }}
                                </td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">{{ $departure?->flight_no ?? '-' }}</td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">{{ $departure?->flight_name ?? '-' }}</td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">{{ $departure?->airport?->value ?? ucwords($departure?->mode) }}</td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">{{ $delegate?->getTranslation('designation') ?? $relation }}</td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center; @if($delegate->team_head === true) color: red; @endif">
                                    <strong>
                                        {{ getLangTitleSeperator($delegate?->getTranslation('title'), $delegate?->getTranslation('name')) }}
                                    </strong>
                                </td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">{{ $key + 1 }}</td>
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
                                  
                                    <td colspan="2" style="padding:6px; border:0;">
                                        <span class="ltr" style="display:inline-block; width:100%;">
                                            <span style="float:left;">
                                                {{ $driver->car_number  ?? '-'}}
                                            </span>
                                            <strong>: {{ __db('car_number') }}</strong>
                                        </span>
                                    </td>
                                    
                                    <td colspan="2" style="padding:6px; border:0;">
                                        <span class="ltr" style="display:inline-block; width:100%;">
                                            <span style="float:left;">
                                                {{ $driver->car_type  ?? '-'}}
                                            </span>
                                            <strong>: {{ __db('car_type') }}</strong>
                                        </span>
                                    </td>
                                    
                                    <td colspan="2" style="padding:6px; border:0;">
                                        <span class="ltr" style="display:inline-block; width:100%;">
                                            <span style="float:left;">
                                                {{ $driver->phone_number ?? '-' }}
                                            </span>
                                            <strong>: {{ __db('mobile') }}</strong>
                                        </span>
                                    </td>
                                    
                                    <td colspan="5" style="padding:6px; border:0; text-align:right;">
                                        <span class="ltr" style="display:inline-block; width:100%;">
                                            <span style="float:left;">
                                                @if (getActiveLanguage() == 'en')
                                                    {{ $driver?->military_number }} &nbsp;-&nbsp; {{ $driver?->getTranslation('title') .' '. $driver?->getTranslation('name') }}
                                                @else
                                                    {{ $driver?->getTranslation('title') .' '. $driver?->getTranslation('name') }}&nbsp;-&nbsp; {{ $driver?->military_number }}
                                                   
                                                @endif
                                            </span>

                                            <strong> : {{ __db('driver') }}{{ $index+1 }}</strong>
                                        </span>
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
