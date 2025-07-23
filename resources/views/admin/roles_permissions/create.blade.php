@extends('layouts.admin_account', ['title' => __db('create_new_role')])

@section('content')
<div class="dashboard-main-body ">
    <div class="flex flex-wrap items-center justify-between gap-2 mb-6 mb-10">
        <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('create_new_role') }}</h2>
        <a href="{{ route('roles.index') }}" id="add-attachment-btn"
            class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-2 px-3">
            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 12H5m14 0-4 4m4-4-4-4" />
            </svg>
            <span>{{ __db('back') }}</span>
        </a>
    </div>

    <form action="{{ route('roles.store') }}" method="POST" autocomplete="off">
        @csrf
        <div class="bg-white rounded-lg p-6 mb-10 mt-4">
            <div class="bg-white p-6 grid grid-cols-1 gap-5 mb-4">
                <!-- Date & Time -->
                <div>
                    <label class="form-label block mb-1 text-gray-700 font-medium text-base">{{ __db('role_name') }} <span class="text-danger">*</span></label>
                    <input  placeholder="{{ __db('enter') }}" value="{{ old('name') }}" id="name" name="name" type="text" class="p-3 rounded-lg w-1/2 border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                    @error('name')
                        <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="" >
                    <label class="form-label block text-gray-700 font-semibold text-base">{{ __db('permissions') }} <span class="text-danger">*</span></label>
                    <div class="w-full">
                        @foreach ($permission as $parent)
                            <div class="w-full flex flex-col mt-4">
                                <div class="permission-group">
                                    <!-- Parent Permission -->
                                    <label class="flex items-center text-sm gap-2 font-semibold text-gray-800">
                                        <input type="checkbox" name="permissions[]"
                                            value="{{ $parent->name }}"
                                            class="parent-checkbox accent-yellow-600 h-4 w-4"
                                            data-parent="{{ $parent->name }}">
                                        {{ $parent->title }}
                                    </label>

                                    <!-- Child Permissions (inline) -->
                                    <div class="flex flex-wrap gap-4 mt-2 ml-6">
                                        @foreach ($parent->children as $child)
                                            <label class="flex items-center  text-sm gap-2 text-gray-700">
                                                <input type="checkbox" name="permissions[]"
                                                    value="{{ $child->name }}"
                                                    class="child-checkbox accent-yellow-500 h-4 w-4"
                                                    data-parent="{{ $parent->name }}">
                                                {{ $child->title }}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach

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
        // When a child is checked/unchecked, update parent
        $('.child-checkbox').on('change', function() {
            let parentCheckbox = $('input[value="' + $(this).data('parent') + '"]');
            let allChildren = $('.child-checkbox[data-parent="' + $(this).data('parent') + '"]');
            let anyChecked = allChildren.is(':checked');

            parentCheckbox.prop('checked', anyChecked); // ✅ Check parent if any child is checked
        });

        // When a parent is checked/unchecked, update all children
        $('.parent-checkbox').on('change', function() {
            let allChildren = $('.child-checkbox[data-parent="' + $(this).data('parent') + '"]');
            allChildren.prop('checked', $(this).is(':checked')); // ✅ Check/uncheck all children
        });
    });
</script>
@endsection