document.addEventListener('DOMContentLoaded', function () {
    const isArrivalsPage = document.getElementById('fullDiv') !== null;
    const isDeparturesPage = document.getElementById('fullDiv1') !== null;

    if (!isArrivalsPage && !isDeparturesPage) {
        return;
    }

    function refreshData() {
        console.log("refreshing");

        const currentUrl = new URL(window.location.href);
        const url = currentUrl.toString();

        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data) {
                    const tableContainer = isArrivalsPage ?
                        document.getElementById('arrivals-table-container') :
                        document.getElementById('departures-table-container');

                    if (tableContainer) {
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = data.data;

                        const newTable = tempDiv.querySelector('table');
                        const newLegend = tempDiv.querySelector('.mt-3.flex.items-center.flex-wrap.gap-4');
                        const columnVisibilityModal = tempDiv.querySelector('[id$="-table-column-visibility-modal"]');

                        if (newTable) {
                            newTable.classList.remove('hidden');

                            const currentTable = tableContainer.querySelector('table');
                            if (currentTable) {
                                currentTable.replaceWith(newTable);
                            } else {
                                tableContainer.innerHTML = '';
                                tableContainer.appendChild(newTable);
                            }

                            if (newLegend) {
                                const currentLegend = tableContainer.nextElementSibling;
                                if (currentLegend && currentLegend.classList.contains('mt-3')) {
                                    currentLegend.replaceWith(newLegend);
                                } else {
                                    tableContainer.parentNode.insertBefore(newLegend, tableContainer.nextSibling);
                                }
                            }

                            applySavedColumnVisibility();

                            setTimeout(() => {
                                if (typeof $ !== 'undefined' && $.fn.select2) {
                                    $('.select2').select2({
                                        placeholder: "Select an option",
                                        allowClear: true
                                    });
                                }

                                bindEditButtons();
                            }, 100);
                        }
                    }

                    console.log('Data refreshed at: ' + new Date().toLocaleTimeString());
                }
            })
            .catch(error => {
                console.error('Error refreshing data:', error);
            });
    }

    function bindEditButtons() {
        document.querySelectorAll('.edit-arrival-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const arrival = JSON.parse(btn.getAttribute('data-arrival'));
                window.dispatchEvent(new CustomEvent('open-edit-arrival', {
                    detail: arrival
                }));
            });
        });

        document.querySelectorAll('.edit-departure-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const departure = JSON.parse(btn.getAttribute('data-departure'));
                window.dispatchEvent(new CustomEvent('open-edit-departure', {
                    detail: departure
                }));
            });
        });

        const fullscreenToggleBtn = document.getElementById('fullscreenToggleBtn');
        if (fullscreenToggleBtn) {
            fullscreenToggleBtn.addEventListener('click', toggleFullscreen);
        }

        const fullscreenToggleBtn1 = document.getElementById('fullscreenToggleBtn1');
        if (fullscreenToggleBtn1) {
            fullscreenToggleBtn1.addEventListener('click', toggleFullscreen);
        }
    }

    function applySavedColumnVisibility() {
        document.querySelectorAll('[id$="-column-toggles"]').forEach(toggleContainer => {
            const tableId = toggleContainer.id.replace('-column-toggles', '');
            const storageKey = tableId + '_column_visibility';
            const savedPrefs = JSON.parse(localStorage.getItem(storageKey));

            if (savedPrefs) {
                Object.keys(savedPrefs).forEach(columnKey => {
                    const isVisible = savedPrefs[columnKey];
                    document.querySelectorAll(
                        `#${tableId} th[data-column-key='${columnKey}'], #${tableId} td[data-column-key='${columnKey}']`
                    ).forEach(el => {
                        el.style.display = isVisible ? '' : 'none';
                    });

                    const checkbox = toggleContainer.querySelector(`.column-toggle-checkbox[value="${columnKey}"]`);
                    if (checkbox) checkbox.checked = isVisible;
                });
            }
        });
    }


    function toggleFullscreen() {
        const elem = document.getElementById('fullDiv') || document.getElementById('fullDiv1');
        const logoElements = document.querySelectorAll('.full-screen-logo');

        if (!document.fullscreenElement) {
            if (elem.requestFullscreen) {
                elem.requestFullscreen();
            } else if (elem.webkitRequestFullscreen) {
                elem.webkitRequestFullscreen();
            } else if (elem.msRequestFullscreen) {
                elem.msRequestFullscreen();
            }

            logoElements.forEach(el => el.classList.remove('hidden'));
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }

            logoElements.forEach(el => el.classList.add('hidden'));
        }
    }

    bindEditButtons();
    applySavedColumnVisibility();

    setInterval(refreshData, 60000);

    console.log('Auto-refresh initialized for ' + (isArrivalsPage ? 'arrivals' : 'departures') + ' page');
});