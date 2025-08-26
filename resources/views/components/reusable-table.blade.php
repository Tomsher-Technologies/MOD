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
                    <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                        {{ $column['label'] }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $key => $row)
                <tr class=" text-sm align-[middle] {{ $rowClass ? $rowClass($row) : '' }}"
                    data-id="{{ $row->id }}">
                    @foreach ($columns as $column)
                        <td class="px-4 py-2 border border-gray-200" data-column="{{ Str::slug($column['label']) }}">
                            {!! $column['render']($row, $key) !!}
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($columns) }}" class="px-4 py-2 border border-gray-200">
                        {{ $noDataMessage ?? __db('no_data_found') }}
                    </td>
                </tr>
            @endforelse
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
