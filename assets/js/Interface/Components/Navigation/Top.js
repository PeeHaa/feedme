import Html from "../../../Template/Html";

class Top {
    render(...items) {
        const container = document.querySelector('.navbar .navbar-nav');

        Html.clearContent(container);

        items.forEach((item) => {
            Html.renderPartial(container, item.render().toString());
        });
    }
}

export default new Top();
