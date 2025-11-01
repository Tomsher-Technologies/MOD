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
            <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('interviews_report') }}</h2>
        </div>
        <div class="flex flex-wrap items-center justify-between gap-2 mb-6">

            <form class="w-[75%] me-4" method="GET">
                <div class="flex relative">

                    <div class="flex flex-row w-[70%] gap-4">
                        <div class="w-[50%]">
                            <input type="text"
                                class="block w-full text-secondary-light text-sm !border-[#d1d5db] rounded-lg date-range"
                                id="date_range" name="date_range" placeholder="DD-MM-YYYY - DD-MM-YYYY"
                                data-time-picker="true" data-format="DD-MM-Y HH:mm:ss" data-separator=" to "
                                autocomplete="off" value="{{ request('date_range') }}">
                        </div>

                        <div class="w-[50%]">
                            <select name="interviewee" data-placeholder="{{ __db('select') }} {{ __db('interviewee') }}"
                                class="select2 rounded-lg border border-gray-300 text-sm w-full">
                                <option value="">{{ __db('select') }} {{ __db('interviewee') }}</option>

                                @foreach ($delegates as $delegate)
                                    <option value="{{ $delegate['id'] }}"
                                        {{ request('interviewee') == $delegate['id'] ? 'selected' : '' }}>
                                        {{ $delegate['name'] }}</option>
                                @endforeach

                                @foreach ($otherMembers as $other)
                                    <option value="{{ $other['id'] }}"
                                        {{ request('interviewee') == $other['id'] ? 'selected' : '' }}>
                                        {{ $other['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="w-[30%]">
                        <button type="submit"
                            class="!text-[#5D471D] mr-2  end-[3px] bottom-[3px] !bg-[#E6D7A2] hover:bg-yellow-400 focus:ring-4 focus:outline-none focus:ring-yellow-200 font-medium rounded-lg text-sm px-4 py-2">{{ __db('search') }}</button>

                        <a href="{{ route('report.interviews') }}"
                            class=" end-[80px]  bottom-[3px] mr-2 border !border-[#B68A35] !text-[#B68A35] font-medium rounded-lg text-sm px-4 py-2 ">{{ __db('reset') }}</a>
                    </div>

                </div>
            </form>

            <div class="flex gap-3 ms-auto">
                @directCanany(['export_interviews_reports'])
                    <form action="{{ route('interviews.bulk-exportPdf') }}" method="POST" style="display:inline;">
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

                @foreach ($interviews as $intervieweeName => $group)
                    <table
                        style="width: 100%; margin-top: 20px; font-weight: bold; font-size: 16px; border-collapse: collapse;">
                        <tr>
                            <td style="text-align: right; white-space: nowrap;">

                                <span class="ltr" style="display:inline-block; width:100%;">
                                    <span style="">
                                        {{ $intervieweeName }}
                                    </span>
                                    <strong>: {{ __db('interview_with') }}</strong>
                                </span>
                            </td>
                        </tr>
                    </table>

                    <table style="width:100%;border-collapse:collapse;margin-bottom:20px;">
                        <thead>
                            <tr style="background:#d9d9d9; font-size: 13px">
                                <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('note') }}
                                <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('mobile') }}
                                </th>
                                <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('escort') }}
                                </th>
                                <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('date') }}
                                </th>
                                <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('time') }}
                                </th>
                                <th style="padding: 8px; border: 2px solid #000; text-align: center;">
                                    {{ __db('position') }}</th>
                                <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('name') }}
                                </th>
                                <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('country') }}
                                </th>
                                <th style="padding: 8px; border: 2px solid #000; text-align: center;">{{ __db('sl_no') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 12px">
                            @php $ser = 1; @endphp
                            @foreach ($group as $interview)
                                @foreach ($interview->fromMembers as $member)
                                    <tr style="text-align: center">
                                        @php
                                            $escort = $member->delegate?->delegation?->escorts?->first();
                                        @endphp

                                        <td style="padding:8px;border:2px solid #000;">
                                            {{ $interview?->comment ?? '-' }}
                                        </td>

                                        <td style="padding:8px;border:2px solid #000;">
                                            {{ $escort?->phone_number ?? '-' }}
                                        </td>
                                        <td style="padding:8px;border:2px solid #000;">

                                            @if (getActiveLanguage() == 'en')
                                                <span>{{ $escort?->military_number }}</span> -
                                                <span>{{ $escort?->internalRanking?->value . ' ' . $escort?->name }}</span>
                                            @else
                                                <span>{{ $escort?->internalRanking?->value . ' ' . $escort?->name }}</span>
                                                -
                                                <span>{{ $escort?->military_number }}</span>
                                            @endif
                                        </td>
                                        <td style="padding:8px;border:2px solid #000;">
                                            {{ $interview->date_time ? date('d-m-Y', strtotime($interview->date_time)) : '-' }}
                                        </td>
                                        <td style="padding:8px;border:2px solid #000;">
                                            {{ $interview->date_time ? date('H:i', strtotime($interview->date_time)) : '-' }}
                                        </td>
                                        <td style="padding:8px;border:2px solid #000;">
                                            {{ $member->delegate?->getTranslation('designation') ?? '' }}
                                        </td>
                                        <td style="padding:8px;border:2px solid #000;">
                                            <strong>{{ $member->delegate ? getLangTitleSeperator($member->delegate->getTranslation('title'), $member->delegate->getTranslation('name')) : $member->otherMember?->getTranslation('name') }}</strong>
                                        </td>
                                        <td style="padding:8px;border:2px solid #000;">
                                            {{ $member->delegate?->delegation?->country?->name ?? '' }}
                                        </td>
                                        <td style="padding:8px;border:2px solid #000;">
                                            {{ $ser++ }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                @endforeach

            </div>
        </div>
    </div>
@endsection
