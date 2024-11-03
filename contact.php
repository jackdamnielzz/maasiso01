<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = isset($_POST['name']) ? $_POST['name'] : 'No name provided';
    $email = isset($_POST['email']) ? $_POST['email'] : 'No email provided';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : 'No phone provided';
    $subject = isset($_POST['subject']) ? $_POST['subject'] : 'No subject provided';
    $message = isset($_POST['message']) ? $_POST['message'] : 'No message provided';
    
    // Set email parameters
    $to = "info@maasiso.nl";
    $email_subject = "Nieuw contactformulier bericht: $subject";
    
    // Create email body
    $email_body = "U heeft een nieuw bericht ontvangen via het contactformulier.\n\n".
        "Naam: $name\n".
        "Email: $email\n".
        "Telefoonnummer: $phone\n".
        "Onderwerp: $subject\n\n".
        "Bericht:\n$message";
    
    // Create email headers
    $headers = array(
        'From' => $email,
        'Reply-To' => $email,
        'X-Mailer' => 'PHP/' . phpversion()
    );
    
    // Try to send email and log result
    try {
        $mail_sent = mail($to, $email_subject, $email_body, $headers);
        
        if($mail_sent) {
            error_log("Email sent successfully from $email to $to");
            header("Location: contact.html?status=success");
        } else {
            error_log("Failed to send email from $email to $to");
            throw new Exception("Mail sending failed");
        }
    } catch (Exception $e) {
        error_log("Exception occurred while sending email: " . $e->getMessage());
        header("Location: contact.html?status=error");
    }
    
    exit();
} else {
    // If accessed directly without POST data
    header("Location: contact.html");
    exit();
}
?>
