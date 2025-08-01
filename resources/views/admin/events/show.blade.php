@extends('layouts.admin_account', ['title' => __db('event_details')])

@section('content')
<div class="dashboard-main-body ">
    <div class="flex flex-wrap items-center justify-between gap-2 mb-6 mb-10">
        <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('event_details') }}</h2>
        <a href="{{ Session::has('events_last_url') ? Session::get('events_last_url') : route('events.index') }}"
            id="add-attachment-btn"
            class="float-left btn text-md mb-[-10px] border !border-[#B68A35] !text-[#B68A35] rounded-lg h-12">
            <svg class="w-6 h-6 me-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m14 0-4 4m4-4-4-4" />
            </svg>
            <span>{{ __db('back') }}</span>
        </a>
    </div>

    <div class=" mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        {{-- Event Header --}}
        <div class="bg-white shadow rounded-xl p-6">
            <h2 class="text-2xl font-bold text-primary-700 mb-6">
                {{ __db('event_information') }}
                @if ($event->is_default)
                <span
                    class="inline-block rounded bg-green-500 px-2 py-1 text-xs font-semibold text-white">Default</span>
                @endif
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <div class="bg-white rounded-lg  p-6 space-y-4 max-w-md">

                        <div class="space-y-3 text-gray-700">
                            <div class="flex justify-between">
                                <span class="font-semibold">{{ __db('name') }} (EN) :</span>
                                <span>{{ $event->name_en }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-semibold">{{ __db('name') }} (AR) :</span>
                                <span>{{ $event->name_ar }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-semibold">{{ __db('code') }} :</span>
                                <span>{{ $event->code }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-semibold">{{ __db('start_date') }} :</span>
                                <span>{{ $event->start_date }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-semibold">{{ __db('end_date') }} :</span>
                                <span>{{ $event->end_date }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-semibold">{{ __db('status') }} :</span>
                                @if ($event->status)
                                <span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded">{{
                                    __db('completed') }}</span>
                                @else
                                <span class="px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded">{{
                                    __db('not_completed') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="space-y-2">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <!-- Logo Card -->
                        <div
                            class="bg-gray-50 border border-gray-200 rounded-xl shadow-sm p-4 flex flex-col items-center text-center">
                            <div class="text-sm font-semibold text-gray-700 mb-2">
                                {{ __db('logo') }}
                            </div>
                            <div class="w-full h-32 flex items-center justify-center rounded overflow-hidden">
                                <img src="{{ getUploadedImage($event->logo) }}" alt="Logo"
                                    class="max-w-full max-h-full object-contain">
                            </div>
                        </div>

                        <!-- Image Card -->
                        @if ($event->image)
                        <div
                            class="bg-gray-50 border border-gray-200 rounded-xl shadow-sm p-4 flex flex-col items-center text-center">
                            <div class="text-sm font-semibold text-gray-700 mb-2">
                                {{ __db('image') }}
                            </div>
                            <div class="w-full h-32 flex items-center justify-center rounded overflow-hidden">
                                <img src="{{ getUploadedImage($event->image) }}" alt="Image"
                                    class="max-w-full max-h-full object-contain">
                            </div>
                        </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        @foreach($allModules as $module)
        @php
        $users = $assignedUsers[$module] ?? collect(); // assigned users or empty collection
        @endphp

        <div class="bg-white rounded-lg shadow p-6 mb-10">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-2xl font-bold text-gray-900 border-b border-gray-200 pb-2">
                    {{ __db($module) }} {{ __db('module') }}
                </h3>

                @if ($event->status === 0)
                    <button class="openAssignModalBtn bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                        data-module="{{ $module }}">
                        Assign Users to {{ ucfirst($module) }}
                    </button>
                @endif
            </div>

            @if($users->count())
            <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                @foreach($users as $row)
                <div class="user-card border border-gray-200 rounded-lg p-4 flex flex-col items-center text-center hover:shadow-lg transition-shadow relative"
                    data-id="{{ $row->id }}">
                    <div
                        class="w-10 h-10 flex items-center justify-center rounded-full bg-gradient-to-tr from-[#aa853e40] to-[#B68A35] text-black text-xl font-extrabold shadow-md">
                        {{ strtoupper(substr($row->user->name ?? '?', 0, 1)) }}
                    </div>

                    <h4 class="text-lg font-semibold text-gray-900 text-center truncate w-full">{{ $row->user->name ??
                        '-' }}</h4>
                    <p class="text-sm text-gray-600 mt-1 truncate w-full text-center">{{ $row->user->email ?? '-' }}</p>
                    <span
                        class="mt-2 inline-block bg-indigo-100 text-indigo-800 text-xs font-semibold px-3 py-1 rounded-full">
                        {{ $row->role->name ?? '-' }}
                    </span>
                    @if ($event->status === 0)
                        <button type="button"
                            class="unassign-btn absolute top-2 right-2 text-red-500 hover:text-red-700 text-sm"
                            data-id="{{ $row->id }}" title="{{ __db('unassign') }}">
                            ✖
                        </button>
                    @endif
                </div>
                @endforeach
            </div>
            @else
            <p class="text-center text-gray-500 italic">{{ __db('no_users_assigned') }}</p>
            @endif
        </div>

        <!-- Your modal here as before -->
        <div class="assignUserModal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50"
            data-module="{{ $module }}">
            <!-- Modal Content -->
            <div class="bg-white rounded-lg shadow-lg max-w-lg w-full p-6 relative">
                <button
                    class="closeAssignModalBtn absolute top-3 right-3 text-gray-600 hover:text-gray-900 text-xl font-bold">&times;</button>

                <h2 class="text-xl font-semibold mb-4">Assign Users to {{ ucfirst($module) }} Module</h2>

                <form method="POST" action="{{ route('events.assignUsers', $event->id) }}" class="assignUsersForm"
                    data-module="{{ $module }}">
                    @csrf
                    <input type="hidden" name="module" value="{{ $module }}">

                    <label class="block text-sm mb-1 text-gray-600 font-medium" for="user_ids_{{ $module }}">Select
                        Users</label>
                    <select id="user_ids_{{ $module }}" name="user_ids[]" multiple required
                        class="w-full rounded border border-gray-300 p-2 mb-4" style="min-height: 120px;">
                        @foreach ($availableUsers as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mb-4">Hold Ctrl (Cmd) to select multiple users.</p>

                    <label class="block text-sm mb-1 text-gray-600 font-medium" for="role_id_{{ $module }}">Select
                        Role</label>
                    <select id="role_id_{{ $module }}" name="role_id" required
                        class="w-full rounded border border-gray-300 p-2 mb-6">
                        <option value="">Select role</option>
                        @foreach ($roles as $role)
                        @if ($role->module === $module && $role->is_active)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endif
                        @endforeach
                    </select>

                    <div class="text-right">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Assign
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @endforeach

        {{-- @if (!empty($assignedUsers))
        @foreach($assignedUsers as $module => $users)
        <div class="bg-white rounded-lg shadow p-6 mb-10">
            <button class="openAssignModalBtn bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mb-4"
                data-module="{{ $module }}">
                Assign Users to {{ ucfirst($module) }}
            </button>


            <h3 class="text-2xl font-bold text-gray-900 mb-6 border-b border-gray-200 pb-2">
                {{ __db($module) }} {{ __db('module') }}
            </h3>
            @if($users->count())
            <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                @foreach($users as $row)
                <div class="user-card border border-gray-200 rounded-lg p-4 flex flex-col items-center text-center hover:shadow-lg transition-shadow relative"
                    data-id="{{ $row->id }}">
                    <div
                        class="w-10 h-10 flex items-center justify-center rounded-full bg-gradient-to-tr from-[#aa853e40] to-[#B68A35] text-black text-xl font-extrabold shadow-md">
                        {{ strtoupper(substr($row->user->name ?? '?', 0, 1)) }}
                    </div>

                    <h4 class="text-lg font-semibold text-gray-900 text-center truncate w-full">{{ $row->user->name ??
                        '-' }}</h4>
                    <p class="text-sm text-gray-600 mt-1 truncate w-full text-center">{{ $row->user->email ?? '-' }}</p>
                    <span
                        class="mt-2 inline-block bg-indigo-100 text-indigo-800 text-xs font-semibold px-3 py-1 rounded-full">
                        {{ $row->role->name ?? '-' }}
                    </span>

                    <button type="button"
                        class="unassign-btn absolute top-2 right-2 text-red-500 hover:text-red-700 text-sm"
                        data-id="{{ $row->id }}" title="{{ __db('unassign') }}">
                        ✖
                    </button>
                </div>
                @endforeach

            </div>
            @else
            <p class="text-center text-gray-500 italic">{{ __db('no_users_assigned') }}</p>
            @endif
        </div>


        <div class="assignUserModal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50"
            data-module="{{ $module }}">

            <div class="bg-white rounded-lg shadow-lg max-w-lg w-full p-6 relative">
                <button
                    class="closeAssignModalBtn absolute top-3 right-3 text-gray-600 hover:text-gray-900 text-xl font-bold">&times;</button>

                <h2 class="text-xl font-semibold mb-4">Assign Users to {{ ucfirst($module) }} Module</h2>

                <form method="POST" action="{{ route('events.assignUsers', $event->id) }}" class="assignUsersForm"
                    data-module="{{ $module }}">
                    @csrf
                    <input type="hidden" name="module" value="{{ $module }}">

                    <label class="block text-sm mb-1 text-gray-600 font-medium" for="user_ids_{{ $module }}">Select
                        Users</label>
                    <select id="user_ids_{{ $module }}" name="user_ids[]" multiple required
                        class="w-full rounded border border-gray-300 p-2 mb-4" style="min-height: 120px;">
                        @foreach ($availableUsers as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mb-4">Hold Ctrl (Cmd) to select multiple users.</p>

                    <label class="block text-sm mb-1 text-gray-600 font-medium" for="role_id_{{ $module }}">Select
                        Role</label>
                    <select id="role_id_{{ $module }}" name="role_id" required
                        class="w-full rounded border border-gray-300 p-2 mb-6">
                        <option value="">Select role</option>
                        @foreach ($roles as $role)
                        @if ($role->module === $module && $role->is_active)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endif
                        @endforeach
                    </select>

                    <div class="text-right">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Assign
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endforeach
        @endif --}}

    </div>


</div>
@endsection

@section('style')
<style>

</style>
@endsection

@section('script')
<script>
    document.addEventListener("DOMContentLoaded", function () {

        const unassignUrlTemplate = "{{ route('events.unassignUser', ['event' => '__EVENT__', 'assigned' => '__USER__']) }}";
        

        document.querySelectorAll('.unassign-btn').forEach(button => {
            button.addEventListener('click', function () {
                const assignedId = this.dataset.id;
                const eventId = "{{ $event->id }}"; // Pass this from your controller
                const routeUrl = unassignUrlTemplate.replace('__EVENT__', eventId).replace('__USER__', assignedId);
                
                Swal.fire({
                    title: "{{ __db('are_you_sure') }}",
                    text: "{{ __db('unassign_confirm_msg') }}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: "{{ __db('yes') }}"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: routeUrl,
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                'Accept': 'application/json'
                            },
                            success: function (data) {
                                toastr.success("{{ __db('user_unassigned') }}");
                                $(`.user-card[data-id="${assignedId}"]`).remove();
                                setTimeout(function() {
                                    window.location.reload();
                                }, 2000);
                            },
                            error: function (xhr) {
                                toastr.error("{{ __db('something_went_wrong') }}");
                            }
                        });
                    }
                });
            });
        });


        // Open modal buttons
        document.querySelectorAll('.openAssignModalBtn').forEach(btn => {
            btn.addEventListener('click', function () {
                const module = btn.getAttribute('data-module');
                const modal = document.querySelector(`.assignUserModal[data-module="${module}"]`);
                if(modal) {
                    modal.classList.remove('hidden');
                }
            });
        });

        // Close modal buttons
        document.querySelectorAll('.closeAssignModalBtn').forEach(btn => {
            btn.addEventListener('click', function () {
                const modal = btn.closest('.assignUserModal');
                if(modal) {
                    modal.classList.add('hidden');
                }
            });
        });

        // Close modal if clicking outside modal content
        document.querySelectorAll('.assignUserModal').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if(e.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        });

        // AJAX submit with SweetAlert confirmation for all forms
        document.querySelectorAll('.assignUsersForm').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Assign selected users to this module?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, assign!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let formData = new FormData(form);

                        fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            },
                            body: formData
                        })
                        .then(response => {
                            if (!response.ok) throw new Error("Failed to assign users.");
                            return response.json();
                        })
                        .then(data => {
                    
                            toastr.success('Users have been assigned successfully.');
                            // Close modal
                            form.closest('.assignUserModal').classList.add('hidden');
                            setTimeout(function() {
                                window.location.reload();
                            }, 2000);
                        })
                        .catch(error => {
                            toastr.error("{{ __db('something_went_wrong') }}");
                        });
                    }
                });
            });
        });
    });
</script>
@endsection