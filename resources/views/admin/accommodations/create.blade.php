@extends('layouts.admin_account', ['title' => __db('create_accommodation')])

@section('content')
    @include('shared-pages.accommodations.create')
@endsection

@section('script')
<script>
    let roomIndex = 1;
    let contactIndex = 1;

    document.getElementById('add-room-btn').addEventListener('click', function () {
        let container = document.getElementById('room-container');
        let row = `
            <div class="grid grid-cols-12 gap-5 room-row mt-2">
                <div class="col-span-4">
                    <label class="form-label">{{ __db('room_type') }}:</label>
                    <select name="rooms[${roomIndex}][room_type]" class="select2 p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600 focus:border-primary-600 focus:ring-0">
                        @foreach ($roomTypes as $roomType)
                            <option value="{{ $roomType->id }}">{{ $roomType->value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-4">
                    <label class="form-label">{{ __db('total_rooms') }}:</label>
                    <input type="number" name="rooms[${roomIndex}][total_rooms]" class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                </div>
                <div class="col-span-3 flex items-end">
                    <button type="button" class="delete-room bg-red-600 text-white px-4 py-2 rounded">{{ __db('delete') }}</button>
                </div>
            </div>`;
        container.insertAdjacentHTML('beforeend', row);
        $(container).find('.select2').last().select2({
            width: '100%'
        });

        $(`select[name="rooms[${roomIndex}][room_type]"]`).rules("add", {
            required: true,
            messages: { required: "{{ __db('this_field_is_required') }}" }
        });

        $(`input[name="rooms[${roomIndex}][total_rooms]"]`).rules("add", {
            required: true,
            digits: true,
            min: 1,
            messages: {
                required: "{{ __db('this_field_is_required') }}",
                digits: "{{ __db('only_numbers_allowed') }}"
            }
        });
        
        roomIndex++;
    });

    document.getElementById('room-container').addEventListener('click', function(e){
        if(e.target.classList.contains('delete-room')){
            e.target.closest('.room-row').remove();
        }
    });

    // Contacts
    document.getElementById('add-attachment-btn').addEventListener('click', function () {
        let container = document.getElementById('attachment-container');
        let row = `
            <div class="grid grid-cols-12 gap-5 attachment-row mt-2">
                <div class="col-span-4">
                    <label class="form-label">{{ __db('contact_person_name') }}:</label>
                    <input type="text" name="contacts[${contactIndex}][name]" placeholder="{{ __db('enter') }}" class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                </div>
                <div class="col-span-4">
                    <label class="form-label">{{ __db('contact_number') }}:</label>
                    <input type="text" name="contacts[${contactIndex}][phone]" placeholder="{{ __db('enter') }}" class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                </div>
                <div class="col-span-3 flex items-end">
                    <button type="button" class="delete-contact bg-red-600 text-white px-4 py-2 rounded">{{ __db('delete') }}</button>
                </div>
            </div>`;
        container.insertAdjacentHTML('beforeend', row);

        $(`input[name="contacts[${contactIndex}][name]"]`).rules("add", {
            required: true,
            messages: { required: "{{ __db('this_field_is_required') }}" }
        });

        $(`input[name="contacts[${contactIndex}][phone]"]`).rules("add", {
            required: true,
            phonePattern: true,
            messages: { 
                required: "{{ __db('this_field_is_required') }}"
            }
        });

        contactIndex++;
    });

    document.getElementById('attachment-container').addEventListener('click', function(e){
        if(e.target.classList.contains('delete-contact')){
            e.target.closest('.attachment-row').remove();
        }
    });

    document.addEventListener("DOMContentLoaded", () => {
        $.validator.addMethod("phonePattern", function(value, element) {
            return this.optional(element) || /^[+]?[0-9]{7,15}$/.test(value);
        }, "{{ __db('phone_regex') }}");

        
        $("#accommodationForm").validate({
            rules: {
                hotel_name: {
                    required: true
                },
                contact_number: {
                    required: true,
                    phonePattern: true
                },
                // Validate first room row
                "rooms[0][room_type]": {
                    required: true
                },
                "rooms[0][total_rooms]": {
                    required: true,
                    digits: true
                },
                "contacts[0][name]": {
                    required: true
                },
                "contacts[0][phone]": {
                    required: true,
                    phonePattern: true
                }
            },
            messages: {
                hotel_name: {
                    required: "{{ __db('this_field_is_required') }}",
                },
                contact_number: {
                    required: "{{ __db('this_field_is_required') }}",
                    digits: "{{ __db('only_numbers_allowed') }}",
                    minlength: "{{ __db('at_least_8_digits') }}",
                    maxlength: "{{ __db('not_more_than_15_digits') }}"
                },
                "rooms[0][room_type]": {
                    required: "{{ __db('this_field_is_required') }}"
                },
                "rooms[0][total_rooms]": {
                    required: "{{ __db('this_field_is_required') }}",
                    digits: "{{ __db('only_numbers_allowed') }}"
                },
                "contacts[0][name]": {
                    required: "{{ __db('this_field_is_required') }}"
                },
                "contacts[0][phone]": {
                    required: "{{ __db('this_field_is_required') }}",
                }
            },
            errorClass: "text-red-500 text-sm", // Tailwind error style
            errorElement: "span",
            errorPlacement: function (error, element) {
                error.addClass('text-red-500 text-sm');

                if (element.hasClass('select2-hidden-accessible')) {
                    error.insertAfter(element.next('.select2')); 
                } else if (element.attr("type") === "checkbox") {
                    error.insertAfter(element.closest('label'));
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function (element) {
                $(element).addClass("border-red-500");
            },
            unhighlight: function (element) {
                $(element).removeClass("border-red-500");
            }
        });
    });
</script>

@endsection