<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>{{ __db('wives_report') }}</title>
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
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #d9d9d9; font-size: 13px">
                        <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('guest_type') }}</th>
                        <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('invitation_from') }} </th>
                        <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('female_escort') }}</th>
                        <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('name') }}</th>
                        <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('country') }}</th>
                        <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('sl_no') }}</th>
                    </tr>
                </thead>
                <tbody style="font-size: 12px">
                    @foreach ($delegates as $i => $del)
                        
                        <tr>
                            <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                {{ $del->relationship?->value ?? __db('guest')}}
                            </td>
                            <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                {{ $del->delegation->invitationFrom?->value ?? '-' }}
                            </td>
                            <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                @php
                                    $escort = $del?->delegation?->escorts?->first();
                                @endphp
                                {{ $escort?->military_number .' - '. $escort?->internalRanking?->value .' '. $escort?->name  }}
                                <br>
                                {{ $escort?->phone_number }}
                            </td>
                            
                            <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                {{ $del->getTranslation('title') }}
                                {{ $del->getTranslation('name') ?? '-' }}
                            </td>
                            <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                {{ $del->delegation?->country?->name ?? '-' }}
                            </td>
                            <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                {{ $i + 1 }}
                            </td>
                        </tr>
                        
                        @if($loop->iteration % 50 == 0)
                            <!--CHUNKHTML-->
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
