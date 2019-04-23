const form = document.getElementById('indipetae-advanced-search-form');

form.onsubmit = function(event) {
    let count = 0;
    let queryArray = [];

    event.preventDefault();

    const text_inputs = [...document.getElementsByClassName('advanced-search-field__input')];
    const selects = [...document.getElementsByClassName('advanced-search-field__select')];
    const all_inputs = text_inputs.concat(selects);
    for (input of all_inputs) {
        if (input.value) {
            queryArray.push(`advanced[${count}][joiner]=and`);
            queryArray.push(`advanced[${count}][element_id]=${input.name}`);
            queryArray.push(`advanced[${count}][type]=contains`);
            queryArray.push(`advanced[${count}][terms]=${input.value}`);
            count++;
        }
    }

    const queryString = queryArray.join('&');
    window.location = form.action + `?${queryString}`;
};