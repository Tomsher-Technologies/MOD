<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>{{ __db('interviews_report') }}</title>
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
            @foreach($interviews as $intervieweeName => $group)
                <div style="display: flex; align-items: center; gap: 20px; justify-content: end;font-weight: bold">
                    <h4>{{ $intervieweeName }}</h4>
                    <h4>: {{ __db('interview_with') }}</h4>
                </div>

                <table style="width:100%;border-collapse:collapse;margin-bottom:20px;">
                    <thead>
                        <tr style="background:#d9d9d9; font-size: 13px">
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('mobile') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('escort') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('date') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('time') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('position') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('name') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('country') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('sl_no') }}</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 12px">
                        @php $ser = 1; @endphp
                        @foreach($group as $interview)
                            @foreach($interview->fromMembers as $member)
                                <tr style="text-align: center">
                                    @php
                                        $escort = $member->delegate?->delegation?->escorts?->first();
                                    @endphp
                                    
                                    <td style="padding:8px;border:2px solid #000;">
                                        {{ $escort?->phone_number ?? '-' }}
                                    </td>
                                    <td style="padding:8px;border:2px solid #000;">
                                        {{ $escort?->military_number .' - '. $escort?->internalRanking?->value .' '. $escort?->name  }}
                                    </td>
                                    <td style="padding:8px;border:2px solid #000;">
                                        {{ $interview->date_time ? date('d-m-Y', strtotime($interview->date_time)) : '-' }}
                                    </td>
                                    <td style="padding:8px;border:2px solid #000;">
                                        {{ $interview->date_time ? date('H:i', strtotime($interview->date_time)) : '-' }}
                                    </td>
                                    <td style="padding:8px;border:2px solid #000;">
                                        {{ $member->delegate?->designation_en ?? '' }}
                                    </td>
                                    <td style="padding:8px;border:2px solid #000;">
                                        <strong>{{ ($member->delegate) ? $member->delegate?->getTranslation('title').' '.$member->delegate?->getTranslation('name') : $member->otherMember?->getTranslation('name') }}</strong>
                                    </td>
                                    <td style="padding:8px;border:2px solid #000;">
                                        {{ $member->delegate?->delegation?->country?->name ?? '' }}
                                    </td>
                                    <td style="padding:8px;border:2px solid #000;">
                                        {{ $ser++ }}
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            @endforeach
        </div>
    </div>
</body>
</html>
