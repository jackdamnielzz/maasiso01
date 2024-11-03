document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('#contact-form');
    const submitButton = form.querySelector('button[type="submit"]');
    const originalButtonText = submitButton.textContent;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Change button state
        submitButton.disabled = true;
        submitButton.textContent = 'VERZENDEN...';

        // Show maintenance message
        alert('Ons contactformulier is momenteel in onderhoud. Stuur ons een e-mail op info@maasiso.nl en we nemen zo spoedig mogelijk contact met u op.');
        
        // Reset form and button
        form.reset();
        submitButton.disabled = false;
        submitButton.textContent = originalButtonText;
    });
});
