console.log('Contact form script starting...');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded - Initializing form handling');

    const contactForm = document.getElementById('contact-form');
    const formFeedback = document.getElementById('form-feedback');

    if (!contactForm) {
        console.error('Contact form not found! Check form ID');
        return;
    }
    console.log('Contact form found:', contactForm);

    // Add input event listeners for all form fields
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

    // Handle form submission
    contactForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        console.log('Form submitted - Starting submission process');

        const submitButton = contactForm.querySelector('button[type="submit"]');
        submitButton.textContent = 'Verzenden...';
        submitButton.disabled = true;

        try {
            // Get form data
            const formData = new FormData(contactForm);
            
            // Log form data
            console.log('Form data collected:');
            for (let pair of formData.entries()) {
                console.log(`${pair[0]}: ${pair[1]}`);
            }

            // Send form data
            console.log('Sending form data to server');
            const response = await fetch('https://maasiso.nl/contact-handler.php', {
                method: 'POST',
                body: formData
            });

            console.log('Response received:', response.status, response.statusText);
            const text = await response.text();
            console.log('Response text:', text);

            let data;
            try {
                data = JSON.parse(text);
                console.log('Parsed response:', data);
            } catch (error) {
                console.error('Failed to parse response:', error);
                throw new Error('Invalid server response');
            }

            if (data.status === 'success') {
                console.log('Form submission successful');
                formFeedback.textContent = 'Uw bericht is succesvol verzonden. Wij nemen zo spoedig mogelijk contact met u op.';
                formFeedback.className = 'form-feedback success show';
                contactForm.reset();
            } else {
                console.log('Form submission failed:', data.message);
                formFeedback.textContent = data.message || 'Er is een fout opgetreden bij het verzenden van uw bericht.';
                formFeedback.className = 'form-feedback error show';
            }
        } catch (error) {
            console.error('Error during form submission:', error);
            formFeedback.textContent = 'Er is een technische fout opgetreden. Probeer het later opnieuw.';
            formFeedback.className = 'form-feedback error show';
        } finally {
            submitButton.textContent = 'Verzenden';
            submitButton.disabled = false;
        }
    });

    console.log('Form handler initialization complete');
});
