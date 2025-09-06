@php
    $mode = old($type . '.mode', $transport->mode ?? 'flight');
    $dateTime =
        $transport && $transport->date_time ? \Carbon\Carbon::parse($transport->date_time)->format('Y-m-d\TH:i') : '';
@endphp

<h2 class="font-semibold text-2xl mb-4">{{ $title }}</h2>
<div class="bg-gray-50 rounded-lg p-6" data-transport-section="{{ $type }}">
    <div class="flex items-center gap-4 mb-5">
        <label class="flex items-center gap-1.5 cursor-pointer">
            <input type="radio" name="{{ $type }}[mode]" value="flight" class="form-radio"
                @if ($mode === 'flight') checked @endif />
            <span class="text-sm text-gray-700">{{ __db('flight') }}</span>
        </label>
        <label class="flex items-center gap-1.5 cursor-pointer">
            <input type="radio" name="{{ $type }}[mode]" value="land" class="form-radio"
                @if ($mode === 'land') checked @endif />
            <span class="text-sm text-gray-700">{{ __db('land') }}</span>
        </label>
        <label class="flex items-center gap-1.5 cursor-pointer">
            <input type="radio" name="{{ $type }}[mode]" value="sea" class="form-radio"
                @if ($mode === 'sea') checked @endif />
            <span class="text-sm text-gray-700">{{ __db('sea') }}</span>
        </label>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-5 gap-5 w-full">

        <div data-mode-fields="flight" class="md:col-span-3 grid grid-cols-1 md:grid-cols-3 gap-5">
            <div>
                <label class="form-label block mb-1 text-sm">{{ __db($type . '_airport') }}:</label>
                <select name="{{ $type }}[airport_id]"
                    class="select2 p-3 rounded-lg w-full border border-neutral-300 text-sm">
                    <option value="">{{ __db('select_airport') }}</option>
                    @foreach (getDropdown('airports')->options as $airport)
                        <option value="{{ $airport->id }}" @if (old($type . '.airport_id', $transport->airport_id ?? '') == $airport->id) selected @endif>
                            {{ $airport->value }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label block mb-1 text-sm">{{ __db('flight_no') }}:</label>
                <input name="{{ $type }}[flight_no]" type="text"
                    value="{{ old($type . '.flight_no', $transport->flight_no ?? '') }}"
                    class="p-3 rounded-lg w-full border border-neutral-300 text-sm" />
            </div>
            <div>
                <label class="form-label block mb-1 text-sm">{{ __db('flight_name') }}:</label>
                <input name="{{ $type }}[flight_name]" type="text"
                    value="{{ old($type . '.flight_name', $transport->flight_name ?? '') }}"
                    class="p-3 rounded-lg w-full border border-neutral-300 text-sm" />
            </div>
        </div>


        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="form-label block mb-1 text-sm">{{ __db('date_time') }}:</label>
                <input name="{{ $type }}[date_time]" type="datetime-local"
                    value="{{ old($type . '.date_time', $dateTime) }}"
                    class="p-3 rounded-lg w-full border border-neutral-300 text-sm" />
            </div>

            @php
                $arrivalStatuses = [
                    'arrived' => __db('arrived'),
                    'to_be_arrived' => __db('to_be_arrived'),
                ];
                $departureStatuses = [
                    'to_be_departed' => __db('to_be_departed'),
                    'departed' => __db('departed'),
                ];

                $statuses = $type === 'arrival' ? $arrivalStatuses : $departureStatuses;

                $selectedStatus = old(
                    $type . '.status',
                    $transport->status ?? ($type === 'arrival' ? 'to_be_arrived' : 'to_be_departed'),
                );

            @endphp

            <div>
                <label class="form-label block mb-1 text-sm">{{ __db('status') }}:</label>
                <select name="{{ $type }}[status]"
                    class="p-3 rounded-lg w-full border border-neutral-300 text-sm">
                    <option value="">{{ __db('select_status') }}</option>
                    @foreach ($statuses as $value => $label)
                        <option value="{{ $value }}" @if ($selectedStatus == $value) selected @endif>
                            {{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>


    <div data-mode-fields="land sea" class="mt-4">
        <label class="form-label block mb-1 text-sm">{{ __db('comment') }}:</label>
        <textarea name="{{ $type }}[comment]" rows="3"
            class="block p-2.5 w-full text-sm rounded-lg border border-neutral-300">{{ old($type . '.comment', $transport->comment ?? '') }}</textarea>
    </div>
</div>
