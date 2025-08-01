@props(['id'])

<div id="{{ $id }}" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full">
    <div class="relative p-4 w-full max-w-md h-full md:h-auto mx-auto mt-20">
        <div class="relative bg-white rounded-lg shadow">
            <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 rounded-lg
                text-sm p-1.5 ml-auto inline-flex items-center" data-modal-hide="{{ $id }}">
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                          d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                          clip-rule="evenodd"></path>
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
            {{ $slot }}
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('[data-modal-hide="{{ $id }}"]').forEach(el => {
        el.addEventListener('click', () => {
            document.getElementById('{{ $id }}').classList.add('hidden');
        });
    });
    document.querySelectorAll('[data-modal-toggle="{{ $id }}"]').forEach(el => {
        el.addEventListener('click', () => {
            document.getElementById('{{ $id }}').classList.toggle('hidden');
        });
    });
</script>
