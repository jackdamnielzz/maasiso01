<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Logging function with direct, visible output
function visibleLog($message, $level = 'INFO') {
    $timestamp = date('[Y-m-d H:i:s]');
    $log_entry = "{$timestamp} [{$level}] {$message}\n";
    
    // Log to a visible file in the web root
    $log_path = dirname(__FILE__) . '/contact-form-log.txt';
    
    // Append log entry
    file_put_contents($log_path, $log_entry, FILE_APPEND);
    
    // Also use error_log for system logging
    error_log($log_entry);
}

// Enhanced error handling and logging
function handleContactFormSubmission() {
    // Log incoming request details
    visibleLog("Received contact form submission", 'INFO');
    visibleLog("Request Method: " . $_SERVER['REQUEST_METHOD'], 'DEBUG');
    visibleLog("Content Type: " . $_SERVER['CONTENT_TYPE'], 'DEBUG');
    
    // Log raw POST data
    visibleLog("Raw POST data: " . file_get_contents('php://input'), 'DEBUG');
    
    // Log all incoming POST data (sanitized)
    visibleLog("POST Data: " . print_r($_POST, true), 'DEBUG');

    // Validate and process form
    try {
        // Input validation
        $name = $_POST['name'] ?? '';
        $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $subject = $_POST['subject'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $message = $_POST['message'] ?? '';

        // Detailed validation logging
        visibleLog("Validating form data...", 'INFO');
        visibleLog("Name: $name", 'DEBUG');
        visibleLog("Email: $email", 'DEBUG');
        visibleLog("Subject: $subject", 'DEBUG');
        visibleLog("Phone: $phone", 'DEBUG');

        if (empty($name)) {
            visibleLog("Validation Error: Name is empty", 'WARNING');
            throw new Exception("Naam is verplicht");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            visibleLog("Validation Error: Invalid email", 'WARNING');
            throw new Exception("Ongeldig e-mailadres");
        }

        if (empty($subject)) {
            visibleLog("Validation Error: Subject is empty", 'WARNING');
            throw new Exception("Onderwerp is verplicht");
        }

        if (empty($message)) {
            visibleLog("Validation Error: Message is empty", 'WARNING');
            throw new Exception("Bericht is verplicht");
        }

        // Attempt to send email
        $to = 'info@maasiso.nl';
        $email_subject = "Nieuw contactformulier: $subject";
        $email_body = "Naam: $name\n";
        $email_body .= "E-mail: $email\n";
        if (!empty($phone)) {
            $email_body .= "Telefoon: $phone\n";
        }
        $email_body .= "Onderwerp: $subject\n\n";
        $email_body .= "Bericht:\n$message";
        
        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();

        // Log email sending attempt
        visibleLog("Attempting to send email to $to", 'INFO');
        visibleLog("Email subject: $email_subject", 'DEBUG');
        visibleLog("Email headers: $headers", 'DEBUG');
        
        // Send email and log result
        $mail_sent = mail($to, $email_subject, $email_body, $headers);
        
        if ($mail_sent) {
            visibleLog("Email sent successfully", 'SUCCESS');
            return ['status' => 'success', 'message' => 'Bericht succesvol verzonden'];
        } else {
            visibleLog("Email sending failed", 'ERROR');
            throw new Exception("Kon het bericht niet verzenden");
        }
    } catch (Exception $e) {
        // Log any exceptions
        visibleLog("Exception: " . $e->getMessage(), 'CRITICAL');
        return ['status' => 'error', 'message' => $e->getMessage()];
    }
}

// Main script execution
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Set headers
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Content-Type');
    
    // Log request start
    visibleLog("Starting form submission handling", 'INFO');
    
    // Process form and return JSON response
    $result = handleContactFormSubmission();
    echo json_encode($result);
    exit;
} else {
    // Log any non-POST requests
    visibleLog("Non-POST request received", 'WARNING');
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
    exit;
}
?>
