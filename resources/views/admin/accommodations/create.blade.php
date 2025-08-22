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
                <label class="form-label">Room Type:</label>
                <select name="rooms[${roomIndex}][room_type]" class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600 focus:border-primary-600 focus:ring-0">
                    <option>Single Room</option>
                    <option>Double Room</option>
                    <option>King Room</option>
                </select>
            </div>
            <div class="col-span-4">
                <label class="form-label">Total Rooms:</label>
                <input type="number" name="rooms[${roomIndex}][total_rooms]" class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
            </div>
            <div class="col-span-3 flex items-end">
                <button type="button" class="delete-room bg-red-600 text-white px-4 py-2 rounded">Delete</button>
            </div>
        </div>`;
    container.insertAdjacentHTML('beforeend', row);
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
                <label class="form-label">Contact Person Name:</label>
                <input type="text" name="contacts[${contactIndex}][name]" placeholder="Enter Contact Person Name" class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
            </div>
            <div class="col-span-4">
                <label class="form-label">Contact Number:</label>
                <input type="text" name="contacts[${contactIndex}][phone]" placeholder="Enter Contact Number" class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
            </div>
            <div class="col-span-3 flex items-end">
                <button type="button" class="delete-contact bg-red-600 text-white px-4 py-2 rounded">Delete</button>
            </div>
        </div>`;
    container.insertAdjacentHTML('beforeend', row);
    contactIndex++;
});

document.getElementById('attachment-container').addEventListener('click', function(e){
    if(e.target.classList.contains('delete-contact')){
        e.target.closest('.attachment-row').remove();
    }
});
</script>

@endsection