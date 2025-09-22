@extends('layouts.admin_account', ['title' => __db('add_floor_plan')])

@section('content')
    <x-back-btn title="" back-url="{{ route('floor-plans.index') }}" />

    <div class="bg-white h-full w-full rounded-lg border-0 p-6">
        <h2 class="font-semibold text-2xl mb-6">{{ __db('add_floor_plan') }}</h2>

        <form action="{{ route('floor-plans.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-12 gap-5">
                <div class="col-span-12 md:col-span-3">
                    <label class="form-label">{{ __db('event') }}: <span class="text-red-600">*</span></label>
                    <select name="event_id" class="select2 p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0" required>
                        <option value="">{{ __db('select_event') }}</option>
                        @foreach ($events as $event)
                            <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                {{ $event->name_en }}
                            </option>
                        @endforeach
                    </select>
                    @error('event_id')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-12 md:col-span-3">
                    <label class="form-label">{{ __db('title_en') }}: <span class="text-red-600">*</span></label>
                    <input type="text" name="title_en" 
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        value="{{ old('title_en') }}" required>
                    @error('title_en')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-12 md:col-span-3">
                    <label class="form-label">{{ __db('title_ar') }}: <span class="text-red-600">*</span></label>
                    <input type="text" name="title_ar" dir="rtl"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        value="{{ old('title_ar') }}" required>
                    @error('title_ar')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-12">
                    <label class="form-label">{{ __db('floor_plan_files') }}: <span class="text-red-600">*</span></label>
                    <input type="file" name="floor_plan_files[]" multiple accept=".pdf"
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 p-3"
                        required>
                    <p class="mt-1 text-sm text-gray-500">{{ __db('floor_plan_files_help') }}</p>
                    @error('floor_plan_files.*')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror
                    @error('floor_plan_files')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-12 mt-6">
                    <button type="submit"
                        class="btn !bg-[#B68A35] text-white rounded-lg py-3 px-6 font-semibold hover:shadow-lg transition">
                        {{ __db('save_floor_plan') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection