<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/mail_error.log');

header('Content-Type: application/json');

function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

try {
    // Log incoming request
    error_log("Form submission received: " . json_encode($_POST));

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

    // Additional headers for Cloud86
    $headers = array();
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=UTF-8';
    $headers[] = 'From: ' . $name . ' <' . $email . '>';  // Changed to use sender's email as From
    $headers[] = 'Reply-To: ' . $email;
    $headers[] = 'X-Mailer: PHP/' . phpversion();

    // Prepare email body
    $emailBody = "
    <html>
    <head>
        <title>Nieuw contactformulier bericht</title>
    </head>
    <body>
        <h2>Nieuw bericht via het contactformulier</h2>
        <p><strong>Naam:</strong> {$name}</p>
        <p><strong>E-mail:</strong> {$email}</p>
        <p><strong>Telefoon:</strong> {$phone}</p>
        <p><strong>Onderwerp:</strong> {$subject}</p>
        <p><strong>Bericht:</strong></p>
        <p>" . nl2br($message) . "</p>
    </body>
    </html>
    ";

    // Log email attempt
    error_log("Attempting to send email to info@maasiso.nl");
    error_log("Headers: " . print_r($headers, true));
    error_log("Subject: Nieuw contactformulier bericht: $subject");

    // Send email using PHP's mail function with full headers
    $mailSent = mail(
        'info@maasiso.nl',
        "Nieuw contactformulier bericht: $subject",
        $emailBody,
        implode("\r\n", $headers)
    );

    // Log mail result
    error_log("Mail send result: " . ($mailSent ? "Success" : "Failed"));

    if (!$mailSent) {
        error_log("Mail error info: " . error_get_last()['message']);
        throw new Exception('Er kon geen e-mail worden verzonden. Probeer het later opnieuw.');
    }

    // Send success response
    echo json_encode([
        'success' => true,
        'message' => 'Bericht succesvol verzonden.'
    ]);

} catch (Exception $e) {
    // Log the error
    error_log("Error in contact form: " . $e->getMessage());
    
    // Send error response
    echo json_encode([
        'success' => false,
        'message' => 'Er is een fout opgetreden: ' . $e->getMessage()
    ]);
}
