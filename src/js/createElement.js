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

export {createElement};