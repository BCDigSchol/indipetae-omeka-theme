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

// Class name constants
const addedFieldClass = 'advanced-search__added-field';

// Minimum and maximum years for date range searches.
const minDateHolder = document.getElementById('min-date-holder');
const maxDateHolder = document.getElementById('max-date-holder');

const minYear = minDateHolder ? parseInt(minDateHolder.innerText) : 1400;
const maxYear = maxDateHolder ? parseInt(maxDateHolder.innerText) : 1600;

/**
 * Handle a form submission
 *
 * We don't want to submit every possible value with each advanced search form submission. We need
 * to filter out empty input fields.
 *
 * @param event
 */
function handleSubmit(event) {

    // Don't submit form yet.
    event.preventDefault();

    // Build query string by extracting result from all non-empty input fields.
    const text_inputs = Array.prototype.slice.call(document.querySelectorAll('#indipetae-advanced-search-form .advanced-search-field__input'));
    const selects = Array.prototype.slice.call(document.querySelectorAll('#indipetae-advanced-search-form .advanced-search-field__select'));
    const allInputs = text_inputs.concat(selects);

    const queryArray = [];
    allInputs.forEach(function (parsedInput) {
        if (parsedInput.value) {
            queryArray.push(`${parsedInput.name}="${parsedInput.value}"`);
        }
    });

    // Redirect page to the search URL.
    window.location = form.action + '?' + queryArray.join('&');

}

/**
 *  Empty the form
 *
 * @param event
 * @return {boolean}
 */
function resetForm(event) {

    // Don't submit.
    event.preventDefault();

    // Delete all search boxes except the keyword search.
    document.querySelectorAll(`.${addedFieldClass}`).forEach((node) => {
        node.parentNode.removeChild(node);
    });

    // Empty the added fields array.
    addedFields.clear();

    // Set the keyword input blank.
    form.reset();
}

/**
 * Generate a random 11 character string
 *
 * @return {string}
 */
function randomString() {
    return Math.random().toString(32).slice(2);
}

/**
 * Add an Advanced Search field
 *
 * @param event
 */
function addField(event) {

    // Don't submit the form.
    event.preventDefault();

    // The field to be searched.
    const field = event.target.dataset.field;

    // No need to continue if the field has already been added to the page.
    if (addedFields.has(field)) {
        return;
    }

    // Clone the template HTML to create a new field.
    const template = document.querySelector(`#adv-search__${field}_template`);
    const clone = template.content ? template.content.cloneNode(true) : createElement(template.innerHTML);
    if (template.content) {
        addedFields.add(field);
    }

    // Generate random 'id' attribute for the new input and a matching label 'for' attribute. Required since
    // we might have more than one search box for each field.
    const input = clone.querySelector(`input[id], select[id]`);
    const label = clone.querySelector('label');
    const row = clone.querySelector('.advanced-search-field ');
    input.id = input.id + '-' + randomString();
    row.classList.add(addedFieldClass);
    label.setAttribute('for', input.id);

    // If there are multiple instances of a field (i.e. two destinations) they are bound with a
    // logical OR. Indicate this to the user.
    if (hasSubfields(field)) {
        clone.querySelector('label').innerHTML = '<span class="advanced-search-field__or-label">or</span>';
        insertAfter(lastRowOfType(field), clone);
    } else {
        appliedFields.appendChild(clone);
    }

    // Add a "plus" button to add a new field. If there is already a "plus" button, remove it
    // and add a new one.
    const oldButton = document.getElementById(`add-${field}-button`);
    if (oldButton) {
        oldButton.parentNode.removeChild(oldButton);
    }
    const addButton = createAddRowButton(field);
    insertAfter(row, addButton);
    document.getElementById(`add-${field}-button`).addEventListener('click', addField);

    // If this field requires a date range selector, activate it.
    addDateRangeSelector($(`.adv-search-field--date_range #${input.id}`));
}

