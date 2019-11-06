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

const grayedOutClass = 'adv-select__opt--grayed-out';


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
    addedFields.forEach((field) => {
            document.querySelector(`.adv-search-field--${field}`).remove();
            unGrayOut(field);
        }
    );
    addedFields.clear();
    form.reset();
    event.preventDefault();
}

/**
 * Add an Advanced Search field
 *
 * @param event
 */
function addField(event) {
    const field = event.target.dataset.field;
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
        const idAppend = Math.random().toString().slice(2,11);
        const needsNewId =clone.querySelector(`[id]`);
        const needsNewFor = clone.querySelector('label');
        needsNewId.id = `${needsNewId.id}_${idAppend}`;
        needsNewFor.setAttribute('for', needsNewId.id);

        appliedFields.appendChild(clone);
    }
}

function createElement(str) {
    var frag = document.createDocumentFragment();

    var elem = document.createElement('div');
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
        const field = element.dataset.field;
        const input = document.querySelector(`#applied-fields .adv-search-field--${field}`);
        input.remove();
        addedFields.delete(field);
        unGrayOut(field);
    }
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

export {advancedSearch};