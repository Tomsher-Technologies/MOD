@extends('layouts.admin_account', ['title' => __db('reports')])

@section('content')
    <div>
        <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
            <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('drivers_report') }}</h2>
            
            <div class="flex gap-3 ms-auto">
                @directCanany(['export_drivers_report'])
                    <form action="{{ route('drivers.bulk-exportPdf') }}" method="POST" style="display:inline;">
                        @csrf
                       
                        <button type="submit" class="!text-[#5D471D]  !bg-[#E6D7A2] hover:bg-yellow-400 rounded-lg py-2 px-3">
                            {{ __db('export_pdf') }}
                        </button>
                    </form>
                @enddirectCanany
                <x-back-btn class="" back-url="{{ route('reports.index') }}" />
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
                        <div style="font-size: 20px; font-weight: bold; color: #cc0000; margin-top: 5px;">{{ __db('drivers_report') }}</div>
                    </div>
                    <div style=" width: auto; text-align: right;">
                        <img src="{{ getAdminEventLogo() }}" alt="{{ getCurrentEventName() }}"
                            style=" width: 150px; height: auto;">
                    </div>
                </div>
                <div style="text-align: right; font-size: 0.9em; margin-top:10px;">{{ date('d-m-Y H:i A') }}</div>

            </div> --}}

            <div style="font-family: Arial, sans-serif; display: flex; flex-direction: column; gap: 20px;">
                <h3 style="text-align: right !important; font-weight: bold; color: #cc0000;">{{ __db('assigned_drivers') }}</h3>
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
                                            return ($delegation->pivot->end_date ? '('.__db('till').' : ' . $delegation->pivot->end_date . ') ' : '') . $delegation?->country?->name .' - '. $delegation?->invitationFrom?->value;                                         
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
                            
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="font-family: Arial, sans-serif; display: flex; flex-direction: column; gap: 20px; margin-top:5%;">
                <h3 style="text-align: right !important; font-weight: bold; color: #cc0000;"> {{ __db('unassigned_drivers') }}</h3>
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
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection