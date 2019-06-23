import LoginComponent from "./../Interface/Components/Content/LogIn";
import Top from "./../Interface/Components/Navigation/Top";
import SubmitLoader from "../Interface/Components/SubmitLoader";
import LogInRequest from "../Request/LogIn";
import Form from "../Template/Form";

export default class LogIn {
    constructor(handler, router) {
        this.handler = handler;
        this.router  = router;

        document.addEventListener('submit', this.handleSubmit.bind(this));
    }

    run() {
        Top.render();

        LoginComponent.render();
    }

    handleSubmit(event) {
        if (!event.target.classList.contains('login')) {
            return;
        }

        event.preventDefault();

        SubmitLoader.show(event.target);

        Form.clearvalidationErrors(event.target);

        this.handler.handleSubmit(new LogInRequest(
            event.target.querySelector('[name="username"]').value,
            event.target.querySelector('[name="password"]').value
        ), (response) => {
            const form = document.querySelector('form.login');

            if (!form) {
                return;
            }

            if (response.status !== 200) {
                this.handleInvalidLogIn(form);

                return;
            }

            this.handleValidLogIn(form, response.data.session);
        });
    }

    handleInvalidLogIn(form) {
        SubmitLoader.hide(form);

        form.querySelector('[name="password"]').value = '';
        form.querySelector('.alert').classList.remove('d-none');
    }

    handleValidLogIn(form, sessionData) {
        fetch(`/start-session/${sessionData.id}/${sessionData.userId}/${sessionData.token}`, {
            method: 'GET',
            credentials: 'same-origin'
        }).then((response) => {
            if (response.status !== 200) {
                this.handleInvalidLogIn(form);

                return;
            }

            SubmitLoader.hide(form);

            this.router.run('/dashboard');
        });
    }
}
