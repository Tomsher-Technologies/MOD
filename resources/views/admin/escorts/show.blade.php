@extends('layouts.admin_account', ['title' => __db('escort_details')])

@section('content')
<div class="dashboard-main-body ">
    <div class="flex flex-wrap items-center justify-between gap-2 mb-6 mb-10">
        <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('escort_details') }}</h2>
        <a href="{{ route('escorts.index') }}" id="add-attachment-btn" class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-2 px-3">
            <svg class="w-6 h-6 me-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"  stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19 12H5m14 0-4 4m4-4-4-4" />
            </svg>
            <span>{{ __db('back') }}</span>
        </a>
    </div>

    <div class="bg-white rounded-lg p-6 mb-10 mt-4">
        <div class="bg-white p-6 grid grid-cols-1 gap-5 mb-4">
            <div>
                <h2 class="font-semibold mb-0 !text-[22px] mb-3 mt-5">{{ __db('escort_information') }}
                </h2>
                <div class="delegate-row border bg-white p-6 rounded bg-gray-100 mb-2">
                    <div class="grid grid-cols-12 gap-5">

                        <div class="col-span-6">
                            <label class="form-label">{{ __db('name_en') }}:</label>
                            <p class="text-neutral-600">{{ $escort->name_en }}</p>
                        </div>

                        <div class="col-span-6">
                            <label class="form-label">{{ __db('name_ar') }}:</label>
                            <p class="text-neutral-600">{{ $escort->name_ar }}</p>
                        </div>

                        <div class="col-span-6">
                            <label class="form-label">{{ __db('delegation') }}:</label>
                            <p class="text-neutral-600">{{ $escort->delegation->code ?? 'N/A' }}</p>
                        </div>

                        <div class="col-span-6">
                            <label class="form-label">{{ __db('phone_number') }}:</label>
                            <p class="text-neutral-600">{{ $escort->phone_number ?? 'N/A' }}</p>
                        </div>

                        <div class="col-span-6">
                            <label class="form-label">{{ __db('email') }}:</label>
                            <p class="text-neutral-600">{{ $escort->email ?? 'N/A' }}</p>
                        </div>

                        <div class="col-span-6">
                            <label class="form-label">{{ __db('gender') }}:</label>
                            <p class="text-neutral-600">{{ $escort->gender->value ?? 'N/A' }}</p>
                        </div>

                        <div class="col-span-6">
                            <label class="form-label">{{ __db('nationality') }}:</label>
                            <p class="text-neutral-600">{{ $escort->nationality->value ?? 'N/A' }}</p>
                        </div>

                        <div class="col-span-6">
                            <label class="form-label">{{ __db('date_of_birth') }}:</label>
                            <p class="text-neutral-600">{{ $escort->date_of_birth ?? 'N/A' }}</p>
                        </div>

                        <div class="col-span-6">
                            <label class="form-label">{{ __db('id_number') }}:</label>
                            <p class="text-neutral-600">{{ $escort->id_number ?? 'N/A' }}</p>
                        </div>

                        <div class="col-span-6">
                            <label class="form-label">{{ __db('id_issue_date') }}:</label>
                            <p class="text-neutral-600">{{ $escort->id_issue_date ?? 'N/A' }}</p>
                        </div>

                        <div class="col-span-6">
                            <label class="form-label">{{ __db('id_expiry_date') }}:</label>
                            <p class="text-neutral-600">{{ $escort->id_expiry_date ?? 'N/A' }}</p>
                        </div>

                        <div class="col-span-6">
                            <label class="form-label">{{ __db('status') }}:</label>
                            <p class="text-neutral-600">
                                @if ($escort->status)
                                    <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">{{ __db('active') }}</span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">{{ __db('inactive') }}</span>
                                @endif
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
