const form = document.getElementById('indipetae-advanced-search-form');

let handleSubmit = function (event) {
    let queryArray = [];

    event.preventDefault();

    const textInputs = [...document.getElementsByClassName('advanced-search-field__input')];
    const selects = [...document.getElementsByClassName('advanced-search-field__select')];
    const allInputs = textInputs.concat(selects);
    for (input of allInputs) {
        if (input.value) {
            queryArray.push(`${input.name}=${input.value}`);
        }
    }

    const queryString = queryArray.join('&');
    form.reset();
    window.location = form.action + `?${queryString}`;
};

let resetForm = function (event) {
    form.reset();
    return false;
};

function addHandlers() {
    form.onsubmit = handleSubmit;
    resetButton.addEventListener('click', resetForm);
}

export default function advancedSearch() {
    document.addEventListener("DOMContentLoaded", function (event) {
        addHandlers();
    });
}