@extends('layouts.admin_account', ['title' => __db('countries')])

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
        <div class="justify-between">
            <h2 class="font-semiboldadd_dropdown_options mb-0 !text-[22px]">{{ __db('countries') }}</h2>
        </div>

        <div class="flex gap-2">
            @directCan('add_dropdown_options')
                <a href="{{ route('countries.import.form') }}"
                    class="btn text-md mb-[-10px] !bg-[#B68A35] text-white rounded-lg flex items-center">
                    <svg class="w-6 h-6 text-white me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 15v2a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3v-2M12 4v12m0-12 4 4m-4-4L8 8" />
                    </svg>
                    <span>{{ __db('import') }}</span>
                </a>

                <a href="{{ route('countries.index', ['export' => 'all']) }}"
                    class="btn text-md mb-[-10px] !bg-[#B68A35] text-white rounded-lg flex items-center">
                    <svg class="w-6 h-6 text-white me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h3a3 3 0 0 0 3-3v-6a3 3 0 0 0-3-3H6a3 3 0 0 0-3 3v6a3 3 0 0 0 3 3h3m4-9h2m-2 3h2m-6 0h.01M12 17v.01" />
                    </svg>
                    <span>{{ __db('export_all') }} (XLSX)</span>
                </a>

                <button data-modal-target="add-country" data-modal-toggle="add-country"
                    class="btn text-md mb-[-10px] !bg-[#B68A35] text-white rounded-lg flex items-center">
                    <svg class="w-6 h-6 text-white me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 12h14m-7 7V5" />
                    </svg>
                    <span>{{ __db('add_country') }}</span>
                </button>
            @enddirectCan
        </div>

    </div>


    @if (session('error'))
        <div class="mb-4 p-4 rounded-md bg-red-100 text-red-800">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
        <div class="xl:col-span-12 h-full">
            <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                <form method="GET" action="{{ route('countries.index') }}" class="mb-4">
                    <div class="flex flex-wrap gap-4">
                        <div class="flex-1 min-w-[200px]">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="{{ __db('search') }}" class="w-full border border-gray-300 rounded p-2">
                        </div>
                        <div class="flex-1 min-w-[150px]">
                            <select name="status" class="w-full border border-gray-300 rounded p-2">
                                <option value="">{{ __db('all_status') }}</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>
                                    {{ __db('active') }}
                                </option>
                                <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>
                                    {{ __db('inactive') }}</option>
                            </select>
                        </div>
                        <div class="flex-1 min-w-[150px]">
                            <select name="continent_id" class="w-full border border-gray-300 rounded p-2">
                                <option value="">{{ __db('all_continents') }}</option>
                                @foreach (getDropDown('continents')->options as $option)
                                    <option value="{{ $option->id }}"
                                        {{ request('continent_id') == $option->id ? 'selected' : '' }}>
                                        {{ $option->value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="submit"
                                class="bg-[#B68A35] text-white px-4 py-2 rounded mr-2">{{ __db('filter') }}</button>
                            <a href="{{ route('countries.index') }}"
                                class="border border-[#B68A35] text-[#B68A35] px-4 py-2 rounded">{{ __db('reset') }}</a>
                        </div>
                    </div>
                </form>

                @php
                    $columns = [
                        [
                            'label' => __db('sl_no'),
                            'render' => function ($country, $key) use ($countries) {
                                return $countries->firstItem() + $key;
                            },
                        ],
                        ['label' => __db('name'), 'render' => fn($country) => e($country?->getNameEn())],
                        ['label' => __db('name_ar'), 'render' => fn($country) => e($country?->getNameAr() ?? '')],
                        ['label' => __db('short_code'), 'render' => fn($country) => e($country?->short_code)],
                        ['label' => __db('code'), 'render' => fn($country) => e($country?->code)],
                        [
                            'label' => __db('continent'),
                            'render' => fn($country) => e($country?->continent->value ?? ''),
                        ],
                        [
                            'label' => __db('flag'),
                            'render' => function ($country) {
                                if ($country->flag) {
                                    return '<img src="' .
                                        e(getUploadedImage($country->flag)) .
                                        '" alt="Flag" width="100" height="100" class="mb-2 mt-4" />';
                                }
                                return '';
                            },
                        ],
                        ['label' => __db('sort_order'), 'render' => fn($country) => e($country->sort_order ?? 0)],
                        [
                            'label' => __db('status'),
                            'render' => function ($country) {
                                if (can(['add_dropdown_options'])) {
                                    $checked = $country->status == 1 ? 'checked' : '';
                                    return '
                                    <div class="items-center">
                                        <label for="switch-' .
                                        $country->id .
                                        '" class="relative inline-block w-11 h-6">
                                            <input type="checkbox" id="switch-' .
                                        $country->id .
                                        '" onchange="update_status(this)" value="' .
                                        $country->id .
                                        '" class="sr-only peer" ' .
                                        $checked .
                                        ' />
                                            <div class="block bg-gray-300 peer-checked:bg-[#009448] w-11 h-6 rounded-full transition"></div>
                                            <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition peer-checked:translate-x-5"></div>
                                        </label>
                                    </div>';
                                } else {
                                    $class = $country->status == 1 ? 'text-green-500' : 'text-red-500';
                                    $label = $country->status == 1 ? __db('active') : __db('inactive');
                                    return '<span class="' . $class . '">' . $label . '</span>';
                                }
                            },
                        ],
                        [
                            'label' => __db('action'),
                            'permission' => ['add_dropdown_options'],
                            'render' => function ($country) {
                                return '
                                         <button data-modal-target="edit-country-' .
                                    $country->id .
                                    '" data-modal-toggle="edit-country-' .
                                    $country->id .
                                    '" class="w-8 h-8 bg-[#FBF3D6] text-primary-600 rounded-full inline-flex items-center justify-center">
                                       <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 512 512">
                                                <path
                                                    d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z"
                                                    fill="#B68A35"></path>
                                            </svg>
                                        </button> ';
                                return '';
                            },
                        ],
                    ];
                @endphp

                <x-reusable-table :columns="$columns" :data="$countries" />

            </div>
        </div>


    </div>

    <div id="add-country" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <div class="relative bg-white rounded-lg shadow">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                    <h3 class="text-xl font-semibold text-gray-900">
                        {{ __db('add_country') }}
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center close-modal"
                        data-modal-hide="add-country">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">{{ __db('close_modal') }}</span>
                    </button>
                </div>
                <form action="{{ route('countries.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="p-4 md:p-5 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block mb-2 text-sm font-medium text-gray-900">
                                    {{ __db('name') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="name"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    required>
                            </div>
                            <div>
                                <label for="name_ar" class="block mb-2 text-sm font-medium text-gray-900">
                                    {{ __db('name_ar') }}
                                </label>
                                <input type="text" name="name_ar" id="name_ar"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            </div>
                            <div>
                                <label for="short_code" class="block mb-2 text-sm font-medium text-gray-900">
                                    {{ __db('short_code') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="short_code" id="short_code"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    required maxlength="10">
                            </div>
                            <div>
                                <label for="code" class="block mb-2 text-sm font-medium text-gray-900">
                                    {{ __db('code') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="code" id="code"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    required maxlength="10">
                            </div>
                            <div>
                                <label for="sort_order" class="block mb-2 text-sm font-medium text-gray-900">
                                    {{ __db('sort_order') }}
                                </label>
                                <input type="number" name="sort_order" id="sort_order"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    value="0">
                            </div>
                            <div>
                                <label for="continent_id" class="block mb-2 text-sm font-medium text-gray-900">
                                    {{ __db('continent') }} <span class="text-red-500">*</span>
                                </label>
                                <select name="continent_id" id="continent_id"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    required>
                                    <option value="">{{ __db('select_continent') }}</option>
                                    @foreach (getDropDown('continents')->options as $option)
                                        <option value="{{ $option->id }}">{{ $option->value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="flag" class="block mb-2 text-sm font-medium text-gray-900">
                                    {{ __db('flag') }}
                                </label>
                                <input type="file" name="flag" id="flag"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    accept="image/*">
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b">
                        <button type="submit"
                            class="text-white bg-[#B68A35] hover:bg-[#9d752e] focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                            {{ __db('save') }}
                        </button>
                        <button data-modal-hide="add-country" type="button"
                            class="ms-3 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 close-modal">
                            {{ __db('cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach ($countries as $country)
        <div id="edit-country-{{ $country->id }}" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-2xl max-h-full">
                <div class="relative bg-white rounded-lg shadow">
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                        <h3 class="text-xl font-semibold text-gray-900">
                            {{ __db('edit_country') }}
                        </h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center close-modal"
                            data-modal-hide="edit-country-{{ $country->id }}">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">{{ __db('close_modal') }}</span>
                        </button>
                    </div>
                    <form action="{{ route('countries.update', $country->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="p-4 md:p-5 space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="name-{{ $country->id }}"
                                        class="block mb-2 text-sm font-medium text-gray-900">
                                        {{ __db('name') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="name" id="name-{{ $country->id }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        value="{{ old('name', $country->getNameEn()) }}" required>
                                </div>
                                <div>
                                    <label for="name_ar-{{ $country->id }}"
                                        class="block mb-2 text-sm font-medium text-gray-900">
                                        {{ __db('name_ar') }}
                                    </label>
                                    <input type="text" name="name_ar" id="name_ar-{{ $country->id }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        value="{{ old('name_ar', $country->getNameAr()) }}">
                                </div>
                                <div>
                                    <label for="short_code-{{ $country->id }}"
                                        class="block mb-2 text-sm font-medium text-gray-900">
                                        {{ __db('short_code') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="short_code" id="short_code-{{ $country->id }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        value="{{ old('short_code', $country->short_code) }}" required maxlength="10">
                                </div>
                                <div>
                                    <label for="code-{{ $country->id }}"
                                        class="block mb-2 text-sm font-medium text-gray-900">
                                        {{ __db('code') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="code" id="code-{{ $country->id }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        value="{{ old('code', $country->code) }}" required maxlength="10">
                                </div>
                                <div>
                                    <label for="sort_order-{{ $country->id }}"
                                        class="block mb-2 text-sm font-medium text-gray-900">
                                        {{ __db('sort_order') }}
                                    </label>
                                    <input type="number" name="sort_order" id="sort_order-{{ $country->id }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        value="{{ old('sort_order', $country->sort_order ?? 0) }}">
                                </div>
                                <div>
                                    <label for="continent_id-{{ $country->id }}"
                                        class="block mb-2 text-sm font-medium text-gray-900">
                                        {{ __db('continent') }} <span class="text-red-500">*</span>
                                    </label>
                                    <select name="continent_id" id="continent_id-{{ $country->id }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        required>
                                        <option value="">{{ __db('select_continent') }}</option>
                                        @foreach (getDropDown('continents')->options as $option)
                                            <option value="{{ $option->id }}"
                                                {{ old('continent_id', $country->continent_id) == $option->id ? 'selected' : '' }}>
                                                {{ $option->value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="flag-{{ $country->id }}"
                                        class="block mb-2 text-sm font-medium text-gray-900">
                                        {{ __db('flag') }}
                                    </label>
                                    <input type="file" name="flag" id="flag-{{ $country->id }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        accept="image/*">
                                    @if ($country->flag)
                                        <div class="mt-2">
                                            <img src="{{ getUploadedImage($country->flag) }}" alt="Current flag"
                                                class="w-16 h-16 object-cover">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b">
                            <button type="submit"
                                class="text-white bg-[#B68A35] hover:bg-[#9d752e] focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                {{ __db('save') }}
                            </button>
                            <button data-modal-hide="edit-country-{{ $country->id }}" type="button"
                                class="ms-3 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 close-modal">
                                {{ __db('cancel') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@section('script')
    <script>
        document.querySelectorAll('[data-modal-toggle]').forEach(btn => {
            btn.addEventListener('click', () => {
                const modalId = btn.getAttribute('data-modal-toggle');
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                }
            });
        });

        document.querySelectorAll('.close-modal').forEach(btn => {
            btn.addEventListener('click', () => {
                const modal = btn.closest('[id^="edit-country-"], #add-country');
                if (modal) {
                    modal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }
            });
        });

        document.querySelectorAll('[id^="edit-country-"], #add-country').forEach(modal => {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {

            @if (session('error'))
                toastr.error('{{ session('error') }}');
            @endif

            @if ($errors->any())
                toastr.error('{{ $errors->first() }}');
            @endif

        });

        function update_status(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ route('countries.status') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {
                if (data == 1) {
                    toastr.success("{{ __db('status_updated') }}");
                } else {
                    toastr.error("{{ __db('something_went_wrong') }}");
                }
            }).fail(function() {
                toastr.error("{{ __db('something_went_wrong') }}");
            });
        }

        function deleteCountry(id, name) {
            if (confirm("{{ __db('are_you_sure_you_want_to_delete') }} " + name + "?")) {
                $.post('{{ url('mod-events/countries') }}/' + id, {
                    _token: '{{ csrf_token() }}',
                    _method: 'DELETE'
                }, function(data) {
                    toastr.success("{{ __db('country_deleted_successfully') }}");
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                }).fail(function() {
                    toastr.error("{{ __db('something_went_wrong') }}");
                });
            }
        }
    </script>
@endsection
