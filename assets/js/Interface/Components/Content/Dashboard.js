import Html, {text} from "../../../Template/Html";
import Loader from "../Loader";
import GetArticles from "../../../Request/GetArticles";

class Dashboard {
    render(handler) {
        document.querySelector('main').classList.add('text-center');

        Loader.show(document.querySelector('main'), 'Loading articles');

        handler.handleSubmit(new GetArticles(), (response) => {
            document.querySelector('main').classList.remove('text-center');

            Html.render(document.querySelector('main'), text`<h2>All</h2>
            <table class="table articles">
                <tbody>
                </tbody>
            </table>`);

            const tableBody = document.querySelector('table.articles tbody');

            if (!response.data.articles.length) {
                Html.renderPartial(tableBody, `<tr>
                    <td colspan="3">No articles available (yet)!</td>
                </tr>`);

                return;
            }

            response.data.articles.forEach((article) => {
                Html.renderPartial(tableBody, text`<tr data-id="${article.id}" class="${article.read ? '' : 'unread'}">
                    <td class="author">
                        <a href="/read/${article.id}" target="_blank">
                            ${article.source}
                        </a>
                    </td>
                    <td class="content">
                        <div class="title">
                            <a href="/read/${article.id}" target="_blank">
                                ${article.title}
                            </a>
                        </div>
                    </td>
                    <td class="date">
                        <a href="/read/${article.id}" target="_blank">
                            ${article.createdAt}
                        </a>
                    </td>
                </tr>`);
            });
        });
    }

    renderNewArticle(article) {
        const tableBody = document.querySelector('table.articles tbody');

        Html.renderPartial(tableBody, text`<tr class="unread" data-id="${article.id}">
            <td class="author">
                <a href="/read/${article.id}" target="_blank">
                    ${article.source}
                </a>
            </td>
            <td class="content">
                <div class="title">
                    <a href="/read/${article.id}" target="_blank">
                        ${article.title}
                    </a>
                </div>
            </td>
            <td class="date">
                <a href="/read/${article.id}" target="_blank">
                    ${article.createdAt}
                </a>
            </td>
        </tr>`, 'afterbegin');
    }

    readArticle(article) {
        const articleElement = document.querySelector(`[data-id="${article.id}"]`);

        if (!articleElement) {
            return;
        }

        articleElement.classList.remove('unread');
    }
}

export default new Dashboard();
