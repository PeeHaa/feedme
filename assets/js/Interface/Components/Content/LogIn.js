import Html, {text} from "../../../Template/Html";

class LogIn {
    render() {
        Html.render(document.querySelector('main'), this.getContent());
    }

    getContent() {
        return text`<div class="container h-100">
            <div class="row h-100 justify-content-center align-items-center">
                <form class="col-sm-12 col-md-6 login" method="post" action="/login" novalidate>
                    <h5 class="mb-4">
                        Log In
                        <small class="d-block d-md-inline">
                            <span class="text-muted"> or </span>
                            <a class="text-primary" href="/register">create a new account</a>
                        </small>
                    </h5>
                    <div class="form-group">
                        <label>Email address</label>
                        <input type="text" name="username" class="form-control" placeholder="Email address">
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <div class="pretty p-default p-thick">
                            <input type="checkbox" />
                            <div class="state p-primary">
                                <label>Remember me</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">
                            <span>
                                Log In
                            </span>
                        </button>
                    </div>
                    <div class="alert alert-danger d-none" role="alert">
                        Invalid credentials! Please try again...
                    </div>
                </form>
            </div>
        </div>`;
    }
}

export default new LogIn();
