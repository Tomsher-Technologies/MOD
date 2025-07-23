@foreach ($permissions as $parent)
    <div class="w-full flex flex-col mt-4">
        <div class="permission-group">
            <label class="flex items-center text-sm gap-2 font-semibold text-gray-800">
                <input type="checkbox" name="permissions[]"
                    value="{{ $parent->name }}"
                    class="parent-checkbox accent-yellow-600 h-4 w-4"
                    data-parent="{{ $parent->name }}">
                {{ $parent->title }}
            </label>

            <div class="flex flex-wrap gap-4 mt-2 ml-6">
                @foreach ($parent->children as $child)
                    <label class="flex items-center  text-sm gap-2 text-gray-700">
                        <input type="checkbox" name="permissions[]"
                            value="{{ $child->name }}"
                            class="child-checkbox accent-yellow-500 h-4 w-4"
                            data-parent="{{ $parent->name }}">
                        {{ $child->title }}
                    </label>
                @endforeach
            </div>
        </div>
    </div>
@endforeach