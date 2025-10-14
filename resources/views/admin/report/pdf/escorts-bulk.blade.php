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
        <div style="font-family: Arial, sans-serif;  gap: 20px; align-items: center;margin-top:3%;">
            <div style="font-family: Arial, sans-serif;  gap: 20px; align-items: center;page-break-after: always;margin-top:5% !important;">
                <h3 dir="rtl" style="font-weight: bold; color: #cc0000;padding-top: 2%;">{{ __db('assigned_escorts') }}</h3>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #d9d9d9;font-size: 13px">
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('notes') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('delegation') }} - {{ __db('invitation_from') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('languages') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('mobile') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('name') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('rank') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('military_number') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('sl_no') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assignedEscorts as $i => $escort)
                            <tr  style="font-size: 12px">
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;"></td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                    {{ $escort->delegation?->country?->name }} - {{ $escort->delegation?->invitationFrom?->value ?? '-' }}
                                </td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                    @php
                                        $ids = $escort->spoken_languages ? explode(',', $escort->spoken_languages) : [];
                                        $names = \App\Models\DropdownOption::whereIn('id', $ids)->pluck('value')->toArray();
                                    @endphp 
                                    {{ implode(', ', $names) }}
                                </td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">{{ $escort->phone_number }}</td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">{{ $escort->getTranslation('name') }}</td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">{{ optional($escort->internalRanking)->value }}</td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">{{ $escort->military_number }}</td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">{{ $i+1 }}</td>
                                
                            </tr>

                            @if($loop->iteration % 50 == 0)
                                <!--CHUNKHTML-->
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="font-family: Arial, sans-serif;  gap: 20px; align-items: center;margin-top:5%;">
                <h3 dir="rtl" style="font-weight: bold; color: #cc0000; padding-top: 2%;"> {{ __db('unassigned_escorts') }}</h3>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #d9d9d9;font-size: 13px">
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('notes') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('delegation') }} - {{ __db('invitation_from') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('languages') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('mobile') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('name') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('rank') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('military_number') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('sl_no') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($unassignedEscorts as $i => $unEscort)
                            <tr style="font-size: 12px">
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;"></td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                    --
                                </td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                    @php
                                        $ids = $unEscort->spoken_languages ? explode(',', $unEscort->spoken_languages) : [];
                                        $names = \App\Models\DropdownOption::whereIn('id', $ids)->pluck('value')->toArray();
                                    @endphp 
                                    {{ implode(', ', $names) }}
                                </td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">{{ $unEscort->phone_number }}</td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">{{ $unEscort->getTranslation('name') }}</td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">{{ optional($unEscort->internalRanking)->value }}</td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">{{ $unEscort->military_number }}</td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">{{ $i+1 }}</td>
                                
                            </tr>

                            @if($loop->iteration % 50 == 0)
                                <!--CHUNKHTML-->
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
