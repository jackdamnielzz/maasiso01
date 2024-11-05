document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contact-experiment-form');
    const formFeedback = document.getElementById('form-feedback');

    function showFeedback(message, type) {
        formFeedback.textContent = message;
        formFeedback.classList.remove('success', 'error');
        formFeedback.classList.add(type);
    }

    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Basic client-side validation
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const subject = document.getElementById('subject').value;
            const message = document.getElementById('message').value.trim();
            const privacyConsent = document.getElementById('privacy-consent').checked;

            // Reset previous feedback
            formFeedback.textContent = '';
            formFeedback.classList.remove('success', 'error');

            // Validate form
            if (!name || !email || !subject || !message) {
                showFeedback('Vul alstublieft alle verplichte velden in.', 'error');
                return;
            }

            if (!privacyConsent) {
                showFeedback('U moet akkoord gaan met de privacyverklaring.', 'error');
                return;
            }

            // Prepare form data
            const formData = new FormData(contactForm);
            formData.append('recipient', 'info@maasiso.nl');

            // Send form data
            fetch('contact-handler.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showFeedback(data.message, 'success');
                    contactForm.reset();
                } else {
                    showFeedback(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showFeedback('Er is een technische fout opgetreden. Probeer het later opnieuw.', 'error');
            });
        });
    }
});
