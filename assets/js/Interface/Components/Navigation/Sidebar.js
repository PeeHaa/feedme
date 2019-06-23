import Html, {text} from "../../../Template/Html";
import Loader from "../Loader";
import GetCategories from "../../../Request/GetCategories";

export default class Sidebar {
    constructor(handler) {
        this.handler = handler;

        document.addEventListener('click', this.handleClick.bind(this));
    }

    handleClick(event) {
        if (event.target.classList.contains('add-category')) {
            event.preventDefault();

            this.handleNewCategoryClick();
        }
    }

    handleNewCategoryClick() {
        const modal = text`<div class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="#" class="add-category">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Category name">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary float-left" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>`;

        Html.renderPartial(document.querySelector('main'), modal);
    }

    render() {
        this.createContainerWhenNeeded();

        Loader.show(document.querySelector('.sidebar-sticky'), 'Loading categories');

        this.handler.handleSubmit(new GetCategories(), (response) => {
            const sidebar = document.querySelector('.sidebar-sticky');

            sidebar.classList.remove('text-center');

            Html.render(sidebar, this.getCategories());

            const categoriesContainer = sidebar.querySelector('ul.mb-2');

            response.data.categories.forEach((category) => {
                Html.renderPartial(categoriesContainer, `<li class="nav-item" data-id="${category.id}">
                    <a class="nav-link" href="/category/${category.id}">
                        <span class="badge badge-primary float-right">4</span>
                    </a>
                </li>`);

                document.querySelector('[data-id="' + category.id + '"] a').childNodes[0].textContent = category.name;
            });
        });
    }

    createContainerWhenNeeded() {
        if (this.containerExists()) {
            return;
        }

        const container = `<nav class="col-md-2 d-none d-md-block bg-light sidebar">
            <div class="sidebar-sticky text-center">
            </div>
        </nav>`;

        Html.renderPartial(document.querySelector('.container-fluid > div'), container);
    }

    getCategories() {
        return `<ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="/dashboard">
                    All <span class="sr-only">(current)</span>
                </a>
            </li>
        </ul>
        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            Categories
            <span class="text-right text-muted"><a href="#" class="add-category">+ Add new</a></span>
        </h6>
        <ul class="nav flex-column mb-2">
            
        </ul>`;
    }

    containerExists() {
        const sidebar = document.querySelector('.sidebar-sticky');

        return sidebar !== null;
    }
}

