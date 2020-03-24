class AdvancedSearchFieldList {
    constructor() {
        this.fields = {};
    }

    /**
     *
     * @param field {AdvancedSearchField}
     */
    add(field) {
        if (!this.fields[field.fieldName]) {
            this.fields[field.fieldName] = [];
        }
        this.fields[field.fieldName].push(field);
    }

    /**
     *
     * @param fieldName {string}
     * @return {boolean}
     */
    contains(fieldName) {
        return !!this.fields[fieldName];
    }

    /**
     *
     * @param fieldToRemove {AdvancedSearchField}
     */
    remove(fieldToRemove) {
        this.fields[fieldToRemove.fieldName] = this.fields[fieldToRemove.fieldName].filter((field) => {
            return field !== fieldToRemove;
        });

        if (this.fields[fieldToRemove.fieldName]) {
            delete this.fields[fieldToRemove.fieldName];
        }
    }

    /**
     * Get first input for this field
     *
     * @param fieldName {string}
     */
    firstOfField(fieldName) {
        if (!this.fields[fieldName]) {
            return null
        }

        return this.fields[fieldName][0];
    }

    /**
     * Return true if this input is the first in its field
     *
     * @param field {AdvancedSearchField}
     * @return {boolean}
     */
    isFirstOfField(input) {
        if (!this.fields[input.fieldName]) {
            return false;
        }
        return (this.fields[input.fieldName][0] === input);
    }

    /**
     * Get last input for this field
     *
     * @param fieldName {string}
     * @return {null|*}
     */
    lastOfField(fieldName) {
        if (!this.fields[fieldName]) {
            return null
        }

        return this.fields[fieldName][this.fields[fieldName].length - 1];
    }


    /**
     * Return true if this input is the last in its field
     *
     * @param input {AdvancedSearchField}
     * @return {boolean}
     */
    isLastOfField(input) {
        if (!this.fields[input.fieldName]) {
            return false;
        }
        return (this.fields[input.fieldName][this.fields[input.fieldName].length - 1] === input);
    }
}

export {AdvancedSearchFieldList};