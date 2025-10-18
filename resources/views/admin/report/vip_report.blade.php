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
            <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('vip_report') }}</h2>
        </div>
        <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
            
             <form class="w-[75%] me-4"  method="GET"> 
                <div class="flex relative">
                    
                    <div class="flex flex-row w-[70%] gap-4">
                        <div class="w-[50%]">
                            <input type="text" class="block w-full text-secondary-light text-sm !border-[#d1d5db] rounded-lg date-range" id="date_range" name="date_range" placeholder="DD-MM-YYYY - DD-MM-YYYY" data-time-picker="true" data-format="DD-MM-Y HH:mm:ss" data-separator=" to " autocomplete="off" value="{{ request('date_range') }}">
                        </div>

                        <div class="w-[50%]">
                            <select name="internal_ranking[]" multiple data-placeholder="{{ __db('internal_ranking') }}" class="select2 rounded-lg border border-gray-300 text-sm w-full">
                                <option value="">{{ __db('select') }} {{ __db('internal_ranking') }}</option>
                                @foreach (getDropDown('internal_ranking')->options as $option)
                                    <option value="{{ $option->id }}" @if (in_array($option->id, request('internal_ranking', []))) selected @endif>
                                        {{ $option->value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="w-[30%]"> 
                        <button type="submit" class="!text-[#5D471D] mr-2  end-[3px] bottom-[3px] !bg-[#E6D7A2] hover:bg-yellow-400 focus:ring-4 focus:outline-none focus:ring-yellow-200 font-medium rounded-lg text-sm px-4 py-2">{{ __db('search') }}</button>

                        <a href="{{ route('report.vip') }}"
                            class=" end-[80px]  bottom-[3px] mr-2 border !border-[#B68A35] !text-[#B68A35] font-medium rounded-lg text-sm px-4 py-2 ">{{ __db('reset') }}</a>
                    </div>
                        
                </div>
            </form>

            <div class="flex gap-3 ms-auto">
                @directCanany(['export_vip'])
                    <form action="{{ route('vip.bulk-exportPdf') }}" method="POST" style="display:inline;">
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

        <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6" dir="ltr">
            <div style="font-family: Arial, sans-serif; display: flex; flex-direction: column; gap: 20px;">
               
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #d9d9d9; font-size: 13px">
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('departure') }} </th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('time') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('arrival') }} </th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('escort') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('position') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('delegation_head') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('country') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('sl_no') }}</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 12px">
                        @foreach ($delegates as $i => $del)
                            @php
                                $arrival_date = $departure_date = '';
                                if($del->arrivals->isNotEmpty()){
                                    $arrival_date = $del->arrivals->first()->date_time;
                                }
                                if($del->departures->isNotEmpty()){
                                    $departure_date = $del->departures->first()->date_time;
                                }
                            @endphp
                            <tr>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                    {{ $departure_date ? \Carbon\Carbon::parse($departure_date)?->format('d-m-Y') : '-' }}
                                </td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                    {{  $arrival_date ? \Carbon\Carbon::parse($arrival_date)?->format('H:i') : '-' }}
                                </td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                    {{  $arrival_date ? \Carbon\Carbon::parse($arrival_date)?->format('d-m-Y') : '-' }}
                                </td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                    @php
                                        $escort = $del?->delegation?->escorts?->first();
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
                                    {{ $del?->getTranslation('designation') ?? '-' }}
                                </td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                    {{  getLangTitleSeperator($del?->getTranslation('title'), $del?->getTranslation('name')) }}
                                </td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                    {{ $del->delegation?->country?->name ?? '-' }}
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