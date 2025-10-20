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

            <table style="width:100%; border-collapse:collapse; text-align:center; font-size:12px;">
                <tbody>
                    @forelse($filteredInvitationFromIds as $invitationFromId)
                        @php
                            $invitationFromName = \App\Models\DropdownOption::find($invitationFromId)?->value ?? '-';
                            $statusCountsForThis = $delegateCounts[$invitationFromId] ?? [];
                        @endphp
            
                        {{-- Invitation From Name --}}
                        <tr>
                            <td colspan="{{ count($allStatuses) }}" style="padding:8px 0; text-align:right;">
                                <strong style="font-size:13px; color:#222;">{{ $invitationFromName }}</strong>
                            </td>
                        </tr>
            
                        {{-- Status Counts Row --}}
                        <tr style="line-height:28px;">
                            @foreach($allStatuses as $statusVal)
                                @php
                                    $statusId = $statusVal['id'];
            
                                    if($statusVal['code'] == '1') { // Waiting
                                        $color = '#b82020';
                                    } elseif($statusVal['code'] == '2') { // Accepted
                                        $color = '#039f03';
                                    } elseif ($statusVal['code'] == '10') { // Accepted with secretary
                                        $color = 'gray';
                                    } elseif ($statusVal['code'] == '9') { // Accepted with acting person
                                        $color = '#de6c05';
                                    } elseif ($statusVal['code'] == '3') { // Rejected
                                        $color = '#0e54e5';
                                    } else {
                                        $color = '#E6D7A2'; // default
                                    }
                                @endphp
            
                                <td style="padding:6px 4px;">
                                    <table style="border-collapse:collapse;">
                                        <tr>
                                            <td style="padding-right:10px; white-space:nowrap;text-align:right;">
                                                @if(getActiveLanguage() == 'ar')
                                                    {{ $statusVal['value_ar'] ?? $statusVal['value'] }}
                                                @else
                                                    {{ $statusVal['value'] ?? $statusVal['value_ar'] }}
                                                @endif
                                            </td>
                                            <td style="width:40px; height:20px; background-color:{{ $color }}; color:#fff; text-align:center; font-weight:bold; font-size:12px; border-radius:4px;">
                                                {{ $statusCountsForThis[$statusId] ?? 0 }}
                                            </td>
                                        </tr>
                                    </table>
                                </td>

                            @endforeach
                        </tr>
            
                        {{-- Spacing Row --}}
                        <tr><td colspan="{{ count($allStatuses) }}" style="height:8px;"></td></tr>
            
                    @empty
                        {{-- No Invitation From Filter --}}
                        <tr style="line-height:28px;">
                            @foreach($allStatuses as $statusVal)
                                @php
                                    $statusId = $statusVal['id'];
            
                                    if($statusVal['code'] == '1') { $color = '#b82020';
                                    } elseif($statusVal['code'] == '2') { $color = '#039f03';
                                    } elseif ($statusVal['code'] == '10') { $color = 'gray';
                                    } elseif ($statusVal['code'] == '9') { $color = '#de6c05';
                                    } elseif ($statusVal['code'] == '3') { $color = '#0e54e5';
                                    } else { $color = '#E6D7A2'; }
                                @endphp
            
                              
                                <td style="padding:6px 4px;">
                                    <table style="border-collapse:collapse;">
                                        <tr>
                                            <td style="padding-right:10px; white-space:nowrap;text-align:right;">
                                                @if(getActiveLanguage() == 'ar')
                                                    {{ $statusVal['value_ar'] ?? $statusVal['value'] }}
                                                @else
                                                    {{ $statusVal['value'] ?? $statusVal['value_ar'] }}
                                                @endif
                                            </td>
                                            <td style="width:40px; height:20px; background-color:{{ $color }}; color:#fff; text-align:center; font-weight:bold; font-size:12px; border-radius:4px;">
                                               {{ $delegateCounts[$statusId] ?? 0 }}
                                            </td>
                                        </tr>
                                    </table>
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
                        <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('note_2') }}</th>
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
                                {{ $invt->delegation?->note2 ?? '-' }}
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
