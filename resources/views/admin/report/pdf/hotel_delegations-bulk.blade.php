<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ __db('hotel_delegations_report') }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #000;
            margin: 0;
            text-align: right;
        }

        * {
            box-sizing: border-box;
        }

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

<body>
    <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
        <div style="font-family: Arial, sans-serif; margin-top:3%;">
            @foreach ($hotelsData as $hotel)
                @php
                    $delegations = $hotel->rooms
                        ->flatMap(fn($room) => $room->roomAssignments)
                        ->groupBy('delegation_id');
                    $serial = 1;
                @endphp

                @if ($delegations->count() > 0)
                    <table
                        style="width: 100%; margin-top: 20px; font-weight: bold; font-size: 16px; border-collapse: collapse;">
                        <tr>
                            <td style="text-align: right; white-space: nowrap;">
                                {{ $hotel->hotel_name }} : {{ __('Hotel') }}
                            </td>
                        </tr>
                    </table>

                    @foreach ($delegations as $delegationId => $assignments)
                        @php
                            $delegation = $assignments->first()->delegation;
                        @endphp

                        <table style="border: 2px solid black; border-collapse: collapse; width: 100%; margin-bottom: 30px;">
                            <thead class="rtl">
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
                            <tbody class="rtl">
                                @foreach ($assignments as $index => $assignment)
                                    <tr>
                                        @if ($index == 0)
                                            <td rowspan="{{ count($assignments) }}"  style="border:2px solid black; padding:5px; text-align:center;">
                                                {{ $delegation->invitationFrom?->value ?? ' - ' }}
                                            </td>
                                        @endif

                                        <td style="border:2px solid black; padding:5px; text-align:center;">{{ $assignment->room_number ?? ' - ' }}</td>
                                        <td style="border:2px solid black; padding:5px; text-align:center;">{{ $assignment->roomType?->roomType?->value ?? ' - ' }}</td>

                                        <td style="@if ($assignment->assignable?->team_head === true) color:red; @endif border:2px solid black; padding:5px; text-align:center;">
                                            {{ $assignment->assignable?->getTranslation('designation') ?? ' - ' }}
                                        </td>

                                        <td style="@if ($assignment->assignable?->team_head === true) color:red; @endif border:2px solid black; padding:5px; text-align:center;">
                                            {{ getLangTitleSeperator($assignment->assignable?->getTranslation('title'), $assignment->assignable?->getTranslation('name')) ?? ' - ' }}
                                        </td>

                                        @if ($index == 0)
                                            <td rowspan="{{ count($assignments) }}"  style="border:2px solid black; padding:5px; text-align:center;">{{ $delegation->country?->name ?? ' - ' }}</td>
                                            <td rowspan="{{ count($assignments) }}" style="border:2px solid black; padding:5px; text-align:center;">{{ $serial }}</td>
                                        @endif
                                    </tr>
                                @endforeach

                                @foreach ($delegation->escorts as $key => $escort)
                                    @php
                                        $roomEscort = $escort->currentRoomAssignment ?? null;
                                    @endphp
                                    <tr>
                                        <td colspan="3" style="padding:6px; border:0;">
                                            <span class="ltr" style="display:inline-block; width:100%;">
                                                <span style="float:left;">
                                                    {{ $roomEscort?->room_number ?? ' ' }} - {{ $roomEscort?->roomType?->roomType?->value ?? ' ' }} - {{ $roomEscort?->hotel?->hotel_name ?? ' ' }} 
                                                </span>
                                                <strong>: {{ __db('accommodation') }}</strong>
                                            </span>
                                        </td>

                                        <td colspan="1" style="padding:6px; border:0;">
                                            <span class="ltr">
                                                {{ $escort?->phone_number ?? ' - ' }} <strong> : {{ __db('mobile') }}</strong>
                                            </span>
                                        </td>

                                        <td colspan="3" style="padding:6px; border:0; text-align:right;">
                                            <span class="ltr" style="display:inline-block; width:100%;">
                                                <span style="float:left;">
                                                    @if (getActiveLanguage() == 'en')
                                                        {{ $escort?->military_number ?? ' ' }} &nbsp;-&nbsp; {{ $escort?->internalRanking?->value ?? ' ' }}&nbsp;{{ $escort?->name ?? ' ' }}
                                                    @else
                                                        {{ $escort?->internalRanking?->value ?? ' ' }}&nbsp;{{ $escort?->name ?? ' ' }}&nbsp;-&nbsp; {{ $escort?->military_number ?? ' ' }}
                                                       
                                                    @endif
                                                </span>

                                                @if($key == 0)
                                                    <strong >
                                                        : {{ __db('escort') }} 
                                                    </strong>
                                                @endif
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @php $serial++; @endphp
                    @endforeach
                @endif
                @if ($loop->iteration % 5 == 0)
                    <!--CHUNKHTML-->
                @endif
            @endforeach
        </div>
    </div>
</body>
</html>
