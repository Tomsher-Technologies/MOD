<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>{{ __db('delegations_members_report') }}</title>
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

<body style="margin: 0; padding: 0; font-size: 12px;">
    <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
        <div style="font-family: Arial, sans-serif;  gap: 20px; align-items: center;margin-top:3%;">
            @php
                $columns = [
                    ['key' => 'participation_status', 'label' => __db('participation_status')],
                    ['key' => 'invitation_status', 'label' => __db('invitation_status')],
                    ['key' => 'invitation_from', 'label' => __db('invitation_from')],
                    ['key' => 'escorts', 'label' => __db('escorts')],
                    ['key' => 'positions', 'label' => __db('position')],
                    ['key' => 'delegations', 'label' => __db('delegations')],
                    ['key' => 'country', 'label' => __db('country')],
                    ['key' => 'sl_no', 'label' => __db('sl_no')],
                ];

                if (getActiveLanguage() == 'en') {
                    $columns = array_reverse($columns);
                }
            @endphp
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #d9d9d9; font-size: 13px">
                        @foreach ($columns as $col)
                            <th style="padding:8px; border:2px solid #000; text-align:center;">
                                {{ $col['label'] }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody style="font-size: 12px">
                  
                    @foreach ($delegations as $i => $del)
                        @php
                            $delegates = $positions = '<ul style="list-style:none; margin:0; padding:0;">';
                            foreach ($del->delegates as $member) {
                                $position = $member->getTranslation('designation') ?: '<strong>-</strong>';
                                $delegates .= '<li style="display:block; list-style:none; margin:0; padding:0;"><span style="'.($member?->team_head ? 'color: red; font-weight: 600;' : '').'">'.getLangTitleSeperator($member->getTranslation('title'), $member?->getTranslation('name')).'</span></li>';
                                $positions .= '<li style="display:block; list-style:none; margin:0; padding:0;"><span style="'.($member?->team_head ? 'color: red; font-weight: 600;' : '').'">'.($position) .'</span></li>';
                            }
                            $escortsData = '';
                            foreach ($del->escorts as $escort) {
                                if (getActiveLanguage() == 'en'){
                                    $escortsData .= '<span>'.$escort?->military_number .'</span> - <span>'.$escort?->internalRanking?->value .' '. $escort?->name.'</span><br>'.$escort?->phone_number.'<br>';
                                }else{
                                    $escortsData .= '<span>'.$escort?->internalRanking?->value .' '. $escort?->name .'</span> - <span>'.$escort?->military_number .'</span><br>'.$escort?->phone_number.'<br>';
                                }
                            }
                            $positions .= '</ul>';
                            $delegates .= '</ul>';
                        @endphp
                        <tr>
                            @foreach ($columns as $col)
                                <td style="padding:8px; border:2px solid #000; text-align:center;">
                                    @switch($col['key'])
                                        @case('participation_status')
                                            {{ $del->participationStatus?->value ?? '-' }}
                                        @break

                                        @case('invitation_status')
                                            {{ $del->invitationStatus?->value ?? '-' }}
                                        @break

                                        @case('invitation_from')
                                            {{ $del->invitationFrom?->value ?? '-' }}
                                        @break

                                        @case('escorts')
                                            {!! $escortsData !!}
                                        @break

                                        @case('positions')
                                            {!! $positions !!}
                                        @break

                                        @case('delegations')
                                            {!! $delegates !!}
                                        @break

                                        @case('country')
                                            {{ $del->country?->name ?? '-' }}
                                        @break

                                        @case('sl_no')
                                            {{ $i + 1 }}
                                        @break
                                    @endswitch
                                </td>
                            @endforeach
                        </tr>

                        @if ($loop->iteration % 50 == 0)
                            <!--CHUNKHTML-->
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
