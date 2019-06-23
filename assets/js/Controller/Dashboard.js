import DashboardComponent from "./../Interface/Components/Content/Dashboard";
import Top from "./../Interface/Components/Navigation/Top";
import SubscribeToAll from "../Request/SubscribeToAll";

export default class Dashboard {
    constructor(sidebar, handler, connection) {
        this.sidebar    = sidebar;
        this.handler    = handler;

        connection.addEventListener('NewArticle', (response) => {
            DashboardComponent.renderNewArticle(response.data.article);
        });

        connection.addEventListener('ReadArticle', (response) => {
            DashboardComponent.readArticle(response.data.article);
        });

        document.addEventListener('submit', this.handleSubmit.bind(this));
    }

    run() {
        Top.render();

        DashboardComponent.render(this.handler);
        this.sidebar.render();

        this.handler.handleSubmit(new SubscribeToAll());

        document.querySelector('body').classList.add('authenticated');
        document.querySelector('main').classList.remove('col-sm-12');
        document.querySelector('main').classList.add('col-md-9', 'ml-sm-auto', 'col-lg-10');
    }

    handleSubmit(event) {

    }
}
