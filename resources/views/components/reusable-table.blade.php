@props([
    'columns',
    'data',
    'noDataMessage' => null,
    'rowClass' => null,
    'enableRowLimit' => false,
    'enableColumnListBtn' => false,
    'tableId' => 'table',
    'defaultVisibleKeys' => [],
])

@php
    $columns = collect($columns)
        ->map(function ($column) {
            if (!isset($column['key'])) {
                $column['key'] = \Illuminate\Support\Str::slug($column['label']);
            }
            return $column;
        })
        ->toArray();

    $storageKey = $tableId . '_column_visibility';
    $modalId = $tableId . '-column-visibility-modal';
@endphp

@if ($enableColumnListBtn || $enableRowLimit)
    <div class="mb-2 flex items-center justify-between">
        @if ($enableColumnListBtn)
            <button data-modal-target="{{ $modalId }}" data-modal-toggle="{{ $modalId }}" type="button"
                class="!bg-[#E6D7A2] !text-[#5D471D] px-3 flex items-center gap-2 py-2 text-sm rounded-lg">
                <svg class="w-6 h-6 !text-[#5D471D]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                    height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M15 5v14M9 5v14M4 5h16a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z" />
                </svg>
                <span>{{ __db('column_list') }}</span>
            </button>
        @endif

        @if (
            ($data instanceof \Illuminate\Contracts\Pagination\Paginator ||
                $data instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator) &&
                $enableRowLimit)
            <form method="GET">
                @foreach (request()->except('limit', 'page') as $key => $value)
                    @if (is_array($value))
                        @foreach ($value as $subKey => $subValue)
                            <input type="hidden" name="{{ $key }}[{{ $subKey }}]"
                                value="{{ $subValue }}">
                        @endforeach
                    @else
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach
                <select id="limit" name="limit" onchange="this.form.submit()"
                    class="border text-secondary-light text-xs !border-[#d1d5db] rounded px-5 py-1 !pe-7">
                    @foreach ([10, 25, 50, 100] as $size)
                        <option value="{{ $size }}" {{ request('limit', 25) == $size ? 'selected' : '' }}>
                            {{ $size }}
                        </option>
                    @endforeach
                </select>
                <span class="mr-2 text-sm">{{ __db('rows') }}</span>
            </form>
        @endif
    </div>
@endif

<table class="table-auto mb-0 !border-[#F9F7ED] w-full hidden" id="{{ $tableId }}">
    <thead>
        <tr class="text-[13px]">
            @foreach ($columns as $column)
                @php
                    $permissionKey = $column['permission'] ?? null;
                    $colPermissions = $permissionKey
                        ? (is_array($permissionKey)
                            ? $permissionKey
                            : [$permissionKey])
                        : null;
                @endphp
                @if (!$colPermissions || can($colPermissions))
                    <th scope="col"
                        class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71] {{ isset($column['class']) ? $column['class'] : '' }}"
                        data-column-key="{{ $column['key'] }}">
                        {{ $column['label'] }}
                    </th>
                @endif
            @endforeach
        </tr>
    </thead>
    <tbody>
        @if ((is_array($data) && count($data) === 0) || (!is_array($data) && $data->count() === 0))
            <tr>
                <td class="px-4 py-2 border border-gray-200 text-center" colspan="{{ count($columns) }}">
                    {{ $noDataMessage ?? 'No data found.' }}
                </td>
            </tr>
        @else
            @foreach ($data as $key => $row)
                @php
                    $rowId = is_array($row) ? $row['id'] ?? '' : $row->id ?? '';

                    $rowPermissionsKey = is_array($row) ? $row['permission'] ?? null : $row->permission ?? null;
                    $rowPermissions = $rowPermissionsKey
                        ? (is_array($rowPermissionsKey)
                            ? $rowPermissionsKey
                            : [$rowPermissionsKey])
                        : null;
                @endphp
                @if (!$rowPermissions || can($rowPermissions))
                    <tr class="text-[12px] align-[middle] {{ $rowClass ? $rowClass($row) : '' }}"
                        data-id="{{ $rowId }}">
                        @foreach ($columns as $column)
                            @php
                                $colPermissionKey = $column['permission'] ?? null;
                                $colPermissions = $colPermissionKey
                                    ? (is_array($colPermissionKey)
                                        ? $colPermissionKey
                                        : [$colPermissionKey])
                                    : null;
                            @endphp
                            @if (!$colPermissions || can($colPermissions))
                                <td class="px-4 py-2 border {{ $rowClass ? $rowClass($row) : 'border-gray-200' }}"
                                    data-column-key="{{ $column['key'] }}">
                                    {!! $column['render']($row, $key) !!}
                                </td>
                            @endif
                        @endforeach
                    </tr>
                @endif
            @endforeach
        @endif
    </tbody>



