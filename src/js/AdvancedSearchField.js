import {createElement} from './createElement';

class AdvancedSearchField {
    /**
     *
     * @param fieldName {string}
     * @param hasSiblings {boolean}
     * @param deleteHandler
     */
    constructor(fieldName, hasSiblings, deleteHandler) {

        this.deleteHandler = deleteHandler;
        this.fieldName = fieldName;

        // Generate a random id.
        this.id = randomString();

        // Clone the template HTML to create a new field.
        const template = document.querySelector(`#adv-search__${fieldName}_template`);
        const clone = template.content ? template.content.cloneNode(true) : createElement(template.innerHTML);
        const input = clone.querySelector('input[id], select[id]');

        // Set the ID for the whole field <div> and for the input element.
        clone.querySelector('.advanced-search-field').id = this.getElementId();
        input.id = input.id + '-' + this.id;
        clone.querySelector('label').setAttribute('for', input.id);

        // Bind delete button click handler.
        const deleteField = this.handleDelete.bind(this);
        clone.querySelector('.advanced-search-field__delete-button').addEventListener('click', deleteField);

        // If there are already inputs for this field on the screen, connect them with an 'or'.
        if (hasSiblings) {
            clone.querySelector('label').innerHTML = '<span class="advanced-search-field__or-label">or</span>';
        }

        document.querySelector(`.advanced-search__${fieldName}_holder`).appendChild(clone);
        this.node = document.querySelector(`#${this.getElementId()}`);

        if (hasSiblings) {
            this.hideLabel();
        }
    }

    hideLabel() {
        this.node.querySelector('.advanced-search-field__label').classList.add('sr-only');
    }

    showLabel() {
        this.node.querySelector('.advanced-search-field__label').classList.remove('sr-only');
    }



    /**
     * Get the ID of the input element
     *
     * @return {string}
     */
    getInputId() {
        return this.id;
    }

    /**
     * Get the input value
     *
     * @return {*}
     */
    getValue() {
        return this.node.querySelector('input, select').value;
    }

    handleDelete() {
        this.node.parentNode.removeChild(this.node);
        this.deleteHandler(this);
    }

    getElementId() {
        return `advanced-search-field-${this.id}`;
    }
}

/**
 * Generate a random 11 character string
 *
 * @return {string}
 */
function randomString() {
    return Math.random().toString(32).slice(2);
}


export {AdvancedSearchField};