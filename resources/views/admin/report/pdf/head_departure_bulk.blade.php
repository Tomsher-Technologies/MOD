<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>{{ __db('delegations_heads_departure') }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #000;
            /* direction: rtl; */
            /* margin: 20px; */
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
    </style>
</head>

<body style="margin: 0; font-size: 12px;">
    <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
        <div style="font-family: Arial, sans-serif;  gap: 20px; align-items: center;margin-top:3%;">
            <table style="width: 100%; border-collapse: collapse;padding-top: 2%;">
                <thead>
                    <tr style="background-color: #d9d9d9; font-size: 13px">
                        <th style="padding: 8px; border: 2px solid #000; text-align: center;">
                            {{ __db('invitation_from') }}</th>
                        <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('hotel') }}</th>
                        <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('time') }}</th>
                        <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('departure') }}
                        </th>
                        <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('flight') }}</th>
                        <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('airport') }}</th>
                        <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('escort') }}</th>
                        <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('position') }}
                        </th>
                        <th style="padding: 8px; border: 2px solid #000; text-align: center;">
                            {{ __db('delegation_head') }}</th>
                        <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('country') }}
                        </th>
                        <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('sl_no') }}</th>
                    </tr>
                </thead>
                <tbody style="font-size: 12px">
                    
                    @foreach ($headsDeparture as $i => $departure)
                        <tr>
                            <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                {{ $departure?->delegate?->delegation?->invitationFrom?->value ?? '-' }}
                            </td>
                            <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                {{ $departure?->delegate?->currentRoomAssignment?->hotel?->hotel_name ?? '-' }}
                            </td>
                            <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                {{ $departure?->date_time ? \Carbon\Carbon::parse($departure?->date_time)?->format('H:i') : '-' }}
                            </td>
                            <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                {{ $departure?->date_time ? \Carbon\Carbon::parse($departure?->date_time)?->format('d-m-Y') : '-' }}
                            </td>
                            <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                {{ $departure?->flight_no ?? '-' }}</td>
                            <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                {{ $departure?->airport?->value ?? ucwords($departure?->mode) }}
                            </td>
                            <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                @php
                                    $escort = $departure?->delegate?->delegation?->escorts?->first();
                                @endphp
                                
                                @if (getActiveLanguage() == 'en')
                                    <span>{{ $escort?->military_number }}</span> - <span>{{ $escort?->internalRanking?->value .' '. $escort?->name }}</span>
                                @else
                                    <span>{{ $escort?->internalRanking?->value .' '. $escort?->name }}</span> - <span>{{ $escort?->military_number }}</span>
                                @endif

                                <br>
                                {{ $escort?->phone_number }}
                            </td>
                            <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                {{ $departure?->delegate?->getTranslation('designation') ?? '-' }}
                            </td>
                            <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                {{ getLangTitleSeperator($departure?->delegate?->getTranslation('title'), $departure?->delegate?->getTranslation('name')) }}
                            </td>
                            <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                {{ $departure?->delegate?->delegation?->country?->name ?? '-' }}
                            </td>
                            <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                {{ $i + 1 }}
                            </td>
                        </tr>

                        @if ($loop->iteration % 40 == 0)
                            <!--CHUNKHTML-->
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