</table>

@if (
    $data instanceof \Illuminate\Contracts\Pagination\Paginator ||
        $data instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
    <div class="mt-2">
        {{ $data->appends(request()->except('page'))->links() }}
    </div>
@endif

<div id="{{ $modalId }}" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow">
            <div class="flex items-start justify-between p-4 border-b rounded-t">
                <h3 class="text-xl font-semibold text-gray-900">{{ __db('column_list') }}</h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 rounded-lg text-sm p-1.5 mr-auto inline-flex items-center"
                    data-modal-hide="{{ $modalId }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <div class="p-6 space-y-6">
                <div class="space-y-3 grid grid-cols-3" id="{{ $tableId }}-column-toggles">
                    @foreach ($columns as $column)
                        @php
                            $isActionColumn = in_array($column['key'], ['action', 'actions']);
                        @endphp
                        @if (!$isActionColumn)
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" class="form-checkbox text-blue-600 column-toggle-checkbox me-2"
                                    value="{{ $column['key'] }}" checked>
                                <span>{{ $column['label'] }}</span>
                            </label>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        (() => {
            const defaultVisibleTableKeys = @json($defaultVisibleKeys);
            const storageKey = @json($storageKey);
            const tableId = @json($tableId);
            const modalId = @json($modalId);
            const enableColumnListBtn = @json($enableColumnListBtn);

            document.addEventListener('DOMContentLoaded', function() {
                const columnTogglesContainer = document.getElementById(tableId + '-column-toggles');
                if (!columnTogglesContainer) return;

                const checkboxes = columnTogglesContainer.querySelectorAll('.column-toggle-checkbox');

                if (!enableColumnListBtn) {
                    const table = document.getElementById(tableId);

                    document.querySelectorAll(`#${tableId} th, #${tableId} td`).forEach(el => {
                        el.style.display = '';
                    });

                    if (table) {
                        table.classList.remove('hidden');
                    }

                    return;
                }

                const applyVisibility = () => {
                    let preferences = {};
                    checkboxes.forEach(checkbox => {
                        const columnKey = checkbox.value;
                        const isVisible = checkbox.checked;
                        preferences[columnKey] = isVisible;

                        document.querySelectorAll(
                            `#${tableId} th[data-column-key='${columnKey}'], #${tableId} td[data-column-key='${columnKey}']`
                        ).forEach(el => {
                            el.style.display = isVisible ? '' : 'none';
                        });
                    });

                    // Always show action columns
                    document.querySelectorAll(
                        `#${tableId} th[data-column-key='action'], #${tableId} td[data-column-key='action'],` +
                        `#${tableId} th[data-column-key='actions'], #${tableId} td[data-column-key='actions']`
                    ).forEach(el => {
                        el.style.display = '';
                    });

                    localStorage.setItem(storageKey, JSON.stringify(preferences));
                };

                const loadPreferences = () => {
                    const savedPrefs = JSON.parse(localStorage.getItem(storageKey));
                    if (savedPrefs) {
                        checkboxes.forEach(checkbox => {
                            checkbox.checked = savedPrefs[checkbox.value] !== false;
                        });
                    } else if (defaultVisibleTableKeys.length > 0) {
                        checkboxes.forEach(checkbox => {
                            checkbox.checked = defaultVisibleTableKeys.includes(checkbox.value);
                        });
                    } else {
                        checkboxes.forEach(checkbox => {
                            checkbox.checked = true;
                        });
                    }


                    // Always show action columns
                    document.querySelectorAll(
                        `#${tableId} th[data-column-key='action'], #${tableId} td[data-column-key='action'],` +
                        `#${tableId} th[data-column-key='actions'], #${tableId} td[data-column-key='actions']`
                    ).forEach(el => {
                        el.style.display = '';
                    });
                };

                loadPreferences();
                applyVisibility();

                const table = document.getElementById(tableId);
                if (table) {
                    table.classList.remove('hidden');
                }

                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', applyVisibility);
                });

                document.querySelectorAll(`[data-modal-toggle="${modalId}"]`).forEach(btn => {
                    btn.addEventListener('click', () => {
                        const modal = document.getElementById(modalId);
                        if (modal) {
                            modal.classList.toggle('hidden');
                        }
                    });
                });

                document.querySelectorAll(`[data-modal-hide="${modalId}"]`).forEach(btn => {
                    btn.addEventListener('click', () => {
                        const modal = document.getElementById(modalId);
                        if (modal) {
                            modal.classList.add('hidden');
                        }
                    });
                });
            });
        })();
    </script>
@endpush
