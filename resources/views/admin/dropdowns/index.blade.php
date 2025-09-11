@extends('layouts.admin_account', ['title' => __db('dynamic_contents')])

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
        <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('dynamic_contents') }}</h2>

        <div class="flex items-center">
            @directCan('add_dropdown_options')
                <a href="{{ route('countries.index') }}"
                    class="btn me-4 text-sm mb-[-10px] border !border-[#a57d30] text-[#a57d30] rounded-lg ">
                    <span class="mr-2">{{ __db('countries') }}</span>
                </a>

                <a href="{{ route('dropdowns.bulk.import') }}"
                    class="btn me-8 text-sm mb-[-10px] bg-[#a57d30] text-white rounded-lg ">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="white" stroke-width="2"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M16 12l-4 4m0 0l-4-4m4 4V4" />
                    </svg>

                    <span class="mr-2">{{ __db('bulk_import_options') }}</span>
                </a>
            @enddirectCan
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
        <div class="xl:col-span-12 h-full">
            <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">

                <table class="table-auto mb-0 !border-[#F9F7ED] w-full">
                    <thead>
                        <tr class="text-[13px]">
                            <th class="p-3 !bg-[#B68A35] text-start text-white">#</th>
                            <th class="p-3 !bg-[#B68A35] text-start text-white">{{ __db('name') }}</th>
                            <th class="p-3 !bg-[#B68A35] text-start text-white">{{ __db('code') }}</th>
                            <th class="p-3 !bg-[#B68A35] text-center text-white">{{ __db('action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dropdowns as $key => $dropdown)
                            <tr class="border border-gray-200  align-[middle] text-[12px]">
                                <td class="px-4 py-3 text-end" dir="ltr">
                                    {{ $key + 1 }}
                                </td>
                                <td class="border border-gray-200 px-4 py-3 text-black">{{ __db($dropdown->code) }}</td>
                                <td class=" px-4 py-4 text-black text-center flex items-center">
                                    <div class="relative inline-block">
                                        <svg class="copy-icon w-6 h-6 text-black cursor-pointer"
                                            fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg" aria-hidden="true" title="Copy code">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>

                                        <!-- Tooltip -->
                                        <span
                                            class="tooltip-text absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 bg-black text-white text-xs rounded opacity-0 pointer-events-none transition-opacity duration-300">
                                            {{ __db('copied') }}
                                        </span>
                                    </div>

                                    <div class="mr-3 code-text">{{ $dropdown->code }}</div>
                                </td>
                                <td class="border border-gray-200 px-4 py-3">
                                    <div class=" text-center gap-5">
                                        @directCan('edit_role')
                                            <a href="{{ route('dropdowns.options.show', $dropdown) }}"
                                                class="btn me-8 text-xs px-4 py-2 !bg-[#B68A35] text-white rounded-lg">

                                                <span>{{ __db('view_options') }}</span>
                                            </a>
                                        @enddirectCan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="text-[12px] text-sm align-[middle]">
                                <td class="px-4 py-3 text-center " colspan="3" dir="ltr">
                                    {{ __db('no_data_found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.querySelectorAll('.copy-icon').forEach(icon => {
            icon.addEventListener('click', () => {
                const codeElement = icon.closest('td').querySelector('.code-text');
                const code = codeElement?.textContent?.trim();

                if (!code) return;

                navigator.clipboard.writeText(code).then(() => {
                    const tooltip = icon.parentElement.querySelector('.tooltip-text');
                    tooltip.classList.remove('opacity-0');
                    tooltip.classList.add('opacity-100');
                    setTimeout(() => {
                        tooltip.classList.remove('opacity-100');
                        tooltip.classList.add('opacity-0');
                    }, 1500);
                });
            });
        });
    </script>
@endsection
