@extends('layouts.admin_account', ['title' => __db('edit_floor_plan')])

@section('content')
    <x-back-btn title="" back-url="{{ route('floor-plans.index') }}" />

    <div class="bg-white h-full w-full rounded-lg border-0 p-6">
        <h2 class="font-semibold text-2xl mb-6">{{ __db('edit_floor_plan') }}</h2>

        @if ($errors->any())
            <div class="mb-6 p-4 border border-red-400 bg-red-100 text-red-700 rounded">
                <h4 class="font-semibold mb-2">{{ __db('please_fix_the_following_errors') }}</h4>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('floor-plans.update', $floorPlan->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-12 gap-5">
                <div class="col-span-12 md:col-span-3">
                    <label class="form-label">{{ __db('event') }}: <span class="text-red-600">*</span></label>
                    <select name="event_id"
                        class="select2 p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        required>
                        <option value="">{{ __db('select_event') }}</option>
                        @foreach ($events as $event)
                            <option value="{{ $event->id }}"
                                {{ old('event_id', $floorPlan->event_id) == $event->id ? 'selected' : '' }}>
                                {{ $event->name_en }}
                            </option>
                        @endforeach
                    </select>
                    @error('event_id')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-12 md:col-span-3">
                    <label class="form-label">{{ __db('floor_plan_title_en') }}: <span class="text-red-600">*</span></label>
                    <input type="text" name="title_en"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        value="{{ old('title_en', $floorPlan->title_en) }}" required>
                    @error('title_en')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-12 md:col-span-3">
                    <label class="form-label">{{ __db('floor_plan_title_ar') }}: <span class="text-red-600">*</span></label>
                    <input type="text" name="title_ar" dir="rtl"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        value="{{ old('title_ar', $floorPlan->title_ar) }}" required>
                    @error('title_ar')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-12">
                    <label class="form-label font-medium">{{ __db('floor_plan_files') }}:</label>
                    <div class="border rounded-lg p-4">
                        @php
                            $existing = old('existing_file_paths', $floorPlan->file_objects ?? []);
                        @endphp
                        @if (count($existing) > 0)
                            <div class="grid grid-cols-1 md:grid-cols-1 gap-4" id="files-container">
                                @foreach ($existing as $index => $fileObj)
                                    @php
                                        $path = is_string($fileObj) ? $fileObj : ($fileObj['path'] ?? $fileObj);
                                        $title = is_string($fileObj) ? basename($fileObj) : ($fileObj['title'] ?? basename($fileObj['path']));
                                    @endphp
                                    <div class="p-3 bg-gray-50 rounded file-item flex flex-col md:flex-row gap-4"
                                        data-index="{{ $index }}">
                                        <div class="flex items-center flex-1">
                                            <svg class="w-5 h-5 text-red-500 mr-2" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                <path fill-rule="evenodd"
                                                    d="M3 3a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H3Zm1.741 4.47L7.5 10.724 11.259 7l1.2 1.2-5.018 4.542a.6.6 0 0 1-.844-.001L2.542 8.2l1.2-1.2Zm2.434 5.745L11.259 18l-1.2 1.2-5.018-4.542a.6.6 0 0 1-.001-.844L9.584 8.8l1.2 1.2Zm7.671-1.2-1.2-1.2 5.018-4.542a.6.6 0 0 1 .844-.001L21.458 15.8l-1.2 1.2-4.046-4.045Zm-1.2 6.47-1.2-1.2 4.046-4.045 1.2 1.2-4.045 4.045Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <div class="flex-1">
                                                <div class="text-sm text-gray-600 font-medium">{{ $title }}</div>
                                                <div class="text-xs text-gray-500">{{ basename($path) }}</div>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <a href="{{ asset('storage/' . $path) }}" target="_blank"
                                                class="text-[#B68A35] hover:underline">
                                                {{ __db('view') }}
                                            </a>

                                            <button type="button" class="delete-file-btn text-red-600 hover:text-red-800"
                                                data-index="{{ $index }}">
                                                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                                </svg>
                                            </button>
                                        </div>

                                        <input type="hidden" name="existing_file_paths[{{ $index }}][path]" value="{{ $path }}"
                                            class="existing-file-input" data-index="{{ $index }}">
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __db('title_en') }}</label>
                                                <input type="text" name="existing_file_paths[{{ $index }}][title_en]" 
                                                    value="{{ is_array($fileObj) && isset($fileObj['title_en']) ? $fileObj['title_en'] : $title }}"
                                                    class="p-2 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                                                    placeholder="{{ __db('enter_file_title') }}">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __db('title_ar') }}</label>
                                                <input type="text" name="existing_file_paths[{{ $index }}][title_ar]" dir="rtl"
                                                    value="{{ is_array($fileObj) && isset($fileObj['title_ar']) ? $fileObj['title_ar'] : '' }}"
                                                    class="p-2 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                                                    placeholder="{{ __db('enter_file_title_ar') }}">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">{{ __db('no_files_uploaded') }}</p>
                        @endif
                    </div>
                </div>

                <div class="col-span-12">
                    <div class="mt-4 pt-4 border-t">
                        <label class="form-label font-medium">{{ __db('add_new_files') }}:</label>
                        
                        <div id="new-files-container">
                            <div class="new-file-group mb-4 p-4 border rounded-lg bg-gray-50">
                                <div class="flex flex-col md:flex-row gap-4">
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __db('file') }}</label>
                                        <input type="file" name="new_floor_plan_files[]"
                                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white p-2">
                                    </div>
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __db('title_en') }}</label>
                                        <input type="text" name="new_file_titles_en[]" 
                                            class="p-2 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                                            placeholder="{{ __db('enter_file_title') }}">
                                    </div>
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __db('title_ar') }}</label>
                                        <input type="text" name="new_file_titles_ar[]" dir="rtl"
                                            class="p-2 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                                            placeholder="{{ __db('enter_file_title_ar') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <button type="button" id="add-more-new-files" 
                                class="mt-2 px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                            {{ __db('add_more_files') }}
                        </button>
                        
                        <p class="text-sm text-gray-500 mt-1">{{ __db('upload_new_floor_plan_files_msg') }}</p>
                    </div>
                </div>

                <div class="col-span-12 mt-6">
                    <button type="submit"
                        class="btn !bg-[#B68A35] text-white rounded-lg py-3 px-6 font-semibold hover:shadow-lg transition">
                        {{ __db('update_floor_plan') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('files-container');
            const newFilesContainer = document.getElementById('new-files-container');
            const addMoreNewFilesBtn = document.getElementById('add-more-new-files');
            
            if (container) {
                container.addEventListener('click', function(event) {
                    if (event.target.closest('.delete-file-btn')) {
                        const btn = event.target.closest('.delete-file-btn');
                        const index = btn.getAttribute('data-index');
                        
                        Swal.fire({
                            title: '{{ __db('are_you_sure') }}',
                            text: "{{ __db('delete_file_confirm_msg') }}",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: '{{ __db('yes_delete') }}',
                            cancelButtonText: '{{ __db('cancel') }}'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const fileItem = btn.closest('.file-item');
                                if (fileItem) {
                                    fileItem.style.display = 'none';
                                    // Hide the inputs but keep them in the form so the backend knows they're removed
                                    fileItem.querySelectorAll('input').forEach(input => {
                                        input.type = 'hidden';
                                        input.disabled = true;
                                    });
                                }
                            }
                        });
                    }
                });
            }
            
            // Handle adding more new files
            if (addMoreNewFilesBtn && newFilesContainer) {
                let newFileIndex = 1;
                
                addMoreNewFilesBtn.addEventListener('click', function() {
                    const newFileGroup = document.createElement('div');
                    newFileGroup.className = 'new-file-group mb-4 p-4 border rounded-lg bg-gray-50';
                    newFileGroup.innerHTML = `
                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __db('file') }}</label>
                                <input type="file" name="new_floor_plan_files[]"
                                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white p-2">
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __db('title_en') }}</label>
                                <input type="text" name="new_file_titles_en[]" 
                                    class="p-2 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                                    placeholder="{{ __db('enter_file_title') }}">
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __db('title_ar') }}</label>
                                <input type="text" name="new_file_titles_ar[]" dir="rtl"
                                    class="p-2 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                                    placeholder="{{ __db('enter_file_title_ar') }}">
                            </div>
                            <div class="flex items-end">
                                <button type="button" class="remove-new-file px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                                    {{ __db('remove') }}
                                </button>
                            </div>
                        </div>
                    `;
                    
                    newFilesContainer.appendChild(newFileGroup);
                    
                    // Add event listener to the new remove button
                    newFileGroup.querySelector('.remove-new-file').addEventListener('click', function() {
                        if (newFilesContainer.children.length > 1) {
                            newFileGroup.remove();
                        } else {
                            alert('{{ __db('at_least_one_file_required') }}');
                        }
                    });
                    
                    newFileIndex++;
                });
                
                // Add event listeners to existing remove buttons
                document.querySelectorAll('.remove-new-file').forEach(button => {
                    button.addEventListener('click', function() {
                        const newFileGroup = this.closest('.new-file-group');
                        if (newFilesContainer.children.length > 1) {
                            newFileGroup.remove();
                        } else {
                            alert('{{ __db('at_least_one_file_required') }}');
                        }
                    });
                });
            }
        });
    </script>
@endsection
