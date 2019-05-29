/**
 * Helper functions for advanced search form
 */

/**
 * The advanced search form
 *
 * @type {HTMLElement}
 */
const form = document.getElementById('indipetae-advanced-search-form');

/**
 * The reset button
 *
 * @type {HTMLElement}
 */
const resetButton = document.getElementById('indipetae-advanced-search-form__reset-button');

/**
 * Handle a form submission
 *
 * We don't want to submit every possible value with each advanced search form submission. We need
 * to filter out empty input fields.
 *
 * @param event
 */
function handleSubmit(event) {

    console.log('happened');

    // Don't submit form.
    event.preventDefault();

    // Build query string by extracting result from all non-empty input fields.
    const inputClassNames = 'advanced-search-field__input advanced-search-field__select';
    const pp = [...document.getElementsByClassName(inputClassNames)];

    console.log(pp);

    const text_inputs = [...document.getElementsByClassName('advanced-search-field__input')];
    const selects = [...document.getElementsByClassName('advanced-search-field__select')];
    const allInputs = text_inputs.concat(selects);

    console.log(allInputs);
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

/**
 * Load advanced search form
 */
function advancedSearch() {
    document.addEventListener("DOMContentLoaded", () => {
        form.addEventListener('submit', handleSubmit);
        resetButton.addEventListener('click', resetForm);
    });
}

export {advancedSearch};