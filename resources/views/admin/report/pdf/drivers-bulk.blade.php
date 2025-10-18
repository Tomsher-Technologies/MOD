<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>{{ __db('drivers_report') }}</title>
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
<body style="margin: 0; padding: 0; font-size: 12px;">
    <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
        <div style="font-family: Arial, sans-serif;  gap: 20px; align-items: center;margin-top:3%;">
            <div style="font-family: Arial, sans-serif; display: flex; flex-direction: column; gap: 20px;page-break-after: always;">
                <h3 dir="rtl" style="font-weight: bold; color: #cc0000;padding-top: 2%;">{{ __db('assigned_drivers') }}</h3>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #d9d9d9; font-size: 13px">
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('notes') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('delegation') }} - {{ __db('invitation_from') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('mobile') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('car_number') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('car_type') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('name') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('title') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('military_number') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('sl_no') }}</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 12px">
                        @foreach ($assignedDrivers as $i => $driver)
                            <tr>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">{{ $driver->notes ?? '-' }}</td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                    @php
                                        $delegationData = $driver->delegations->map(function ($delegation) use ($driver) {
                                            if ($delegation->pivot->status === 1) {
                                                if(getActiveLanguage() == 'en'){
                                                    $delInv = '<span>'.$delegation?->country?->name .' </span> - <span>'. $delegation?->invitationFrom?->value.' </span>';
                                                }else {
                                                    $delInv = '<span>'. $delegation?->invitationFrom?->value.' </span> - <span>'.$delegation?->country?->name .'</span>';
                                                }

                                                return ($delegation->pivot->end_date ? '('.__db('till').' : ' . $delegation->pivot->end_date . ') ' : '') . $delInv;                                         
                                            }
                                            return null;
                                        })
                                        ->filter()
                                        ->implode('<br>');
                                    @endphp

                                    {!! $delegationData !!}
                                </td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">{{ $driver->phone_number ?? '-' }}</td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">{{ $driver->car_number ?? '-' }}</td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">{{ $driver->car_type ?? '-' }}</td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">{{ $driver->name ?? '-' }}</td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">{{ $driver->getTranslation('title') ?? '-' }}</td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">{{ $driver->military_number ?? '-' }}</td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">{{ $i + 1 }}</td>
                            </tr>
                            
                            @if($loop->iteration % 50 == 0)
                                <!--CHUNKHTML-->
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="font-family: Arial, sans-serif; display: flex; flex-direction: column; gap: 20px; margin-top:5%;">
                <h3 dir="rtl" style="font-weight: bold; color: #cc0000;padding-top: 2%;"> {{ __db('unassigned_drivers') }}</h3>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #d9d9d9;font-size: 13px">
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('notes') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('delegation') }} - {{ __db('invitation_from') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('mobile') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('car_number') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('car_type') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('name') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('title') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('military_number') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('sl_no') }}</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 12px">
                        @foreach ($unassignedDrivers as $i => $driver)
                            <tr>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">-</td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                    -
                                </td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">{{ $driver->phone_number ?? '-' }}</td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">{{ $driver->car_number ?? '-' }}</td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">{{ $driver->car_type ?? '-' }}</td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">{{ $driver->name ?? '-' }}</td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">{{ $driver->getTranslation('title') ?? '-' }}</td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">{{ $driver->military_number ?? '-' }}</td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">{{ $i + 1 }}</td>
                            </tr>

                            @if($loop->iteration % 50 == 0)
                                <!--CHUNKHTML-->
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
