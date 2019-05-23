const form = document.getElementById('indipetae-advanced-search-form');

form.onsubmit = function(event) {
    let queryArray = [];

    event.preventDefault();

    const text_inputs = [...document.getElementsByClassName('advanced-search-field__input')];
    const selects = [...document.getElementsByClassName('advanced-search-field__select')];
    const all_inputs = text_inputs.concat(selects);
    for (input of all_inputs) {
        if (input.value) {
            queryArray.push(`${input.name}=${input.value}`);
        }
    }

    const queryString = queryArray.join('&');
    window.location = form.action + `?${queryString}`;
};