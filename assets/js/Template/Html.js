class Html {
    render(container, html) {
        this.clearContent(container);

        container.insertAdjacentHTML('beforeend', html);
    }

    renderPartial(container, html, position = 'beforeend') {
        container.insertAdjacentHTML(position, html);
    }

    clearContent(container) {
        while (container.firstChild) {
            container.removeChild(container.firstChild);
        }
    }
}

export default new Html();

export const text = (literals, ...expressions) => {
    let string = '';

    for (const [i, val] of expressions.entries()) {
        const documentNode = new DOMParser().parseFromString(val, 'text/html');

        string += literals[i] + documentNode.querySelector('body').textContent;
    }

    string += literals[literals.length - 1];

    return string
};
