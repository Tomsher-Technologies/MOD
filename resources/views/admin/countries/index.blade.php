@extends('layouts.admin_account', ['title' => __db('countries')])

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
        <div class="justify-between">
            <h2 class="font-semiboldadd_dropdown_options mb-0 !text-[22px]">{{ __db('countries') }}</h2>
        </div>

        <div>
            @can('add_dropdown_options')
                <button data-modal-target="add-country" data-modal-toggle="add-country"
                    class="btn me-8 text-md mb-[-10px] !bg-[#B68A35] text-white rounded-lg ">
                    <svg class="w-6 h-6 text-white me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 12h14m-7 7V5" />
                    </svg>
                    <span>{{ __db('add_country') }}</span>
                </button>
            @endcan
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
                <!-- Search Form -->
                <form method="GET" action="{{ route('countries.index') }}" class="mb-4">
                    <div class="flex flex-wrap gap-4">
                        <div class="flex-1 min-w-[200px]">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="{{ __db('search') }}" class="w-full border border-gray-300 rounded p-2">
                        </div>
                        <div class="flex-1 min-w-[150px]">
                            <select name="status" class="w-full border border-gray-300 rounded p-2">
                                <option value="">{{ __db('all_status') }}</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>{{ __db('active') }}
                                </option>
                                <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>
                                    {{ __db('inactive') }}</option>
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
                        ['label' => __db('name'), 'render' => fn($country) => e($country->name)],
                        ['label' => __db('short_code'), 'render' => fn($country) => e($country->short_code)],
                        ['label' => __db('continent'), 'render' => fn($country) => e($country->continent->value ?? '')],
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

                <div class="mt-4">
                    {{ $countries->links() }}
                </div>

                <div id="add-country" tabindex="-1" aria-hidden="true"
                    class="hidden fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
                    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
                        <button type="button"
                            class="close-modal absolute top-2 right-2 text-gray-600 hover:text-gray-900 text-xl font-bold">&times;</button>

                        <h2 class="text-xl font-semibold mb-4">{{ __db('add_new_country') }}</h2>

                        <form method="POST" action="{{ route('countries.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-4">
                                <label
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __db('name') }}</label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                    class="w-full border border-gray-300 rounded p-2">
                            </div>
                            <div class="mb-4">
                                <label
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __db('short_code') }}</label>
                                <input type="text" name="short_code" value="{{ old('short_code') }}" required
                                    class="w-full border border-gray-300 rounded p-2">
                            </div>


                            <div class="mb-4">
                                <label class="form-label">{{ __db('continent') }}:</label>
                                <select name="continent_id"
                                    class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                                    <option value="">{{ __db('select_continent') }}</option>
                                    @foreach (getDropDown('continents')->options as $option)
                                        <option value="{{ $option->id }}"
                                            {{ old('continent_id', request('continent_id')) == $option->id ? 'selected' : '' }}>
                                            {{ $option->value }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('continent_id')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror

                            </div>

                            <div class="mb-4">
                                <label
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __db('sort_order') }}</label>
                                <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}"
                                    class="w-full border border-gray-300 rounded p-2">
                            </div>

                            <div class="col-span-3">
                                <label class="form-label">{{ __db('flag') }}</label>
                                <input type="file" name="flag" id="image"
                                    class=" rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                                @error('flag')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __db('status') }}</label>
                                <select name="status" class="w-full border border-gray-300 rounded p-2">
                                    <option value="1" {{ old('status', 1) ? 'selected' : '' }}>{{ __db('active') }}
                                    </option>
                                    <option value="0" {{ !old('status', 1) ? 'selected' : '' }}>
                                        {{ __db('inactive') }}</option>
                                </select>
                            </div>

                            <div class="flex justify-start space-x-2 pt-4">
                                <button type="submit"
                                    class="btn text-md mb-[-10px] !bg-[#B68A35] text-white rounded-lg h-12 ml-2">
                                    {{ __db('add') }}
                                </button>
                                <button type="button"
                                    class="close-modal btn text-md mb-[-10px] border !border-[#B68A35] !text-[#B68A35] rounded-lg h-12">{{ __db('cancel') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>



        @foreach ($countries as $country)
            <div id="edit-country-{{ $country->id }}" tabindex="-1" aria-hidden="true"
                class="hidden fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
                    <button type="button"
                        class="close-modal absolute top-2 right-2 text-gray-600 hover:text-gray-900 text-xl font-bold">&times;</button>

                    <h2 class="text-xl font-semibold mb-4">{{ __db('edit') . ' ' . __db('country') }}</h2>

                    <form method="POST" action="{{ route('countries.update', $country->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block mb-2 text-sm font-medium text-gray-900">{{ __db('name') }}</label>
                            <input type="text" name="name" value="{{ old('name', $country->name) }}" required
                                class="w-full border border-gray-300 rounded p-2">
                        </div>

                        <div class="mb-4">
                            <label class="block mb-2 text-sm font-medium text-gray-900">{{ __db('short_code') }}</label>
                            <input type="text" name="short_code"
                                value="{{ old('short_code', $country->short_code) }}" required
                                class="w-full border border-gray-300 rounded p-2">
                        </div>


                        <div class="mb-4">
                            <label class="form-label">{{ __db('continent') }}:</label>
                            <select name="continent_id"
                                class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                                <option disabled>{{ __('Select Continent') }}</option>
                                @foreach (getDropDown('continents')->options as $option)
                                    <option value="{{ $option->id }}"
                                        {{ old('continent_id', $country->continent_id) == $option->id ? 'selected' : '' }}>
                                        {{ $option->value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('continent_id')
                                <div class="text-red-600">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="mb-4">
                            <label class="block mb-2 text-sm font-medium text-gray-900">{{ __db('sort_order') }}</label>
                            <input type="number" name="sort_order"
                                value="{{ old('sort_order', $country->sort_order ?? 0) }}"
                                class="w-full border border-gray-300 rounded p-2">
                        </div>

                        <div class="col-span-3">
                            <label class="form-label">{{ __db('flag') }}</label>
                            <input type="file" name="flag" id="flag"
                                class=" rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                            @error('flag')
                                <div class="text-red-600">{{ $message }}</div>
                            @enderror
                            @if ($country->flag)
                                <img src="{{ getUploadedImage($country->flag) }}" alt="Image" width="100"
                                    height="100" class="mb-2 mt-4"><br>
                            @endif
                        </div>



                        <div class="mb-4">
                            <label class="block mb-2 text-sm font-medium text-gray-900">{{ __db('Status') }}</label>
                            <select name="status" class="w-full border border-gray-300 rounded p-2">
                                <option value="1" {{ $country->status == 1 ? 'selected' : '' }}>{{ __db('active') }}
                                </option>
                                <option value="0" {{ $country->status == 0 ? 'selected' : '' }}>
                                    {{ __db('inactive') }}
                                </option>
                            </select>
                        </div>

                        <div class="flex justify-start space-x-2 pt-4">
                            <button type="submit"
                                class="btn text-md mb-[-10px] !bg-[#B68A35] text-white rounded-lg h-12">{{ __db('update') }}</button>
                            <button type="button"
                                class="close-modal btn text-md mb-[-10px] border !border-[#B68A35] !text-[#B68A35] rounded-lg h-12">{{ __db('cancel') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach


    </div>
@endsection

@section('script')
    <script>
        // Show modals
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

        // Close modals with close buttons
        document.querySelectorAll('.close-modal').forEach(btn => {
            btn.addEventListener('click', () => {
                const modal = btn.closest('[id^="edit-country-"], #add-country');
                if (modal) {
                    modal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }
            });
        });

        // Close modals when clicking outside
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
                $.post('{{ url('mod-admin/countries') }}/' + id, {
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