/**
 * Insert an element immediately after another one
 *
 * @param oldElement
 * @param newElement
 */
function insertAfter(oldElement, newElement) {
    oldElement.parentNode.insertBefore(newElement, oldElement.nextSibling);
}

/**
 * Create an element from a string
 *
 * @param str
 * @return {DocumentFragment}
 */
function createElement(str) {
    const frag = document.createDocumentFragment();
    const elem = document.createElement('div');
    elem.innerHTML = str;
    while (elem.childNodes[0]) {
        frag.appendChild(elem.childNodes[0]);
    }
    return frag;
}

function handleDeleteClick(event) {
    if (event.target.matches('.advanced-search-field__delete-button')) {
        event.preventDefault();
        deleteSearchField(event.target);
    }
}

function deleteSearchField(element) {
    if (element.matches('.advanced-search-field__delete-button')) {

        // The data field we're dealing with.
        const field = element.dataset.field;

        // Selector to find data rows of this type.
        const fieldGroupSelector = getFieldGroupSelector(field);

        // Are there multiple fields of this type?
        const hasMultipleFields = hasMultipleSubFields(field);

        // Original type label (e.g. 'Date range')
        const typeLabel = document.querySelector(`${fieldGroupSelector} label`).innerHTML;

        // Delete the node.
        element.parentNode.parentNode.remove();

        // If there were multiple instances of this field type, relabel the first instance. If not, delete
        // the node and the add button.
        if (hasMultipleFields) {
            document.querySelector(`${fieldGroupSelector} label`).innerHTML = typeLabel;
        } else {
            addedFields.delete(field);
            document.getElementById(`add-${field}-button`).remove();
        }
    }
}

/**
 * Add date range selector to a text input
 *
 * @param $daterangeinput
 */
function addDateRangeSelector($daterangeinput) {
    $daterangeinput.daterangepicker({
        "showDropdowns": true,
        "startDate": `${minYear}/1/1`,
        "endDate": `${maxYear}/12/31`,
        "locale": {
            "format": "YYYY-MM-DD",
            "separator": " – "
        },
        "minYear": minYear,
        "maxYear": maxYear
    });
}

/**
 * Load advanced search form
 */
function advancedSearch() {
    document.addEventListener("DOMContentLoaded", () => {

        // Activate form buttons.
        form.addEventListener('submit', handleSubmit);
        resetButton.addEventListener('click', resetForm);
        appliedFields.addEventListener('click', handleDeleteClick);

        // Activate left menu.
        document.querySelectorAll('.adv-select__opt').forEach((opt) => {
            opt.addEventListener('click', addField);
        });
    });
}

function getFieldGroupSelector(field) {
    return `#applied-fields .adv-search-field--${field}`;
}

/**
 * Returns the last row containing a particular field
 *
 * @param field
 * @return {Element}
 */
function lastRowOfType(field) {
    const selector = getFieldGroupSelector(field);
    const nodes = document.querySelectorAll(selector);
    return nodes.length > 0 ? nodes[nodes.length - 1] : null;
}

/**
 * Returns true if there is even one input set for a field
 *
 * @param field
 * @return {boolean}
 */
function hasSubfields(field) {
    const fieldGroupSelector = getFieldGroupSelector(field);
    return document.querySelectorAll(fieldGroupSelector).length > 0;
}

/**
 * Returns true if there are multiple subfields of a field
 *
 * @param field
 * @return {boolean}
 */
function hasMultipleSubFields(field) {
    const fieldGroupSelector = getFieldGroupSelector(field);
    return document.querySelectorAll(fieldGroupSelector).length > 1;
}

/**
 * Creates button for adding row (e.g. a second destination field)
 *
 * @param field
 * @return {DocumentFragment}
 */
function createAddRowButton(field) {
    const html = `<div class="row">
    <div class="col-md-8">
        <button id="add-${field}-button" class="add-query-row-button" data-field="${field}">+</button>
    </div>
</div>`;
    return createElement(html);
}

export {advancedSearch};