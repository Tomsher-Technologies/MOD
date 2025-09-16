@extends('layouts.admin_account', ['title' => __db('delegation_details')])

@section('content')
  <div>
      <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
          <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('delegations_escort_report') }}</h2>
          

          <div class="flex gap-3 ms-auto">
             <a href="{{ route('delegations.exportPdf', ['id' => base64_encode($delegation->id)]) }}" class="!text-[#5D471D]  !bg-[#E6D7A2] hover:bg-yellow-400  rounded-lg py-2 px-3">
              {{ __db('export_pdf') }}
            </a>
            <x-back-btn class="" back-url="{{ route('reports-delegations') }}" />
          </div>
         
      </div>

      <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-6 h-full">
          <div class="xl:col-span-12 h-full">
              <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                  <div class=" mb-4 items-center justify-between gap-3">
                      <header class="flex justify-between items-center mb-4">
                          <img src="{{ asset('assets/img/md-logo.svg') }}" alt="{{ env('APP_NAME') }}" class="h-16">
                          <div class="flex-1 text-center">
                              <h1 class="text-lg font-bold">{{ __db('united_arab_emirates') }}</h1>
                              <h2 class="text-lg">{{ __db('ministry_of_defense') }}</h2>
                              <h3 class="text-lg font-bold text-header-red mt-2">{{ __db('delegations_escort_report') }}</h3>
                          </div>
                          <img src="{{ getAdminEventLogo() }}" alt="{{ getCurrentEventName() }}" class="h-12">
                      </header>
                      <hr class="border-t-2 border-black mb-6">

                      @php
                          $assignedHotels = [];
                      @endphp

                      @foreach ($delegation->escorts as $escort)
                          <div class="flex flex-col md:flex-row justify-between mb-2 text-sm ">
                              <div class="space-y-1 mt-4 md:mt-0 ">
                                
                                      <div class="flex">
                                          <div><b>{{ __db('escort') }} :</b> <span>{{ $escort->name }}-{{ $escort->code }}</span></div> &nbsp; 
                                          <div class="mr-4"><b>{{ __db('mobile') }} :</b> <span>{{ $escort->phone_number }}</span></div>
                                      </div>
                                                       
                              </div>
                              <div class="space-y-1">
                                  @php
                                      $roomEscort = $escort->currentRoomAssignment ?? null;
                                      $assignedHotels[] = $roomEscort?->hotel_id ?? null;
                                  @endphp
                                  <div><b>{{ __db('accommodation') }} :</b> <span>{{ $roomEscort?->room_number }}-{{ $roomEscort?->hotel?->hotel_name ?? '' }}</span></div>
                              </div>
                          </div>
                        @endforeach

                      <div class="flex flex-col md:flex-row justify-between mt-8 mb-8 text-sm ">
                        <div class="space-y-1 mt-4 md:mt-0 ">
                              <div><b>{{ __db('country') }} :</b> <span>{{ $delegation->country?->name ?? '-' }}</span></div>
                              <div><b>{{ __db('invitation_status') }} :</b> <span>{{ $delegation->invitationStatus?->value ?? '-' }}</span></div>
                          </div>

                          <div class="space-y-1 md:text-right rtl:md:text-left">
                              <div><b>{{ __db('invitation_from') }} :</b> <span>{{ $delegation->invitationFrom?->value ?? '-' }}</span></div>
                              <div><b>{{ __db('participation_status') }} :</b> <span>{{ $delegation->participationStatus?->value ?? '-' }}</span></div>
                          </div>
                          
                      </div>
                      @php
                          $teamHead = '';
                      @endphp
                      <section class="mb-8">
                          <h2 class="text-md font-bold text-center mb-3">{{ __db('arrival_details') }}
                          </h2>
                          <div class="overflow-x-auto">
                              <table class="w-full border-collapse text-xs">
                                  <thead>
                                      <tr class="bg-gray-200">
                                          <th class="border-2 border-black p-2 text-left rtl:text-right">{{ __db('sl_no') }}</th>
                                          <th class="border-2 border-black p-2 text-left rtl:text-right">
                                            {{ __db('delegations') }}</th>
                                          <th class="border-2 border-black p-2 text-left rtl:text-right">
                                            {{ __db('position') }}</th>
                                          <th class="border-2 border-black p-2 text-left rtl:text-right">{{ __db('room') }}</th>
                                          <th class="border-2 border-black p-2 text-left rtl:text-right">
                                            {{ __db('flight_name') }}</th>
                                          <th class="border-2 border-black p-2 text-left rtl:text-right">
                                            {{ __db('flight_number') }}</th>
                                          <th class="border-2 border-black p-2 text-left rtl:text-right">{{ __db('airport') }}
                                          </th>
                                          <th class="border-2 border-black p-2 text-left rtl:text-right">{{ __db('date') }}</th>
                                          <th class="border-2 border-black p-2 text-left rtl:text-right">{{ __db('time') }}</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      @forelse ($delegation->delegates as $key => $delegate)
                                          @php
                                              $delegateRoom = $delegate->currentRoomAssignment ?? null;
                                              $assignedHotels[] = $delegateRoom?->hotel_id ?? null;
                                              if($delegate->team_head == 1){
                                                  $departure = $delegate->delegateTransports->where('type', 'departure')->first();
                                          
                                                  $teamHead.= '<tr>
                                                      <td class="border-2 border-black p-2">'.($departure?->flight_name).'</td>
                                                      <td class="border-2 border-black p-2">'.($departure?->flight_no).'</td>
                                                      <td class="border-2 border-black p-2">'.($departure?->airport?->value) .'</td>
                                                      <td class="border-2 border-black p-2">'. date('d-m-Y', strtotime($departure?->date_time)) .'</td>
                                                      <td class="border-2 border-black p-2">'.date('H:i', strtotime($departure?->date_time)).'</td>
                                                  </tr>';
                                              }
                                          @endphp
                                          <tr>
                                              <td class="border-2 border-black p-2">{{ $key + 1 }}</td>
                                              <td class="border-2 border-black p-2 @if($delegate->team_head === true) text-report-red @endif  font-bold">
                                                {{ $delegate->getTranslation('title').' '.$delegate->getTranslation('name') }}
                                              </td>
                                              <td class="border-2 border-black p-2">
                                                @php
                                                    $relation = '';
                                                    if($delegate->relationship){
                                                        $relation = $delegate->relationship?->value .' '. __db('of') .' '. $delegate->parent?->getTranslation('name');
                                                    }
                                                @endphp
                                                  {{ $delegate->internalRanking?->value ?? $relation }}
                                              </td>
                                              <td class="border-2 border-black p-2">
                                                  {{ $delegateRoom ? $delegateRoom?->room_number .' - '. $delegateRoom?->hotel?->hotel_name : 'Not Required'}}
                                              </td>
                                              <td class="border-2 border-black p-2">
                                                  @php
                                                      $arrival = $delegate->delegateTransports->where('type', 'arrival')->first();
                                                  @endphp
                                                  {{ $arrival?->flight_name ?? '-' }}
                                              </td>
                                              <td class="border-2 border-black p-2">{{ $arrival?->flight_no ?? '-' }}</td>
                                              <td class="border-2 border-black p-2">{{ $arrival?->airport?->value ?? '-' }}</td>
                                              <td class="border-2 border-black p-2">{{ $arrival?->date_time ? date('d-m-Y', strtotime($arrival?->date_time)) : '-' }}</td>
                                              <td class="border-2 border-black p-2">{{ $arrival?->date_time ? date('H:i', strtotime($arrival?->date_time)) : '-' }}</td>
                                             
                                          </tr>
                                      @empty
                                          <tr class="border-t">
                                              <td colspan="9" class="border-2 border-black p-2 text-center">
                                                  {{ __db('no_record_found') }}
                                              </td>
                                          </tr>
                                      @endforelse
                                  </tbody>
                              </table>
                          </div>
                      </section>

                      <section class="mb-8">
                          <h2 class="text-md font-bold text-center mb-3">{{  __db('departure_details_of_head_of_delegation') }}</h2>
                          <div class="overflow-x-auto">
                              <table class="w-full border-collapse text-xs">
                                  <thead>
                                      <tr class="bg-gray-200">
                                          <th class="border-2 border-black p-2 text-left rtl:text-right">
                                            {{ __db('flight_name') }}</th>
                                          <th class="border-2 border-black p-2 text-left rtl:text-right">
                                            {{ __db('flight_number') }}</th>
                                          <th class="border-2 border-black p-2 text-left rtl:text-right">{{ __db('airport') }}
                                          </th>
                                          <th class="border-2 border-black p-2 text-left rtl:text-right">{{ __db('date') }}</th>
                                          <th class="border-2 border-black p-2 text-left rtl:text-right">{{ __db('time') }}</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      {!! $teamHead !!}
                                  </tbody>
                              </table>
                          </div>
                      </section>

                      @php
                          $assignedHotels = array_filter(array_unique($assignedHotels));
                          // $assignedHotels = implode(',', $assignedHotels);
                          $hotelDetails = getAccommodationDetails($assignedHotels);

                      @endphp
                      
                      @foreach($hotelDetails as $key => $hotel)
                          @php
                              $acc_con = '';
                          @endphp
                          <div class="flex flex-col md:flex-row justify-between mb-8 text-sm ">
                              <div class="space-y-1">
                                  <div><b>{{ __db('hotel') }}:</b> <span>{{ $hotel->hotel_name }}</span></div>
                                  @foreach($hotel->contacts as $k => $con)
                                      <div><b>{{ __db('res'.$k) }}:</b> <span>{{ $con->name }}</span></div>
                                      @php
                                          $acc_con .= '<div><b>'. __db('mobile').':</b> <span>'.$con->phone.'</span></div>';
                                      @endphp
                                  @endforeach
                              </div>

                              <div class="space-y-1 mt-4 md:mt-0 md:text-right rtl:md:text-left">
                                  <div><b>{{ __db('hotel_number') }}:</b> <span>{{ $hotel->contact_number }}</span></div>
                                  
                                  {!! $acc_con !!}
                              </div>
                          </div>
                      @endforeach
                      
                          


                      <hr class="border-t-2 border-black my-6">

                      <div class="grid grid-cols-1 md:grid-cols-1 gap-x-0 md:gap-x-8 gap-y-8">
                          <section>
                              <h2 class="text-md font-bold mb-3">{{ __db('drivers') }}</h2>
                              <div class="overflow-x-auto">
                                  <table class="w-full border-collapse text-xs">
                                      <thead>
                                          <tr class="bg-gray-200">
                                              <th class="border-2 border-black p-2 text-left rtl:text-right">{{ __db('sl_no') }}</th>
                                              <th class="border-2 border-black p-2 text-left rtl:text-right">{{ __db('name') }}</th>
                                              <th class="border-2 border-black p-2 text-left rtl:text-right">{{ __db('phone') }}</th>
                                              <th class="border-2 border-black p-2 text-left rtl:text-right">
                                                {{ __db('car_type') }}</th>
                                              <th class="border-2 border-black p-2 text-left rtl:text-right">{{ __db('car_number') }}</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          @forelse ($delegation->drivers as $keyDriver => $rowDriver)
                                              <tr>
                                                  <td class="border-2 border-black p-2">{{ $keyDriver + 1 }}</td>
                                                  <td class="border-2 border-black p-2">
                                                      {{ $rowDriver->name ?? '-' }}
                                                  </td>
                                                  <td class="border-2 border-black p-2">{{ $rowDriver->phone_number ?? '-' }}</td>
                                                  <td class="border-2 border-black p-2">{{ $rowDriver->car_type ?? '-' }}</td>
                                                  <td class="border-2 border-black p-2">{{ $rowDriver->car_number ?? '-' }}</td>
                                              </tr>
                                          @empty
                                              <tr class="border-t">
                                                  <td colspan="5" class="border-2 border-black p-2 text-center">
                                                      {{ __db('no_record_found') }}
                                                  </td>
                                              </tr>
                                          @endforelse
                                      </tbody>
                                  </table>
                              </div>
                          </section>

                          <section>
                              <h2 class="text-md font-bold mb-3">{{ __db('interviews') }}</h2>
                              <div class="overflow-x-auto">
                                  <table class="w-full border-collapse text-xs">
                                      <thead>
                                          <tr class="bg-gray-200">
                                              <th class="border-2 border-black p-2 text-left rtl:text-right">{{ __db('sl_no') }}</th>
                                              <th class="border-2 border-black p-2 text-left rtl:text-right">{{ __db('interview_request_with') }}</th>
                                              <th class="border-2 border-black p-2 text-left rtl:text-right">{{ __db('date') }}
                                              </th>
                                              <th class="border-2 border-black p-2 text-left rtl:text-right">{{ __db('time') }}</th>
                                              <th class="border-2 border-black p-2 text-left rtl:text-right">{{ __db('notes') }}
                                              </th>
                                          </tr>
                                      </thead>
                                      <tbody>
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
                                                  <td class="border-2 border-black p-2">{{ $in + 1 }}</td>
                                                  <td class="border-2 border-black p-2">{!! $interviewMembers !!}</td>
                                                  <td class="border-2 border-black p-2">{{ date('d-m-Y', strtotime($row->date_time)) }}</td>
                                                  <td class="border-2 border-black p-2">{{ date('H:i', strtotime($row->date_time)) }}</td>
                                                  <td class="border-2 border-black p-2">in
                                                    Airshow venue</td>
                                              </tr>
                                          @empty
                                            
                                          @endforelse
                                          
                                      </tbody>
                                  </table>
                              </div>
                          </section>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
@endsection