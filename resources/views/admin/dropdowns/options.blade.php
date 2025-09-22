@extends('layouts.admin_account',['title' =>  $dropdown->name])

@section('content')

<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div class="justify-between">
        <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('options') }}: {{ $dropdown->name }}</h2>
    </div>

    <div class="flex items-center">
        @directCan('add_dropdown_options')
            <button data-modal-target="add-option" data-modal-toggle="add-option" class="ml-2 btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-2 px-3">
                <svg class="w-6 h-6 text-white me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                    height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 12h14m-7 7V5"/>
                </svg>
                <span>{{ __db('add_option') }}</span>
            </button>
        @enddirectCan

        <a href="{{ route('dropdowns.index') }}" class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-2 px-3">
            <svg class="w-6 h-6 me-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"  stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19 12H5m14 0-4 4m4-4-4-4" />
            </svg>
            {{ __db('back') }}
        </a>

    </div>
   
</div>

<div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
    <div class="xl:col-span-12 h-full">
        <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
           <div class="flex items-center justify-between gap-12 mb-4">
                <form class="w-[80%]" action="{{ route('dropdowns.options.show', $dropdown) }}" method="GET">
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-3 text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                        </div>
                        <div class="flex">
                            <input type="text" name="search"
                                class="block w-[40%] p-2.5 !ps-10 text-secondary-light text-sm !border-[#d1d5db] rounded-lg"
                                placeholder="{{ __db('search') }}" value="{{ request('search') }}">
                            
                            <select name="status" class="block w-[35%] mr-2 p-2.5 !ps-10 text-secondary-light text-sm !border-[#d1d5db] rounded-lg">
                                <option value="">{{ __db('select_status') }}</option>
                                <option value="1" {{ request()->status == 1 ? 'selected' : '' }}>{{ __db('active') }}
                                </option>
                                <option value="2" {{ request()->status == 2 ? 'selected' : '' }}>{{ __db('inactive') }}
                                </option>
                            </select>

                            <div class="w-[25%] mr-2">
                                <a href="{{ route('dropdowns.options.show', $dropdown) }}"  class="float-right   bottom-[3px] border !border-[#B68A35] !text-[#B68A35] font-medium rounded-lg text-sm px-4 py-2">
                                    {{ __db('reset') }}</a>
                        
                                <button type="submit" class="!text-[#5D471D] mr-2  end-[3px] bottom-[3px] !bg-[#E6D7A2] hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">{{ __db('search') }}</button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <table class="table-auto mb-0 !border-[#F9F7ED] w-full">
                <thead>
                    <tr class="text-[13px]">
                        <th class="p-3 !bg-[#B68A35] text-center text-white">#</th>
                        <th class="p-3 !bg-[#B68A35] text-center text-white">{{ __db('name') }}</th>
                        <th class="p-3 !bg-[#B68A35] text-center text-white">{{ __db('code') }}</th>
                        <th class="p-3 !bg-[#B68A35] text-center text-white">{{ __db('sort_order') }}</th>
                        <th class="p-3 !bg-[#B68A35] text-center text-white">{{ __db('status') }}</th>
                        <th class="p-3 !bg-[#B68A35] text-center text-white">{{ __db('action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    
                    @forelse($options as $key => $option)
                        <tr class="odd:bg-[#F9F7ED] align-[middle] text-[12px]" >
                            <td class="px-4 py-3 text-center" dir="ltr">
                                {{ ($key+1) }}
                            </td>
                            <td class="px-4 py-3 text-black text-center">{{ $option->value }}</td>
                            <td class="px-4 py-3 text-black text-center">{{ $option->code ?? '-' }}</td>
                            <td class="px-4 py-3 text-black text-center">{{ $option->sort_order }}</td>
                            <td class="px-4 py-3 text-center">
                                @directCan('edit_dropdown_options')
                                    <div class=" items-center">
                                        <label for="switch-{{ $key }}" class="relative inline-block w-11 h-6">
                                            <input type="checkbox" id="switch-{{ $key }}" onchange="update_status(this)" value="{{ $option->id }}"
                                                class="sr-only peer" {{ $option->status == 1 ? 'checked' : '' }} />

                                            <div class="block bg-gray-300 peer-checked:bg-[#009448] w-11 h-6 rounded-full transition"></div>
                                            <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition peer-checked:translate-x-5"></div>
                                        </label>
                                    </div>
                                @enddirectCan
                            </td>

                            <td class="text-center">
                                @directCan('edit_dropdown_options')
                                    <button data-modal-target="edit-option-{{ $option->id }}" data-modal-toggle="edit-option-{{ $option->id }}" class="w-8 h-8 bg-[#FBF3D6] text-primary-600 dark:text-primary-400 rounded-full inline-flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512">
                                            <path
                                                d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z"
                                                fill="#B68A35"></path>
                                        </svg>
                                    </button>
                                @enddirectCan
                            </td>

                            <div id="edit-option-{{ $option->id }}" tabindex="-1" aria-hidden="true"
                                class="hidden fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
                                <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
                                 

                                    <h2 class="text-xl font-semibold mb-4">{{ __db('edit') . ' ' . __db('option') }}</h2>

                                    <form class="" method="POST" action="{{ route('dropdowns.options.update', $option) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-4">
                            <label class="block mb-2 text-sm font-medium text-gray-900 ">{{ __db('value') }} ({{ __db('english') }})</label>
                            <input type="text" name="value" value="{{ $option->getRawOriginal('value') }}" required
                                class="w-full border border-gray-300 rounded p-2">
                        </div>
                        <div class="mb-4">
                            <label class="block mb-2 text-sm font-medium text-gray-900 ">{{ __db('value') }} ({{ __db('arabic') }})</label>
                            <input type="text" name="value_ar" value="{{ $option->value_ar }}"
                                class="w-full border border-gray-300 rounded p-2">
                        </div>
                        <div class="mb-4">
                            <label class="block mb-2 text-sm font-medium text-gray-900 ">{{ __db('code') }}</label>
                            <input type="text" name="code" value="{{ $option->code }}"
                                class="w-full border border-gray-300 rounded p-2">
                        </div>
                        <div class="mb-4">
                            <label class="block mb-2 text-sm font-medium text-gray-900 ">{{ __db('sort_order') }}</label>
                            <input type="number" name="sort_order" value="{{ $option->sort_order ?? 0 }}"
                                class="w-full border border-gray-300 rounded p-2">
                        </div>
                                        <div class="mb-4">
                                            <label class="block mb-2 text-sm font-medium text-gray-900 ">{{ __db('status') }}</label>
                                            <select name="status" class="w-full border border-gray-300 rounded p-2">
                                                <option value="1" {{ $option->status ? 'selected' : '' }}>{{ __db('active') }}</option>
                                                <option value="0" {{ !$option->status ? 'selected' : '' }}>{{ __db('inactive') }}</option>
                                            </select>
                                        </div>

                                        <div class="flex justify-start space-x-2 pt-4">
                                            <button type="submit"
                                                class="btn text-md mb-[-10px] !bg-[#B68A35] text-white rounded-lg h-12 ml-2">
                                                {{ __db('update') }}
                                            </button>
                                            <button type="button" data-modal-hide="edit-option-{{ $option->id }}" class="btn text-md mb-[-10px] border !border-[#B68A35] !text-[#B68A35] rounded-lg h-12">{{ __db('cancel') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </tr>
                    @empty
                        <tr class="odd:bg-[#F9F7ED] text-sm align-[middle]">
                            <td class="px-4 py-3 text-center " colspan="6" dir="ltr">
                                {{ __db('no_data_found') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

             <div class="mt-4">
                {{ $options->links() }}
            </div>

            <div id="add-option" tabindex="-1" aria-hidden="true"
                class="hidden fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
                    <button type="button" data-modal-hide="add-option"
                        class="absolute top-2 right-2 text-gray-600 hover:text-gray-900 text-xl font-bold">&times;</button>

                    <h2 class="text-xl font-semibold mb-4">{{ __db('add_new_option') }}</h2>

                    <form method="POST" action="{{ route('dropdowns.options.store') }}">
                        @csrf
                        <input type="hidden" name="dropdown_id" value="{{ $dropdown->id }}">

                        <label class="block mb-2 font-medium">{{ __db('value') }} ({{ __db('english') }})<span class="text-red-600">*</span></label>
                        <input type="text" name="value" required class="w-full border p-2 rounded mb-4">

                        <label class="block mb-2 font-medium">{{ __db('value') }} ({{ __db('arabic') }})</label>
                        <input type="text" name="value_ar" class="w-full border p-2 rounded mb-4">

                        <label class="block mb-2 font-medium">{{ __db('code') }}</label>
                        <input type="text" name="code" class="w-full border p-2 rounded mb-4">

                        <label class="block mb-2 font-medium">{{ __db('sort_order') }}</label>
                        <input type="number" name="sort_order" class="w-full border p-2 rounded mb-4" value="0">

                        <div class="text-right">
                            <button type="submit" class="btn text-md !bg-[#B68A35] text-white rounded-lg h-12 px-8 submit-btn">
                                {{ __db('save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

    
@endsection

@section('script')
    <script>
        document.querySelectorAll('[data-modal-toggle]').forEach(btn => {
            btn.addEventListener('click', () => {
                const modalId = btn.getAttribute('data-modal-toggle');
                const modal = document.getElementById(modalId);
                modal.classList.remove('hidden');
            });
        });
        document.querySelectorAll('[data-modal-hide]').forEach(btn => {
            btn.addEventListener('click', () => {
                const modalId = btn.getAttribute('data-modal-hide');
                const modal = document.getElementById(modalId);
                modal.classList.add('hidden');
            });
        });

        function update_status(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ route('dropdowns.options.status') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {
                if (data == 1) {
                    toastr.success("{{ __db('status_updated') }}");
                    setTimeout(function() {
                        window.location.reload();
                    }, 2000);

                } else {
                    toastr.error("{{ __db('something_went_wrong') }}");
                    setTimeout(function() {
                        window.location.reload();
                    }, 2000);
                }
            });
        }
    </script>
@endsection