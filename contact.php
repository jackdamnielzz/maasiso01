<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to log errors
function logError($message) {
    $logFile = 'mail_errors.log';
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Get form data
        $name = isset($_POST['name']) ? $_POST['name'] : 'No name provided';
        $email = isset($_POST['email']) ? $_POST['email'] : 'No email provided';
        $phone = isset($_POST['phone']) ? $_POST['phone'] : 'No phone provided';
        $subject = isset($_POST['subject']) ? $_POST['subject'] : 'No subject provided';
        $message = isset($_POST['message']) ? $_POST['message'] : 'No message provided';

        // Set email parameters
        $to = "info@maasiso.nl";
        $email_subject = "Nieuw contactformulier bericht: $subject";
        
        // Create HTML message
        $email_body = "
            <html>
            <head>
                <title>Nieuw contactformulier bericht</title>
            </head>
            <body>
                <h2>Nieuw bericht via contactformulier</h2>
                <p><strong>Naam:</strong> $name</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Telefoon:</strong> $phone</p>
                <p><strong>Onderwerp:</strong> $subject</p>
                <p><strong>Bericht:</strong><br>$message</p>
            </body>
            </html>
        ";

        // Create email headers
        $headers = array(
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=utf-8',
            'From: ' . $email,
            'Reply-To: ' . $email,
            'X-Mailer: PHP/' . phpversion()
        );

        // Log attempt
        logError("Attempting to send email from $email to $to");
        
        // Try to send email
        $mail_sent = mail($to, $email_subject, $email_body, implode("\r\n", $headers));
        
        if($mail_sent) {
            logError("Email sent successfully from $email to $to");
            header("Location: contact.html?status=success");
        } else {
            throw new Exception("Mail sending failed");
        }
    } catch (Exception $e) {
        logError("Failed to send email: " . $e->getMessage());
        header("Location: contact.html?status=error");
    }
    exit();
} else {
    // If accessed directly without POST data
    header("Location: contact.html");
    exit();
}
?>
