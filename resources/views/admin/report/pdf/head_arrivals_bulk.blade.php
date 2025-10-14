<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>{{ __db('delegations_heads_arrival') }}</title>
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
        <div style="font-family: Arial, sans-serif;  gap: 20px; align-items: center;margin-top:3%;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #d9d9d9; font-size: 13px">
                        <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('invitation_from') }}</th>
                        <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('hotel') }}</th>
                        <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('time') }}</th>
                        <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('arrival') }} </th>
                        <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('flight') }}</th>
                        <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('airport') }}</th>
                        <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('escort') }}</th>
                        <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('position') }}</th>
                        <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('delegation_head') }}</th>
                        <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('country') }}</th>
                        <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('sl_no') }}</th>
                    </tr>
                </thead>
                <tbody style="font-size: 12px">
                    @foreach ($headsArrivals as $i => $arrival)
                        <tr>
                            <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                {{ $arrival?->delegate?->delegation?->invitationFrom?->value ?? '-' }}
                            </td>
                            <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                {{ $arrival?->delegate?->currentRoomAssignment?->hotel?->hotel_name ?? '-' }}
                            </td>
                            <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                {{ $arrival?->date_time ? \Carbon\Carbon::parse($arrival?->date_time)?->format('H:i') : '-' }}
                            </td>
                            <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                {{ $arrival?->date_time ? \Carbon\Carbon::parse($arrival?->date_time)?->format('d-m-Y') : '-' }}
                            </td>
                            <td style="padding: 8px; border: 2px solid #000; text-align: center;">{{ $arrival?->flight_no ?? '-' }}</td>
                            <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                {{ $arrival?->airport?->value ?? ucwords($arrival?->mode) }}
                            </td>
                            <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                @php
                                    $escort = $arrival?->delegate?->delegation?->escorts?->first();
                                @endphp
                                {{ $escort?->military_number .' - '. $escort?->internalRanking?->value .' '. $escort?->name  }}
                                <br>
                                {{ $escort?->phone_number }}
                            </td>
                            <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                {{ $arrival?->delegate?->getTranslation('designation') ?? '-' }}
                            </td>
                            <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                {{ $arrival?->delegate?->getTranslation('title') }}
                                {{ $arrival?->delegate?->getTranslation('name') ?? '-' }}
                            </td>
                            <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                {{ $arrival?->delegate?->delegation?->country?->name ?? '-' }}
                            </td>
                            <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                {{ $i + 1 }}
                            </td>
                        </tr>
                        
                        @if($loop->iteration % 40 == 0)
                            <!--CHUNKHTML-->
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
