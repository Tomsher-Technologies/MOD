@props(['columns', 'data', 'noDataMessage' => null, 'rowClass' => null, 'enableRowLimit' => false])

<div class="">

    @if (
        ($data instanceof \Illuminate\Contracts\Pagination\Paginator ||
            $data instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator) &&
            $enableRowLimit)
        <div class="flex justify-end items-center mb-2 mt-4">
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
                    class="border rounded px-5 py-1 text-sm">
                    @foreach ([10, 25, 50, 100] as $size)
                        <option value="{{ $size }}" {{ request('limit', 25) == $size ? 'selected' : '' }}>
                            {{ $size }}
                        </option>
                    @endforeach
                </select>
                <span class="ml-2 text-sm">rows</span>
            </form>
        </div>
    @endif

    <table class="table-auto mb-0  !border-[#F9F7ED] w-full">
        <thead>
            <tr>
                @foreach ($columns as $column)
                    @php
                        $permissionKey = $column['permission'] ?? null;
                        $colPermissions = null;

                        if ($permissionKey) {
                            $colPermissions = is_array($permissionKey) ? $permissionKey : [$permissionKey];
                        }
                    @endphp

                    @if (!$colPermissions || auth()->user()->canAny($colPermissions))
                        <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                            {{ $column['label'] }}
                        </th>
                    @endif
                @endforeach

            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $row)
                @php
                    $rowPermissionsKey = $row->permission ?? null;
                    $rowPermissions = null;

                    if ($rowPermissionsKey) {
                        $rowPermissions = is_array($rowPermissionsKey) ? $rowPermissionsKey : [$rowPermissionsKey];
                    }
                @endphp

                @if (!$rowPermissions || auth()->user()->canAny($rowPermissions))
                    <tr class="text-sm align-[middle] {{ $rowClass ? $rowClass($row) : '' }}"
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

                            @if (!$colPermissions || auth()->user()->canAny($colPermissions))
                                <td class="px-4 py-2 border border-gray-200"
                                    data-column="{{ Str::slug($column['label']) }}">
                                    {!! $column['render']($row, $key) !!}
                                </td>
                            @endif
                        @endforeach
                    </tr>
                @endif
            @endforeach

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
