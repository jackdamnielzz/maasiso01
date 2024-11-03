<?php
// Ensure all errors are caught
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set error handler to catch all errors
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
    header('Content-Type: application/json');

    // Log request data
    $logData = "Request received: " . date('Y-m-d H:i:s') . "\n";
    $logData .= "POST data: " . print_r($_POST, true) . "\n";
    file_put_contents('debug.log', $logData, FILE_APPEND);

    // Validate required fields
    $requiredFields = ['name', 'email', 'subject', 'message'];
    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            throw new Exception("Veld '$field' is verplicht maar ontbreekt.");
        }
    }

    // Sanitize inputs
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $phone = isset($_POST['phone']) ? htmlspecialchars(trim($_POST['phone'])) : 'Niet opgegeven';
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Ongeldig e-mailadres.');
    }

    // Prepare headers
    $headers = array();
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=UTF-8';
    $headers[] = 'From: ' . $name . ' <' . $email . '>';
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
    </html>";

    // Log email attempt
    $logData = "Attempting to send email at " . date('Y-m-d H:i:s') . "\n";
    $logData .= "To: info@maasiso.nl\n";
    $logData .= "Headers: " . print_r($headers, true) . "\n";
    file_put_contents('debug.log', $logData, FILE_APPEND);

    // Send email
    $mailSent = mail(
        'info@maasiso.nl',
        "Nieuw contactformulier bericht: $subject",
        $emailBody,
        implode("\r\n", $headers)
    );

    // Log mail result
    $logData = "Mail result: " . ($mailSent ? "Success" : "Failed") . "\n";
    if (!$mailSent) {
        $error = error_get_last();
        $logData .= "Mail error: " . print_r($error, true) . "\n";
    }
    file_put_contents('debug.log', $logData, FILE_APPEND);

    if (!$mailSent) {
        throw new Exception('E-mail kon niet worden verzonden. Probeer het later opnieuw.');
    }

    echo json_encode([
        'success' => true,
        'message' => 'Bericht succesvol verzonden.'
    ]);

} catch (Exception $e) {
    $errorMessage = $e->getMessage();
    $logData = "Error occurred at " . date('Y-m-d H:i:s') . "\n";
    $logData .= "Error: " . $errorMessage . "\n";
    $logData .= "Stack trace: " . $e->getTraceAsString() . "\n";
    file_put_contents('debug.log', $logData, FILE_APPEND);

    echo json_encode([
        'success' => false,
        'message' => $errorMessage,
        'debug' => 'Check debug.log for details'
    ]);
}
