export default class WebSocket {
    constructor() {
        this.socket         = null;
        this.eventListeners = [];
    }

    connect(connectedCallback) {
        this.socket = new window.WebSocket(this.getUrl());

        this.socket.addEventListener('open', connectedCallback);

        this.socket.addEventListener('message', (event) => {
            const response = JSON.parse(event.data);

            console.log('RECEIVED');
            console.log(response);

            if (this.eventListeners.hasOwnProperty(response.requestId)) {
                this.eventListeners[response.requestId](response);
            }
        });
    }

    send(data) {
        console.log('SENDING');
        console.log(data);
        this.socket.send(data);
    }

    addEventListener(id, callback) {
        this.eventListeners[id] = callback;
    }

    getUrl() {
        let url = 'ws';

        if (location.protocol === 'https') {
            url += 's://';
        }

        url += '://';

        return url + location.host + '/ws';
    }
}
