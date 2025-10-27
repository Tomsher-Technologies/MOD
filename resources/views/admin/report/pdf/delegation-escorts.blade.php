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

<body style="margin: 0px; padding: 0px; font-size: 12px;">
    <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
        <div style=" border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px;">
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
                <tr>
                    <td style="width: 25%; text-align: left; vertical-align: middle;">
                        @if(getActiveLanguage() == 'ar')
                            <img src="{{ public_path('assets/img/md-logo-ar.svg') }}" 
                                alt="{{ env('APP_NAME') }}" 
                                style="width: 150px; height: auto;">
                        @else
                            <img src="{{ public_path('assets/img/md-logo.svg') }}" 
                            alt="{{ env('APP_NAME') }}" 
                            style="width: 150px; height: auto;">
                        @endif
                    </td>

                    <td style="width: 50%; text-align: center; vertical-align: middle;">
                        <div style="font-size: 14px; font-weight: bold;">
                            {{ __db('united_arab_emirates') }}
                        </div>
                        <div style="font-size: 14px; font-weight: bold; margin-top: 5px;">
                            {{ __db('ministry_of_defense') }}
                        </div>
                        <div style="font-size: 14px; font-weight: bold; color: #cc0000; margin-top: 5px;">
                            {{ __db('delegations_escort_report') }}
                        </div>
                    </td>

                    <td style="width: 25%; text-align: right; vertical-align: middle;">
                        <img src="{{ asset(getAdminEventPDFLogo()) }}" alt="{{ getCurrentEventName() }}"
                            style="width: 150px; height: auto;">
                    </td>
                </tr>
            </table>

            <div style="text-align: right; font-size: 0.9em; margin-top:10px;">{{ date('d-m-Y H:i A') }}</div>

        </div>

        @php
            $assignedHotels = [];
        @endphp

        <table style="width:100%; border-collapse:collapse; font-size:12px; margin-bottom:8px; table-layout:fixed;">
          
            @foreach ($delegation->escorts as $ekey => $escort)
                @php
                    $roomEscort = $escort->currentRoomAssignment ?? null;
                    // $assignedHotels[] = $roomEscort?->hotel_id ?? null;

                    $accValue = ($roomEscort?->room_number || $roomEscort?->hotel?->hotel_name)
                                ? trim(($roomEscort?->room_number ? $roomEscort->room_number . ' - ' : '') . ($roomEscort?->hotel?->hotel_name ?? ''))
                                : '-';

                    $mobileValue = $escort?->phone_number ?? '-';

                    if (getActiveLanguage() == 'en') {
                        $escortValue = '<div style=" display: flex; justify-content: flex-end;"><span>'. $escort?->military_number .'</span>&nbsp; - &nbsp;<span>'. $escort?->internalRanking?->value .' '. $escort?->name .'</span></div>';
                    } else {
                        $escortValue = '<div style=" display: flex; justify-content: flex-end;"><span>'. $escort?->internalRanking?->value .' '. $escort?->name .'</span>&nbsp; - &nbsp;<span>'. $escort?->military_number .'</span></div>';
                    }

                    $accValue = $accValue === '' ? '-' : $accValue;
                    $escortValue = $escortValue === '' ? '-' : $escortValue;
                @endphp

                <tr>
                   
                    <td colspan="3" style="padding:6px; border:0;">
                        <span class="ltr" style="display:inline-block; width:100%;">
                            <span style="float:left;">
                                {!! e($accValue) !!}
                            </span>
                            <strong>: {{ __db('accommodation') }}</strong>
                        </span>
                    </td>

                    <td colspan="1" style="padding:6px; border:0;">
                        <span class="ltr">
                            {!! e($mobileValue) !!} <strong> : {!! e(__db('mobile')) !!}</strong>
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

                            <strong >
                                : {{ __db('escort') }} 
                            </strong>
                        </span>
                    </td>
                    
                 
                </tr>
            @endforeach
        </table>


        <table style="width: 100%; font-size: 12px !important; border-collapse: collapse; margin-top: 15px; margin-bottom: 8px;">
            <tr>
                <td style="padding:6px; border:0; text-align:left;">
                    <span class="ltr" style="display:inline-block; width:100%;">
                        <span style="float:left;">
                            {{ $delegation->invitationFrom?->value ?? ' - ' }}
                        </span>
                        <strong>: {{ __db('invitation_from') }}</strong>
                    </span>
                </td>
                
                <td style="padding:6px; border:0;">
                    <span class="ltr" style="display:inline-block; width:100%;">
                        <span style="float:left;">
                            
                        </span>
                        <strong></strong>
                    </span>
                </td>
                
                <td style="padding:6px; border:0; text-align:right;">
                    <span class="ltr" style="display:inline-block; width:100%;">
                        <span style="float:left;">
                            {{ $delegation->country?->name ?? ' - ' }}
                        </span>
                        <strong>: {{ __db('country') }}</strong>
                    </span>
                </td>
            </tr>
            <tr>
                <td style="padding:6px; border:0; text-align:left;">
                    <span class="ltr" style="display:inline-block; width:100%;">
                        <span style="float:left;">
                             {{ $delegation->participationStatus?->value ?? ' - ' }}
                        </span>
                        <strong>: {{ __db('participation_status') }}</strong>
                    </span>
                </td>
                <td  style="padding:6px; border:0;">
                    
                </td>

                <td  style="padding:6px; border:0; text-align:right;">
                    <span class="ltr" style="display:inline-block; width:100%;">
                        <span style="float:left;">
                            {{ $delegation->invitationStatus?->value ?? ' - ' }}
                        </span>
                        <strong>: {{ __db('invitation_status') }}</strong>
                    </span>
                </td>
            </tr>
            
        </table>
        @php
            $teamHead = '';
        @endphp

        <h2 class="font-bold text-center mb-3  text-end" style="font-size: 16px;">{{ __db('arrival_details') }}</h2>
        
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #d9d9d9; font-size: 13px">
                    <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('time') }}</th>
                    <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('date') }}</th>
                    <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('airport') }}</th>
                    <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('flight_number') }}</th>
                    <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('flight_name') }}</th>
                    <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('room') }}</th>
                    <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('position') }}</th>
                    <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('delegations') }}</th>
                    <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('sl_no') }}</th>
                </tr>
            </thead>
            <tbody style="font-size: 12px; text-align: center;">
                @forelse ($delegation->delegates as $key => $delegate)
                    @php
                        $delegateRoom = $delegate->currentRoomAssignment ?? null;
                        
                        if($delegate->team_head == 1){
                            $assignedHotels[] = $delegateRoom?->hotel_id ?? null;
                            $departure = $delegate->delegateTransports->where('type', 'departure')->first();

                            $deptTime = ($departure?->date_time) ? date('H:i', strtotime($departure->date_time)) : '';
                            $deptDate = ($departure?->date_time) ? date('d-m-Y', strtotime($departure->date_time)) : '';
                    
                            if ($departure) {
                                $teamHead.= '<tr>                                    
                                        <td style="padding: 8px; border: 1px solid #000; text-align: center;">'.$deptTime.'</td>
                                        <td style="padding: 8px; border: 1px solid #000; text-align: center;">'. $deptDate.'</td>
                                        <td style="padding: 8px; border: 1px solid #000; text-align: center;">'.($departure?->airport?->value ?? ucwords($departure?->mode)) .'</td>
                                        <td style="padding: 8px; border: 1px solid #000; text-align: center;">'.($departure?->flight_no).'</td>
                                        <td style="padding: 8px; border: 1px solid #000; text-align: center;">'.($departure?->flight_name).'</td>
                                    </tr>';
                            }
                            
                        }
                    @endphp
                    <tr>
                        @php
                            $relation = '';
                            if($delegate->relationship){
                                $relation = $delegate->relationship?->value .' '. __db('of') .' '. $delegate->parent?->getTranslation('name');
                            }
                            $arrival = $delegate->delegateTransports->where('type', 'arrival')->first();
                        @endphp
                        <td style="padding: 8px; border: 1px solid #000; text-align: center;">{{ $arrival?->date_time ? date('H:i', strtotime($arrival?->date_time)) : '-' }}</td>
                        <td style="padding: 8px; border: 1px solid #000; text-align: center;">{{ $arrival?->date_time ? date('d-m-Y', strtotime($arrival?->date_time)) : '-' }}</td>
                        <td style="padding: 8px; border: 1px solid #000; text-align: center;">{{ $arrival?->airport?->value ?? ucwords($arrival?->mode) }}</td>
                        <td style="padding: 8px; border: 1px solid #000; text-align: center;">{{ $arrival?->flight_no ?? '-' }}</td>
                        <td style="padding: 8px; border: 1px solid #000; text-align: center;">
                            {{ $arrival?->flight_name ?? '-' }}
                        </td>
                        <td style="padding: 8px; border: 1px solid #000; text-align: center;">
                            {{ $delegateRoom ? ($delegateRoom?->room_number .' - '. $delegateRoom?->hotel?->hotel_name) : '-' }}
                        </td>
                        <td style="padding: 8px; border: 1px solid #000; text-align: center;">
                            {{ $delegate?->getTranslation('designation') ?? $relation }}
                        </td>
                        <td style="padding: 8px; border: 1px solid #000; text-align: center; @if($delegate->team_head === true) text-report-red @endif  font-bold">
                            {{  getLangTitleSeperator($delegate?->getTranslation('title'), $delegate?->getTranslation('name')) }}
                        </td>
                        <td style="padding: 8px; border: 1px solid #000; text-align: center;">{{ $key + 1 }}</td>
                    </tr>
                @empty
                    <tr class="border-t">
                        <td colspan="9" style="padding: 8px; border: 1px solid #000; text-center">
                            {{ __db('no_record_found') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <h2 class="text-md font-bold mb-3 text-end" style="margin-top: 30px;font-size: 16px;">{{  __db('departure_details_of_head_of_delegation') }}</h2>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #d9d9d9; font-size: 13px">
                    <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('time') }}</th>
                    <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('date') }}</th>
                    <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('airport') }}
                    </th>
                    <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('flight_number') }}</th>
                    <th style="padding: 8px; border: 1px solid #000; text-align: center;"> {{ __db('flight_name') }}</th>
                </tr>
            </thead>
            <tbody style="font-size: 12px;text-align: center;">
                @if($teamHead != '')
                    {!! $teamHead !!}
                @else
                    <tr class="border-t">
                        <td colspan="5" style="padding: 8px; border: 1px solid #000; text-center">
                            {{ __db('no_record_found') }}
                        </td>
                    </tr>
                @endif
                
            </tbody>
        </table>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; gap: 20px; margin-top: 20px;">

            @php
                $assignedHotels = array_filter(array_unique($assignedHotels));
                // $assignedHotels = implode(',', $assignedHotels);
                $hotelDetails = getAccommodationDetails($assignedHotels);

            @endphp
            
            <table style="width:100%; border-collapse: collapse; margin-bottom: 12px;">
                <tbody>
                    @foreach($hotelDetails as $key => $hotel)
                        @if($key == 0)
                            <tr>
                                <!-- Left side (hotel number) -->
                                <td style="padding:6px; border:0; text-align:left; direction:ltr;">
                                    <span>{{ $hotel->contact_number }}</span>
                                    <strong style="float:right;">: {{ __db('hotel_number') }}</strong>
                                </td>
            
                                <!-- Right side (hotel name) -->
                                <td style="padding:6px; border:0; text-align:right; direction:ltr;">
                                    <span>{{ $hotel->hotel_name }}</span>
                                    <strong style="float:right;">: {{ __db('hotel') }}</strong>
                                </td>
                            </tr>
                        @endif
            
                        @foreach($hotel->contacts as $k => $con)
                            <tr>
                                <!-- Left side (contact number) -->
                                <td style="padding:6px; border:0; text-align:left; direction:ltr;">
                                    <span>{{ $con->phone ?? '' }}</span>
                                    <strong style="float:right;">: {{ __db('mobile') }}</strong>
                                </td>
            
                                <!-- Right side (responsible person) -->
                                <td style="padding:6px; border:0; text-align:right; direction:ltr;">
                                    <span>{{ $con->name ?? '' }}</span>
                                    <strong style="float:right;">: {{ __db('res') }}</strong>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-1 gap-x-0 md:gap-x-8 gap-y-8">
            <section>
                <h2 class="text-md font-bold mb-3 text-end" style="font-size: 16px;" >{{ __db('drivers') }}</h2>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #d9d9d9; font-size: 13px">
                            <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('car_number') }}</th>
                            <th style="padding: 8px; border: 1px solid #000; text-align: center;"> {{  __db('vehicle') . " " . __db('type') }}</th>
                            <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('phone') }}</th>
                            <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('name') }}</th>
                            <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('sl_no') }}</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 12px;text-align: center;">
                        @forelse ($delegation->drivers as $keyDriver => $rowDriver)
                            <tr>
                                <td  style="padding: 8px; border: 1px solid #000; text-align: center;">{{ $rowDriver->car_number ?? '-' }}</td>
                                <td  style="padding: 8px; border: 1px solid #000; text-align: center;">{{ $rowDriver->car_type ?? '-' }}</td>
                                <td  style="padding: 8px; border: 1px solid #000; text-align: center;">{{ $rowDriver->phone_number ?? '-' }}</td>
                                <td  style="padding: 8px; border: 1px solid #000; text-align: center;">
                                    {{ getLangTitleSeperator($rowDriver?->getTranslation('title'),$rowDriver?->getTranslation('name')) }}
                                </td>
                                <td  style="padding: 8px; border: 1px solid #000; text-align: center;">{{ $keyDriver + 1 }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="padding: 8px; border: 1px solid #000;text-align: center;">
                                    {{ __db('no_record_found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </section>

            <section>
                <h2 class="text-md font-bold mb-3 text-end" style="font-size: 16px;">{{ __db('interviews') }}</h2>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr class="bg-gray-200"  style="font-size: 13px;">
                            <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('time') }}</th>
                            <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('date') }}
                            </th>
                            <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('interview_request_with') }}</th>
                            <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('sl_no') }}</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 12px;text-align: center;">
                        @php
                            $interviewData = $delegation->interviews ?? collect();
                            $interviewMembers = '';
                        @endphp 
                        @forelse ( $interviewData as $in => $row)
                            @php
                                if (!empty($row->other_member_id) && $row->otherMember) {
                                    $otherMemberName = $row->otherMember->name ?? '';
                                    $otherMemberId = $row->otherMember->getTranslation('name') ?? $row->other_member_id;
                                    if ($otherMemberId) {
                                        $with = 'Other Member: '.$otherMemberId;
                                    }
                                    $names = $row->interviewMembers
                                                ->map(fn($member) => '<span class="block">' . e($member->name ?? '') . '</span>')
                                                ->implode('');
                                } else {
                                    $with = 'Delegation ID : ' .$row->interviewWithDelegation->code ?? '';
                                    $names = $row->toMembers
                                            ->map(fn($member) => '<br><span class="block">' . e(getLangTitleSeperator($member?->delegate?->getTranslation('title'),$member?->delegate?->getTranslation('name'))) . '</span>')
                                            ->implode('');
                                }

                                

                                $interviewMembers =  $with . $names;
                            @endphp

                            <tr>
                                <td  style="padding: 8px; border: 1px solid #000; text-align: center;">{{ ($row->date_time) ? date('H:i', strtotime($row->date_time)) : '' }}</td>
                                <td  style="padding: 8px; border: 1px solid #000; text-align: center;">{{ ($row->date_time) ? date('d-m-Y', strtotime($row->date_time)) : '' }}</td>
                                <td  style="padding: 8px; border: 1px solid #000; text-align: center;">{!! $interviewMembers !!}</td>
                                <td  style="padding: 8px; border: 1px solid #000; text-align: center;">{{ $in + 1 }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="padding: 8px; border: 1px solid #000;text-align: center;">
                                    {{ __db('no_record_found') }}
                                </td>
                            </tr>
                        @endforelse
                        
                    </tbody>
                </table>
            </section>
        </div>
    </div>

</body>

</html>
