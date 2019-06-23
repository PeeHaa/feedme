import Request from "./Request";

export default class LogIn extends Request {
    constructor(username, password, password2) {
        super('Register', {
            username: username,
            password: password,
            password2: password2
        });
    }
}
