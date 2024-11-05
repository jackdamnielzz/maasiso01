<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Logging function
function visibleLog($message, $level = 'INFO') {
    $timestamp = date('[Y-m-d H:i:s]');
    $log_entry = "{$timestamp} [{$level}] {$message}\n";
    error_log($log_entry);
}

// Main script execution
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    visibleLog("POST request received");
    visibleLog("POST data: " . print_r($_POST, true));

    // Get form data
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';

    // Validate data
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        visibleLog("Validation failed - missing required fields");
        echo json_encode(['status' => 'error', 'message' => 'Alle verplichte velden moeten ingevuld zijn']);
        exit;
    }

    // Prepare email
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
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Send email
    visibleLog("Attempting to send email");
    $mail_sent = mail($to, $email_subject, $email_body, $headers);

    if ($mail_sent) {
        visibleLog("Email sent successfully");
        echo json_encode(['status' => 'success', 'message' => 'Bericht succesvol verzonden']);
    } else {
        visibleLog("Failed to send email");
        echo json_encode(['status' => 'error', 'message' => 'Kon het bericht niet verzenden']);
    }
} else {
    visibleLog("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
}
?>
