document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('#contact-form');
    const submitButton = form.querySelector('button[type="submit"]');
    const originalButtonText = submitButton.textContent;

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Change button state
        submitButton.disabled = true;
        submitButton.textContent = 'VERZENDEN...';

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                // Success message
                form.reset();
                alert('Bedankt voor uw bericht. We nemen zo spoedig mogelijk contact met u op.');
            } else {
                throw new Error('Er ging iets mis');
            }
        } catch (error) {
            alert('Er is een fout opgetreden bij het verzenden van het formulier. Probeer het later opnieuw.');
        } finally {
            // Reset button state
            submitButton.disabled = false;
            submitButton.textContent = originalButtonText;
        }
    });
});
