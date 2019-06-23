import uuid from 'uuid/v4';

export default class Request {
    constructor(type, data) {
        this.data = {
            type: type,
            id: uuid(),
            data: data
        };
    }

    getId() {
        return this.data.id;
    }

    toString() {
        return JSON.stringify(this.data);
    }
}
