<?php
header('Content-Type: application/json');

function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

try {
    // Validate required fields
    $requiredFields = ['name', 'email', 'subject', 'message'];
    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            throw new Exception('Alle verplichte velden moeten worden ingevuld.');
        }
    }

    // Sanitize inputs
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $phone = isset($_POST['phone']) ? sanitizeInput($_POST['phone']) : 'Niet opgegeven';
    $subject = sanitizeInput($_POST['subject']);
    $message = sanitizeInput($_POST['message']);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Ongeldig e-mailadres.');
    }

    // Prepare email headers
    $headers = array(
        'From: MaasISO Contact Form <info@maasiso.nl>',
        'Reply-To: ' . $name . ' <' . $email . '>',
        'MIME-Version: 1.0',
        'Content-Type: text/html; charset=UTF-8'
    );

    // Prepare email body
    $emailBody = "
    <h2>Nieuw bericht via het contactformulier</h2>
    <p><strong>Naam:</strong> {$name}</p>
    <p><strong>E-mail:</strong> {$email}</p>
    <p><strong>Telefoon:</strong> {$phone}</p>
    <p><strong>Onderwerp:</strong> {$subject}</p>
    <p><strong>Bericht:</strong></p>
    <p>" . nl2br($message) . "</p>
    ";

    // Send email using PHP's mail function
    $mailSent = mail(
        'info@maasiso.nl',
        "Nieuw contactformulier bericht: $subject",
        $emailBody,
        implode("\r\n", $headers)
    );

    if (!$mailSent) {
        throw new Exception('Er kon geen e-mail worden verzonden. Probeer het later opnieuw.');
    }

    // Send success response
    echo json_encode([
        'success' => true,
        'message' => 'Bericht succesvol verzonden.'
    ]);

} catch (Exception $e) {
    // Send error response
    echo json_encode([
        'success' => false,
        'message' => 'Er is een fout opgetreden: ' . $e->getMessage()
    ]);
}