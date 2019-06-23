import Request from "./Request";

export default class LogIn extends Request {
    constructor(username, password) {
        super('LogIn', {
            username: username,
            password: password
        });
    }
}
