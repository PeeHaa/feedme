import Html, {text} from "./Html";

class Form {
    constructor() {
        this.translations = {
            'Email.NativeEmailAddress': 'Invalid email address',
            'Text.MinimumLength': 'Must be at least 6 characters',
            'Password.NoMatch': 'Passwords do not match',
            'Username.Unique': 'Email address already exists'
        };
    }

    clearvalidationErrors(form) {
        form.querySelectorAll('.is-invalid').forEach((input) => {
            input.classList.remove('is-invalid');
        });

        form.querySelectorAll('.invalid-feedback').forEach((feedback) => {
            feedback.parentNode.removeChild(feedback);
        });

        form.querySelectorAll('.alert-danger').forEach((alert) => {
            alert.classList.add('d-none');
        });
    }

    invalidateField(field, error) {
        field.classList.add('is-invalid');

        if (typeof error === 'undefined') {
            return;
        }

        Html.renderPartial(field.parentNode, this.getContent(error))
    }

    getContent(error) {
        return text`<div class="invalid-feedback d-block">${this.translateError(error)}</div>`;
    }

    translateError(key) {
        if (!this.translations.hasOwnProperty(key)) {
            return '{{' + key + '}}';
        }

        return this.translations[key];
    }
}

export default new Form();
