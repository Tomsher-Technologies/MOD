  <div class="dashboard-main-body ">
      <div class="flex flex-wrap items-center justify-between gap-2 mb-6 mb-10">
          <h2 class="font-semibold mb-0 !text-[22px] "> Assign Escorts</h2>
          <a href="{{ route('escorts.index') }}" id="add-attachment-btn"
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
      <div class="bg-white h-full w-full rounded-lg border-0 p-6">
          <form action="{{ route('escorts.assign', $escort->id) }}" method="POST">
              @csrf
              <div class="p-4 md:p-5 space-y-6 px-0">
                  <div class="grid  grid-cols-12 gap-2 items-end">
                      <div class="col-span-5">
                          <div class="flex col-span-2 items-end gap-3 max-w-2xl" id="delegation-input">
                              <div class="w-full">
                                  <div class="flex justify-between ">
                                      <label class="form-label block text-gray-700 font-semibold">Delegation
                                          ID:</label>
                                  </div>
                                  <input type="text" id="delegation_code"
                                      class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                                      placeholder="Enter Delegation ID" />
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
                                  <select id="country_id" class="p-3 rounded-lg w-full border text-sm">
                                      <option selected="" disabled="">Select Country</option>
                                      @foreach (getDropDown('country')->options as $country)
                                          <option value="{{ $country->id }}">{{ $country->value }}</option>
                                      @endforeach
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
              <div id="delegationTable">
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
                          @foreach ($delegations as $delegation)
                              <tr class="text-sm align-middle">
                                  <td class="px-4 py-2 border border-gray-200">
                                      <input type="radio" name="delegation_id" value="{{ $delegation->id }}"
                                          class="w-4 h-4 !accent-[#B68A35]"
                                          {{ $escort->delegation_id == $delegation->id ? 'checked' : '' }} />
                                  </td>
                                  <td class="px-4 py-2 border border-gray-200">{{ $delegation->code }}</td>
                                  <td class="px-4 py-2 border border-gray-200">{{ $delegation->continent?->name_en }}
                                  </td>
                                  <td class="px-4 py-2 border border-gray-200">{{ $delegation->country?->name_en }}
                                  </td>
                                  <td class="px-4 py-2 border border-gray-200">{{ $delegation->team_head?->name_en }}
                                  </td>
                              </tr>
                          @endforeach
                      </tbody>
                  </table>

              </div>
              <div class="flex items-center p-4 md:p-5 border-gray-200 rounded-b px-0 pb-0">
                  <button type="submit"
                      class="btn text-md !bg-[#B68A35] text-white rounded-lg py-[1px] h-12">Assign</button>
              </div>
          </form>
      </div>
  </div>

  @section('scripts')
      <script>
          document.addEventListener('DOMContentLoaded', function() {
              const searchBtn = document.getElementById('searchBtn');
              const delegationCodeInput = document.getElementById('delegation_code');
              const countryIdInput = document.getElementById('country_id');
              const languageIdInput = document.getElementById('language_id');
              const delegationTable = document.getElementById('delegationTable');
              const tableRows = delegationTable.querySelectorAll('tbody tr');

              searchBtn.addEventListener('click', function() {
                  const delegationCode = delegationCodeInput.value.toLowerCase();
                  const countryId = countryIdInput.value;
                  const languageId = languageIdInput.value;

                  tableRows.forEach(row => {
                      const code = row.cells[1].textContent.toLowerCase();
                      const country = row.cells[3].textContent.toLowerCase();
                      const radio = row.querySelector('input[name="delegation_id"]');
                      const delegationId = radio.value;

                      let show = true;

                      if (delegationCode && code.indexOf(delegationCode) === -1) {
                          show = false;
                      }

                      if (countryId && delegationId !== countryId) {
                          show = false;
                      }

                      if (languageId) {
                          const languages = Array.from(row.querySelectorAll('td:nth-child(6) span'))
                              .map(span => span.textContent.toLowerCase());
                          if (!languages.includes(languageId.toLowerCase())) {
                              show = false;
                          }
                      }

                      row.style.display = show ? '' : 'none';
                  });
              });
          });
      </script>
  @endsection
