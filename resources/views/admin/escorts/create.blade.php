@extends('layouts.admin_account', ['title' => __db('create_new_escort')])

@section('content')
<div class="dashboard-main-body ">
    <div class="flex flex-wrap items-center justify-between gap-2 mb-6 mb-10">
        <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('create_new_escort') }}</h2>
        <a href="{{ route('escorts.index') }}" id="add-attachment-btn" class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-2 px-3">
            <svg class="w-6 h-6 me-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"  stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19 12H5m14 0-4 4m4-4-4-4" />
            </svg>
            <span>{{ __db('back') }}</span>
        </a>
    </div>

    <form action="{{ route('escorts.store') }}" method="POST" autocomplete="off">
        @csrf
        <div class="bg-white rounded-lg p-6 mb-10 mt-4">
            <div class="bg-white p-6 grid grid-cols-1 gap-5 mb-4">
                <div>
                    <h2 class="font-semibold mb-0 !text-[22px] mb-3 mt-5">{{ __db('escort_details') }}
                    </h2>
                    <div class="delegate-row border bg-white p-6 rounded bg-gray-100 mb-2">
                        <div class="grid grid-cols-12 gap-5">

                            <div class="col-span-6">
                                <label class="form-label">{{ __db('name_en') }} <span class="text-red-600">*</span></label>
                                <input type="text" id="name_en" name="name_en" class="form-control" value="{{ old('name_en') }}">
                                @error('name_en')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-span-6">
                                <label class="form-label">{{ __db('name_ar') }} <span class="text-red-600">*</span></label>
                                <input type="text" id="name_ar" name="name_ar" class="form-control" value="{{ old('name_ar') }}">
                                @error('name_ar')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-span-6">
                                <label class="form-label">{{ __db('delegation') }}</label>
                                <select name="delegation_id" id="delegation_id" class="form-control">
                                    <option value="">{{ __db('choose_option') }}</option>
                                    @foreach($delegations as $delegation)
                                        <option value="{{ $delegation->id }}" {{ (old('delegation_id') == $delegation->id) ? 'selected' : '' }}>{{ $delegation->code }}</option>
                                    @endforeach
                                </select>
                                @error('delegation_id')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-span-6">
                                <label class="form-label">{{ __db('phone_number') }}</label>
                                <input type="text" id="phone_number" name="phone_number" class="form-control" value="{{ old('phone_number') }}">
                                @error('phone_number')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-span-6">
                                <label class="form-label">{{ __db('email') }}</label>
                                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}">
                                @error('email')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-span-6">
                                <label class="form-label">{{ __db('gender') }}</label>
                                <select name="gender_id" id="gender_id" class="form-control">
                                    <option value="">{{ __db('choose_option') }}</option>
                                    @foreach($dropdowns['genders'] as $gender)
                                        <option value="{{ $gender->id }}" {{ (old('gender_id') == $gender->id) ? 'selected' : '' }}>{{ $gender->value }}</option>
                                    @endforeach
                                </select>
                                @error('gender_id')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-span-6">
                                <label class="form-label">{{ __db('nationality') }}</label>
                                <select name="nationality_id" id="nationality_id" class="form-control">
                                    <option value="">{{ __db('choose_option') }}</option>
                                    @foreach($dropdowns['nationalities'] as $nationality)
                                        <option value="{{ $nationality->id }}" {{ (old('nationality_id') == $nationality->id) ? 'selected' : '' }}>{{ $nationality->value }}</option>
                                    @endforeach
                                </select>
                                @error('nationality_id')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-span-6">
                                <label class="form-label">{{ __db('date_of_birth') }}</label>
                                <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}">
                                @error('date_of_birth')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-span-6">
                                <label class="form-label">{{ __db('id_number') }}</label>
                                <input type="text" id="id_number" name="id_number" class="form-control" value="{{ old('id_number') }}">
                                @error('id_number')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-span-6">
                                <label class="form-label">{{ __db('id_issue_date') }}</label>
                                <input type="date" id="id_issue_date" name="id_issue_date" class="form-control" value="{{ old('id_issue_date') }}">
                                @error('id_issue_date')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-span-6">
                                <label class="form-label">{{ __db('id_expiry_date') }}</label>
                                <input type="date" id="id_expiry_date" name="id_expiry_date" class="form-control" value="{{ old('id_expiry_date') }}">
                                @error('id_expiry_date')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                    </div>
                </div>
            </div>
            <div class="flex justify-start items-center gap-5">
                <button type="submit" class="btn text-md  !bg-[#B68A35] text-white rounded-lg h-12 mr-4">{{
                    __db('submit') }}</button>

                <a href="{{ route('escorts.index') }}" class="btn text-md  !bg-[#637a85] border !border-[#637a85] !text-[#fff] rounded-lg h-12 mr-1">{{
                    __db('cancel') }}</a>
            </div>
        </div>

    </form>

</div>
@endsection
