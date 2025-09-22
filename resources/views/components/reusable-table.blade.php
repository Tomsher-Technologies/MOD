@props([
    'columns',
    'data',
    'noDataMessage' => null,
    'rowClass' => null,
    'enableRowLimit' => false,
    'enableColumnListBtn' => false,
    'tableId' => 'table',
    'defaultVisibleKeys' => [],
    'loading' => false,
])

@php
    $columns = collect($columns)
        ->map(function ($column, $index) {
            if (!isset($column['key'])) {
                $column['key'] = \Illuminate\Support\Str::slug($column['label']);
            }
            $column['narrow'] = $index === 0;
            return $column;
        })
        ->toArray();

    $storageKey = $tableId . '_column_visibility';
    $modalId = $tableId . '-column-visibility-modal';
@endphp

{{-- Enhanced loader styles --}}
<style>
    @keyframes shimmer {
        100% { transform: translateX(100%); }
    }
    .shimmer-bg {
        position: relative;
        overflow: hidden;
        background-color: #e2e8f0; /* Tailwind's bg-gray-200 */
    }
    .shimmer-bg::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        transform: translateX(-100%);
        background-image: linear-gradient(
            90deg,
            rgba(255, 255, 255, 0) 0,
            rgba(255, 255, 255, 0.4) 50%,
            rgba(255, 255, 255, 0) 100%
        );
        animation: shimmer 1.5s infinite linear;
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    .table-body-fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }
</style>

@if ($enableColumnListBtn || $enableRowLimit)
    <div class="mb-2 flex items-center justify-between flex-wrap gap-2">
        @if ($enableColumnListBtn)
            <button data-modal-target="{{ $modalId }}" data-modal-toggle="{{ $modalId }}" type="button"
                class="!bg-[#E6D7A2] !text-[#5D471D] px-3 flex items-center gap-2 py-2 text-sm rounded-lg whitespace-nowrap">
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
            <form method="GET" class="flex items-center gap-2">
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
                    class="border text-secondary-light text-xs !border-[#d1d5db] rounded px-3 py-1 !pe-7">
                    @foreach ([10, 25, 50, 100] as $size)
                        <option value="{{ $size }}" {{ request('limit', 25) == $size ? 'selected' : '' }}>
                            {{ $size }}
                        </option>
                    @endforeach
                </select>
                <span class="mr-2 text-sm whitespace-nowrap">{{ __db('rows') }}</span>
            </form>
        @endif
    </div>
@endif

<div class="overflow-x-auto w-full rounded-lg border border-[#e5e4b2]">
    <table class="table-auto w-full border-collapse mb-0 border border-[#e5e4b2]" id="{{ $tableId }}">
        <thead class="bg-[#B68A35] sticky top-0 z-10 shadow-md">
            <tr class="text-[13px] text-white select-none">
                @foreach ($columns as $column)
                    @php
                        $permissionKey = $column['permission'] ?? null;
                        $colPermissions = $permissionKey
                            ? (is_array($permissionKey) ? $permissionKey : [$permissionKey])
                            : null;
                        $thClasses = $column['narrow'] ? 'w-14 max-w-[56px]' : '';
                    @endphp
                    @if (!$colPermissions || can($colPermissions))
                        <th scope="col"
                            class="p-3 border border-[#cbac71] text-start font-semibold tracking-wide {{ $column['class'] ?? '' }} {{ $thClasses }}"
                            data-column-key="{{ $column['key'] }}">
                            {{ $column['label'] }}
                        </th>
                    @endif
                @endforeach
            </tr>
        </thead>
        @if($loading)
            <tbody>
                {{-- UPDATED: Switched to a smoother shimmer effect loader --}}
                @foreach(range(1, request('limit', 10)) as $i)
                    <tr>
                        @foreach ($columns as $column)
                            @php
                                $permissionKey = $column['permission'] ?? null;
                                $colPermissions = $permissionKey ? (is_array($permissionKey) ? $permissionKey : [$permissionKey]) : null;
                                $tdClasses = $column['narrow'] ? 'w-14 max-w-[56px]' : '';
                            @endphp
                            @if (!$colPermissions || can($colPermissions))
                                <td class="px-4 py-3 border border-gray-200 {{ $tdClasses }}" data-column-key="{{ $column['key'] }}">
                                    <div class="h-4 rounded shimmer-bg"></div>
                                </td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        @elseif ((is_array($data) && count($data) === 0) || (!is_array($data) && $data->count() === 0))
            <tbody>
                <tr>
                    <td class="px-4 py-4 border border-gray-200 text-center italic" colspan="{{ count($columns) }}">
                        {{ $noDataMessage ?? 'No data found.' }}
                    </td>
                </tr>
            </tbody>
        @else
            {{-- UPDATED: Added a fade-in class for a smooth content appearance --}}
            <tbody class="table-body-fade-in">
                @foreach ($data as $key => $row)
                    @php
                        $rowId = is_array($row) ? ($row['id'] ?? '') : ($row->id ?? '');
                        $rowPermissionsKey = is_array($row) ? ($row['permission'] ?? null) : ($row->permission ?? null);
                        $rowPermissions = $rowPermissionsKey
                            ? (is_array($rowPermissionsKey) ? $rowPermissionsKey : [$rowPermissionsKey])
                            : null;
                        $rowClassValue = $rowClass ? $rowClass($row) : '';
                    @endphp
                    @if (!$rowPermissions || can($rowPermissions))
                        <tr class="text-[12px] align-middle hover:bg-[#f1e9a2] cursor-pointer {{ $rowClassValue }}"
                            data-id="{{ $rowId }}">
                            @foreach ($columns as $column)
                                @php
                                    $colPermissionKey = $column['permission'] ?? null;
                                    $colPermissions = $colPermissionKey
                                        ? (is_array($colPermissionKey) ? $colPermissionKey : [$colPermissionKey])
                                        : null;
                                    $tdClasses = $column['narrow'] ? 'w-14 max-w-[56px]' : '';
                                @endphp
                                @if (!$colPermissions || can($colPermissions))
                                    <td class="px-4 py-2 border border-gray-200 break-words whitespace-normal max-w-[200px] {{ $tdClasses }}"
                                        data-column-key="{{ $column['key'] }}"
                                        style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        {!! $column['render']($row, $key) !!}
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                    @endif
                @endforeach
            </tbody>
        @endif
    </table>
</div>

@if (
    $data instanceof \Illuminate\Contracts\Pagination\Paginator ||
        $data instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
    <div class="mt-2">
        {{ $data->appends(request()->except('page'))->links() }}
    </div>
@endif

<div id="{{ $modalId }}" tabindex="-1" aria-hidden="true"
     class="hidden fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden p-4 md:p-6">
  <div class="relative w-full max-w-2xl mx-auto bg-white rounded-lg shadow-lg">
    <div class="flex items-center justify-between p-4 border-b rounded-t bg-gray-50">
      <h3 class="text-xl font-semibold text-gray-900">{{ __db('column_list') }}</h3>
      <button type="button"
              class="text-gray-400 bg-transparent hover:bg-gray-200 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
              data-modal-hide="{{ $modalId }}" aria-label="Close modal">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>
    <div class="p-6">
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4" id="{{ $tableId }}-column-toggles">
        @foreach ($columns as $column)
          @php
            $isActionColumn = in_array($column['key'], ['action', 'actions']);
          @endphp
          @if (!$isActionColumn)
            <label class="flex items-center space-x-3 cursor-pointer select-none">
              <input type="checkbox" class="form-checkbox text-blue-600 column-toggle-checkbox"
                     value="{{ $column['key'] }}" checked>
              <span class="text-gray-800">{{ $column['label'] }}</span>
            </label>
          @endif
        @endforeach
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

            document.addEventListener('DOMContentLoaded', function() {
                const columnTogglesContainer = document.getElementById(tableId + '-column-toggles');
                if (!columnTogglesContainer) return;

                const checkboxes = columnTogglesContainer.querySelectorAll('.column-toggle-checkbox');

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

                const modal = document.getElementById(modalId);
                if(modal) {
                    document.querySelectorAll(`[data-modal-toggle="${modalId}"]`).forEach(btn => {
                        btn.addEventListener('click', () => modal.classList.remove('hidden'));
                    });
                    document.querySelectorAll(`[data-modal-hide="${modalId}"]`).forEach(btn => {
                        btn.addEventListener('click', () => modal.classList.add('hidden'));
                    });
                }
            });
        })();
    </script>
@endpush