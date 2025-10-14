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
            <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('delegations_cars_report') }}</h2>
        </div>
        <div class="flex flex-wrap items-center justify-between gap-2 mb-6">

            <form class="w-[50%] me-4" method="GET">
                <div class="flex relative">

                    <div class="flex flex-row w-[70%] gap-4">
                        
                        <div class="w-[90%]">
                            <select name="invitation_status[]" multiple data-placeholder="{{ __db('invitation_status') }}"
                                class="select2 rounded-lg border border-gray-300 text-sm w-full">
                                <option value="">{{ __db('select') }} {{ __db('invitation_status') }}</option>
                                @foreach (getDropDown('invitation_status')->options as $option)
                                    <option value="{{ $option->id }}" @if (in_array($option->id, request('invitation_status', []))) selected @endif>
                                        {{ $option->value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="w-[30%]">
                        <button type="submit"
                            class="!text-[#5D471D] mr-2  end-[3px] bottom-[3px] !bg-[#E6D7A2] hover:bg-yellow-400 focus:ring-4 focus:outline-none focus:ring-yellow-200 font-medium rounded-lg text-sm px-4 py-2">{{ __db('search') }}</button>

                        <a href="{{ route('report.delegation-cars') }}"
                            class=" end-[80px]  bottom-[3px] mr-2 border !border-[#B68A35] !text-[#B68A35] font-medium rounded-lg text-sm px-4 py-2 ">{{ __db('reset') }}</a>
                    </div>

                </div>
            </form>

            <div class="flex gap-3 ms-auto">
                @directCanany(['export_delegations_cars'])
                    <form action="{{ route('delegation-cars.bulk-exportPdf') }}" method="POST" style="display:inline;">
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
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">
                                {{ __db('driver') }}{{ __db('notes') }} </th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('car_type') }}
                            </th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('car_number') }}
                            </th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('driver') }}
                                {{ __db('mobile_number') }} </th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('driver') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">
                                {{ __db('no_of_delegates') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('position') }}
                            </th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">
                                {{ __db('delegation_head') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('country') }}
                            </th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('sl_no') }}</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 12px">
                        @php
                            $separator = (getActiveLanguage() === 'ar') ? ' / ' : ' . ';
                        @endphp
                        @foreach ($delegates as $i => $del)
                            @php
                                $arrival_date = $departure_date = '';
                                if ($del->arrivals->isNotEmpty()) {
                                    $arrival_date = $del->arrivals->first()->date_time;
                                }
                                if ($del->departures->isNotEmpty()) {
                                    $departure_date = $del->departures->first()->date_time;
                                }

                                $drivers = $del?->delegation?->drivers;

                                $driverCount = count($drivers);
                                $ulStyle = 'list-style:none; margin:0; padding:0;';
                                $liStyle = 'padding:4px 0;';

                                $driverNames = $driverPhones = $driverCarNos = $driverCarTypes = '<ul style="' . $ulStyle . '">';

                                foreach ($drivers as $driver) {
                                    $driverNames .= '<li style="' . $liStyle . '">' 
                                        . ($driver?->military_number ?? '') . ' - ' 
                                        . ($driver?->getTranslation('title') ?? '') . '' . $separator . ' ' 
                                        . ($driver?->getTranslation('name') ?? '-') 
                                        . '</li>';

                                    $driverPhones .= '<li style="' . $liStyle . '">' 
                                        . ($driver?->phone_number ?? '-') 
                                        . '</li>';

                                    $driverCarNos .= '<li style="' . $liStyle . '">' 
                                        . ($driver?->car_number ?? '-') 
                                        . '</li>';

                                    $driverCarTypes .= '<li style="' . $liStyle . '">' 
                                        . ($driver?->car_type ?? '-') 
                                        . '</li>';
                                }

                                $driverNames .= '</ul>';
                                $driverPhones .= '</ul>';
                                $driverCarNos .= '</ul>';
                                $driverCarTypes .= '</ul>';
                            @endphp
                            <tr>
                                <td style="padding: 8px; border-left: 2px solid #000;border-right: 2px solid #000;text-align: center;"></td>
                                <td style="padding: 8px; text-align: center; border-right: 2px solid #000;">
                                    {!! $driverCarTypes !!}
                                </td>
                                <td style="padding: 8px;text-align: center; border-right: 2px solid #000;">
                                    {!! $driverCarNos !!}
                                </td>
                                <td style="padding: 8px;text-align: center; border-right: 2px solid #000;">
                                    {!! $driverPhones !!}                                    
                                </td>
                                <td style="padding: 8px;text-align: center; border-right: 2px solid #000;">
                                    {!! $driverNames !!}
                                </td>
                                <td style="padding: 8px;text-align: center; border-right: 2px solid #000;">
                                    {{ $del->delegation?->delegates?->count() ?? '0' }}
                                </td>
                                <td style="padding: 8px;text-align: center; border-right: 2px solid #000;">
                                    {{ $del?->getTranslation('designation') ?? '-' }}
                                </td>
                                <td style="padding: 8px;text-align: center; border-right: 2px solid #000;">
                                    {{ $del->getTranslation('title') }} {{ $separator }}
                                    {{ $del->getTranslation('name') ?? '-' }}
                                </td>
                                <td style="padding: 8px;text-align: center; border-right: 2px solid #000; ">
                                    {{ $del->delegation?->country?->name ?? '-' }}
                                </td>
                                <td style="padding: 8px; border-right: 2px solid #000; text-align: center;">
                                    {{ $i + 1 }}
                                </td>
                            </tr>

                            {{-- @php
                                $minDrivers = 5; // minimum drivers
                                $rowsPerDriver = 2; // 2 rows per driver

                                if ($driverCount === 0) {
                                    $totalRows = $minDrivers * $rowsPerDriver; 
                                } else {
                                    $totalRows = max($driverCount * $rowsPerDriver, $minDrivers * $rowsPerDriver);
                                    if ($driverCount >= $minDrivers) {
                                        $totalRows = 2; 
                                    }else{
                                        $totalRows = ($minDrivers - $driverCount) * $rowsPerDriver;
                                    }
                                }

                                $totalCols = 10; 
                            @endphp --}}

                            {{-- @for ($i = 0; $i < $totalRows; $i++)
                                <tr>
                                    @for ($j = 0; $j < $totalCols; $j++)
                                        @php
                                            $style = 'padding: 8px; text-align: center; border-left: 2px solid #000; border-right: 2px solid #000;';
                                             
                                        @endphp 
                                        <td style="{{ $style }}"> </td>
                                    @endfor
                                </tr>
                            @endfor --}}

                            <tr>
                                <td style="padding: 8px; border-left: 2px solid #000;border-right: 2px solid #000; text-align: center;"></td>
                                <td style="padding: 8px; border-right: 2px solid #000; text-align: center;"></td>
                                <td style="padding: 8px; border-right: 2px solid #000; text-align: center;"></td>
                                <td style="padding: 8px; border-right: 2px solid #000; text-align: center;"></td>
                                <td style="padding: 8px; border-right: 2px solid #000; text-align: center;"></td>
                                <td style="padding: 8px; border-right: 2px solid #000; text-align: center;"></td>
                                <td style="padding: 8px; border-right: 2px solid #000; text-align: center;"></td>
                                <td style="padding: 8px; border-right: 2px solid #000; text-align: center;"></td>
                                <td style="padding: 8px; border-right: 2px solid #000; text-align: center;"></td>
                                <td style="padding: 8px; border-right: 2px solid #000; text-align: center;"></td>
                            </tr>
                            <tr>
                                <td style="padding: 8px; border-left: 2px solid #000;border-right: 2px solid #000; text-align: center;"></td>
                                <td style="padding: 8px; border-right: 2px solid #000; text-align: center;"></td>
                                <td style="padding: 8px; border-right: 2px solid #000; text-align: center;"></td>
                                <td style="padding: 8px; border-right: 2px solid #000; text-align: center;"></td>
                                <td style="padding: 8px; border-right: 2px solid #000; text-align: center;"></td>
                                <td style="padding: 8px; border-right: 2px solid #000; text-align: center;"></td>
                                <td style="padding: 8px; border-right: 2px solid #000; text-align: center;"></td>
                                <td style="padding: 8px; border-right: 2px solid #000; text-align: center;"></td>
                                <td style="padding: 8px; border-right: 2px solid #000; text-align: center;"></td>
                                <td style="padding: 8px; border-right: 2px solid #000; text-align: center;"></td>
                            </tr>

                            <tr>
                                <td colspan="10" style="padding: 8px; border: 2px solid #000;">
                                    <table style="width: 100%; border-collapse: collapse;">
                                        <tr>
                                            <!-- Departure Date -->
                                            <td style="width: 20%; padding: 4px; vertical-align: bottom; text-align: left;">
                                                {{ $departure_date ? \Carbon\Carbon::parse($departure_date)?->format('d-m-Y') : '-' }}
                                                <strong> : {{ __db('head_departure_date') }}</strong>
                                            </td>

                                            <!-- Arrival Date -->
                                            <td style="width: 25%; padding: 4px; vertical-align: bottom; text-align: left;">
                                                {{ $arrival_date ? \Carbon\Carbon::parse($arrival_date)?->format('H:i d-m-Y') : '-' }}
                                                <strong> : {{ __db('head_arrival_date') }}</strong>
                                            </td>

                                            <!-- Mobile -->
                                            @php $escort = $del?->delegation?->escorts?->first(); @endphp
                                            <td style="width: 20%; padding: 4px; vertical-align: bottom; text-align: right;">
                                                {{ $escort?->phone_number ?? '-' }}
                                                <strong> : {{ __db('mobile') }}</strong>
                                            </td>

                                            <!-- Escort -->
                                            <td style="width: 35%; padding: 4px; vertical-align: bottom; text-align: right;">
                                                {{ $escort?->military_number ? $escort->military_number . ' - ' : '' }}
                                                {{ $escort?->internalRanking?->value ?? '' }}
                                                {{ $escort?->name ?? '-' }}
                                                <strong> : {{ __db('escort') }}</strong>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>


                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
