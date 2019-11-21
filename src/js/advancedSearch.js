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
const grayedOutClass = 'adv-select__opt--grayed-out';
const addedFieldClass = 'advanced-search__added-field';

// Minimum and maximum years for date range searches.
const minYear = parseInt(document.getElementById('min-date-holder').innerText);
const maxYear = parseInt(document.getElementById('max-date-holder').innerText);

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
    const text_inputs = Array.prototype.slice.call(document.querySelectorAll('#indipetae-advanced-search-form .advanced-search-field__input'));
    const selects = Array.prototype.slice.call(document.querySelectorAll('#indipetae-advanced-search-form .advanced-search-field__select'));
    const simpleInputs = text_inputs.concat(selects);

    const queryArray = [];

    simpleInputs.forEach(function (parsedInput) {
        if (parsedInput.value) {
            queryArray.push(parsedInput.name + "=" + parsedInput.value);
        }
    });

    // Build year range input
    let yearMin = document.querySelector('#indipetae-advanced-search-form #date_min');
    let yearMax = document.querySelector('#indipetae-advanced-search-form #date_max');
    if (yearMin || yearMax) {
        yearMin = yearMin ? parseInt(yearMin.value) : '_';
        yearMax = yearMax ? parseInt(yearMax.value) : '_';
        queryArray.push(`year=${yearMin}-${yearMax}`);
    }

    const queryString = queryArray.join('&');

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
 * Add an Advanced Search field
 *
 * @param event
 */
function addField(event) {

    // Don't submit the form.
    event.preventDefault();

    // The field to be searched.
    const field = event.target.dataset.field;

    // The ID string to indicate that a
    const lastRowId = getLastRowId(field);

    const addFieldButton = `add-${field}-button`;

    if (!addedFields.has(field)) {
        const template = document.querySelector(`#adv-search__${field}_template`);
        let clone;

        if (template.content) {
            clone = template.content.cloneNode(true);
            addedFields.add(field);
        } else {
            clone = createElement(template.innerHTML);
        }

        // Generate random 'id' attribute for the new input and a matching label 'for' attribute. Required since
        // we might have more than one search box for each field.
        const idAppend = Math.random().toString().slice(2, 11);
        const needsNewId = clone.querySelector(`[id]`);
        const needsNewFor = clone.querySelector('label');
        const row = clone.querySelector('.advanced-search-field ');
        const labelText = clone.querySelector('label').innerText;

        needsNewId.id = `${needsNewId.id}_${idAppend}`;
        row.classList.add(addedFieldClass);
        needsNewFor.setAttribute('for', needsNewId.id);

        if (hasSubfields(field)) {
            clone.querySelector('label').innerHTML = '<span class="advanced-search-field__or-label">or</span>';
            insertAfter(lastRowOfType(field), clone);
        } else {
            appliedFields.appendChild(clone);
        }

        const oldButton = document.getElementById(`add-${field}-button`);
        if (oldButton) {
            oldButton.parentNode.removeChild(oldButton);
        }
        const addButton = createAddRowButton(field, labelText);
        insertAfter(row, addButton);

        const newButton = document.getElementById(`add-${field}-button`);
        newButton.addEventListener('click', addField);

        addDateRangeSelector($(`.adv-search-field--date_range #${needsNewId.id}`));
    }
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

function grayOut(element) {
    element.classList.add(grayedOutClass);
}

function unGrayOut(fieldName) {
    document.querySelector(`.adv-select__opt--${fieldName}`).classList.remove(grayedOutClass)
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

        unGrayOut(field);
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
        form.addEventListener('submit', handleSubmit);
        resetButton.addEventListener('click', resetForm);
        appliedFields.addEventListener('click', handleDeleteClick);

        const opts = document.querySelectorAll('.adv-select__opt');
        opts.forEach((opt) => {
            opt.addEventListener('click', addField);
        });

    });
}

/**
 * Generate ID string for the last row of a type
 *
 * @param {string} field
 * @return {string}
 */
function getLastRowId(field) {
    return `last-row-${field}`;
}

function getAddFieldButtonId(field) {
    return `advanced-search__add-${field}-button`;
}

function getFieldGroupSelector(field) {
    return `#applied-fields .adv-search-field--${field}`;
}

/**
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

function createAddRowButton(field, labelText) {
    const html = `<div class="row">
<div class="col-md-8">
<button id="add-${field}-button" class="add-query-row-button" data-field="${field}">+</button>
</div>
</div>`;
    return createElement(html);
}

export {advancedSearch};