document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contact-form');
    if (!form) return;

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        if (!validateForm(form)) {
            return;
        }

        const submitButton = form.querySelector('button[type="submit"]');
        const buttonText = submitButton.querySelector('.button-text');
        const loadingIndicator = submitButton.querySelector('.loading-indicator');

        // Show loading state
        buttonText.textContent = 'Uw bericht wordt verzonden, een moment geduld alstublieft...';
        loadingIndicator.style.display = 'block';
        submitButton.disabled = true;

        try {
            const formData = new FormData(form);
            
            // Debug log
            console.log('Sending form to:', form.action);
            console.log('Form data:', Object.fromEntries(formData));

            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json'
                }
            });

            // Debug log
            console.log('Response status:', response.status);
            console.log('Response headers:', Object.fromEntries(response.headers));
            
            const responseData = await response.text();
            console.log('Response data:', responseData);

            if (response.ok) {
                // Update button to show success
                buttonText.textContent = 'Uw bericht is succesvol verzonden. Wij nemen zo spoedig mogelijk contact met u op.';
                buttonText.style.color = '#4CAF50';
                loadingIndicator.style.display = 'none';
                submitButton.style.backgroundColor = '#f8f9fa';
                submitButton.style.borderColor = '#4CAF50';
                submitButton.style.cursor = 'default';
                submitButton.style.padding = '15px 30px';
                submitButton.style.whiteSpace = 'normal';
                submitButton.style.height = 'auto';
                submitButton.style.lineHeight = '1.5';
                
                // Clear the form
                form.reset();
                
                // Disable the button permanently for this session
                submitButton.disabled = true;
            } else {
                throw new Error('Server responded with status: ' + response.status);
            }
        } catch (error) {
            console.error('Form submission error:', error);
            // Reset button state and show error
            buttonText.textContent = 'VERSTUUR';
            buttonText.style.color = '';
            loadingIndicator.style.display = 'none';
            submitButton.disabled = false;
            submitButton.style.backgroundColor = '';
            submitButton.style.borderColor = '';
            submitButton.style.cursor = 'pointer';
            submitButton.style.padding = '';
            submitButton.style.whiteSpace = '';
            submitButton.style.height = '';
            submitButton.style.lineHeight = '';
            
            showMessage('Er is een fout opgetreden bij het verzenden van uw bericht. Controleer uw internetverbinding en probeer het later opnieuw.', true);
        }
    });

    function validateForm(form) {
        let isValid = true;
        const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
        
        inputs.forEach(input => {
            const errorDiv = input.nextElementSibling;
            if (errorDiv && errorDiv.classList.contains('error-message')) {
                if (!input.value.trim()) {
                    errorDiv.textContent = 'Dit veld is verplicht';
                    isValid = false;
                } else if (input.type === 'email' && !isValidEmail(input.value)) {
                    errorDiv.textContent = 'Voer een geldig e-mailadres in';
                    isValid = false;
                } else if (input.type === 'tel' && input.value && !isValidPhone(input.value)) {
                    errorDiv.textContent = 'Voer een geldig telefoonnummer in';
                    isValid = false;
                } else {
                    errorDiv.textContent = '';
                }
            }
        });

        return isValid;
    }

    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    function isValidPhone(phone) {
        const re = /^[0-9\s\-\+\(\)]{10,}$/;
        return re.test(phone);
    }

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
