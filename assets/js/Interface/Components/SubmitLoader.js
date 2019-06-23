class SubmitLoader {
    show(form) {
        const submitButtons = form.querySelectorAll('input[type="submit"], button[type="submit"]');

        submitButtons.forEach((submitButton) => {
            submitButton.classList.add('loading');
        });
    }

    hide(form) {
        const submitButtons = form.querySelectorAll('input[type="submit"].loading, button[type="submit"].loading');

        submitButtons.forEach((submitButton) => {
            submitButton.classList.remove('loading');
        });
    }
}

export default new SubmitLoader();
