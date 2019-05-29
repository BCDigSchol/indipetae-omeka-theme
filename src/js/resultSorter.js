/**
 * Helper functions for sort select box
 */

/**
 * Change current URL to value in selected option
 *
 * @param event
 */
function loadSortUrl(event) {
    window.location.href = event.target.value;
}

/**
 * Activate the sort dropdown
 */
function resultSorter() {
    document.addEventListener("DOMContentLoaded", () => {

            /**
             * Sorter select box
             *
             * @type {HTMLElement}
             */
            const sorter = document.getElementById('indipetae-sort-select');

            sorter.addEventListener('change', loadSortUrl);
        }
    );
}

export {resultSorter};