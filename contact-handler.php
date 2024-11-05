<?php
// Enable comprehensive error reporting and logging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

// Logging function with multiple logging methods
function advancedLog($message, $level = 'INFO') {
    // Log levels: DEBUG, INFO, WARNING, ERROR, CRITICAL
    $timestamp = date('[Y-m-d H:i:s]');
    $log_message = "{$timestamp} [{$level}] {$message}\n";

    // Multiple logging methods
    $log_paths = [
        '/var/log/contact-form-debug.log',     // Standard Linux log path
        dirname(__FILE__) . '/contact-form-debug.log', // Current directory
        sys_get_temp_dir() . '/contact-form-debug.log' // Temporary directory
    ];

    // Try to write to writable log paths
    foreach ($log_paths as $log_path) {
        $dir = dirname($log_path);
        if (is_dir($dir) && is_writable($dir)) {
            file_put_contents($log_path, $log_message, FILE_APPEND);
            break;
        }
    }

    // System error log as fallback
    error_log($log_message);
}

// Enhanced mail sending function with detailed logging
function sendEnhancedEmail($to, $subject, $message, $headers) {
    // Log email sending attempt
    advancedLog("Attempting to send email to: {$to}", 'INFO');
    advancedLog("Email Subject: {$subject}", 'DEBUG');
    
    // Log headers
    advancedLog("Email Headers: " . print_r($headers, true), 'DEBUG');

    // Capture system configuration details
    advancedLog("PHP Mail Configuration:", 'DEBUG');
    advancedLog("sendmail_path: " . ini_get('sendmail_path'), 'DEBUG');
    advancedLog("SMTP settings: " . print_r(ini_get_all('smtp'), true), 'DEBUG');

    // Attempt to send email
    $mail_sent = mail($to, $subject, $message, $headers);

    if ($mail_sent) {
        advancedLog("Email sent successfully to {$to}", 'INFO');
        return true;
    } else {
        // Capture potential errors
        $error = error_get_last();
        advancedLog("Email sending failed to {$to}", 'ERROR');
        advancedLog("Error Details: " . print_r($error, true), 'ERROR');
        
        // Additional system error investigation
        if (function_exists('error_get_last')) {
            $last_error = error_get_last();
            advancedLog("Last Error: " . print_r($last_error, true), 'CRITICAL');
        }

        return false;
    }
}

// Main form handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Log incoming request details
    advancedLog("Received POST request", 'INFO');
    advancedLog("POST Data: " . print_r($_POST, true), 'DEBUG');

    // Sanitize input
    $name = htmlspecialchars($_POST['name'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars($_POST['phone'] ?? '');
    $subject = htmlspecialchars($_POST['subject'] ?? '');
    $message = htmlspecialchars($_POST['message'] ?? '');
    $recipient = htmlspecialchars($_POST['recipient'] ?? 'info@maasiso.nl');

    // Validation
    $errors = [];
    if (empty($name)) $errors[] = "Name is required";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email";
    if (empty($subject)) $errors[] = "Subject is required";
    if (empty($message)) $errors[] = "Message is required";

    if (empty($errors)) {
        // Construct email
        $email_subject = "New Contact Form Submission: " . $subject;
        $email_body = "Contact Form Details:\n\n";
        $email_body .= "Name: {$name}\n";
        $email_body .= "Email: {$email}\n";
        $email_body .= "Phone: {$phone}\n";
        $email_body .= "Subject: {$subject}\n\n";
        $email_body .= "Message:\n{$message}";

        // Enhanced email headers
        $headers = "From: {$email}\r\n";
        $headers .= "Reply-To: {$email}\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        // Send email with enhanced logging
        $mail_result = sendEnhancedEmail($recipient, $email_subject, $email_body, $headers);

        if ($mail_result) {
            advancedLog("Form submission processed successfully", 'INFO');
            echo json_encode([
                'status' => 'success', 
                'message' => 'Your message has been sent successfully.'
            ]);
        } else {
            advancedLog("Form submission failed", 'ERROR');
            echo json_encode([
                'status' => 'error', 
                'message' => 'Failed to send message. Please try again later.',
                'debug_info' => 'Check contact-form-debug.log for details'
            ]);
        }
    } else {
        advancedLog("Form validation failed: " . implode(", ", $errors), 'WARNING');
        echo json_encode([
            'status' => 'error', 
            'message' => implode(" ", $errors)
        ]);
    }
} else {
    advancedLog("Invalid request method", 'WARNING');
    echo json_encode([
        'status' => 'error', 
        'message' => 'Invalid request method'
    ]);
}
exit();
?>
