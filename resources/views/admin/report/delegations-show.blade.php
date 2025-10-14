@extends('layouts.admin_account', ['title' => __db('delegation_details')])

@section('content')
    <div>
        <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
            <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('delegations_escort_report') }}</h2>
            

            <div class="flex gap-3 ms-auto">
                @directCanany(['export_delegations_escort'])
                    <a href="{{ route('delegations.exportPdf', ['id' => base64_encode($delegation->id)]) }}" class="!text-[#5D471D]  !bg-[#E6D7A2] hover:bg-yellow-400  rounded-lg py-2 px-3">
                        {{ __db('export_pdf') }}
                    </a>
                @enddirectCanany
                <x-back-btn class="" back-url="{{ route('reports-delegations') }}" />
            </div>
            
        </div>

        <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6" dir="ltr">
            {{-- <div style=" border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">

                    <div style="width: auto;">
                        <img src="{{ asset('assets/img/md-logo.svg') }}" alt="{{ env('APP_NAME') }}"
                            style="height: auto; width: 150px;">

                    </div>
                    <div style="text-align: center; width: 50%;">
                        <div style="font-size: 20px; font-weight: bold;">{{ __db('united_arab_emirates') }}</div>
                        <div style="font-size: 20px; font-weight: bold; margin-top: 5px;">{{ __db('ministry_of_defense') }}</div>
                        <div style="font-size: 20px; font-weight: bold; color: #cc0000; margin-top: 5px;">{{ __db('delegations_escort_report') }}</div>
                    </div>
                    <div style=" width: auto; text-align: right;">
                        <img src="{{ getAdminEventLogo() }}" alt="{{ getCurrentEventName() }}"
                            style=" width: 150px; height: auto;">
                    </div>
                </div>
                <div style="text-align: right; font-size: 0.9em; margin-top:10px;">{{ date('d-m-Y H:i A') }}</div>

            </div> --}}

            @php
                $assignedHotels = [];
                $escortAccHtml = $escortContactHtml = $escortDetailsHtml = '';
            @endphp

            @foreach ($delegation->escorts as $escort)
                @php
                    $roomEscort = $escort->currentRoomAssignment ?? null;
                    $assignedHotels[] = $roomEscort?->hotel_id ?? null;

                    $escortAccHtml .= '<div style="margin-bottom: 5px;">' . $roomEscort?->room_number . '-' . $roomEscort?->hotel?->hotel_name. '<strong>:'.__db('accommodation').'</strong></div>';
            
                    $escortContactHtml .= '<div style="margin-bottom: 5px; display: flex; justify-content: flex-end;"><span>' . $escort?->phone_number . '</span><strong style="width: 10%;">:'.__db('mobile').'</strong></div>';
                    
                    $escortDetailsHtml .= '<div style="margin-bottom: 5px; display: flex; justify-content: flex-end;"><span>' . $escort?->internalRanking?->value.' '.$escort?->name . '-(' . $escort?->military_number . ')</span><strong style="width: 22%;">:'.__db('escort').'</strong></div>';
                @endphp

            @endforeach

            <div style="display: flex;font-size: 12px; justify-content: space-between; align-items: start; margin-bottom: 8px; gap: 20px;">
                <div style="width: 40%;">
                    {!! $escortAccHtml !!}
                </div>

                <div style="width:15%; text-align: right;">
                    {!! $escortContactHtml !!}
                </div>

                <div style="width: 55%; text-align: right;">
                    {!! $escortDetailsHtml !!}
                </div>
            </div>

            <div style="display: flex;font-size: 12px; justify-content: space-between; align-items: start; margin-bottom: 8px; gap: 20px;">
                <div style="width: 40%;">
                    <div style="margin-bottom: 5px;">{{ $delegation->invitationFrom?->value ?? '-' }} <strong>:{{ __db('invitation_from') }}</strong></div>
                    <div style="margin-bottom: 5px;">{{ $delegation->participationStatus?->value ?? '-' }} <strong>:{{ __db('participation_status') }}</strong></div>
                </div>

                <div style="width:15%; text-align: right;">

                </div>

                <div style="width: 55%; text-align: right;">
                    <div style="margin-bottom: 5px;">{{ $delegation->country?->name ?? '-' }} <strong>:{{ __db('country') }}</strong></div>
                    <div style="margin-bottom: 5px; display: flex; justify-content: flex-end;">
                        <span>{{ $delegation->invitationStatus?->value ?? '-' }}</span>
                        <strong style="width: 22%;">:{{ __db('invitation_status') }}</strong>
                    </div>

                </div>
            </div>
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
                            $assignedHotels[] = $delegateRoom?->hotel_id ?? null;
                            if($delegate->team_head == 1){
                                $departure = $delegate->delegateTransports->where('type', 'departure')->first();
                        
                                $teamHead.= '<tr>                                    
                                    <td style="padding: 8px; border: 1px solid #000;">'.date('H:i', strtotime($departure?->date_time)).'</td>
                                    <td style="padding: 8px; border: 1px solid #000;">'. date('d-m-Y', strtotime($departure?->date_time)) .'</td>
                                    <td style="padding: 8px; border: 1px solid #000;">'.($departure?->airport?->value ?? ucwords($departure?->mode)) .'</td>
                                    <td style="padding: 8px; border: 1px solid #000;">'.($departure?->flight_no).'</td>
                                    <td style="padding: 8px; border: 1px solid #000;">'.($departure?->flight_name).'</td>
                                </tr>';
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
                            <td style="padding: 8px; border: 1px solid #000 !important;">{{ $arrival?->date_time ? date('H:i', strtotime($arrival?->date_time)) : '-' }}</td>
                            <td style="padding: 8px; border: 1px solid #000;">{{ $arrival?->date_time ? date('d-m-Y', strtotime($arrival?->date_time)) : '-' }}</td>
                            <td style="padding: 8px; border: 1px solid #000;">{{ $arrival?->airport?->value ?? ucwords($arrival?->mode) }}</td>
                            <td style="padding: 8px; border: 1px solid #000;">{{ $arrival?->flight_no ?? '-' }}</td>
                            <td style="padding: 8px; border: 1px solid #000;">
                                {{ $arrival?->flight_name ?? '-' }}
                            </td>
                            <td style="padding: 8px; border: 1px solid #000;">
                                {{ $delegateRoom ? $delegateRoom?->room_number .' - '. $delegateRoom?->hotel?->hotel_name : __db('not_required')}}
                            </td>
                            <td style="padding: 8px; border: 1px solid #000;">
                                {{ $delegate?->getTranslation('designation') ?? $relation }}
                            </td>
                            <td style="padding: 8px; border: 1px solid #000; @if($delegate->team_head === true) text-report-red @endif  font-bold">
                                {{ $delegate->getTranslation('title').' '.$delegate->getTranslation('name') }}
                            </td>
                            <td style="padding: 8px; border: 1px solid #000;">{{ $key + 1 }}</td>
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
                    {!! $teamHead !!}
                </tbody>
            </table>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; gap: 20px; margin-top: 20px;">

                @php
                    $assignedHotels = array_filter(array_unique($assignedHotels));
                    // $assignedHotels = implode(',', $assignedHotels);
                    $hotelDetails = getAccommodationDetails($assignedHotels);

                @endphp
                
                 <table style="width:100%; border-collapse: collapse; margin-bottom: 12px;">
                    <tbody style="font-size: 12px;">
                        @foreach($hotelDetails as $key => $hotel)
                            @php
                                $acc_con = $acc_name = '';
                                foreach($hotel->contacts as $k => $con){
                                    $acc_con .= '<div style="margin-bottom: 5px; display: flex;">
                                                    <span>'.$con->phone.'</span>
                                                    <strong style="width: 30%;"> : '. __db('mobile').'</strong>
                                                </div>';
                                    $acc_name .= '<div style="margin-bottom: 5px; display: flex; justify-content: flex-end;">
                                                    <span>'.$con->name.'</span>
                                                    <strong style="width: 22%;"> : '. __db('res'.$k).'</strong>
                                                </div>';
                                }
                            @endphp

                            <tr>
                                <!-- Left side (hotel numbers + contacts) -->
                                <td style="width:30%; vertical-align: top; padding: 6px;">
                                    <div style="margin-bottom: 5px; display: flex;">
                                        <span>{{ $hotel->contact_number }}</span>
                                        <strong style="width: 30%;"> : {{ __db('hotel_number') }}</strong>
                                    </div>
                                    {!! $acc_con !!}
                                </td>

                                <!-- Right side (hotel name + responsible persons) -->
                                <td style="width:25%; text-align: right; vertical-align: top; padding: 6px;">
                                    <div style="margin-bottom: 5px; display: flex; justify-content: flex-end;">
                                        <span>{{ $hotel->hotel_name }}</span>
                                        <strong style="width: 22%;"> : {{ __db('hotel') }}</strong>
                                    </div>
                                    {!! $acc_name !!}
                                </td>
                            </tr>
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
                                    <td  style="padding: 8px; border: 1px solid #000;">{{ $rowDriver->car_number ?? '-' }}</td>
                                    <td  style="padding: 8px; border: 1px solid #000;">{{ $rowDriver->car_type ?? '-' }}</td>
                                    <td  style="padding: 8px; border: 1px solid #000;">{{ $rowDriver->phone_number ?? '-' }}</td>
                                    <td  style="padding: 8px; border: 1px solid #000;">
                                        {{ $rowDriver->name ?? '-' }}
                                    </td>
                                    <td  style="padding: 8px; border: 1px solid #000;">{{ $keyDriver + 1 }}</td>
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
                                <th style="padding: 8px; border: 1px solid #000; text-align: center;">{{ __db('notes') }}
                                </th>
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
                                    } else {
                                        $with = 'Delegation ID : ' .$row->interviewWithDelegation->code ?? '';
                                    }

                                    $names = $row->interviewMembers
                                        ->map(fn($member) => '<span class="block">' . e($member->name ?? '') . '</span>')
                                        ->implode('');

                                    $interviewMembers =  $with . $names;
                                @endphp

                                <tr>
                                    <td  style="padding: 8px; border: 1px solid #000;">-</td>
                                    <td  style="padding: 8px; border: 1px solid #000;">{{ date('H:i', strtotime($row->date_time)) }}</td>
                                    <td  style="padding: 8px; border: 1px solid #000;">{{ date('d-m-Y', strtotime($row->date_time)) }}</td>
                                    <td  style="padding: 8px; border: 1px solid #000;">{!! $interviewMembers !!}</td>
                                    <td  style="padding: 8px; border: 1px solid #000;">{{ $in + 1 }}</td>
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
            </div>
        </div>
    </div>
@endsection