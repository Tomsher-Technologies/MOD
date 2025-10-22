<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>{{ __db('delegations_cars_report') }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
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

<body style="margin: 0; font-size: 10px;">
    <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
        <div style="font-family: Arial, sans-serif;  gap: 20px; align-items: center;margin-top:3%;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #d9d9d9; font-size: 12px">
                        <th style="width: 10%; padding: 8px; border: 2px solid #000; text-align: center;">
                            {{ __db('driver') }}{{ __db('notes') }}
                        </th>
                        <th style="width: 8%; padding: 8px; border: 2px solid #000; text-align: center;">
                            {{ __db('car_type') }}
                        </th>
                        <th style="width: 8%; padding: 8px; border: 2px solid #000; text-align: center;">
                            {{ __db('car_number') }}
                        </th>
                        <th style="width: 10%; padding: 8px; border: 2px solid #000; text-align: center;">
                            {{ __db('driver') }} {{ __db('mobile_number') }}
                        </th>
                        <th style="width: 15%; padding: 8px; border: 2px solid #000; text-align: center;">
                            {{ __db('driver') }}
                        </th>
                        <th style="width: 8%; padding: 8px; border: 2px solid #000; text-align: center;">
                            {{ __db('no_of_delegates') }}
                        </th>
                        <th style="width: 11%; padding: 8px; border: 2px solid #000; text-align: center;">
                            {{ __db('position') }}
                        </th>
                        <th style="width: 16%; padding: 8px; border: 2px solid #000; text-align: center;">
                            {{ __db('delegation_head') }}
                        </th>
                        <th style="width: 10%; padding: 8px; border: 2px solid #000; text-align: center;">
                            {{ __db('country') }}
                        </th>
                        <th style="width: 4%; padding: 8px; border: 2px solid #000; text-align: center;">
                            {{ __db('sl_no') }}
                        </th>
                    </tr>
                </thead>
                <tbody style="font-size: 10px">
                    
                    @foreach ($delegates as $idel => $del)
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
                           
                            $ulStyle = 'margin:0; padding:0; list-style-type:none; list-style:none;';
                            $liStyle = 'margin:0; padding:2px 0; text-indent:0;';
                            
                            $driverNames = $driverPhones = $driverCarNos = $driverCarTypes = '<div style="margin:0; padding:0;">';
                            
                            foreach ($drivers as $driver) {
                            
                                if (getActiveLanguage() == 'en') {
                                    $driverNames .= '<div style="' . $liStyle . '"><span>' . $driver?->military_number . '</span> - <span>' . getLangTitleSeperator($driver?->getTranslation('title'), $driver?->getTranslation('name')) . '</span></div>';
                                } else {
                                    $driverNames .= '<div style="' . $liStyle . '"><span>' . getLangTitleSeperator($driver?->getTranslation('title'), $driver?->getTranslation('name')) . '</span> - <span>' . $driver?->military_number . '</span></div>';
                                }
                            
                                $driverPhones .= '<div style="' . $liStyle . '">' . ($driver?->phone_number ?? '-') . '</div>';
                                $driverCarNos .= '<div style="' . $liStyle . '">' . ($driver?->car_number ?? '-') . '</div>';
                                $driverCarTypes .= '<div style="' . $liStyle . '">' . ($driver?->car_type ?? '-') . '</div>';
                            }
                            
                            $driverNames .= '</div>';
                            $driverPhones .= '</div>';
                            $driverCarNos .= '</div>';
                            $driverCarTypes .= '</div>';

                        @endphp
                        <tr>
                            <td
                                style="padding: 8px; border-left: 2px solid #000;border-right: 2px solid #000;text-align: center;">


                            </td>
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
                                {{ $del?->getTranslation('designation') ?: '-' }}
                            </td>
                            <td style="padding: 8px;text-align: center; border-right: 2px solid #000;">
                                {{ getLangTitleSeperator($del?->getTranslation('title'), $del?->getTranslation('name')) }}
                            </td>
                            <td style="padding: 8px;text-align: center; border-right: 2px solid #000; ">
                                {{ $del->delegation?->country?->name ?: '-' }}
                            </td>
                            <td style="padding: 8px; border-right: 2px solid #000; text-align: center;">
                                {{ $idel + 1 }}
                            </td>
                        </tr>

                        @php
                            $minDrivers = 5; // minimum drivers
                            $rowsPerDriver = 2; // 2 rows per driver

                            if ($driverCount === 0) {
                                $totalRows = $minDrivers * $rowsPerDriver;
                            } else {
                                $totalRows = max($driverCount * $rowsPerDriver, $minDrivers * $rowsPerDriver);
                                if ($driverCount >= $minDrivers) {
                                    $totalRows = 3;
                                } else {
                                    $totalRows = ($minDrivers - $driverCount) * $rowsPerDriver;
                                }
                            }

                            $totalCols = 10;
                        @endphp

                        @for ($i = 0; $i < $totalRows; $i++)
                            <tr>
                                @for ($j = 0; $j < $totalCols; $j++)
                                    @php
                                        $style =
                                            'padding: 8px; text-align: center; border-left: 2px solid #000; border-right: 2px solid #000;';

                                    @endphp
                                    <td style="{{ $style }}"> </td>
                                @endfor
                            </tr>
                        @endfor

                        <tr>
                            <td colspan="10" style="padding: 8px; border: 2px solid #000;">
                                <table style="width: 100%; border-collapse: collapse;">
                                    <tr>
                                        <!-- Departure Date -->
                                        <td style="width: 20%; padding: 4px; vertical-align: bottom; text-align: left;">
                                            <span>{{ $departure_date ? \Carbon\Carbon::parse($departure_date)?->format('d-m-Y') : '-' }}</span>
                                            <strong> : {{ __db('head_departure_date') }}</strong>
                                        </td>

                                        <!-- Arrival Date -->
                                        <td style="width: 25%; padding: 4px; vertical-align: bottom; text-align: left;">
                                            <span>{{ $arrival_date ? \Carbon\Carbon::parse($arrival_date)?->format('H:i d-m-Y') : '-' }}</span>
                                            <strong> : {{ __db('head_arrival_date') }}</strong>
                                        </td>

                                        <!-- Mobile -->
                                        @php $escort = $del?->delegation?->escorts?->first(); @endphp
                                        <td style="width: 20%; padding: 4px; vertical-align: bottom; text-align: right;">
                                            <span>{{ $escort?->phone_number ?: '-' }}</span>
                                            <strong> : {{ __db('mobile') }}</strong>
                                        </td>

                                        <!-- Escort -->
                                        <td style="width: 35%; padding: 4px; vertical-align: bottom; text-align: right;">
                                            <span class="ltr" style="display:inline-block; width:100%;">
                                                <span style="float:left;">
                                                    @if (getActiveLanguage() == 'en')
                                                        {{ $escort?->military_number ?? ' ' }} &nbsp;-&nbsp; {{ $escort?->internalRanking?->value ?? ' ' }}&nbsp;{{ $escort?->name ?? ' ' }}
                                                    @else
                                                        {{ $escort?->internalRanking?->value ?? ' ' }}&nbsp;{{ $escort?->name ?? ' ' }}&nbsp;-&nbsp; {{ $escort?->military_number ?? ' ' }}
                                                       
                                                    @endif
                                                </span>
        
                                                <strong> : {{ __db('escort') }}</strong> 
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        @php $isPageBreak = ($idel + 1) % 2 == 0 && !$loop->last; @endphp

                        @if ($isPageBreak)
                </tbody>
            </table>

            <div style="page-break-after: always;"></div>
            <!--CHUNKHTML-->
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #d9d9d9; font-size: 12px">
                        <th style="width: 10%; padding: 8px; border: 2px solid #000; text-align: center;">
                            {{ __db('driver') }}{{ __db('notes') }}
                        </th>
                        <th style="width: 8%; padding: 8px; border: 2px solid #000; text-align: center;">
                            {{ __db('car_type') }}
                        </th>
                        <th style="width: 8%; padding: 8px; border: 2px solid #000; text-align: center;">
                            {{ __db('car_number') }}
                        </th>
                        <th style="width: 10%; padding: 8px; border: 2px solid #000; text-align: center;">
                            {{ __db('driver') }} {{ __db('mobile_number') }}
                        </th>
                        <th style="width: 15%; padding: 8px; border: 2px solid #000; text-align: center;">
                            {{ __db('driver') }}
                        </th>
                        <th style="width: 8%; padding: 8px; border: 2px solid #000; text-align: center;">
                            {{ __db('no_of_delegates') }}
                        </th>
                        <th style="width: 11%; padding: 8px; border: 2px solid #000; text-align: center;">
                            {{ __db('position') }}
                        </th>
                        <th style="width: 16%; padding: 8px; border: 2px solid #000; text-align: center;">
                            {{ __db('delegation_head') }}
                        </th>
                        <th style="width: 10%; padding: 8px; border: 2px solid #000; text-align: center;">
                            {{ __db('country') }}
                        </th>
                        <th style="width: 4%; padding: 8px; border: 2px solid #000; text-align: center;">
                            {{ __db('sl_no') }}
                        </th>
                    </tr>
                </thead>
                <tbody style="font-size: 10px">
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
