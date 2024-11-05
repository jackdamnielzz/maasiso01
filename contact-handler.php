<?php
// Leverage server's error reporting settings
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
ini_set('display_errors', 1);

// Ensure logging is enabled
ini_set('log_errors', 1);
ini_set('error_log', '/var/log/php_errors.log');

header('Content-Type: application/json');

// Improved logging function
function logError($message, $level = 'ERROR') {
    $logFile = '/var/log/contact-form-errors.log';
    $timestamp = date('[Y-m-d H:i:s]');
    $logMessage = "{$timestamp} [{$level}] {$message}" . PHP_EOL;
    
    // Use error_log for system logging
    error_log($logMessage, 3, $logFile);
}

// Enhanced input sanitization
function sanitize_input($data) {
    if (is_array($data)) {
        return array_map('sanitize_input', $data);
    }
    
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// Advanced email validation
function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) 
           && preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email);
}

// SMTP-like configuration (fallback method)
function send_advanced_email($to, $subject, $message, $headers) {
    $additional_parameters = "-f info@maasiso.nl";
    
    // Use PHP's mail with additional parameters
    $result = mail($to, $subject, $message, $headers, $additional_parameters);
    
    if (!$result) {
        logError("Mail sending failed via mail() function", 'CRITICAL');
    }
    
    return $result;
}

// Main form handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize all input
    $_POST = sanitize_input($_POST);
    
    // Extract and validate form data
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';
    $recipient = $_POST['recipient'] ?? 'info@maasiso.nl';

    // Validation checks
    $validation_errors = [];
    
    if (empty($name)) {
        $validation_errors[] = "Naam is verplicht.";
    }
    
    if (!is_valid_email($email)) {
        $validation_errors[] = "Ongeldig e-mailadres.";
    }
    
    if (empty($subject)) {
        $validation_errors[] = "Onderwerp is verplicht.";
    }
    
    if (empty($message)) {
        $validation_errors[] = "Bericht is verplicht.";
    }

    // Proceed if no validation errors
    if (empty($validation_errors)) {
        // Construct email content
        $email_subject = "Nieuw contactformulier bericht: " . $subject;
        $email_body = "Contactformulier ontvangen:\n\n";
        $email_body .= "Naam: {$name}\n";
        $email_body .= "E-mail: {$email}\n";
        $email_body .= "Telefoon: {$phone}\n";
        $email_body .= "Onderwerp: {$subject}\n\n";
        $email_body .= "Bericht:\n{$message}";

        // Email headers
        $headers = "From: {$email}\r\n";
        $headers .= "Reply-To: {$email}\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        // Attempt email sending with advanced method
        try {
            $mail_sent = send_advanced_email($recipient, $email_subject, $email_body, $headers);
            
            if ($mail_sent) {
                logError("Email successfully sent to {$recipient}", 'INFO');
                echo json_encode([
                    'status' => 'success', 
                    'message' => 'Uw bericht is succesvol verzonden.'
                ]);
            } else {
                logError("Email sending failed", 'ERROR');
                echo json_encode([
                    'status' => 'error', 
                    'message' => 'Verzenden van e-mail is mislukt. Probeer het later opnieuw.'
                ]);
            }
        } catch (Exception $e) {
            logError("Email exception: " . $e->getMessage(), 'CRITICAL');
            echo json_encode([
                'status' => 'error', 
                'message' => 'Er is een technische fout opgetreden.'
            ]);
        }
    } else {
        // Validation errors
        echo json_encode([
            'status' => 'error', 
            'message' => implode(" ", $validation_errors)
        ]);
    }
} else {
    // Invalid request method
    echo json_encode([
        'status' => 'error', 
        'message' => 'Ongeldige aanvraag.'
    ]);
}
exit();
?>
