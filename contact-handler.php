<?php
// Enable error reporting based on server settings
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
ini_set('display_errors', 1);

// Set headers for CORS and JSON response
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: https://maasiso.nl');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Main script execution
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
    
    // Validate required fields
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Alle verplichte velden moeten ingevuld zijn'
        ]);
        exit;
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Ongeldig e-mailadres'
        ]);
        exit;
    }

    // Prepare email content
    $to = 'info@maasiso.nl';
    $email_subject = "Nieuw contactformulier: " . $subject;
    
    // Create email body
    $email_body = "Nieuw bericht via het contactformulier:\n\n";
    $email_body .= "Naam: " . $name . "\n";
    $email_body .= "E-mail: " . $email . "\n";
    if (!empty($phone)) {
        $email_body .= "Telefoon: " . $phone . "\n";
    }
    $email_body .= "Onderwerp: " . $subject . "\n\n";
    $email_body .= "Bericht:\n" . $message . "\n";

    // Set email headers
    $headers = array(
        'From: website@maasiso.nl',
        'Reply-To: ' . $email,
        'X-Mailer: PHP/' . phpversion(),
        'Content-Type: text/plain; charset=UTF-8',
        'MIME-Version: 1.0'
    );

    // Convert headers array to string
    $headers_string = implode("\r\n", $headers);

    // Attempt to send email
    $mail_sent = mail($to, $email_subject, $email_body, $headers_string);

    if ($mail_sent) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Uw bericht is succesvol verzonden'
        ]);
    } else {
        error_log("Failed to send email from contact form. From: $email, Subject: $email_subject");
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Er is een probleem opgetreden bij het verzenden van uw bericht'
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Method Not Allowed'
    ]);
}
?>
