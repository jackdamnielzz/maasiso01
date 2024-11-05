// Immediately log when script loads
console.log('Contact form script loaded and executing');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded - Initializing form handling');

    // Get form elements
    const contactForm = document.getElementById('contact-form');
    const formFeedback = document.getElementById('form-feedback');

    // Log if form is found
    if (!contactForm) {
        console.error('Contact form not found! Check form ID');
        return;
    }
    console.log('Contact form found:', contactForm);

    // Add input event listeners
    const formInputs = contactForm.querySelectorAll('input, textarea, select');
    formInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            console.log(`Input changed - ${e.target.name}:`, e.target.value);
        });
    });

    // Add specific checkbox listener
    const privacyCheckbox = document.getElementById('privacy-consent');
    if (privacyCheckbox) {
        privacyCheckbox.addEventListener('change', function(e) {
            console.log('Privacy consent changed:', e.target.checked);
        });
    }

    function showFeedback(message, type) {
        console.log(`Showing feedback: ${message} (${type})`);
        if (formFeedback) {
            formFeedback.textContent = message;
            formFeedback.classList.remove('success', 'error');
            formFeedback.classList.add(type);
        }
    }

    // Add submit button click handler
    const submitButton = contactForm.querySelector('button[type="submit"]');
    if (submitButton) {
        submitButton.addEventListener('click', function(e) {
            console.log('Submit button clicked - Manually handling submission');
            e.preventDefault();
            handleSubmit(e);
        });
    }

    function handleSubmit(e) {
        console.log('Starting form submission handling');
        if (e) e.preventDefault();
        console.log('Default form submission prevented');

        // Get form data
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const subject = document.getElementById('subject').value;
        const message = document.getElementById('message').value.trim();
        const privacyConsent = document.getElementById('privacy-consent').checked;

        // Log collected form data
        console.log('Form data collected:', {
            name,
            email,
            subject,
            message,
            privacyConsent
        });

        // Validate form
        if (!name || !email || !subject || !message) {
            console.log('Validation failed: Missing required fields');
            showFeedback('Vul alstublieft alle verplichte velden in.', 'error');
            return;
        }

        if (!privacyConsent) {
            console.log('Validation failed: Privacy consent not checked');
            showFeedback('U moet akkoord gaan met de privacyverklaring.', 'error');
            return;
        }

        console.log('Form validation passed, preparing to send');

        // Prepare form data
        const formData = new FormData(contactForm);
        formData.append('recipient', 'info@maasiso.nl');

        // Log FormData contents
        for (let pair of formData.entries()) {
            console.log('FormData field:', pair[0], pair[1]);
        }

        console.log('Sending form data to server...');

        // Send form data
        fetch('contact-handler.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('Raw server response:', response);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Parsed response data:', data);
            if (data.status === 'success') {
                showFeedback(data.message, 'success');
                contactForm.reset();
            } else {
                showFeedback(data.message || 'Er is een fout opgetreden.', 'error');
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            console.error('Error details:', {
                message: error.message,
                stack: error.stack
            });
            showFeedback('Er is een technische fout opgetreden. Probeer het later opnieuw.', 'error');
        });
    }

    console.log('Form handler initialization complete');
});

// Add window error handler
window.onerror = function(msg, url, line) {
    console.error('JavaScript error:', msg);
    console.error('Script URL:', url);
    console.error('Line number:', line);
    return false;
};
