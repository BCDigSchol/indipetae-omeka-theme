import {createElement} from './createElement';
import {AdvancedSearchField} from './AdvancedSearchField';
import {AdvancedSearchFieldList} from "./AdvancedSearchFieldList";

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

const addedInputList = new AdvancedSearchFieldList();

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

    const range_inputs = Array.prototype.slice.call(document.querySelectorAll('#indipetae-advanced-search-form .advanced-search-field__range-inputs'));

    const queryArray = [];
    allInputs.forEach(function (parsedInput) {
        if (parsedInput.value) {
            queryArray.push(`${parsedInput.name}="${parsedInput.value}"`);
        }
    });

    range_inputs.forEach(function (rangeInput){
        const minVal = rangeInput.querySelector('.advanced-search-field__input--range__min').value;
        const maxVal = rangeInput.querySelector('.advanced-search-field__input--range__max').value;
        const field = rangeInput.dataset.field;
        queryArray.push(`${field}[][or]=${minVal} – ${maxVal}`);
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

    // Set the keyword input blank.
    form.reset();
}

/**
 * Create an advanced search field
 *
 * @param fieldName
 * @return AdvancedSearchField
 */
function createField(fieldName) {
    // Create a div to hold the new field inputs.
    const fieldHolder = document.createElement('div');
    fieldHolder.classList.add(`advanced-search__${fieldName}_holder`);
    appliedFields.appendChild(fieldHolder);

    // Create the new field input
    const fieldElement = new AdvancedSearchField(fieldName, false, deleteInput);
    addedInputList.add(fieldElement);

    const addButton = createAddRowButton(fieldName);
    fieldHolder.parentNode.insertBefore(addButton, fieldHolder.nextSibling);
    document.getElementById(`add-${fieldName}-button`).addEventListener('click', addField);

    return fieldElement;
}

/**
 * Add another input to an existing field
 *
 * @param fieldName
 * @return AdvancedSearchField
 */
function addInput(fieldName) {
    const fieldElement = new AdvancedSearchField(fieldName, true, deleteInput);
    addedInputList.add(fieldElement);
    return fieldElement;
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
    const fieldName = event.target.dataset.field;

    // If a field of this type has already been created, append a new input. Otherwise, create the
    // entire field.
    const fieldElement = addedInputList.contains(fieldName) ? addInput(fieldName) : createField(fieldName);

    // If this is a date range selector, activate it.
    addDateRangeSelector($(`#date_range-${fieldElement.getInputId()}`));
}

/**
 * Called when a input is deleted
 *
 * @param deletedInput {AdvancedSearchField}
 */
function deleteInput(deletedInput) {
    addedInputList.remove(deletedInput);

    /**
    if (! addedInputList.contains(deletedInput)) {
        const addButton = document.getElementById(`add-${deletedInput.fieldName}-button`);
        addButton.parentNode.removeChild(addButton);
        const fieldHolder = document.querySelector(`.advanced-search__${deletedInput.fieldName}_holder`);
        fieldHolder.parentNode.removeChild(fieldHolder);
    }**/

    deletedInput.handleDelete();

    if (addedInputList.isFirstOfField(deletedInput)) {
        deletedInput.showLabel();
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