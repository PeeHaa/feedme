export default class Router {
    constructor(history) {
        this.routes = {};

        this.history = history;
    }

    add(pattern, controller, withHistory = true) {
        this.routes['^' + pattern + '$'] = {
            controller: controller,
            withHistory: withHistory
        };
    }

    run(url, withHistory = true) {
        for (let pattern in this.routes) {
            if (!this.routes.hasOwnProperty(pattern)) {
                continue;
            }

            const matches = url.match(new RegExp(pattern));

            if (matches === null) {
                continue;
            }

            if (this.routes[pattern].withHistory && withHistory) {
                this.history.pushUrl(url);
            }

            matches.shift();

            this.routes[pattern].controller.run(...matches);

            return;
        }

        console.warn('No matching route found for URL: ' + url);
    }
}
