@extends('layouts.admin_account', ['title' => __db('delegation_details')])

@section('content')
    <div class="dashboard-main-body">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-10">
            <h2 class="font-semibold text-2xl">{{ __db('delegation') }}</h2>
            <div class="flex gap-3 ms-auto">
                <a href="{{ route('delegations.edit', $delegation) }}" data-modal-hide="default-modal"
                    class="btn text-sm ms-auto !bg-[#B68A35] flex items-center text-white rounded-lg py-3 px-5">
                    {{ __db('edit') }}
                </a>
                <a href="{{ route('delegations.index') }}"
                    class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-2 px-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M19 12H5m14 0-4 4m4-4-4-4" />
                    </svg>
                    {{ __db('back') }}
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-4">
            <div class="xl:col-span-12">
                <div class="bg-white h-full w-full rounded-lg border-0 p-10">
                    <ul class="flex">
                        <li class="flex-1 ">
                            <span
                                class="font-bold !bg-[#B68A35] text-white py-2 px-3 w-full">{{ __db('delegation_id') }}:</span>
                            <h4 class="bg-[#f9f7ed] py-2 px-3">{{ $delegation->delegate_id }}</h4>
                        </li>
                        <li class="flex-1 ">
                            <span
                                class="font-bold !bg-[#B68A35] text-white py-2 px-3 w-full">{{ __db('invitation_from') }}:</span>
                            <h4 class="bg-[#f9f7ed] py-2 px-3">{{ $delegation->invitationFrom->value ?? '-' }}</h4>
                        </li>
                        <li class="flex-1 ">
                            <span
                                class="font-bold !bg-[#B68A35] text-white py-2 px-3 w-full">{{ __db('continent') }}:</span>
                            <h4 class="bg-[#f9f7ed] py-2 px-3">{{ $delegation->continent->value ?? '-' }}</h4>
                        </li>
                        <li class="flex-1 ">
                            <span class="font-bold !bg-[#B68A35] text-white py-2 px-3 w-full">{{ __db('country') }}:</span>
                            <h4 class="bg-[#f9f7ed] py-2 px-3">{{ $delegation->country->value ?? '-' }}</h4>
                        </li>

                        <li class="flex-1 ">
                            <span
                                class="font-bold !bg-[#B68A35] text-white py-2 px-3 w-full">{{ __db('invitation_status') }}:</span>
                            <h4 class="bg-[#f9f7ed] py-2 px-3">{{ $delegation->invitationStatus->value ?? '-' }}</h4>
                        </li>
                        <li class="flex-1 ">
                            <span
                                class="font-bold !bg-[#B68A35] text-white py-2 px-3 w-full">{{ __db('participation_status') }}:</span>
                            <h4 class="bg-[#f9f7ed] py-2 px-3">{{ $delegation->participationStatus->value ?? '-' }}</h4>
                        </li>
                    </ul>
                    <hr class="my-5">

                    <ul class="flex gap-6 justify-start">
                        <li>
                            <span class="font-bold !bg-[#B68A35] text-white py-2 px-3 w-full">{{ __db('note_1') }}:</span>
                            <div class="bg-[#f9f7ed] py-2 px-3">
                                <h4>{{ $delegation->note1 ?? '-' }}</h4>

                            </div>
                        </li>
                        <li>
                            <span class="font-bold !bg-[#B68A35] text-white py-2 px-3 w-full">{{ __db('note_2') }}:</span>
                            <div class="bg-[#f9f7ed] py-2 px-3">
                                <h4>{{ $delegation->note2 ?? '-' }}</h4>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <hr class="mx-6 border-neutral-200 h-10">
        <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('delegates') }}</h2>
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
            <div class="xl:col-span-12 h-full">
                <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">

                    <table class="table-auto mb-0 !border-[#F9F7ED] w-full">
                        <thead>
                            <tr>
                                <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">{{ __db('title') }}</th>
                                <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">{{ __db('name') }}</th>
                                <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">
                                    {{ __db('designation') }}</th>
                                <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">
                                    {{ __db('internal_ranking') }}</th>
                                <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">{{ __db('gender') }}
                                </th>
                                <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">{{ __db('parent_id') }}
                                </th>
                                <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">
                                    {{ __db('relationship') }}</th>
                                <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">
                                    {{ __db('badge_printed') }}</th>
                                <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">
                                    {{ __db('participation_status') }}</th>
                                <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">
                                    {{ __db('accommodation') }}</th>
                                <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">
                                    {{ __db('arrival_status') }}</th>
                                <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">{{ __db('action') }}
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @if ($delegation->delegates->count())
                                @foreach ($delegation->delegates as $delegate)
                                    <tr class="odd:bg-[#F9F7ED] text-sm align-[middle]">
                                        <td class="px-4 py-3">{{ $delegate->title ?? '-' }}</td>

                                        <td class="px-4 py-3">
                                            @if ($delegate->team_head)
                                                <span
                                                    class="bg-[#B68A35] font-semibold text-[10px] px-3 py-[1px] rounded-lg text-white">TH</span>
                                            @endif

                                            <div class="block">{{ $delegate->name_en ?? ($delegate->name_ar ?? '-') }}
                                            </div>
                                        </td>

                                        <td class="px-4 py-3">{{ $delegate->designation_en ?? '-' }}</td>
                                        <td class="px-4 py-3">{{ $delegate->internal_ranking ?? '-' }}</td>
                                        <td class="px-4 py-3">{{ $delegate->gender?->value ?? '-' }}</td>
                                        <td class="px-4 py-3">{{ $delegate->parent?->delegate_id ?? '-' }}</td>
                                        <td class="px-4 py-3">{{ $delegate->relationship ?? '-' }}</td>
                                        <td class="px-4 py-3">{{ $delegate->badge_printed ? 'Yes' : 'No' }}</td>
                                        <td class="px-4 py-3">{{ $delegation->participationStatus->value ?? '-' }}</td>
                                        <td class="px-4 py-3">{{ $delegate->accommodation ?? '-' }}</td>
                                        <td class="px-4 py-2">
                                            {{ $delegate->arrival_status ?? '-' }}
                                            {{-- <svg class=" cursor-pointer" width="36" height="30"
                                            data-modal-target="default-modal3" data-modal-toggle="default-modal3"
                                            viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg" fill="#B68A35">
                                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                            </g>
                                            <g id="SVGRepo_iconCarrier">
                                                <rect width="480" height="32" x="16" y="464"
                                                    fill="var(--ci-primary-color, #B68A35)" class="ci-primary"></rect>
                                                <path fill="var(--ci-primary-color, #B68A35)"
                                                    d="M455.688,152.164c-23.388-6.515-48.252-6.053-70.008,1.3l-.894.3-65.1,30.94L129.705,109.176a47.719,47.719,0,0,0-49.771,8.862L54.5,140.836a24,24,0,0,0,2.145,37.452l117.767,83.458-45.173,23.663L93.464,252.722a48.067,48.067,0,0,0-51.47-8.6l-19.455,8.435a24,24,0,0,0-11.642,33.3L83.718,422.684,480.3,227.21c23.746-11.177,26.641-29.045,21.419-42.059C495.931,170.723,479.151,158.7,455.688,152.164Zm10.9,46.133-.149.07L97.394,380.267l-54.176-101.8,11.5-4.987a16.021,16.021,0,0,1,17.157,2.867l52.336,47.819,111.329-58.318L83.322,157.974l17.971-16.108a15.908,15.908,0,0,1,16.59-2.954l202.943,80.681,75.95-36.095c15.456-5.009,33.863-5.165,50.662-.413,13.834,3.914,21.182,9.6,23.672,12.582A24.211,24.211,0,0,1,466.59,198.3Z"
                                                    class="ci-primary"></path>
                                            </g>
                                        </svg> --}}
                                        </td>

                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-5">

                                                <form method="POST"
                                                    action="{{ route('delegations.delete', $delegate->id) }}"
                                                    class="delete-delegate-form" data-delegate-id="{{ $delegate->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="delete-delegate-btn" title="Delete"
                                                        style="background:none; border:none; padding:0; cursor:pointer;">
                                                        <svg class="w-5.5 h-5.5" aria-hidden="true"
                                                            xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" fill="none" viewBox="0 0 24 24"
                                                            stroke="#B68A35" stroke-width="1.5" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path
                                                                d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                                        </svg>
                                                    </button>
                                                </form>

                                                <a href="{{ route('delegations.edit', $delegate->id) }}" title="Edit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        viewBox="0 0 512 512" fill="#B68A35">
                                                        <path
                                                            d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>

                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="px-4 py-3 text-center">{{ __db('no_delegates_found') }}.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        {{--
        <hr class="mx-6 border-neutral-200 h-10">
        <h2 class="font-semibold mb-0 !text-[22px] ">Escorts</h2>
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
            <div class="xl:col-span-12 h-full">
                <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                    <table class="table-auto mb-0 !border-[#F9F7ED] w-full">
                        <thead>
                            <tr>
                                <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">Military Number</th>
                                <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">Title</th>
                                <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">Name</th>
                                <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">Mobile Number</th>
                                <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">Gender</th>
                                <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">Known Languages</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="odd:bg-[#F9F7ED] text-sm align-[middle]">
                                <td class="px-4 py-3">UM123</td>
                                <td class="px-4 py-3">Captain</td>
                                <td class="px-4 py-3">Amar Preet Singh</td>
                                <td class="px-4 py-3 text-end" dir="ltr">+971 50 123 4567</td>
                                <td class="px-4 py-3">Male</td>
                                <td class="px-4 py-3">Arabic, English</td>
                            </tr>
                            <tr class="odd:bg-[#F9F7ED] text-sm align-[middle]">
                                <td class="px-4 py-3">DX456</td>
                                <td class="px-4 py-3">HH</td>
                                <td class="px-4 py-3">Laila Al Kaabi</td>
                                <td class="px-4 py-3 text-end" dir="ltr">+971 55 234 7890</td>
                                <td class="px-4 py-3">Female</td>
                                <td class="px-4 py-3">Arabic</td>
                            </tr>
                            <tr class="odd:bg-[#F9F7ED] text-sm align-[middle]">
                                <td class="px-4 py-3">AB789</td>
                                <td class="px-4 py-3">Major</td>
                                <td class="px-4 py-3">Yousef Al Ali</td>
                                <td class="px-4 py-3 text-end" dir="ltr">+971 52 345 6789</td>
                                <td class="px-4 py-3">Male</td>
                                <td class="px-4 py-3">Arabic, English, French</td>
                            </tr>
                            <tr class="odd:bg-[#F9F7ED] text-sm align-[middle]">
                                <td class="px-4 py-3">SH321</td>
                                <td class="px-4 py-3">Ms</td>
                                <td class="px-4 py-3">Sara Mansour</td>
                                <td class="px-4 py-3 text-end" dir="ltr">+971 56 987 6543</td>
                                <td class="px-4 py-3">Female</td>
                                <td class="px-4 py-3">English</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div> --}}


        {{-- <hr class="mx-6 border-neutral-200 h-10">
        <h2 class="font-semibold mb-0 !text-[22px] ">Drivers
        </h2>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
            <div class="xl:col-span-12 h-full">
                <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                    <table class="table-auto mb-0 !border-[#F9F7ED] w-full">
                        <thead>
                            <tr>
                                <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">Military Number</th>
                                <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">Title</th>
                                <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">Name</th>
                                <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">Mobile Number</th>
                                <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">Vehicle Type</th>
                                <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">Plate Number</th>
                                <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">Capacity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="odd:bg-[#F9F7ED] text-sm align-[middle]">
                                <td class="px-4 py-3">MIL-1024</td>
                                <td class="px-4 py-3">Captain</td>
                                <td class="px-4 py-3">Saeed Al Kaabi</td>
                                <td class="px-4 py-3 text-end" dir="ltr">+971 55 789 3210</td>
                                <td class="px-4 py-3">SUV</td>
                                <td class="px-4 py-3">DXB 4567</td>
                                <td class="px-4 py-3">5</td>
                            </tr>
                            <tr class="odd:bg-[#F9F7ED] text-sm align-[middle]">
                                <td class="px-4 py-3">MIL-2548</td>
                                <td class="px-4 py-3">Mr</td>
                                <td class="px-4 py-3">Mohammed Al Obaidi</td>
                                <td class="px-4 py-3 text-end" dir="ltr">+971 50 112 3344</td>
                                <td class="px-4 py-3">Sedan</td>
                                <td class="px-4 py-3">AUH 2345</td>
                                <td class="px-4 py-3">4</td>
                            </tr>
                            <tr class="odd:bg-[#F9F7ED] text-sm align-[middle]">
                                <td class="px-4 py-3">MIL-8789</td>
                                <td class="px-4 py-3">Ms</td>
                                <td class="px-4 py-3">Fatima Al Zahra</td>
                                <td class="px-4 py-3 text-end" dir="ltr">+971 52 223 4455</td>
                                <td class="px-4 py-3">Hatchback</td>
                                <td class="px-4 py-3">SHJ 9876</td>
                                <td class="px-4 py-3">5</td>
                            </tr>
                            <tr class="odd:bg-[#F9F7ED] text-sm align-[middle]">
                                <td class="px-4 py-3">MIL-0024</td>
                                <td class="px-4 py-3">Captain</td>
                                <td class="px-4 py-3">John Doe</td>
                                <td class="px-4 py-3 text-end" dir="ltr">+971 58 667 8899</td>
                                <td class="px-4 py-3">Crossover</td>
                                <td class="px-4 py-3">RAK 1234</td>
                                <td class="px-4 py-3">4</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div> --}}

        {{-- <hr class="mx-6 border-neutral-200 h-10">
        <h2 class="font-semibold mb-0 !text-[22px] ">Interviews
        </h2>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
            <div class="xl:col-span-12 h-full">
                <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                    <table class="table-auto mb-0 !border-[#F9F7ED] w-full">
                        <thead>
                            <tr>
                                <th class="p-3 !bg-[#B68A35] text-start text-white">Date & Time</th>
                                <th class="p-3 !bg-[#B68A35] text-start text-white">Attended By</th>
                                <th class="p-3 !bg-[#B68A35] text-start text-white">Interview With</th>
                                <th class="p-3 !bg-[#B68A35] text-start text-white">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="odd:bg-[#F9F7ED] text-sm align-[middle]">
                                <td class="px-4 py-3 text-end" dir="ltr">2025-06-18 10:00 AM</td>
                                <td class="px-4 py-3">
                                    <span class="block">Sr.Sara Al-Suwaidi</span>
                                    <span class="block">Mr.Dalia Al-Hassan</span>
                                </td>
                                <td class="px-4 py-3">


                                    <a href="#" class="!text-[#B68A35]" data-modal-target="DelegationModal"
                                        data-modal-toggle="DelegationModal"> Delegation ID : DA25-002</a>
                                    <span class="block">Khalid</span>
                                    <span class="block">Omar</span>

                                </td>
                                <td class="px-4 py-3 text-black">Pending</td>
                            </tr>
                            <tr class="odd:bg-[#F9F7ED] text-sm align-[middle]">
                                <td class="px-4 py-3 text-end" dir="ltr">2025-07-11 11:30 AM</td>
                                <td class="px-4 py-3">Dr.Bandar bin Abdulaziz </td>
                                <td class="px-4 py-3">Hadi</td>
                                <td class="px-4 py-3 text-black">Accepted</td>
                            </tr>
                            <tr class="odd:bg-[#F9F7ED] text-sm align-[middle]">
                                <td class="px-4 py-3 text-end" dir="ltr">2025-06-26 01:00 PM</td>
                                <td class="px-4 py-3">Mr. Crispus Kiyonga, </td>
                                <td class="px-4 py-3">Rashed</td>
                                <td class="px-4 py-3 text-black">Canceled</td>
                            </tr>
                            <tr class="odd:bg-[#F9F7ED] text-sm align-[middle]">
                                <td class="px-4 py-3 text-end" dir="ltr">2025-09-14 02:30 PM</td>
                                <td class="px-4 py-3">Mr.Mark Carney </td>
                                <td class="px-4 py-3">Zayed</td>
                                <td class="px-4 py-3 text-black">Completed</td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div> --}}

        <h4 class="text-lg font-semibold mb-3 mt-6">{{ __db('attachments') }}</h4>
        <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
            <table class="table-auto mb-0 !border-[#F9F7ED] w-full">
                <thead>
                    <tr>
                        <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">{{ __db('title') }}</th>
                        <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">{{ __db('file_name') }}</th>
                        <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">{{ __db('uploaded_file') }}
                        </th>
                        <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">{{ __db('uploaded_date') }}
                        </th>
                        <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">{{ __db('document_date') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if ($delegation->attachments->count())
                        @foreach ($delegation->attachments as $attachment)
                            <tr class="odd:bg-[#F9F7ED] text-sm align-[middle]">
                                <td class="px-4 py-3">{{ $attachment->title->value ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank"
                                        class="font-medium !text-[#B68A35] ">{{ $attachment->file_name }}</a>
                                </td>
                                <td class="px-4 py-3">
                                    {{ $attachment->created_at ? $attachment->created_at->format('d-m-Y') : '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ $attachment->document_date ? \Illuminate\Support\Carbon::parse($attachment->document_date)->format('d-m-Y') : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" class="px-4 py-3 text-center">{{ __db('no_attachments_found') }}.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>



    </div>
@endsection

@section('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.delete-delegate-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: "{{ __db('are_you_sure') }}",
                        text: "{{ __db('delete_delegate_confirm_msg') }}",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: "{{ __db('yes_delete') }}",
                        cancelButtonText: "{{ __db('cancel') }}"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });
            });
        });
    </script>




@endsection
