@if (session('_requires_confirmation'))
    @php
        $changedFields = session('_changed_fields', []);
    @endphp

    <div id="update-confirmation-dialog" class="hidden">
        <div class="text-left p-4">
            <h3 class="text-lg font-semibold mb-4">{{ __db('confirm_changes_notification') }}</h3>
            <p class="text-sm text-gray-600 mb-4">
                {{__db('following_fields_have_changed')}}.
            </p>
            <div id="changed-fields-checkboxes" class="space-y-2 max-h-48 overflow-y-auto border p-3 rounded-md">
                @foreach ($changedFields as $key => $label)
                    <label class="flex items-center">
                        <input type="checkbox" name="_notify_fields[]" value="{{ $key }}"
                            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" checked>
                        <span class="ml-2 text-gray-700">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form[method="POST"]');

            if (form) {
                Swal.fire({
                    title: 'Confirm Update',
                    html: document.getElementById('update-confirmation-dialog').innerHTML,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Confirm & Save',
                    cancelButtonText: 'Cancel',
                    preConfirm: () => {
                        const checkboxes = Swal.getPopup().querySelectorAll(
                            'input[name="_notify_fields[]"]:checked');
                        const values = Array.from(checkboxes).map(cb => cb.value);
                        return values;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const fieldsToNotify = result.value;

                        const confirmationInput = document.createElement('input');
                        confirmationInput.type = 'hidden';
                        confirmationInput.name = '_is_confirmed';
                        confirmationInput.value = 'true';
                        form.appendChild(confirmationInput);

                        fieldsToNotify.forEach(fieldName => {
                            const notifyInput = document.createElement('input');
                            notifyInput.type = 'hidden';
                            notifyInput.name = '_notify_fields[]';
                            notifyInput.value = fieldName;
                            form.appendChild(notifyInput);
                        });

                        form.submit();
                    }
                });
            }
        });
    </script>
@endif
