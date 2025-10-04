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
            <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('delegations_without_escort_report') }}</h2>
        </div>
        <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
            
             <form class="w-[75%] me-4"  method="GET"> 
                <div class="flex relative">
                    
                    <div class="flex flex-row w-[70%] gap-4">
                        <div class="w-[50%]">
                            <select name="country_id[]" multiple data-placeholder="{{ __db('select') }} {{ __db('country') }}" class="select2 rounded-lg border border-gray-300 text-sm w-full" >
                                <option value="">{{ __db('select') }} {{ __db('country') }}</option>
                                @foreach (getAllCountries() as $option)
                                    <option value="{{ $option->id }}"
                                        {{ is_array(request('country_id')) && in_array($option->id, request('country_id')) ? 'selected' : '' }}>
                                        {{ $option->name }}
                                    </option>
                                @endforeach
                            </select>
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

                        <a href="{{ route('report.without-escorts') }}"
                            class=" end-[80px]  bottom-[3px] mr-2 border !border-[#B68A35] !text-[#B68A35] font-medium rounded-lg text-sm px-4 py-2 ">{{ __db('reset') }}</a>
                    </div>
                        
                </div>
            </form>

            <div class="flex gap-3 ms-auto">
                @directCanany(['export_delegations_without_escort'])
                    <form action="{{ route('without-escorts.bulk-exportPdf') }}" method="POST" style="display:inline;">
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
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('participation_status') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('invitation_status') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('invitation_from') }} </th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('escorts') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('positions') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('delegations') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('country') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('sl_no') }}</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 12px">
                        @foreach ($delegations as $i => $del)
                            @php
                                $delegates = $positions = '';
                            @endphp

                            @foreach ($del->delegates as $member)
                                @php
                                    $delegates .= '<span style="'.($member?->team_head ? 'color: red; font-weight: 600;' : '').'">'.$member->getTranslation('title').' '.$member?->getTranslation('name').'</span><br>';
                                    $positions .= '<span style="'.($member?->team_head ? 'color: red; font-weight: 600;' : '').'">'.$member?->internalRanking?->value .'</span><br>';
                                @endphp
                            @endforeach
                            <tr>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                    {{ $del->participationStatus?->value ?? '-' }}
                                </td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                    {{ $del->invitationStatus?->value ?? '-' }}
                                </td>
                                
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                    {{ $del->invitationFrom?->value ?? '-' }}
                                </td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                                                        
                                </td>
                               
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                    {!! $positions !!}
                                </td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                    {!! $delegates !!}
                                </td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                    {{ $del->country?->name ?? '-' }}
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