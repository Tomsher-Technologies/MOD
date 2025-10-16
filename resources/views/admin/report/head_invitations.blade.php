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
            <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('delegation_head_invitations_report') }}</h2>
        </div>
        <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
            
             <form class="w-[75%] me-4"  method="GET"> 
                <div class="flex relative">
                    
                    <div class="flex flex-row w-[75%] gap-4">
                        <div class="w-[33%]">
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

                        <div class="w-[33%]">
                            <select name="invitation_from[]" multiple data-placeholder="{{ __db('select') }} {{ __db('invitation_from') }}" class="select2 rounded-lg border border-gray-300 text-sm w-full">
                                <option value="">{{ __db('select') }} {{ __db('invitation_from') }}</option>
                                @foreach (getDropDown('departments')->options as $option)
                                    <option value="{{ $option->id }}" @if (in_array($option->id, request('invitation_from', []))) selected @endif>
                                        {{ $option->value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-[33%]">
                            <select name="invitation_status[]" multiple data-placeholder="{{ __db('select') }} {{ __db('invitation_status') }}" class="select2 rounded-lg border border-gray-300 text-sm w-full">
                                <option value="">{{ __db('select') }} {{ __db('invitation_status') }}</option>
                                @foreach (getDropDown('invitation_status')->options as $optionI)
                                    <option value="{{ $optionI->id }}" @if (in_array($optionI->id, request('invitation_status', []))) selected @endif>
                                        {{ $optionI->value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="w-[25%]"> 
                        <button type="submit" class="!text-[#5D471D] mr-2  end-[3px] bottom-[3px] !bg-[#E6D7A2] hover:bg-yellow-400 focus:ring-4 focus:outline-none focus:ring-yellow-200 font-medium rounded-lg text-sm px-4 py-2">{{ __db('search') }}</button>

                        <a href="{{ route('report.heads-invitations') }}"
                            class=" end-[80px]  bottom-[3px] mr-2 border !border-[#B68A35] !text-[#B68A35] font-medium rounded-lg text-sm px-4 py-2 ">{{ __db('reset') }}</a>
                    </div>
                        
                </div>
            </form>

            <div class="flex gap-3 ms-auto">
                @directCanany(['export_delegation_head_invitations'])
                    <form action="{{ route('heads-invitations.bulk-exportPdf') }}" method="POST" style="display:inline;">
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
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('invitation_status') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('invitation_from') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('escort') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('position') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('delegation_head') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('country') }}</th>
                            <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('sl_no') }}</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 12px">
                        @foreach ($invitations as $i => $invt)
                      
                            <tr>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                    {{ $invt->delegation?->invitationStatus?->value ?? '-' }}
                                </td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                    {{ $invt->delegation?->invitationFrom?->value ?? '-' }}
                                </td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                    @php
                                        $escort = $invt->delegation?->escorts?->first();
                                    @endphp
                                  
                                    @if (getActiveLanguage() == 'en')
                                        <span>{{ $escort?->military_number }}</span>&nbsp; - &nbsp;<span>{{ $escort?->internalRanking?->value .' '. $escort?->name }}</span>
                                    @else
                                        <span>{{ $escort?->internalRanking?->value .' '. $escort?->name }}</span>&nbsp; - &nbsp;<span>{{ $escort?->military_number }}</span>
                                    @endif
                                    <br>
                                    {{ $escort?->phone_number }}
                                </td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                    {{ $invt->getTranslation('designation') ?? '-' }}
                                </td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                    {{  getLangTitleSeperator($invt?->getTranslation('title'), $invt?->getTranslation('name')) }}
                                </td>
                                <td style="padding: 8px; border: 2px solid #000; text-align: center;">
                                    {{ $invt->delegation?->country?->name ?? '-' }}
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