<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Configure local logging
$log_file = __DIR__ . '/contact-form.log';

function logMessage($message, $level = 'INFO') {
    global $log_file;
    $timestamp = date('[Y-m-d H:i:s]');
    $log_entry = "{$timestamp} [{$level}] {$message}\n";
    file_put_contents($log_file, $log_entry, FILE_APPEND);
}

// Main script execution
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    logMessage("POST request received");
    
    // Log raw request data
    logMessage("Raw POST data: " . print_r($_POST, true));
    logMessage("Content Type: " . $_SERVER['CONTENT_TYPE']);
    
    // Get form data from FormData
    $formData = $_POST;
    
    // Extract form fields
    $name = $formData['name'] ?? '';
    $email = $formData['email'] ?? '';
    $phone = $formData['phone'] ?? '';
    $subject = $formData['subject'] ?? '';
    $message = $formData['message'] ?? '';
    
    // Log received data
    logMessage("Extracted form data:");
    logMessage("Name: $name");
    logMessage("Email: $email");
    logMessage("Phone: $phone");
    logMessage("Subject: $subject");
    logMessage("Message: $message");
    
    // Validate required fields
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        logMessage("Validation failed - missing required fields", "ERROR");
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Alle verplichte velden moeten ingevuld zijn'
        ]);
        exit;
    }

    // Prepare email content
    $to = 'info@maasiso.nl';
    $email_subject = "Nieuw contactformulier: $subject";
    $email_body = "Naam: $name\n";
    $email_body .= "E-mail: $email\n";
    if (!empty($phone)) {
        $email_body .= "Telefoon: $phone\n";
    }
    $email_body .= "Onderwerp: $subject\n\n";
    $email_body .= "Bericht:\n$message";

    // Set email headers
    $headers = [];
    $headers[] = "From: $email";
    $headers[] = "Reply-To: $email";
    $headers[] = "MIME-Version: 1.0";
    $headers[] = "Content-Type: text/plain; charset=UTF-8";
    $headers[] = "X-Mailer: PHP/" . phpversion();

    $headers_string = implode("\r\n", $headers);

    // Log email attempt details
    logMessage("Attempting to send email");
    logMessage("To: $to");
    logMessage("Subject: $email_subject");
    logMessage("Headers: " . print_r($headers, true));

    // Get PHP mail configuration
    $mail_config = [
        'SMTP' => ini_get('SMTP'),
        'smtp_port' => ini_get('smtp_port'),
        'sendmail_path' => ini_get('sendmail_path')
    ];
    logMessage("PHP Mail Configuration: " . print_r($mail_config, true));

    // Attempt to send email
    $mail_sent = mail($to, $email_subject, $email_body, $headers_string);

    if ($mail_sent) {
        logMessage("Email sent successfully");
        echo json_encode([
            'status' => 'success',
            'message' => 'Uw bericht is succesvol verzonden'
        ]);
    } else {
        logMessage("Failed to send email", "ERROR");
        $error = error_get_last();
        logMessage("Last error: " . print_r($error, true), "ERROR");
        
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Er is een probleem opgetreden bij het verzenden van uw bericht',
            'debug_info' => [
                'last_error' => $error,
                'php_version' => phpversion(),
                'mail_config' => $mail_config
            ]
        ]);
    }
} else {
    logMessage("Invalid request method: " . $_SERVER['REQUEST_METHOD'], "ERROR");
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Method Not Allowed'
    ]);
}
?>
