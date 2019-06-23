import Html, {text} from "../../../Template/Html";

class Register {
    render() {
        Html.render(document.querySelector('main'), this.getContent());
    }

    getContent() {
        return text`<div class="container h-100">
            <div class="row h-100 justify-content-center align-items-center">
                <form class="col-sm-12 col-md-6 register" method="post" action="/register" novalidate>
                    <h5 class="mb-4">
                        Register
                        <small class="d-block d-md-inline">
                            <span class="text-muted"> or </span>
                            <a class="text-primary" href="/login">log in with an existing account</a>
                        </small>
                    </h5>
                    <div class="form-group">
                        <label>Email address</label>
                        <input type="text" name="username" class="form-control" placeholder="Email address">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <label>Repeat password</label>
                        <input type="password" name="password2" class="form-control" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">
                            Register
                        </button>
                    </div>
                    <div class="alert alert-danger d-none" role="alert">
                        This is a danger alert—check it out!
                    </div>
                </form>
            </div>
        </div>`;
    }
}

export default new Register();
