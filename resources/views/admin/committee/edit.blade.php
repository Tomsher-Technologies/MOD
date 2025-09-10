@extends('layouts.admin_account', ['title' => __db('edit') . ' ' . __db('committee_members')])

@section('content')
    <div class="">
        <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
            <h2 class="font-semibold mb-0 !text-[22px]">{{__db('edit') . ' ' . __db('committee_members') }}</h2>
            <a href="{{ route('committees.index') }}"
                class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-2 px-3">
                <svg class="w-6 h-6 text-white me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                    height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 12H5m14 0-4 4m4-4-4-4" />
                </svg>
                <span>{{ __db('back') }}</span>
            </a>
        </div>
       
        <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
            <form action="{{ route('committees.update', $committee->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-12 gap-5">

                    <div class="col-span-4">
                        <label class="form-label">{{ __db('event') }} <span class="text-red-500">*</span> :</label>
                        <select name="event_id" id="event_id" class="select2 w-full p-3 rounded-lg border border-neutral-300 text-sm text-neutral-600 focus:border-primary-600 focus:ring-0"  data-placeholder="{{ __db('select') . ' ' . __db('event') }}">
                            <option value="">{{ __db('select') . ' ' . __db('event') }}</option>
                            @foreach ($events as $event)
                                <option value="{{ $event->id }}" {{ old('event_id', $committee->event_id) == $event->id ? 'selected' : '' }}>{{ $event->getTranslation('name') }}</option>
                            @endforeach
                        </select>
                        @error('event_id')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-span-4">
                        <label class="form-label">{{ __db('name') }} ({{ __db('english') }}) <span class="text-red-500">*</span> :</label>
                        <input type="text" name="name_en" dir="ltr"
                            class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                            value="{{ old('name_en', $committee->name_en ?? '') }}" >
                        @error('name_en')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-span-4">
                        <label class="form-label">{{ __db('name') }} ({{ __db('arabic') }}) <span class="text-red-500">*</span> :</label>
                        <input type="text" name="name_ar"
                            class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                            value="{{ old('name_ar', $committee->name_ar ?? '') }}" >
                        @error('name_ar')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-span-4">
                        <label class="form-label">{{ __db('email') }} <span class="text-red-500">*</span> :</label>
                        <input type="text" name="email" dir="ltr"
                            class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                            value="{{ old('email', $committee->email ?? '') }}" >
                        @error('email')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-span-4">
                        <label class="form-label">{{ __db('phone') }} <span class="text-red-500">*</span> :</label>
                        <input type="text" name="phone"  dir="ltr"
                            class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                            value="{{ old('phone', $committee->phone ?? '') }}" >
                        @error('phone')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-span-4">
                        <label class="form-label">{{ __db('military_number') }} <span class="text-red-500">*</span> :</label>
                        <input type="text" name="military_no"  dir="ltr"
                            class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                            value="{{ old('military_no', $committee->military_no ?? '') }}" >
                        @error('military_no')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-span-4">
                        <label class="form-label">{{ __db('designation') }} <span class="text-red-500">*</span> :</label>
                        <select name="designation_id" id="designation_id" class="select2 w-full p-3 rounded-lg border border-neutral-300 text-sm text-neutral-600 focus:border-primary-600 focus:ring-0" data-placeholder="{{ __db('select') . ' ' . __db('designation') }}">
                            <option value="">{{ __db('select') . ' ' . __db('designation') }}</option>
                            @foreach ($designations as $des)
                                <option value="{{ $des->id }}" {{ old('designation_id', $committee->designation_id) == $des->id ? 'selected' : '' }}>{{ $des->value }}</option>
                            @endforeach
                        </select>
                        @error('designation_id')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-span-4">
                        <label class="form-label">{{ __db('committee') }} <span class="text-red-500">*</span> :</label>
                        <select name="committee_id" id="committee_id" class="select2 w-full p-3 rounded-lg border border-neutral-300 text-sm text-neutral-600 focus:border-primary-600 focus:ring-0" data-placeholder="{{ __db('select') . ' ' . __db('committee') }}" >
                            <option value="">{{ __db('select') . ' ' . __db('committee') }}</option>
                            @foreach ($committees as $com)
                                <option value="{{ $com->id }}" {{ old('committee_id', $committee->committee_id) == $com->id ? 'selected' : '' }}>{{ $com->value }}</option>
                            @endforeach
                        </select>
                        @error('committee_id')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                </div>

                <div class="flex justify-between items-center mt-8">
                    <button type="submit" class="btn text-md mb-[-10px] !bg-[#B68A35] text-white rounded-lg h-12">
                        {{ __db('submit') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection