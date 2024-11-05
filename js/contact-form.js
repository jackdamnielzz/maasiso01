document.addEventListener('DOMContentLoaded', function() {
    console.log('Contact form script loaded'); // Debug log

    const contactForm = document.getElementById('contact-experiment-form');
    const formFeedback = document.getElementById('form-feedback');

    if (!contactForm) {
        console.error('Contact form not found!'); // Debug log
        return;
    }

    console.log('Contact form found and initialized'); // Debug log

    function showFeedback(message, type) {
        console.log(`Showing feedback: ${message} (${type})`); // Debug log
        formFeedback.textContent = message;
        formFeedback.classList.remove('success', 'error');
        formFeedback.classList.add(type);
    }

    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();
        console.log('Form submission started'); // Debug log

        // Basic client-side validation
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const subject = document.getElementById('subject').value;
        const message = document.getElementById('message').value.trim();
        const privacyConsent = document.getElementById('privacy-consent').checked;

        console.log('Form data collected:', { name, email, subject, message, privacyConsent }); // Debug log

        // Reset previous feedback
        formFeedback.textContent = '';
        formFeedback.classList.remove('success', 'error');

        // Validate form
        if (!name || !email || !subject || !message) {
            console.log('Validation failed: Missing required fields'); // Debug log
            showFeedback('Vul alstublieft alle verplichte velden in.', 'error');
            return;
        }

        if (!privacyConsent) {
            console.log('Validation failed: Privacy consent not checked'); // Debug log
            showFeedback('U moet akkoord gaan met de privacyverklaring.', 'error');
            return;
        }

        // Prepare form data
        const formData = new FormData(contactForm);
        formData.append('recipient', 'info@maasiso.nl');
        
        console.log('Sending form data to server...'); // Debug log

        // Send form data
        fetch('contact-handler.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('Server response received:', response); // Debug log
            return response.json();
        })
        .then(data => {
            console.log('Parsed response data:', data); // Debug log
            if (data.status === 'success') {
                showFeedback(data.message, 'success');
                contactForm.reset();
            } else {
                showFeedback(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Fetch error:', error); // Detailed error logging
            console.error('Error stack:', error.stack); // Stack trace
            showFeedback('Er is een technische fout opgetreden. Probeer het later opnieuw.', 'error');
        });

        console.log('Form submission handler completed'); // Debug log
    });

    // Log form field changes for debugging
    ['name', 'email', 'subject', 'message', 'privacy-consent'].forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('change', () => {
                console.log(`Field '${id}' changed:`, element.value || element.checked);
            });
        }
    });
});
