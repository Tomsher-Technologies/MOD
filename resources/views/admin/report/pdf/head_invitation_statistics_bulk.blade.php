<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>{{ __db('delegation_head_invitations_report') }}</title>
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
            @php
                $filteredInvitationFromIds = request('invitation_from', []);
                $allStatuses = \App\Models\DropdownOption::whereHas('dropdown', function ($q) {
                                    $q->where('code', 'invitation_status');
                                })
                                ->where('status', 1)
                                ->orderBy('sort_order','desc')
                                ->get()->toArray(); // id => value
            @endphp

            <table style="width:100%; border-collapse:collapse; text-align:center;">
                <tbody  style="font-size: 12px">
                    @forelse($filteredInvitationFromIds as $invitationFromId)
                        @php
                            $invitationFromName = \App\Models\DropdownOption::find($invitationFromId)?->value ?? '-';
                            $statusCountsForThis = $delegateCounts[$invitationFromId] ?? [];
                        @endphp
                        <tr style="text-align: right; margin-top: 10px;">
                            <td colspan="{{ count($allStatuses) }}">
                                <strong style="font-size: 13px;">{{ $invitationFromName }}</strong>
                            </td>
                        </tr>
                        <tr style="text-align: right;line-height: 25px;">
                            @foreach($allStatuses as $statusVal)
                                @php
                                    $statusId = $statusVal['id'];

                                    if($statusVal['code'] == '1') { // Waiting
                                        $color = '#b82020';
                                    } elseif($statusVal['code'] == '2') { // Accepted
                                        $color = '#039f03';
                                    }elseif ($statusVal['code'] == '10') { // Accepted with secretary
                                        $color = 'gray';
                                    }elseif ($statusVal['code'] == '9') { // Accepted with acting person
                                        $color = '#de6c05';
                                    }elseif ($statusVal['code'] == '3') { // Rejected
                                        $color = '#0e54e5';
                                    }else{
                                        $color = '#E6D7A2'; // default
                                    }
                                @endphp
                                <td style="padding:4px;width:{{ count($allStatuses)/100 }}%;">
                                    <div style="display:flex; align-items:center; gap:5px; justify-content:flex-start; flex-direction: row-reverse;text-align: right;">
                                        <span style="display:inline-block; width:40px; height:23px; background:{{ $color }}; border-radius:3px; color:#fff; text-align: center !important;font-size: 13px;font-weight: bold;">
                                            {{ $statusCountsForThis[$statusId] ?? 0 }}
                                        </span>

                                        <span>
                                            @if(getActiveLanguage() == 'ar')
                                                {{ $statusVal['value_ar'] ?? $statusVal['value'] }}
                                            @else
                                                {{ $statusVal['value'] ?? $statusVal['value_ar'] }}
                                            @endif
                                        </span>
                                    </div>
                                </td>

                            @endforeach
                        </tr>
                        <tr><td colspan="{{ count($allStatuses) }}"></td></tr>
                    @empty
                        <tr style="text-align: right;line-height: 25px;">
                            @foreach($allStatuses as $statusVal)
                                @php
                                    $statusId = $statusVal['id'];

                                    if($statusVal['code'] == '1') { // Waiting
                                        $color = '#b82020';
                                    } elseif($statusVal['code'] == '2') { // Accepted
                                        $color = '#039f03';
                                    }elseif ($statusVal['code'] == '10') { // Accepted with secretary
                                        $color = 'gray';
                                    }elseif ($statusVal['code'] == '9') { // Accepted with acting person
                                        $color = '#de6c05';
                                    }elseif ($statusVal['code'] == '3') { // Rejected
                                        $color = '#0e54e5';
                                    }else{
                                        $color = '#E6D7A2'; // default
                                    }
                                @endphp
                                <td style="padding:4px;width:{{ count($allStatuses)/100 }}%;">
                                    <div style="display:flex; align-items:center; gap:5px; justify-content:flex-start; flex-direction: row-reverse;text-align: right;">
                                        <span style="display:inline-block; width:40px; height:23px; background:{{ $color }}; border-radius:3px; color:#fff; text-align: center !important;font-size: 13px;font-weight: bold;">
                                            {{ $delegateCounts[$statusId] ?? 0 }}
                                        </span>
                                        <span>
                                            @if(getActiveLanguage() == 'ar')
                                                {{ $statusVal['value_ar'] ?? $statusVal['value'] }}
                                            @else
                                                {{ $statusVal['value'] ?? $statusVal['value_ar'] }}
                                            @endif
                                        </span>
                                    </div>
                                </td>

                            @endforeach
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #d9d9d9; font-size: 13px">
                        <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('invitation_status') }}</th>
                        <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('invitation_from') }}</th>
                        <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('escort') }}</th>
                        <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('position') }}</th>
                        <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('delegation_head') }}</th>
                        <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('country') }}</th>
                        <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('sl_no') }}</th>
                    </tr>
                </thead>
                <tbody style="font-size: 12px">
                    @foreach ($invitations as $i => $invt)
                    
                        <tr>
                            <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                {{ $invt->delegation?->invitationStatus?->value ?? '-' }}
                            </td>
                            <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                {{ $invt->delegation?->invitationFrom?->value ?? '-' }}
                            </td>
                            <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                @php
                                    $escort = $invt->delegation?->escorts?->first();
                                @endphp
                                @if (getActiveLanguage() == 'en')
                                    <span>{{ $escort?->military_number }}</span>&nbsp; - &nbsp;<span>{{ $escort?->internalRanking?->value .' '. $escort?->name }}</span>
                                @else
                                    <span>{{ $escort?->internalRanking?->value .' '. $escort?->name }}</span>&nbsp; - &nbsp;<span>{{ $escort?->military_number }}</span>
                                @endif
                                <br>
                                {{ $escort?->phone_number }}
                            </td>
                            <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                {{ $invt->getTranslation('designation') ?? '-' }}
                            </td>
                            <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                {{  getLangTitleSeperator($invt->getTranslation('title'), $invt->getTranslation('name')) }}
                            </td>
                            <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                {{ $invt->delegation?->country?->name ?? '-' }}
                            </td>
                            <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                {{ $i + 1 }}
                            </td>
                        </tr>
                        
                        @if($loop->iteration % 30 == 0)
                            <!--CHUNKHTML-->
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
