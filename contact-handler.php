<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

// Function to log errors
function logError($message) {
    $logFile = 'contact-form-errors.log';
    $timestamp = date('[Y-m-d H:i:s]');
    file_put_contents($logFile, $timestamp . ' ' . $message . PHP_EOL, FILE_APPEND);
}

// Function to sanitize input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form data
    $name = sanitize_input($_POST['name'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $phone = sanitize_input($_POST['phone'] ?? '');
    $subject = sanitize_input($_POST['subject'] ?? '');
    $message = sanitize_input($_POST['message'] ?? '');
    $recipient = sanitize_input($_POST['recipient'] ?? 'info@maasiso.nl');

    // Validate input
    $errors = [];

    if (empty($name)) {
        $errors[] = "Naam is verplicht.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Ongeldig e-mailadres.";
    }

    if (empty($subject)) {
        $errors[] = "Onderwerp is verplicht.";
    }

    if (empty($message)) {
        $errors[] = "Bericht is verplicht.";
    }

    // If there are no errors, send email
    if (empty($errors)) {
        // Email configuration
        $to = $recipient;
        $email_subject = "Nieuw contactformulier bericht: " . $subject;
        $email_body = "U heeft een nieuw bericht ontvangen van:\n\n";
        $email_body .= "Naam: $name\n";
        $email_body .= "E-mail: $email\n";
        $email_body .= "Telefoon: $phone\n";
        $email_body .= "Onderwerp: $subject\n\n";
        $email_body .= "Bericht:\n$message";

        // Additional headers
        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();

        // Attempt to send email with additional logging
        try {
            $mail_sent = mail($to, $email_subject, $email_body, $headers);

            if ($mail_sent) {
                logError("Email sent successfully to $to");
                echo json_encode([
                    'status' => 'success', 
                    'message' => 'Uw bericht is succesvol verzonden.'
                ]);
            } else {
                $error_message = "Failed to send email. Check server mail configuration.";
                logError($error_message);
                echo json_encode([
                    'status' => 'error', 
                    'message' => $error_message
                ]);
            }
        } catch (Exception $e) {
            $error_message = "Email sending exception: " . $e->getMessage();
            logError($error_message);
            echo json_encode([
                'status' => 'error', 
                'message' => $error_message
            ]);
        }
    } else {
        // If there are validation errors
        $error_string = implode(" ", $errors);
        logError("Validation errors: " . $error_string);
        echo json_encode([
            'status' => 'error', 
            'message' => $error_string
        ]);
    }
} else {
    // If not a POST request
    logError("Invalid request method");
    echo json_encode([
        'status' => 'error', 
        'message' => 'Ongeldige aanvraag.'
    ]);
}
exit();
?>
