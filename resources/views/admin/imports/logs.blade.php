@extends('layouts.admin_account', ['title' => __db('import_logs')])

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
        <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('import_logs') }}</h2>
        <div class="flex gap-2">
            <form action="{{ route('admin.import-logs.clear') }}" method="POST" class="flex gap-3">
                @csrf
                <button type="submit" class="btn text-md  !bg-red-600 text-white rounded-lg flex items-center">
                    <svg class="w-5 h-5 text-white me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                    </svg>
                    <span>{{ __db('clear_logs') }}</span>
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
        <form method="GET" action="{{ route('admin.import-logs.index') }}" class="mb-4">
            <div class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="{{ __db('search_errors') }}" class="w-full border border-gray-300 rounded p-2">
                </div>
                <div class="flex-1 min-w-[150px]">
                    <select name="import_type" class="select2 w-full border border-gray-300 rounded p-2">
                        <option value="">{{ __db('all_types') }}</option>
                        <option value="countries" {{ request('import_type') == 'countries' ? 'selected' : '' }}>
                            {{ __db('countries') }}</option>
                        <option value="drivers" {{ request('import_type') == 'drivers' ? 'selected' : '' }}>
                            {{ __db('drivers') }}</option>
                        <option value="escorts" {{ request('import_type') == 'escorts' ? 'selected' : '' }}>
                            {{ __db('escorts') }}</option>
                        <option value="delegations" {{ request('import_type') == 'delegations' ? 'selected' : '' }}>
                            {{ __db('delegations') }}</option>
                        <option value="delegates" {{ request('import_type') == 'delegates' ? 'selected' : '' }}>
                            {{ __db('delegates') }}</option>
                        <option value="hotels" {{ request('import_type') == 'hotels' ? 'selected' : '' }}>
                            {{ __db('hotels') }}</option>
                        <option value="attachments" {{ request('import_type') == 'attachments' ? 'selected' : '' }}>
                            {{ __db('attachments') }}</option>
                    </select>

                </div>
                <div class="flex items-center gap-2">
                    <button type="submit"
                        class="bg-[#B68A35] text-white px-4 py-2 rounded mr-2">{{ __db('filter') }}</button>
                    <a href="{{ route('admin.import-logs.index') }}"
                        class="border border-[#B68A35] text-[#B68A35] px-4 py-2 rounded">{{ __db('reset') }}</a>
                </div>
            </div>
        </form>

        @if ($logs->count() > 0)
            @php
                $columns = [
                    [
                        'label' => __db('import_type'),
                        'render' => function ($log) {
                            $typeClass = '';
                            switch ($log->import_type) {
                                case 'countries':
                                    $typeClass = 'bg-red-100 text-red-800';
                                    break;
                                case 'drivers':
                                    $typeClass = 'bg-blue-100 text-blue-800';
                                    break;
                                case 'escorts':
                                    $typeClass = 'bg-green-100 text-green-800';
                                    break;
                                case 'delegations':
                                    $typeClass = 'bg-yellow-100 text-yellow-800';
                                    break;
                                case 'delegates':
                                    $typeClass = 'bg-purple-100 text-purple-800';
                                    break;
                                case 'hotels':
                                    $typeClass = 'bg-indigo-100 text-indigo-800';
                                    break;
                                case 'attachments':
                                    $typeClass = 'bg-pink-100 text-pink-800';
                                    break;
                                default:
                                    $typeClass = 'bg-gray-100 text-gray-800';
                            }
                            return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ' .
                                $typeClass .
                                '">' .
                                __db($log->import_type) .
                                '</span>';
                        },
                    ],
                    [
                        'label' => __db('file_name'),
                        'render' => fn($log) => e($log->file_name),
                    ],
                    [
                        'label' => __db('row_number'),
                        'render' => fn($log) => e($log->row_number ?? '-'),
                    ],
                    [
                        'label' => __db('status'),
                        'render' => function ($log) {
                            if ($log->status === 'success') {
                                return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">' .
                                    __db('success') .
                                    '</span>';
                            } else {
                                return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">' .
                                    __db('failed') .
                                    '</span>';
                            }
                        },
                    ],
                    [
                        'label' => __db('error_message'),
                        'render' => function ($log) {
                            if ($log->status === 'success') {
                                return '<span class="text-green-600">' . __db('import_successful') . '</span>';
                            } else {
                                return '<button onclick="showErrorDetails(' .
                                    $log->id .
                                    ')" class="text-red-600 hover:text-red-900">' .
                                    $log->error_message .
                                    '</button>';
                            }
                        },
                    ],
                    [
                        'label' => __db('created_at'),
                        'render' => fn($log) => e($log->created_at->format('Y-m-d H:i:s')),
                    ],
                    // [
                    //     'label' => __db('actions'),
                    //     'render' => function ($log) {
                    //         return '<button onclick="showRowData(' .
                    //             $log->id .
                    //             ')" class="text-indigo-600 hover:text-indigo-900">' .
                    //             __db('view_data') .
                    //             '</button>';
                    //     },
                    // ],
                ];
            @endphp

            <x-reusable-table :columns="$columns" :data="$logs" />
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __db('no_import_logs') }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ __db('no_import_logs_description') }}</p>
            </div>
        @endif
    </div>

    <div id="errorModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[80vh] overflow-y-auto">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">{{ __db('error_details') }}</h3>
                <button onclick="closeErrorModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="px-6 py-4">
                <p id="errorMessage" class="text-gray-700"></p>
            </div>
        </div>
    </div>

    <div id="rowDataModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[80vh] overflow-y-auto">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">{{ __db('row_data') }}</h3>
                <button onclick="closeRowDataModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="px-6 py-4">
                <pre id="rowData" class="text-gray-700 bg-gray-100 p-4 rounded"></pre>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function showErrorDetails(logId) {
            const log = @json($logs->keyBy('id'));
            if (log[logId]) {
                document.getElementById('errorMessage').textContent = log[logId].error_message;
                document.getElementById('errorModal').classList.remove('hidden');
                document.getElementById('errorModal').classList.add('flex');
            }
        }

        function closeErrorModal() {
            document.getElementById('errorModal').classList.add('hidden');
            document.getElementById('errorModal').classList.remove('flex');
        }

        function showRowData(logId) {
            const log = @json($logs->keyBy('id'));
            if (log[logId] && log[logId].row_data) {
                document.getElementById('rowData').textContent = JSON.stringify(log[logId].row_data, null, 2);
                document.getElementById('rowDataModal').classList.remove('hidden');
                document.getElementById('rowDataModal').classList.add('flex');
            }
        }

        function closeRowDataModal() {
            document.getElementById('rowDataModal').classList.add('hidden');
            document.getElementById('rowDataModal').classList.remove('flex');
        }

        document.addEventListener('click', function(event) {
            const errorModal = document.getElementById('errorModal');
            const rowDataModal = document.getElementById('rowDataModal');

            if (errorModal && !errorModal.contains(event.target) && event.target.closest('#errorModal') === null) {
                closeErrorModal();
            }

            if (rowDataModal && !rowDataModal.contains(event.target) && event.target.closest('#rowDataModal') ===
                null) {
                closeRowDataModal();
            }
        });
    </script>
@endsection
