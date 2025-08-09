document.addEventListener("DOMContentLoaded", () => {
    /**
     * Attaches a single event listener to the body to handle all form submissions
     * that have the 'data-ajax-form' attribute. This is more efficient than attaching many listeners.
     */
    document.body.addEventListener("submit", function (e) {
        const form = e.target.closest('form[data-ajax-form="true"]');
        if (!form) {
            return; // If the submitted element is not our target form, do nothing.
        }

        e.preventDefault(); // Stop the default browser submission
        handleFormSubmit(form);
    });
});

/**
 * Handles the entire asynchronous form submission process.
 * @param {HTMLFormElement} form The form element being submitted.
 */
async function handleFormSubmit(form) {
    const submitButton = form.querySelector('button[type="submit"]');
    const originalButtonText = submitButton.innerHTML;
    submitButton.innerHTML = "Saving...";
    submitButton.disabled = true;

    const formData = new FormData(form);

    try {
        const response = await fetch(form.action, {
            method: "POST",
            body: formData,
            headers: {
                "Accept": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
            },
        });

        const result = await response.json();

        if (!response.ok) {
            if (response.status === 422 && result.errors) {
                // Handle validation errors
                let errorHtml = '<ul class="mt-1.5 list-disc list-inside text-left">';
                for (const key in result.errors) {
                    result.errors[key].forEach((error) => {
                        errorHtml += `<li>${error}</li>`;
                    });
                }
                errorHtml += "</ul>";
                Swal.fire("Validation Error", errorHtml, "error");
            } else {
                throw new Error(result.message || "An unknown server error occurred.");
            }
        } else {
            // Server responded successfully
            if (result.status === "confirmation_required") {
                promptForConfirmation(form, result.changed_fields);
            } else if (result.status === "success") {
                await Swal.fire("Success!", result.message, "success");
                if (result.redirect_url) {
                    window.location.href = result.redirect_url;
                }
            } else if (result.status === "info") {
                Swal.fire("No Changes", result.message, "info");
            }
        }
    } catch (error) {
        console.error("Submission Error:", error);
        Swal.fire("Request Failed!", error.message, "error");
    } finally {
        // Re-enable the submit button
        submitButton.innerHTML = originalButtonText;
        submitButton.disabled = false;
    }
}

/**
 * Displays a confirmation modal with details of the changed fields.
 * @param {HTMLFormElement} form The form element.
 * @param {object} changedFields An object containing details of the changed fields.
 */
function promptForConfirmation(form, changedFields) {
    let changedFieldsHtml = '<div class="text-left">';

    for (const key in changedFields) {
        const change = changedFields[key]; // Access the object with {label, old, new}

        changedFieldsHtml += `
            <div class="flex items-center justify-between py-2 border-b border-gray-200 last:border-b-0">
                <label class="flex items-center flex-grow cursor-pointer">
                    <input type="checkbox" name="_notify_fields[]" value="${key}" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" checked>
                    <span class="ml-3 text-gray-800 font-medium">${change.label}</span>
                </label>
                <div class="flex items-center text-xs sm:text-sm ml-4 flex-shrink-0">
                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-md font-mono">${change.old}</span>
                    <span class="mx-2 font-bold text-gray-400">→</span>
                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-md font-mono">${change.new}</span>
                </div>
            </div>`;
    }
    changedFieldsHtml += "</div>";

    Swal.fire({
        title: "Confirm Update",
        html: `
            <div class="p-4">
                <p class="text-sm text-gray-600 mb-4 text-left">The following fields have changed. Please select which changes you would like to send notifications for and confirm to save.</p>
                <div class="max-h-60 overflow-y-auto border p-3 rounded-md bg-gray-50">
                   ${changedFieldsHtml}
                </div>
            </div>`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Confirm & Save",
        cancelButtonText: "Cancel",
        customClass: {
            popup: 'w-full max-w-2xl' // Make modal wider to fit content
        },
    }).then((dialogResult) => {
        if (dialogResult.isConfirmed) {
            // Remove any old confirmation flags before adding new ones
            form.querySelector('input[name="_is_confirmed"]')?.remove();
            form.querySelectorAll('input[name="_notify_fields[]"]')?.forEach(
                (el) => el.remove()
            );

            // Add a hidden input to the form to signify confirmation
            const confirmationInput = document.createElement("input");
            confirmationInput.type = "hidden";
            confirmationInput.name = "_is_confirmed";
            confirmationInput.value = "true";
            form.appendChild(confirmationInput);

            // Add hidden inputs for the selected notification fields
            const notifyCheckboxes = Swal.getPopup().querySelectorAll(
                'input[name="_notify_fields[]"]:checked'
            );
            notifyCheckboxes.forEach((cb) => {
                const notifyInput = document.createElement("input");
                notifyInput.type = "hidden";
                notifyInput.name = "_notify_fields[]";
                notifyInput.value = cb.value;
                form.appendChild(notifyInput);
            });

            // Re-submit the form, which will now include the confirmation flags
            handleFormSubmit(form);
        }
    });
}
