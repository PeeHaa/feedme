import RegisterComponent from "./../Interface/Components/Content/Register";
import Top from "./../Interface/Components/Navigation/Top";
import SubmitLoader from "../Interface/Components/SubmitLoader";
import RegisterRequest from "../Request/Register";
import Form from "../Template/Form";

export default class Register {
    constructor(handler, router) {
        this.handler = handler;
        this.router  = router;

        document.addEventListener('submit', this.handleSubmit.bind(this));
    }

    run() {
        Top.render();

        RegisterComponent.render();
    }

    handleSubmit(event) {
        if (!event.target.classList.contains('register')) {
            return;
        }

        event.preventDefault();

        SubmitLoader.show(event.target);

        Form.clearvalidationErrors(event.target);

        this.handler.handleSubmit(new RegisterRequest(
            event.target.querySelector('[name="username"]').value,
            event.target.querySelector('[name="password"]').value,
            event.target.querySelector('[name="password2"]').value
        ), (response) => {
            const form = document.querySelector('form.register');

            if (!form) {
                return;
            }

            if (response.status !== 200) {
                this.handleInvalidRegistration(form, response);

                return;
            }

            this.handleValidRegistration(form, response.data.session);
        });
    }

    handleInvalidRegistration(form, response) {
        SubmitLoader.hide(form);

        form.querySelector('[name="password"]').value = '';
        form.querySelector('[name="password2"]').value = '';

        if (typeof response === 'undefined') {
            return;
        }

        const fields = ['username', 'password', 'password2'];

        fields.forEach((field) => {
            if (response.errors.hasOwnProperty(field)) {
                Form.invalidateField(form.querySelector('[name="' + field + '"]'), response.errors[field]);
            }
        });
    }

    handleValidRegistration(form, sessionData) {
        fetch(`/start-session/${sessionData.id}/${sessionData.userId}/${sessionData.token}`, {
            method: 'GET',
            credentials: 'same-origin'
        }).then((response) => {
            if (response.status !== 200) {
                this.handleInvalidRegistration(form);

                return;
            }

            SubmitLoader.hide(form);

            this.router.run('/dashboard');
        });
    }
}
