<div id="editTranslationModal" tabindex="-1" class="hidden fixed top-0 left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
    <div class="relative w-full max-w-md h-full md:h-auto">
        <div class="relative bg-white rounded-lg shadow">
            <div class="flex items-start justify-between p-4 border-b rounded-t ">
                <h3 class="text-xl font-semibold text-gray-900 ">
                    {{ __db('edit_translation') }}
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-hide="editTranslationModal">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <form id="editTranslationForm" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="id" id="edit_id">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900">{{ __db('label_key') }}</label>
                    <input type="text" id="edit_key" class="w-full border border-gray-300 rounded p-2 bg-gray-100" readonly>
                </div>
                @foreach ($languages as $lang)
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">{{ $lang->name }}</label>
                        <textarea name="value_{{ $lang->code }}" id="edit_{{ $lang->code }}"   dir="{{ ($lang->rtl == 1) ? 'rtl' : 'ltr'  }}" class="w-full border border-gray-300 rounded p-2"></textarea>
                    </div>
                @endforeach

                <div class="flex justify-start space-x-2 pt-4">
                    <button data-modal-hide="editTranslationModal" type="button" class="btn text-md mb-[-10px] border !border-[#B68A35] !text-[#B68A35] rounded-lg h-12 ml-2">{{ __db('cancel') }}</button>
                    <button type="submit" class="btn text-md mb-[-10px] !bg-[#B68A35] text-white rounded-lg h-12">{{ __db('save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
