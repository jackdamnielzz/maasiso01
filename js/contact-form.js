document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');
    const formMessage = document.querySelector('.form-message');
    const submitButton = form.querySelector('button[type="submit"]');
    const loadingIndicator = document.querySelector('.loading-indicator');
    const buttonText = document.querySelector('.button-text');

    function showMessage(message, type, debug = null) {
        let displayMessage = message;
        if (debug) {
            displayMessage += '<br><small style="color: #666;">' + debug + '</small>';
        }
        formMessage.innerHTML = displayMessage;
        formMessage.className = `form-message ${type}`;
        formMessage.style.display = 'block';
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
            // Log the request
            console.log('Sending request to contact-handler.php...');
            
            const response = await fetch('contact-handler.php', {
                method: 'POST',
                body: formData
            });

            // Log the raw response
            console.log('Response status:', response.status);
            const responseText = await response.text();
            console.log('Raw response:', responseText);

            // Try to parse the response as JSON
            let result;
            try {
                result = JSON.parse(responseText);
            } catch (parseError) {
                console.error('Failed to parse response as JSON:', parseError);
                showMessage(
                    'Er is een fout opgetreden bij het verwerken van de server response.',
                    'error',
                    'Server response: ' + responseText
                );
                return;
            }

            if (result.success) {
                showMessage('Bedankt voor uw bericht. We nemen zo spoedig mogelijk contact met u op.', 'success');
                form.reset();
            } else {
                showMessage(
                    'Er is een fout opgetreden bij het verzenden van het bericht.',
                    'error',
                    result.message || 'Server gaf geen specifieke foutmelding.'
                );
            }
        } catch (error) {
            console.error('Network or server error:', error);
            showMessage(
                'Er is een fout opgetreden bij het verzenden van het bericht.',
                'error',
                'Technische details: ' + error.message
            );
        } finally {
            setLoading(false);
        }
    });
});
