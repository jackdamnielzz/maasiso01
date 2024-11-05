<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
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
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';
    
    // Validate required fields
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
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
    $headers = [
        "From: website@maasiso.nl",
        "Reply-To: $email",
        "MIME-Version: 1.0",
        "Content-Type: text/plain; charset=UTF-8",
        "X-Mailer: PHP/" . phpversion()
    ];

    // Attempt to send email
    $mail_sent = mail($to, $email_subject, $email_body, implode("\r\n", $headers));

    if ($mail_sent) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Uw bericht is succesvol verzonden'
        ]);
    } else {
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
