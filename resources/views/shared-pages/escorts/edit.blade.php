<div class="dashboard-main-body ">
    <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
        <h2 class="font-semibold mb-0 !text-[22px]">Edit Escorts</h2>
        <a href="delegates.html" class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-2 px-3">
            <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 12H5m14 0-4 4m4-4-4-4" />
            </svg>
            <span>Back</span>
        </a>
    </div>
    <!-- Delegates -->
    <div class="bg-white h-full w-full rounded-lg border-0 p-6">
        <div class="grid grid-cols-12 gap-5">
            <div class="col-span-4">
                <label class="form-label">Military Number:</label>
                <input type="text"
                    class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                    value="123456789">

            </div>
            <div class="col-span-4">
                <label class="form-label">Name AR:</label>
                <input type="text"
                    class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                    value="عبد الله محمد">
            </div>
            <div class="col-span-4">
                <label class="form-label">Name EN:</label>
                <input type="text"
                    class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                    value="Abdullah Mohammed">
            </div>

            <div class="col-span-4">
                <label class="form-label">Gender:</label>
                <select
                    class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                    <option selected disabled> Select Gender</option>
                    <option selected>Male</option>
                    <option>Female</option>
                </select>
            </div>
            <div class="col-span-4">
                <label class="form-label">Spoken Languages:</label>
                <select id="multiSelect" multiple placeholder="Select Languages"
                    class="w-full p-3 rounded-lg border border-gray-300 text-sm">
                    <option value="English" selected>English</option>
                    <option value="Arabic" selected>Arabic</option>
                    <option value="japan">Hindi</option>
                </select>



            </div>

            <div class="col-span-4">
                <label class="form-label">Rank:</label>
                <select
                    class=" p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600 focus:border-primary-600 focus:ring-0">
                    <option selected disabled> Select Rank</option>
                    <option>General</option>
                    <option>Captain</option>
                    <option selected>Major</option>

                </select>
            </div>




        </div>

        <div class="flex justify-between items-center mt-8">
            <!-- Add Delegate Button -->
            <button type="button" id="add-delegates"
                class="btn text-md mb-[-10px] !bg-[#B68A35] text-white rounded-lg py-[1px] h-12">Save</button>

        </div>

    </div>

    <h2 class="font-semibold mb-0 !text-[22px] mt-6">Reassign</h2>
    <div class="bg-white h-full w-full rounded-lg border-0 p-6 mt-4">

        <div class="flex col-span-2 items-end gap-3" id="delegation-input">
            <div class="max-w-lg w-full">
                <div class="flex justify-between ">
                    <label class="form-label block text-gray-700 font-semibold">Delegation ID:</label>
                </div>
                <input type="text"
                    class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                    placeholder="Enter Delegation ID" value="DA25-001" />
            </div>
            <button type="button" data-modal-target="search-delegation-modal"
                data-modal-toggle="search-delegation-modal"
                class="btn text-md !bg-[#B68A35] text-white rounded-lg py-[1px] h-12 flex items-center gap-2">
                <svg class="pe-1 text-[#FFF]" width="25" height="25" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                        d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                </svg>
                <span class="w-[150px]">Search Delegation ID</span>
            </button>
        </div>

        <button type="button"
            class="btn text-md mb-[-10px] !bg-[#B68A35] text-white rounded-lg py-[1px] h-12 mt-6">Reassign</button>


    </div>


    <!-- Add Delegate Container (Initially Hidden) -->
    <!-- Hidden Template Row -->

</div>
