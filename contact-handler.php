<?php
// Enable comprehensive error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ensure logging is enabled with maximum detail
ini_set('log_errors', 1);
ini_set('error_log', '/var/log/php_errors.log');

header('Content-Type: application/json');

// Enhanced logging function with system error capture
function logDetailedError($message, $level = 'ERROR') {
    $logFile = '/var/log/contact-form-detailed-errors.log';
    $timestamp = date('[Y-m-d H:i:s]');
    
    // Capture system error details
    $last_error = error_get_last();
    $error_details = $last_error ? print_r($last_error, true) : 'No additional system error';
    
    $logMessage = "{$timestamp} [{$level}] {$message}\n";
    $logMessage .= "System Error Details:\n{$error_details}\n";
    $logMessage .= "Server Variables:\n" . print_r($_SERVER, true) . "\n";
    $logMessage .= "POST Data (sanitized):\n" . print_r(array_map('htmlspecialchars', $_POST), true) . "\n";
    $logMessage .= "----------------------------\n";
    
    // Use error_log for system logging with full details
    error_log($logMessage, 3, $logFile);
}

// Advanced email sending function with comprehensive error tracking
function send_detailed_email($to, $subject, $message, $headers) {
    // Attempt to send email and capture detailed information
    $mail_sent = mail($to, $subject, $message, $headers);
    
    if ($mail_sent) {
        logDetailedError("Email apparently sent successfully to {$to}", 'INFO');
        return true;
    } else {
        // Capture and log detailed error information
        $error_message = "Email sending failed. Detailed investigation needed.";
        logDetailedError($error_message, 'CRITICAL');
        
        // Additional system error logging
        $last_error = error_get_last();
        if ($last_error) {
            logDetailedError("System Error Details: " . print_r($last_error, true), 'SYSTEM_ERROR');
        }
        
        return false;
    }
}

// Main form handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $name = htmlspecialchars($_POST['name'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars($_POST['phone'] ?? '');
    $subject = htmlspecialchars($_POST['subject'] ?? '');
    $message = htmlspecialchars($_POST['message'] ?? '');
    $recipient = htmlspecialchars($_POST['recipient'] ?? 'info@maasiso.nl');

    // Validation
    $errors = [];
    if (empty($name)) $errors[] = "Naam is verplicht.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Ongeldig e-mailadres.";
    if (empty($subject)) $errors[] = "Onderwerp is verplicht.";
    if (empty($message)) $errors[] = "Bericht is verplicht.";

    if (empty($errors)) {
        // Construct email
        $email_subject = "Nieuw contactformulier bericht: " . $subject;
        $email_body = "Contactformulier details:\n\n";
        $email_body .= "Naam: {$name}\n";
        $email_body .= "E-mail: {$email}\n";
        $email_body .= "Telefoon: {$phone}\n";
        $email_body .= "Onderwerp: {$subject}\n\n";
        $email_body .= "Bericht:\n{$message}";

        // Email headers with additional diagnostics
        $headers = "From: {$email}\r\n";
        $headers .= "Reply-To: {$email}\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
        $headers .= "X-Contact-Form-Timestamp: " . time() . "\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        // Attempt email sending with detailed logging
        try {
            // Use advanced email sending with comprehensive error tracking
            $mail_result = send_detailed_email($recipient, $email_subject, $email_body, $headers);
            
            if ($mail_result) {
                echo json_encode([
                    'status' => 'success', 
                    'message' => 'Uw bericht is succesvol verzonden.',
                    'debug' => 'Email sending function returned true'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error', 
                    'message' => 'Verzenden van e-mail is mislukt. Onze technische ondersteuning is geïnformeerd.',
                    'debug' => 'Email sending function returned false'
                ]);
            }
        } catch (Exception $e) {
            logDetailedError("Email exception: " . $e->getMessage(), 'EXCEPTION');
            echo json_encode([
                'status' => 'error', 
                'message' => 'Er is een technische fout opgetreden.',
                'debug' => $e->getMessage()
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error', 
            'message' => implode(" ", $errors)
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error', 
        'message' => 'Ongeldige aanvraag.'
    ]);
}
exit();
?>
