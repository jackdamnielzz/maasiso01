<?php
header('Content-Type: application/json');

try {
    // Basic validation
    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['subject']) || empty($_POST['message'])) {
        throw new Exception('Alle verplichte velden moeten worden ingevuld.');
    }

    // Sanitize inputs
    $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $phone = !empty($_POST['phone']) ? filter_var(trim($_POST['phone']), FILTER_SANITIZE_STRING) : 'Niet opgegeven';
    $subject = filter_var(trim($_POST['subject']), FILTER_SANITIZE_STRING);
    $message = filter_var(trim($_POST['message']), FILTER_SANITIZE_STRING);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Ongeldig e-mailadres.');
    }

    // Prepare email content
    $to = 'info@maasiso.nl';
    $emailSubject = "Nieuw contactformulier bericht: $subject";
    
    $emailBody = "
    <html>
    <head>
        <title>Nieuw contactformulier bericht</title>
    </head>
    <body>
        <h2>Nieuw bericht via het contactformulier</h2>
        <p><strong>Naam:</strong> {$name}</p>
        <p><strong>E-mail:</strong> {$email}</p>
        <p><strong>Telefoon:</strong> {$phone}</p>
        <p><strong>Onderwerp:</strong> {$subject}</p>
        <p><strong>Bericht:</strong></p>
        <p>" . nl2br($message) . "</p>
    </body>
    </html>";

    // Basic email headers
    $headers = array();
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=UTF-8';
    $headers[] = 'From: ' . $name . ' <' . $email . '>';
    $headers[] = 'Reply-To: ' . $email;

    // Send email
    $mailSent = mail($to, $emailSubject, $emailBody, implode("\r\n", $headers));

    if (!$mailSent) {
        throw new Exception('E-mail kon niet worden verzonden.');
    }

    echo json_encode([
        'success' => true,
        'message' => 'Bericht succesvol verzonden.'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
