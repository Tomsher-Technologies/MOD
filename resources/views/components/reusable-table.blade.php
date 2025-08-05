<div>
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
                <tr class=" text-sm align-[middle]">
                    @foreach ($columns as $column)
                         <td class="px-4 py-2 border border-gray-200">
                            {!! $column['render']($row, $key) !!}
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($columns) }}" class="px-4 py-2 border border-gray-200">
                        {{ $noDataMessage }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
