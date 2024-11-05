// Immediately log when script loads
console.log('Contact form script loaded and executing');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded - Initializing form handling');

    // Get form elements
    const contactForm = document.getElementById('contact-experiment-form');
    const formFeedback = document.getElementById('form-feedback');

    // Log if form is found
    if (contactForm) {
        console.log('Contact form found:', contactForm);
    } else {
        console.error('Contact form not found! Check form ID');
        return;
    }

    // Log if feedback element is found
    if (formFeedback) {
        console.log('Feedback element found:', formFeedback);
    } else {
        console.error('Feedback element not found! Check element ID');
    }

    function showFeedback(message, type) {
        console.log(`Showing feedback: ${message} (${type})`);
        formFeedback.textContent = message;
        formFeedback.classList.remove('success', 'error');
        formFeedback.classList.add(type);
    }

    // Add form submit handler
    contactForm.addEventListener('submit', function(e) {
        console.log('Form submit event triggered');
        e.preventDefault();
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

        // Reset previous feedback
        formFeedback.textContent = '';
        formFeedback.classList.remove('success', 'error');

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

        console.log('Sending form data to server...');

        // Send form data
        fetch('contact-handler.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('Server response received:', response);
            return response.json();
        })
        .then(data => {
            console.log('Parsed response data:', data);
            if (data.status === 'success') {
                showFeedback(data.message, 'success');
                contactForm.reset();
            } else {
                showFeedback(data.message, 'error');
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
    });

    // Log that initialization is complete
    console.log('Form handler initialization complete');
});
