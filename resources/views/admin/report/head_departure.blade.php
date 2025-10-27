@extends('layouts.admin_account', ['title' => __db('reports')])

@section('content')
<style>
    .select2-container--default .select2-selection--multiple {
        min-height: 2rem !important;
        /* height: 40px !important; */
        padding: 0.2rem 0.75rem;
    }
</style>
    <div>
        <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
            <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('delegations_heads_departure') }}</h2>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
            
             <form class="w-[75%] me-4"  method="GET"> 
                <div class="flex relative">
                    
                    <div class="flex flex-row w-[70%] gap-4">
                        <div class="w-[50%]">
                            <input type="text" class="block w-full text-secondary-light text-sm !border-[#d1d5db] rounded-lg date-range" id="date_range" name="date_range" placeholder="DD-MM-YYYY - DD-MM-YYYY" data-time-picker="true" data-format="DD-MM-Y HH:mm:ss" data-separator=" to " autocomplete="off" value="{{ request('date_range') }}">
                        </div>

                        <div class="w-[50%]">
                            <select name="invitation_from[]" multiple data-placeholder="{{ __db('select') }} {{ __db('invitation_from') }}" class="select2 rounded-lg border border-gray-300 text-sm w-full">
                                <option value="">{{ __db('select') }} {{ __db('invitation_from') }}</option>
                                @foreach (getDropDown('departments')->options as $option)
                                    <option value="{{ $option->id }}" @if (in_array($option->id, request('invitation_from', []))) selected @endif>
                                        {{ $option->value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="w-[30%]"> 
                        <button type="submit" class="!text-[#5D471D] mr-2  end-[3px] bottom-[3px] !bg-[#E6D7A2] hover:bg-yellow-400 focus:ring-4 focus:outline-none focus:ring-yellow-200 font-medium rounded-lg text-sm px-4 py-2">{{ __db('search') }}</button>

                        <a href="{{ route('report.heads-arrivals') }}"
                            class=" end-[80px]  bottom-[3px] mr-2 border !border-[#B68A35] !text-[#B68A35] font-medium rounded-lg text-sm px-4 py-2 ">{{ __db('reset') }}</a>
                    </div>
                        
                </div>
            </form>

            <div class="flex gap-3 ms-auto">
                @directCanany(['export_delegations_head_departure'])
                    <form action="{{ route('heads-departure.bulk-exportPdf') }}" method="POST" style="display:inline;">
                        @csrf
                       @foreach (request()->except('limit', 'page') as $key => $value)
                            @if (is_array($value))
                                @foreach ($value as $subKey => $subValue)
                                    <input type="hidden" name="{{ $key }}[{{ $subKey }}]"
                                        value="{{ $subValue }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <button type="submit" class="!text-[#5D471D]  !bg-[#E6D7A2] hover:bg-yellow-400 rounded-lg py-2 px-3">
                            {{ __db('export_pdf') }}
                        </button>
                    </form>
                @enddirectCanany
                <x-back-btn class="" back-url="{{ route('reports.index') }}" />
            </div>
        </div>
            
            
        </div>

        <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6" dir="ltr">
            {{-- <div style=" border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">

                    <div style="width: auto;">
                        <img src="{{ getLogo() }}" alt="{{ env('APP_NAME') }}"
                            style="height: auto; width: 150px;">

                    </div>
                    <div style="text-align: center; width: 50%;">
                        <div style="font-size: 20px; font-weight: bold;">{{ __db('united_arab_emirates') }}</div>
                        <div style="font-size: 20px; font-weight: bold; margin-top: 5px;">{{ __db('ministry_of_defense') }}</div>
                        <div style="font-size: 20px; font-weight: bold; color: #cc0000; margin-top: 5px;">{{ __db('delegations_heads_departure') }}</div>
                    </div>
                    <div style=" width: auto; text-align: right;">
                        <img src="{{ getAdminEventLogo() }}" alt="{{ getCurrentEventName() }}"
                            style=" width: 150px; height: auto;">
                    </div>
                </div>
                <div style="text-align: right; font-size: 0.9em; margin-top:10px;">{{ date('d-m-Y H:i A') }}</div>

            </div> --}}

            <div style="font-family: Arial, sans-serif; display: flex; flex-direction: column; gap: 20px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #d9d9d9; font-size: 13px">
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('invitation_from') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('hotel') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('time') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('departure') }} </th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('flight') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('airport') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('escort') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('position') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('delegation_head') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('country') }}</th>
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
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">{{ $departure?->flight_no ?? '-' }}</td>
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
                            
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection