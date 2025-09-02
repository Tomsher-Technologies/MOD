@props(['columns', 'data', 'noDataMessage' => null, 'rowClass' => null, 'enableRowLimit' => false])

<div class="">

    @if (
        ($data instanceof \Illuminate\Contracts\Pagination\Paginator ||
            $data instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator) &&
            $enableRowLimit)
           
        <div class="flex justify-end items-center mb-3">
            
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
                <span class="mr-2 text-sm">rows</span>
            </form>
        </div>
    @endif

    <table class="table-auto mb-0  !border-[#F9F7ED] w-full">
        <thead>
            <tr class="text-[11px]">
                @foreach ($columns as $column)
                    @php
                        $permissionKey = $column['permission'] ?? null;
                        $colPermissions = null;

                        if ($permissionKey) {
                            $colPermissions = is_array($permissionKey) ? $permissionKey : [$permissionKey];
                        }
                    @endphp

                    @if (!$colPermissions || can($colPermissions))
                        <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
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
                        $rowPermissionsKey = $row->permission ?? null;
                        $rowPermissions = null;

                        if ($rowPermissionsKey) {
                            $rowPermissions = is_array($rowPermissionsKey) ? $rowPermissionsKey : [$rowPermissionsKey];
                        }
                    @endphp

                @if (!$rowPermissions || can($rowPermissions))
                    <tr class="text-[9px]  align-[middle] {{ $rowClass ? $rowClass($row) : '' }}"
                        data-id="{{ $row->id }}">
                        @foreach ($columns as $column)
                            @php
                                $colPermissionKey = $column['permission'] ?? null;
                                $colPermissions = null;
                                if ($colPermissionKey) {
                                    $colPermissions = is_array($colPermissionKey)
                                        ? $colPermissionKey
                                        : [$colPermissionKey];
                                }
                            @endphp

                                @if (!$colPermissions || can($colPermissions))
                                    <td class="px-4 py-2 border border-gray-200"
                                        data-column="{{ Str::slug($column['label']) }}">
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

    <div class="flex flex-col">
        <div>

            @if (
                $data instanceof \Illuminate\Contracts\Pagination\Paginator ||
                    $data instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
                <div class="mt-4">
                    {{ $data->links() }}
                </div>
            @endif

        </div>


    </div>


</div>
