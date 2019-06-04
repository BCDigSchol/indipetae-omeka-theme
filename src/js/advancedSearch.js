/**
 * Helper functions for advanced search form
 */

/**
 * The advanced search form
 *
 * @type {HTMLElement}
 */
const form = document.getElementById('indipetae-advanced-search-form');
const appliedFields = document.getElementById('applied-fields');

/**
 * The reset button
 *
 * @type {HTMLElement}
 */
const resetButton = document.getElementById('indipetae-advanced-search-form__reset-button');

const addedFields = new Set();

/**
 * Handle a form submission
 *
 * We don't want to submit every possible value with each advanced search form submission. We need
 * to filter out empty input fields.
 *
 * @param event
 */
function handleSubmit(event) {

    // Don't submit form.
    event.preventDefault();

    // Build query string by extracting result from all non-empty input fields.
    const inputClassNames = 'advanced-search-field__input advanced-search-field__select';
    const pp = [...document.getElementsByClassName(inputClassNames)];

    const text_inputs = [...document.getElementsByClassName('advanced-search-field__input')];
    const selects = [...document.getElementsByClassName('advanced-search-field__select')];
    const allInputs = text_inputs.concat(selects);

    const queryArray = [];
    for (let input of allInputs) {
        if (input.value) {
            queryArray.push(`${input.name}=${input.value}`);
        }
    }
    const queryString = queryArray.join('&');

    // Reset the form for the next sbumission
    form.reset();

    // Redirect page to the search URL.
    window.location = form.action + `?${queryString}`;
}

/**
 *  Empty the form
 *
 * @param event
 * @return {boolean}
 */
function resetForm(event) {
    form.reset();
    return false;
}

function addField(event) {
    const field = event.target.dataset.field;
    if (! addedFields.has(field)) {
        const template = document.querySelector(`#adv-search__${field}_template`);
        const clone = document.importNode(template.content, true);
        addedFields.add(field);
        appliedFields.appendChild(clone);
        grayOut(event.target);
    }
}

function grayOut(element) {
    console.log('foo');
    // @todo Make element gray out code
}

function deleteField(event) {
    if (event.target.matches('.advanced-search-field__delete-button')) {
        event.preventDefault();
        const field = event.target.dataset.field;
        const input = document.querySelector(`#applied-fields .adv-search-field--${field}`);
        input.remove();
        addedFields.delete(field);
    }
}

/**
 * Load advanced search form
 */
function advancedSearch() {
    document.addEventListener("DOMContentLoaded", () => {
        form.addEventListener('submit', handleSubmit);
        resetButton.addEventListener('click', resetForm);
        appliedFields.addEventListener('click', deleteField);

        const opts = document.querySelectorAll('.adv-select__opt');
        opts.forEach((opt) => {
            opt.addEventListener('click', addField);
        });

    });
}

export {advancedSearch};