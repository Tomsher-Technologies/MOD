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
    table {
        border-collapse: collapse;
        border-spacing: 0;   
        width: 100%;
    }

    </style>
</head>
<body style="margin: 0; padding: 0; font-size: 12px;">
    <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
        <div style="font-family: Arial, sans-serif; display: flex; flex-direction: column; gap: 20px; align-items: center;padding-top: 2%;">
            @foreach ($accommodations as $hotel)
                <div style="width: 100%; margin-top:3%;">
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


                    @php
                        $rooms = $hotel->rooms;
                        $totalRooms = $rooms->sum('total_rooms');
                        $totalAssigned = $rooms->sum('assigned_rooms');
                        $totalAvailable = $rooms->sum('available_rooms');
                        $accommodatedHtml = '';
                        $totalHtml = '';
                    @endphp

                    <table style="width: 100%; margin-top: 10px;">
                        <tr>
                            <!-- Remaining Rooms Table -->
                            <td style="width: 30%; vertical-align: top; text-align: center;">
                                <table style="width: 100%; background-color: white;">
                                    <tbody style="text-align: center;">
                                        <tr class="header-row" style="font-weight: 600; background-color: #f0f0f0;">
                                            <td style="border: 1px solid black; padding: 8px; text-align: center;"><b>{{ __db('total_remaining') }}</b></td>
                                            <td style="text-align: right;border: 1px solid black; padding: 8px; text-align: center;"><b>{{ $totalAvailable }}</b></td>
                                        </tr>
                                        @foreach ($rooms as $room)
                                            <tr>
                                                <td style="border: 1px solid black; padding: 8px; text-align: center;">{{ $room->roomType?->value ?? 'Unknown' }}</td>
                                                <td style="text-align: right;border: 1px solid black; padding: 8px; text-align: center;">{{ $room->available_rooms }}</td>
                                            </tr>
                                            @php
                                                $accommodatedHtml .= '<tr>
                                                                        <td style="border: 1px solid black; padding: 8px; text-align: center;">'. ($room->roomType?->value ?? 'Unknown') .'</td>
                                                                        <td style="text-align:right;border: 1px solid black; padding: 8px; text-align: center;">'. $room->assigned_rooms .'</td>
                                                                    </tr>';
                                                $totalHtml .= '<tr>
                                                                    <td style="border: 1px solid black; padding: 8px; text-align: center;">'. ($room->roomType?->value ?? 'Unknown') .'</td>
                                                                    <td style="text-align:right;border: 1px solid black; padding: 8px; text-align: center;">'. $room->total_rooms .'</td>
                                                                </tr>';
                                            @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>

                            <td style="width:3%; "></td>
                            <!-- Accommodated Rooms Table -->
                            <td style="width: 30%; vertical-align: top; text-align: center;">
                                <table style="width: 100%; background-color: white;">
                                    <tbody style="text-align: center;">
                                        <tr class="header-row" style="font-weight: bold; background-color: #f0f0f0;">
                                            <td style="border: 1px solid black; padding: 8px; text-align: center;"><b>{{ __db('total_accommodations') }}</b></td>
                                            <td style="text-align: right;border: 1px solid black; padding: 8px; text-align: center;"><b>{{ $totalAssigned }}</b></td>
                                        </tr>
                                        {!! $accommodatedHtml !!}
                                    </tbody>
                                </table>
                            </td>
                            <td style="width:3% ;"></td>
                            <!-- Total Rooms Table -->
                            <td style="width: 30%; vertical-align: top; text-align: center;">
                                <table style="width: 100%; background-color: white;">
                                    <tbody style="text-align: center;">
                                        <tr class="header-row" style="font-weight: bold; background-color: #f0f0f0;">
                                            <td style="border: 1px solid black; padding: 8px; text-align: center;"><b>{{ __db('total_rooms') }}</b></td>
                                            <td style="text-align: right;border: 1px solid black; padding: 8px; text-align: center;"><b>{{ $totalRooms }}</b></td>
                                        </tr>
                                        {!! $totalHtml !!}
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>

                @if($loop->iteration % 20 == 0)
                    <!--CHUNKHTML-->
                @endif
            @endforeach
        </div>
    </div>
</body>
</html>
