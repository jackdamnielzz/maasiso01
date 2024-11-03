<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    
    $to = "info@maasiso.nl";
    $email_subject = "Nieuw contactformulier bericht: $subject";
    
    $email_body = "U heeft een nieuw bericht ontvangen.\n\n".
        "Naam: $name\n".
        "Email: $email\n".
        "Telefoonnummer: $phone\n".
        "Onderwerp: $subject\n\n".
        "Bericht:\n$message";
    
    $headers = "From: $email\n";
    $headers .= "Reply-To: $email";
    
    if(mail($to, $email_subject, $email_body, $headers)) {
        header("Location: contact.html?status=success");
    } else {
        header("Location: contact.html?status=error");
    }
    exit();
}
?>
