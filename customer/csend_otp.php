<?php
session_start();
require('../sql.php');  // Ensure the database connection file is correct

// Get customer email from session
$email = $_SESSION['customer_login_user'];

// Fetch customer from the database
$res = mysqli_query($conn, "SELECT * FROM custlogin WHERE email='$email'");
$count = mysqli_num_rows($res);

/*// If customer exists, generate and send OTP - PREV CODE
if ($count > 0) {
    $otp = rand(11111, 99999);  // Generate 5-digit OTP

    // Update OTP in the database
    mysqli_query($conn, "UPDATE custlogin SET otp='$otp' WHERE email='$email'");

    // Prepare OTP email content
    $html = "Your OTP verification code for Agriculture Portal is: " . $otp;

    // Send the OTP via email
    if (smtp_mailer($email, 'OTP Verification', $html)) {
        echo "OTP sent successfully!";
    } else {
        echo "Failed to send OTP.";
    }
} else {
    echo "Customer not found.";
}*/
// If customer exists, generate and send OTP
if ($count > 0) {
    $otp = rand(11111, 99999);  // Generate 5-digit OTP

    // Update OTP in the database
    if (mysqli_query($conn, "UPDATE custlogin SET otp='$otp' WHERE email='$email'")) {
        // Prepare OTP email content
        $subject = "OTP Verification for Agriculture Portal";  // Set the subject
        $html = "Your OTP verification code for Agriculture Portal is: " . $otp;
        
    
        // Debug output to check OTP content
        echo "Subject: " . $subject . "<br>";  // Check if subject is set
        echo "Message: " . $html . "<br>";  // Check if message body is set
        // Further debugging
        echo "OTP: " . $otp . "<br>";  // Check the OTP value
        
        var_dump($html);  // Check the value of $html
        
        // Send the OTP via email
        if (smtp_mailer($email, $subject, $html)) {
            echo "OTP sent successfully!";
        } else {
            echo "Failed to send OTP.";
        }
    } else {
        echo "Error updating OTP: " . mysqli_error($conn);
    }
} else {
    echo "Customer not found.";
}


// Function to send email using PHPMailer
function smtp_mailer($to, $subject, $msg) {
    require_once("../smtp/class.phpmailer.php");  // Include PHPMailer class

    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPDebug = 2;  // Set to 2 for debugging
    $mail->SMTPAuth = TRUE;
    $mail->SMTPSecure = 'ssl';  // SSL encryption
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 465;  // Gmail SMTP port
    $mail->IsHTML(true);  // Use HTML format for email
    $mail->CharSet = 'UTF-8';  // Character set

    // Enter your Gmail credentials
    $mail->Username = "sharonwanjiku292@gmail.com";  // Your Gmail address
    $mail->Password = "crgbutiiiuajwfjo";  // Replace with your 16-character app password

    $mail->SetFrom("sharonwanjiku292@gmail.com", "Agriculture Portal");  // From address
    $mail->Subject = $subject;  // Email subject
    $mail->Body = $msg;  // Email body content
    $mail->AddAddress($to);  // Add recipient

    // Send the email and return success or failure
    if (!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;  // Output any errors
        return false;  // Email sending failed
    } else {
        return true;  // Email sent successfully
    }
}
?>
