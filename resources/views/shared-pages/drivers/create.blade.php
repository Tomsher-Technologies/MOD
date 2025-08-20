<div class="dashboard-main-body ">
    <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
        <h2 class="font-semibold mb-0 !text-[22px]">Add Drivers</h2>
        <a href="delegates.html" class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-2 px-3">
            <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 12H5m14 0-4 4m4-4-4-4" />
            </svg>
            <span>Back</span>
        </a>
    </div>
    <!-- DAdd Delegation -->
    <div class="bg-white h-full w-full rounded-lg border-0 p-6 mb-10">
        <div class="grid grid-cols-12 gap-5">
            <div class="col-span-4">
                <label class="form-label">Military Number:</label>
                <input type="text"
                    class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                    value="123456789">

            </div>

            <div class="col-span-4">
                <label class="form-label">Title:</label>
                <select
                    class="p-3 rounded-lg w-full text-sm border border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                    <option selected="" disabled="">Select Title</option>
                    <option>Mr.</option>
                    <option>Mrs.</option>
                    <option>Ms.</option>
                    <option>Miss</option>
                    <option>Dr.</option>
                    <option>Prof.</option>
                </select>
            </div>
            <div class="col-span-4">
                <label class="form-label">Name AR:</label>
                <input type="text"
                    class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                    placeholder="أدخل الاسم هنا">
            </div>
            <div class="col-span-4">
                <label class="form-label">Name EN:</label>
                <input type="text"
                    class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                    placeholder="Enter Name Here">
            </div>



            <div class="col-span-4">
                <label class="form-label">Mobile Number:</label>
                <input type="text"
                    class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                    value="123456789">

            </div>

            <div class="col-span-12 grid grid-cols-12 gap-5">
                <div class="col-span-3">
                    <label class="form-label">Driver ID:</label>
                    <input type="text"
                        class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        placeholder="Enter Military Number">
                </div>
                <div class="col-span-3">
                    <label class="form-label">Car Type:</label>
                    <input type="text"
                        class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        placeholder="Enter Military Number">
                </div>
                <div class="col-span-3">
                    <label class="form-label">Car Number:</label>
                    <input type="text"
                        class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        placeholder="Enter Military Number">
                </div>
                <div class="col-span-3">
                    <label class="form-label">Capacity:</label>
                    <input type="text"
                        class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        placeholder="Enter Military Number">
                </div>
            </div>



            <div class="col-span-12">
                <label class="form-label">Note 1:</label>
                <textarea id="message" rows="4"
                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-neutral-300  focus:border-blue-500 bg-white"
                    placeholder="Type here..."></textarea>
            </div>



        </div>

        <div class="flex justify-between items-center mt-8">
            <!-- Add Delegate Button -->
            <button type="button" id="add-delegates"
                class="btn text-md mb-[-10px] !bg-[#B68A35] text-white rounded-lg py-[1px] h-12">Add Drivers</button>

        </div>
    </div>
</div>
