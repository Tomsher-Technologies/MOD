@extends('layouts.admin_account', ['title' => __db('edit_role_details')])

@section('content')
<div class="dashboard-main-body ">
    <div class="flex flex-wrap items-center justify-between gap-2 mb-6 mb-10">
        <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('edit_role_details') }}</h2>
        <a href="{{ route('roles.index') }}" id="add-attachment-btn"
            class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-2 px-3">
            <svg class="w-6 h-6 me-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"  stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19 12H5m14 0-4 4m4-4-4-4" />
            </svg>
            <span>{{ __db('back') }}</span>
        </a>
    </div>

    <form action="{{ route('roles.update', $role->id) }}" method="POST" autocomplete="off">
        @csrf
        @method('PATCH')
        <div class="bg-white rounded-lg p-6 mb-10 mt-4">
            <div class="bg-white p-6 grid grid-cols-12 gap-5 mb-4">
                
                <div class="col-span-3">
                    <label class="form-label block mb-1 text-gray-700 font-medium text-base">{{ __db('module') }} <span class="text-red-600">*</span></label>
                    <select name="module" id="moduleSelect" class="w-full p-3 rounded-lg border border-neutral-300 text-sm text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option value="admin" {{ (old('module', $role->module) == 'admin') ? 'selected' : '' }}>Admin</option>
                        <option value="delegate" {{ (old('module', $role->module) == 'delegate') ? 'selected' : '' }}>Delegate</option>
                        <option value="escort" {{ (old('module', $role->module) == 'escort') ? 'selected' : '' }}>Escort</option>
                        <option value="driver" {{ (old('module', $role->module) == 'driver') ? 'selected' : '' }}>Driver</option>
                        <option value="hotel" {{ (old('module', $role->module) == 'hotel') ? 'selected' : '' }}>Hotel</option>
                    </select>
                    @error('module')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-3">
                    <label class="form-label block mb-1 text-gray-700 font-medium text-base">{{ __db('role_name') }} <span class="text-danger">*</span></label>
                    <input  placeholder="{{ __db('enter') }}" value="{{ old('name', $role->name) }}" id="name" name="name" type="text" class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                    @error('name')
                        <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-12" >
                    <label class="form-label block text-gray-700 font-semibold text-base">{{ __db('permissions') }} <span class="text-danger">*</span></label>
                    <div class="w-full">
                        <div id="permissionContainer" class="mt-4">
                            @foreach ($permission as $parent)
                                @php
                                    $selected = '';
                                    if (in_array($parent->id, old('permission', $rolePermissions))) {
                                        $selected = 'checked';
                                    }
                                @endphp
                                <div class="w-full flex flex-col mt-4">
                                    <div class="permission-group">
                                        <!-- Parent Permission -->
                                        <label class="flex items-center text-sm gap-2 font-semibold text-gray-800">
                                            <input type="checkbox" name="permissions[]"
                                                value="{{ $parent->name }}"
                                                class="parent-checkbox accent-yellow-600 h-4 w-4"
                                                data-parent="{{ $parent->name }}" {{ $selected }}>
                                            {{ $parent->title }}
                                        </label>

                                        <!-- Child Permissions (inline) -->
                                        <div class="flex flex-wrap gap-4 mt-2 ml-6">
                                            @foreach ($parent->children as $child)
                                                @php
                                                    $selectedChild = '';
                                                    if (in_array($child->id, old('permission', $rolePermissions))) {
                                                        $selectedChild = 'checked';
                                                    }
                                                @endphp
                                                <label class="flex items-center  text-sm gap-2 text-gray-700">
                                                    <input type="checkbox" name="permissions[]"
                                                        value="{{ $child->name }}"
                                                        class="child-checkbox accent-yellow-500 h-4 w-4"
                                                        data-parent="{{ $parent->name }}"  {{ $selectedChild }}>
                                                    {{ $child->title }}
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('permissions')
                            <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
            </div>
            <div class="flex justify-start items-center gap-5">
                <button type="submit" class="btn text-md  !bg-[#B68A35] text-white rounded-lg h-12 mr-4">{{ __db('submit') }}</button>
                <a href="{{ route('roles.index') }}" class="btn text-md  !bg-[#637a85] border !border-[#637a85] !text-[#fff] rounded-lg h-12 mr-1">{{ __db('cancel') }}</a>
            </div>
        </div>
       
    </form>
</div>
@endsection

@section('style')
<style>
    
</style>
@endsection

@section('script')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        $(document).on('change', '.child-checkbox', function () {
            let parentName = $(this).data('parent');
            let parentCheckbox = $('input.parent-checkbox[value="' + parentName + '"]');
            let allChildren = $('.child-checkbox[data-parent="' + parentName + '"]');
            let anyChecked = allChildren.is(':checked');
            parentCheckbox.prop('checked', anyChecked);
        });

        $(document).on('change', '.parent-checkbox', function () {
            let parentName = $(this).val();
            let allChildren = $('.child-checkbox[data-parent="' + parentName + '"]');
            allChildren.prop('checked', $(this).is(':checked'));
        });

        $('#moduleSelect').on('change', function () {
            const module = $(this).val();
            let routeUrl = `{{ route('roles.edit-permissions-by-module', ['module' => '___module___']) }}`.replace('___module___', module);

            $.ajax({
                url: routeUrl,
                method: 'GET',
                data: { module },
                success: function (response) {
                    $('#permissionContainer').html(response.html); 
                },
                error: function () {
                    toastr.error('Failed to load permissions.');
                }
            });
        });
    });
</script>
@endsection