<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>{{ __db('hotel_delegations_report') }}</title>
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
           @foreach($hotelsData as $hotel)

                @php
                    $delegations = $hotel->rooms
                                        ->flatMap(fn($room) => $room->roomAssignments)
                                        ->groupBy('delegation_id');
                    $serial = 1;
                @endphp

                @if($delegations->count() > 0)
                    <div style="display: flex; align-items: center; gap: 20px; justify-content: end; font-weight: bold; margin-top:20px;font-size: 16px;">
                        <h4 style="float: right">{{ $hotel->hotel_name }}</h4>
                        <h4>: {{ __('Hotel') }}</h4>
                    </div>
         
                    @foreach($delegations as $delegationId => $assignments)
                        @php
                            $delegation = $assignments->first()->delegation;
                        @endphp

                        <table style="border: 2px solid black; border-collapse: collapse; width: 100%; margin-bottom: 30px;">
                            <thead>
                                <tr>
                                    <th style="border: 2px solid black; padding:5px; background:#d3d3d3; text-align:center;">{{ __db('invitation_from') }}</th>
                                    <th style="border: 2px solid black; padding:5px; background:#d3d3d3; text-align:center;">{{ __db('room_number') }}</th>
                                    <th style="border: 2px solid black; padding:5px; background:#d3d3d3; text-align:center;">{{ __db('room_type') }}</th>
                                    <th style="border: 2px solid black; padding:5px; background:#d3d3d3; text-align:center;">{{ __db('position') }}</th>
                                    <th style="border: 2px solid black; padding:5px; background:#d3d3d3; text-align:center;">{{ __db('delegate') }}</th>
                                    <th style="border: 2px solid black; padding:5px; background:#d3d3d3; text-align:center;">{{ __db('country') }}</th>
                                    <th style="border: 2px solid black; padding:5px; background:#d3d3d3; text-align:center;">{{ __db('sl_no') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tbody>
                                    @foreach($assignments as $index => $assignment)
                                        <tr>
                                            
                                            @if($index == 0)
                                                <td style="border:2px solid black; padding:5px; text-align:center;" rowspan="{{ count($assignments) }}">
                                                    {{ $delegation->invitationFrom?->value ?? '-' }}
                                                </td>
                                            @endif

                                            <td style="border:2px solid black; padding:5px; text-align:center;">{{ $assignment->room_number }}</td>

                                            <td style="border:2px solid black; padding:5px; text-align:center;">
                                                {{ $assignment->roomType?->roomType?->value ?? '' }}
                                            </td>

                                            <td style="border:2px solid black; padding:5px; text-align:center;@if($assignment->assignable?->team_head === true) color: red; @endif">
                                                {{ $assignment->assignable?->internalRanking?->value ?? '' }}
                                            </td>

                                            <td style="border:2px solid black; padding:5px; text-align:center; @if($assignment->assignable?->team_head === true) color: red; @endif">
                                                {{ $assignment->assignable?->getTranslation('title') .' '.$assignment->assignable?->getTranslation('name') }}
                                            </td>

                                            @if($index == 0)
                                                <td style="border:2px solid black; padding:5px; text-align:center;" rowspan="{{ count($assignments) }}">
                                                    {{ $delegation->country?->name ?? '' }}
                                                </td>
                                                <td style="border:2px solid black; padding:5px; text-align:center;" rowspan="{{ count($assignments) }}">
                                                    {{ $serial }}
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach

                                    @foreach ($delegation->escorts as $key => $escort)
                                            @php
                                                $roomEscort = $escort->currentRoomAssignment ?? null;
                                            @endphp
                                            <tr>
                                                <td style="padding: 6px; border: 0px;" colspan="2">
                                                    {{ $roomEscort?->room_number }} - {{ $roomEscort?->roomType?->roomType?->value ?? ''  }} - {{ $roomEscort?->hotel?->hotel_name }} 
                                                    <strong> : {{ __db('accommodation') }}</strong>
                                                </td>
                                                <td style="padding: 6px; border: 0px;" colspan="2">
                                                    {{ $escort?->phone_number }} <strong> : {{ __db('mobile') }} </strong>
                                                </td>
                                                <td style="padding: 6px; border: 0px;text-align: right;" colspan="3">
                                                    <span style="{{ $key != 0 ? 'margin-right: 40px;' : '' }}">{{ $escort?->internalRanking?->value }} {{ $escort?->name }} - {{ $escort?->military_number }}  <span>
                                                    @if($key == 0) <strong> : {{ __db('escort') }}</strong> @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                </tbody>


                            </tbody>
                        </table>

                        @php
                            $serial++;
                        @endphp
                    @endforeach
                @endif
                
            @endforeach
        </div>
    </div>
</body>
</html>
