      <div class="dashboard-main-body ">
          <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
              <h2 class="font-semibold mb-0 !text-[22px]">Edit Drivers </h2>
              <a href="delegates.html"
                  class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-2 px-3">
                  <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                      height="24" fill="none" viewBox="0 0 24 24">
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 12H5m14 0-4 4m4-4-4-4" />
                  </svg>
                  <span>Back</span>
              </a>
          </div>
          <!-- Delegates -->
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
                          <option selected>Mr.</option>
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
                          value="أحمد محمد علي">
                  </div>
                  <div class="col-span-4">
                      <label class="form-label">Name EN:</label>
                      <input type="text"
                          class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                          value="Ahmed Mohamed Ali">
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
                              value="123456789">
                      </div>
                      <div class="col-span-3">
                          <label class="form-label">Car Type:</label>
                          <input type="text"
                              class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                              value="Toyota Camry">
                      </div>
                      <div class="col-span-3">
                          <label class="form-label">Car Number:</label>
                          <input type="text"
                              class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                              value="123456789">
                      </div>
                      <div class="col-span-3">
                          <label class="form-label">Capacity:</label>
                          <input type="text"
                              class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                              value="4">
                      </div>
                  </div>

                  <div class="col-span-12">
                      <label class="form-label">Note 1:</label>
                      <textarea id="message" rows="4"
                          class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-neutral-300  focus:border-blue-500 bg-white"
                          placeholder="Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum."></textarea>
                  </div>

              </div>

              <div class="flex justify-between items-center mt-8">
                  <!-- Add Delegate Button -->
                  <button type="button" id="add-delegates"
                      class="btn text-md mb-[-10px] !bg-[#B68A35] text-white rounded-lg py-[1px] h-12">Save</button>

              </div>
              <!-- Add Delegate Container (Initially Hidden) -->

          </div>

          <h2 class="font-semibold mb-0 !text-[22px] mt-6 mb-4">Reassign</h2>
          <div class="bg-white h-full w-full rounded-lg border-0 p-6">
              <div class="p-4 md:p-5 space-y-6 px-0">
                  <div class="grid  grid-cols-12 gap-2 items-end">
                      <div class="col-span-5">
                          <div class="flex col-span-2 items-end gap-3 max-w-2xl" id="delegation-input">
                              <div class="w-full">
                                  <div class="flex justify-between ">
                                      <label class="form-label block text-gray-700 font-semibold">Delegation ID:</label>
                                  </div>
                                  <input type="text"
                                      class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                                      placeholder="Enter Delegation ID" value="DA25-001" />
                              </div>
                          </div>
                      </div>
                      <div class="col-span-1 text-center">
                          <span class="mb-3 font-semibold"> OR </span>
                      </div>
                      <div class="col-span-6">
                          <div class="grid grid-cols-2 gap-5 items-end">
                              <div>
                                  <label class="form-label block mb-1 text-gray-700 font-medium">Country:</label>
                                  <select class="p-3 rounded-lg w-full border text-sm">
                                      <option selected="" disabled="">Select Country</option>
                                      <option>Saudi Arabia</option>
                                      <option>France</option>
                                      <option>Canada</option>
                                  </select>
                              </div>
                              <button type="button" id="searchBtn"
                                  class="btn text-md !bg-[#B68A35] text-white rounded-lg py-[1px] h-12 flex items-center justify-center gap-2">
                                  <svg class="pe-1 text-[#FFF]" width="25" height="25" fill="none"
                                      viewBox="0 0 24 24">
                                      <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                          d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                                  </svg>
                                  <span class="w-[150px]">Search Delegation ID</span>
                              </button>
                          </div>
                      </div>
                  </div>
              </div>
              <hr class="mx-6">
              <div id="delegationTable" class="hidden">
                  <hr class="mx-6 border-neutral-200 h-5" />
                  <!-- Main Table -->
                  <table class="table-auto mb-0 !border-[#F9F7ED] w-full border border-collapse">
                      <thead>
                          <tr>
                              <th class="p-3 !bg-[#B68A35] text-start text-white"></th>
                              <th class="p-3 !bg-[#B68A35] text-start text-white">Delegation ID</th>
                              <th class="p-3 !bg-[#B68A35] text-start text-white">Continent</th>
                              <th class="p-3 !bg-[#B68A35] text-start text-white">Country</th>
                              <th class="p-3 !bg-[#B68A35] text-start text-white">Team Head</th>
                          </tr>
                      </thead>
                      <tbody>
                          <tr class="text-sm align-middle">
                              <td class="px-4 py-2 border border-gray-200">
                                  <input data-modal-target="delegation-view-modal"
                                      data-modal-toggle="delegation-view-modal" type="radio" name="option"
                                      class="w-4 h-4 !accent-[#B68A35]" />
                              </td>
                              <td class="px-4 py-2 border border-gray-200">DA25-001</td>
                              <td class="px-4 py-2 border border-gray-200">Asia</td>
                              <td class="px-4 py-2 border border-gray-200">India</td>
                              <td class="px-4 py-2 border border-gray-200">Air Chief Marshal Amar Preet Singh</td>
                          </tr>
                          <tr class="text-sm align-middle">
                              <td class="px-4 py-2 border border-gray-200">
                                  <input type="radio" name="option" class="w-4 h-4 !accent-[#B68A35]" />
                              </td>
                              <td class="px-4 py-2 border border-gray-200">DA25-002</td>
                              <td class="px-4 py-2 border border-gray-200">Asia</td>
                              <td class="px-4 py-2 border border-gray-200">Saudi Arabia</td>
                              <td class="px-4 py-2 border border-gray-200">Bandar bin Abdulaziz</td>
                          </tr>
                          <tr class="text-sm align-middle">
                              <td class="px-4 py-2 border border-gray-200">
                                  <input type="radio" name="option" class="w-4 h-4 !accent-[#B68A35]" />
                              </td>
                              <td class="px-4 py-2 border border-gray-200">DA25-003</td>
                              <td class="px-4 py-2 border border-gray-200">Europe</td>
                              <td class="px-4 py-2 border border-gray-200">France</td>
                              <td class="px-4 py-2 border border-gray-200">Mme Nathalie Delattre</td>
                          </tr>
                          <tr class="text-sm align-middle">
                              <td class="px-4 py-2 border border-gray-200">
                                  <input type="radio" name="option" class="w-4 h-4 !accent-[#B68A35]" />
                              </td>
                              <td class="px-4 py-2 border border-gray-200">DA25-004</td>
                              <td class="px-4 py-2 border border-gray-200">Africa</td>
                              <td class="px-4 py-2 border border-gray-200">Cameroon</td>
                              <td class="px-4 py-2 border border-gray-200">Mr. Crispus Kiyonga</td>
                          </tr>
                          <tr class="text-sm align-middle">
                              <td class="px-4 py-2 border border-gray-200">
                                  <input type="radio" name="option" class="w-4 h-4 !accent-[#B68A35]" />
                              </td>
                              <td class="px-4 py-2 border border-gray-200">DA25-005</td>
                              <td class="px-4 py-2 border border-gray-200">North America</td>
                              <td class="px-4 py-2 border border-gray-200">Canada</td>
                              <td class="px-4 py-2 border border-gray-200">Mr. Mark Carney</td>
                          </tr>
                      </tbody>
                  </table>

              </div>
              <div class="flex items-center p-4 md:p-5 border-gray-200 rounded-b px-0 pb-0">
                  <button data-modal-hide="assign-modal" type="button"
                      class="btn text-md !bg-[#B68A35] text-white rounded-lg py-[1px] h-12">Reassign</button>
              </div>
          </div>
      </div>
