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
                    
                    <div id="file-inputs-container">
                        <div class="file-input-group mb-4 p-4 border rounded-lg bg-gray-50">
                            <div class="flex flex-col md:flex-row gap-4">
                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __db('file') }}</label>
                                    <input type="file" name="floor_plan_files[]" 
                                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white p-2"
                                        required>
                                </div>
                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __db('title_en') }}</label>
                                    <input type="text" name="file_titles_en[]" 
                                        class="p-2 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                                        placeholder="{{ __db('enter_file_title') }}">
                                </div>
                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __db('title_ar') }}</label>
                                    <input type="text" name="file_titles_ar[]" dir="rtl"
                                        class="p-2 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                                        placeholder="{{ __db('enter_file_title_ar') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" id="add-more-files" 
                            class="mt-2 px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                        {{ __db('add_more_files') }}
                    </button>
                    
                    <p class="mt-2 text-sm text-gray-500">{{ __db('floor_plan_files_help') }}</p>
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

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('file-inputs-container');
            const addButton = document.getElementById('add-more-files');
            
            let fileIndex = 1;
            
            addButton.addEventListener('click', function() {
                const fileInputGroup = document.createElement('div');
                fileInputGroup.className = 'file-input-group mb-4 p-4 border rounded-lg bg-gray-50';
                fileInputGroup.innerHTML = `
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __db('file') }}</label>
                            <input type="file" name="floor_plan_files[]" 
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white p-2">
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __db('title_en') }}</label>
                            <input type="text" name="file_titles_en[]" 
                                class="p-2 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                                placeholder="{{ __db('enter_file_title') }}">
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __db('title_ar') }}</label>
                            <input type="text" name="file_titles_ar[]" dir="rtl"
                                class="p-2 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                                placeholder="{{ __db('enter_file_title_ar') }}">
                        </div>
                        <div class="flex items-end">
                            <button type="button" class="remove-file-input px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                                {{ __db('remove') }}
                            </button>
                        </div>
                    </div>
                `;
                
                container.appendChild(fileInputGroup);
                
                // Add event listener to the new remove button
                fileInputGroup.querySelector('.remove-file-input').addEventListener('click', function() {
                    if (container.children.length > 1) {
                        fileInputGroup.remove();
                    } else {
                        alert('{{ __db('at_least_one_file_required') }}');
                    }
                });
                
                fileIndex++;
            });
            
            // Add event listeners to existing remove buttons
            document.querySelectorAll('.remove-file-input').forEach(button => {
                button.addEventListener('click', function() {
                    const fileInputGroup = this.closest('.file-input-group');
                    if (container.children.length > 1) {
                        fileInputGroup.remove();
                    } else {
                        alert('{{ __db('at_least_one_file_required') }}');
                    }
                });
            });
        });
    </script>
@endsection