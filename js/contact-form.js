document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');
    const formMessage = document.querySelector('.form-message');
    const submitButton = form.querySelector('button[type="submit"]');
    const loadingIndicator = document.querySelector('.loading-indicator');
    const buttonText = document.querySelector('.button-text');

    function showMessage(message, type, debug = null) {
        let displayMessage = message;
        if (debug && location.hostname === 'localhost') {
            displayMessage += '<br><small>' + debug + '</small>';
        }
        formMessage.innerHTML = displayMessage;
        formMessage.className = `form-message ${type}`;
        formMessage.style.display = 'block';
        // Don't auto-hide error messages
        if (type !== 'error') {
            setTimeout(() => {
                formMessage.style.display = 'none';
            }, 5000);
        }
    }

    function setLoading(isLoading) {
        submitButton.disabled = isLoading;
        loadingIndicator.style.display = isLoading ? 'block' : 'none';
        buttonText.style.opacity = isLoading ? '0' : '1';
    }

    function validateForm() {
        const name = form.querySelector('#name').value.trim();
        const email = form.querySelector('#email').value.trim();
        const subject = form.querySelector('#subject').value.trim();
        const message = form.querySelector('#message').value.trim();

        if (!name || !email || !subject || !message) {
            showMessage('Vul alle verplichte velden in.', 'error');
            return false;
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            showMessage('Voer een geldig e-mailadres in.', 'error');
            return false;
        }

        return true;
    }

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        if (!validateForm()) {
            return;
        }

        setLoading(true);
        formMessage.style.display = 'none';

        const formData = new FormData(form);

        try {
            console.log('Sending form data to contact-handler.php...');
            const response = await fetch('contact-handler.php', {
                method: 'POST',
                body: formData
            });

            console.log('Response received:', response.status);
            const result = await response.json();
            console.log('Response data:', result);

            if (result.success) {
                showMessage('Bedankt voor uw bericht. We nemen zo spoedig mogelijk contact met u op.', 'success');
                form.reset();
            } else {
                showMessage(
                    'Er is een fout opgetreden bij het verzenden van het bericht.',
                    'error',
                    result.message || 'Onbekende fout'
                );
            }
        } catch (error) {
            console.error('Error:', error);
            showMessage(
                'Er is een fout opgetreden bij het verzenden van het bericht.',
                'error',
                'Technical details: ' + error.message
            );
        } finally {
            setLoading(false);
        }
    });
});
