<?php
require_once("class.phpmailer.php");

function smtp_mailer($to, $subject, $msg) {
    $mail = new PHPMailer(); // create a new object
    $mail->IsSMTP(); // enable SMTP
    $mail->SMTPDebug = 0; // debugging: 0 = off, 1 = errors and messages, 2 = messages only
    $mail->SMTPAuth = true; // authentication enabled
    $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
    $mail->Host = "smtp.gmail.com"; // Replace with your SMTP host
    $mail->Port = 465; // or 587
    $mail->IsHTML(true);
    $mail->Username = "sharonwanjiku292@gmail.com"; // Your email address
    $mail->Password = "crgbutiiiuajwfjo"; // Replace with your 16-character app password
    $mail->SetFrom("sharonwanjiku292@gmail.com", "Agriculture Portal"); // From address

    // Validate "To" Address
    if (empty($to) || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid recipient email address.";
        return false;
    }

    // Validate Subject and Body
    if (empty($subject)) {
        echo "Email subject is missing.";
        return false;
    }
    if (empty($msg)) {
        echo "Email body is missing.";
        return false;
    }

    $mail->Subject = $subject;
    $mail->Body = $msg;
    $mail->AddAddress($to);

    if (!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
        return false;
    } else {
        echo "Message has been sent successfully.";
        return true;
    }
}

// Test the function
smtp_mailer('sharonwanjiku.a@gmail.com', "Test Email", "This is a test email body.");
?>
