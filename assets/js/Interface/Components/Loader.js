import Html, {text} from "../../Template/Html";

class Loader {
    show(container, loadText) {
        this.hide(container);

        const loader = `<div class="loading-indicator">
            <div class="loader"><span></span></div>
        </div>`;

        Html.render(container,loader);

        if (typeof loadText === 'undefined') {
            return;
        }

        Html.renderPartial(
            container.querySelector('.loading-indicator'),
            text`<div class="information">${loadText}</div>`
        );
    }

    hide(container) {
        let loader = document.querySelector('.loading-indicator');

        if (typeof container !== 'undefined') {
            loader = container.querySelector('.loading-indicator');
        }

        if (!loader) {
            return;
        }

        loader.parentNode.removeChild(loader);
    }
}

export default new Loader();
