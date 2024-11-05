<?php
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
    
    // Optional: Send log to email for immediate notification
    // Uncomment and configure if needed
    // mail('your-email@example.com', 'Contact Form Log', $log_entry);
}

// Enhanced error handling and logging
function handleContactFormSubmission() {
    // Log incoming request details
    visibleLog("Received contact form submission", 'INFO');
    
    // Log all incoming POST data (sanitized)
    visibleLog("POST Data: " . print_r(array_map('htmlspecialchars', $_POST), true), 'DEBUG');

    // Validate and process form
    try {
        // Input validation
        $name = $_POST['name'] ?? '';
        $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $message = $_POST['message'] ?? '';

        // Detailed validation logging
        if (empty($name)) {
            visibleLog("Validation Error: Name is empty", 'WARNING');
            throw new Exception("Name is required");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            visibleLog("Validation Error: Invalid email", 'WARNING');
            throw new Exception("Invalid email address");
        }

        // Attempt to send email
        $to = 'info@maasiso.nl';
        $subject = "New Contact Form Submission";
        $email_body = "Name: $name\nEmail: $email\n\nMessage:\n$message";
        $headers = "From: $email\r\nReply-To: $email\r\n";

        // Log email sending attempt
        visibleLog("Attempting to send email to $to", 'INFO');
        
        // Send email and log result
        $mail_sent = mail($to, $subject, $email_body, $headers);
        
        if ($mail_sent) {
            visibleLog("Email sent successfully", 'SUCCESS');
            return ['status' => 'success', 'message' => 'Message sent successfully'];
        } else {
            visibleLog("Email sending failed", 'ERROR');
            return ['status' => 'error', 'message' => 'Failed to send message'];
        }
    } catch (Exception $e) {
        // Log any exceptions
        visibleLog("Exception: " . $e->getMessage(), 'CRITICAL');
        return ['status' => 'error', 'message' => $e->getMessage()];
    }
}

// Main script execution
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Set content type to JSON
    header('Content-Type: application/json');
    
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
