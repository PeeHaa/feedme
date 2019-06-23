export default class Handler {
    constructor(router, connection) {
        this.router     = router;
        this.connection = connection;

        window.addEventListener('popstate', this.handlePopState.bind(this));

        document.addEventListener('click', this.handleClick.bind(this));
    }

    handlePopState(event) {
        this.router.run(location.pathname, false);
    }

    handleClick(event) {
        if (event.button !== 0) {
            return;
        }

        const target = this.findNavigationTarget(event);

        if (!target) {
            return;
        }

        if (location.host !== target.host) {
            return;
        }

        event.preventDefault();

        this.router.run(target.pathname);
    }

    findNavigationTarget(event) {
        let target = event.target;

        do {
            if (target.tagName !== 'A') {
                continue;
            }

            if (target.getAttribute('href').startsWith('#')) {
                return null
            }

            return target;
        } while (target = target.parentNode);

        return null;
    }

    handleSubmit(request, callback) {
        if (typeof callback !== 'undefined') {
            this.connection.addEventListener(request.getId(), callback);
        }

        this.connection.send(request.toString());
    }
}
