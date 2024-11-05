<?php
header('Content-Type: application/json');

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
        $to = $recipient;
        $email_subject = "Nieuw contactformulier bericht: " . $subject;
        $email_body = "U heeft een nieuw bericht ontvangen van:\n\n";
        $email_body .= "Naam: $name\n";
        $email_body .= "E-mail: $email\n";
        $email_body .= "Telefoon: $phone\n";
        $email_body .= "Onderwerp: $subject\n\n";
        $email_body .= "Bericht:\n$message";

        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();

        // Attempt to send email
        $mail_sent = mail($to, $email_subject, $email_body, $headers);

        if ($mail_sent) {
            echo json_encode([
                'status' => 'success', 
                'message' => 'Uw bericht is succesvol verzonden.'
            ]);
        } else {
            echo json_encode([
                'status' => 'error', 
                'message' => 'Er is een fout opgetreden bij het verzenden van uw bericht.'
            ]);
        }
    } else {
        // If there are validation errors
        echo json_encode([
            'status' => 'error', 
            'message' => implode(" ", $errors)
        ]);
    }
} else {
    // If not a POST request
    echo json_encode([
        'status' => 'error', 
        'message' => 'Ongeldige aanvraag.'
    ]);
}
exit();
?>
