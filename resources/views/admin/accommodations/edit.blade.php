@extends('layouts.admin_account', ['title' => __db('edit') . ' ' . __db('accommodation')])

@section('content')
    @include('shared-pages.accommodations.edit')
@endsection

@section('script')
<script>
    let roomIndex = {{ $accommodation->rooms->count() }};
    let contactIndex = {{ $accommodation->contacts->count() }};

    document.getElementById('add-room-btn').addEventListener('click', function () {
        let container = document.getElementById('room-container');
        let row = `
            <div class="grid grid-cols-12 gap-5 room-row mt-2">
                <div class="col-span-4">
                    <label class="form-label">{{ __db('room_type') }}:</label>
                    <select name="rooms[${roomIndex}][room_type]" class="select2 p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option value="">{{ __db('select') }}</option>
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
                    <button type="button"  data-id="" data-total="" data-assigned="" class="delete-room bg-red-600 text-white px-4 py-2 rounded">{{ __db('delete') }}</button>
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
        // if(e.target.classList.contains('delete-room')){
        //     e.target.closest('.room-row').remove();
        // }
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
                    require_from_group: [1, ".hotel-name-group"]
                },
                hotel_name_ar: {
                    require_from_group: [1, ".hotel-name-group"]
                },
                address: {
                    required: false
                },
                contact_number: {
                    required: false,
                    phonePattern: true
                },
                // Validate first room row
                "rooms[0][room_type]": {
                    required: false
                },
                "rooms[0][total_rooms]": {
                    required: false,
                    digits: true
                },
                // Validate first contact person row
                "contacts[0][name]": {
                    required: false
                },
                "contacts[0][phone]": {
                    required: false,
                    phonePattern: true
                }
            },
            messages: {
                hotel_name: {
                    require_from_group: "{{ __db('fill_either_english_or_arabic_field') }}",
                },
                hotel_name_ar: {
                    require_from_group: "{{ __db('fill_either_english_or_arabic_field') }}",
                },
                address: {
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

        $(document).on('click', '.delete-room', function () {
            let thisRoom = $(this);
            let total = parseInt($(this).data('total'));
            let assigned = parseInt($(this).data('assigned'));
            let roomId = $(this).data('id');
            let routeUrl = "{{ route('accommodation-rooms.destroy', ':id') }} ".replace(':id', roomId);

            if (!isNaN(assigned) && assigned !== null && assigned !== undefined) {
                if (assigned != 0) {
                    toastr.error("{{ __db('this_room_type_has_been_assigned') }}");
                    return;
                }

                if (assigned == 0) {
                    Swal.fire({
                        title: "{{ __db('are_you_sure') }}",
                        text: "{{ __db('room_type_will_be_deleted') }}",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: "{{ __db('yes') }}"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: routeUrl,
                                type: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                    'Accept': 'application/json'
                                },
                                success: function (data) {
                                    toastr.success("{{ __db('room_type_deleted') }}");
                                    thisRoom.closest('.room-row').remove()
                                },
                                error: function (xhr) {
                                    toastr.error("{{ __db('something_went_wrong') }}");
                                }
                            });
                        }
                    });
                }
            }else{
                thisRoom.closest('.room-row').remove();
            }
        });
    });
</script>

@endsection