document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contact-form');
    if (!form) return;

    // First submission will be to FormSubmit for activation
    let isActivated = false;

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        if (!validateForm(form)) {
            return;
        }

        const submitButton = form.querySelector('button[type="submit"]');
        const buttonText = submitButton.querySelector('.button-text');
        const loadingIndicator = submitButton.querySelector('.loading-indicator');

        // Show loading state
        buttonText.style.opacity = '0';
        loadingIndicator.style.display = 'block';
        submitButton.disabled = true;

        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                showMessage('Bedankt voor uw bericht! We nemen zo spoedig mogelijk contact met u op.');
                form.reset();
                if (!isActivated) {
                    isActivated = true;
                }
            } else {
                throw new Error('Er is iets misgegaan');
            }
        } catch (error) {
            showMessage('Er is een fout opgetreden bij het verzenden van uw bericht. Probeer het later opnieuw.', true);
        } finally {
            // Reset button state
            buttonText.style.opacity = '1';
            loadingIndicator.style.display = 'none';
            submitButton.disabled = false;
        }
    });

    function showMessage(message, isError = false) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `form-message ${isError ? 'error' : 'success'}`;
        messageDiv.textContent = message;
        messageDiv.setAttribute('role', 'alert');
        
        // Remove any existing messages
        const existingMessage = form.querySelector('.form-message');
        if (existingMessage) {
            existingMessage.remove();
        }
        
        form.insertBefore(messageDiv, form.firstChild);
        
        // Auto-remove message after 5 seconds
        setTimeout(() => {
            messageDiv.remove();
        }, 5000);
    }

    // Clear error messages on input
    form.querySelectorAll('input, textarea, select').forEach(field => {
        field.addEventListener('input', function() {
            const errorDiv = this.nextElementSibling;
            if (errorDiv && errorDiv.classList.contains('error-message')) {
                errorDiv.textContent = '';
            }
            // Remove form-level error message if it exists
            const formMessage = form.querySelector('.form-message.error');
            if (formMessage) {
                formMessage.remove();
            }
        });
    });
});
