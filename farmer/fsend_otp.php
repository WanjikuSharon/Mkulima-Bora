<?php
session_start();
require('../sql.php'); // Ensure the database connection file is correct

// Get the farmer's email from the session
$email = $_SESSION['farmer_login_user'];

// Debugging: Check if session email is set
if (empty($email)) {
    die("Error: Farmer email not found in session.");
}

// Fetch farmer details from the database
$res = mysqli_query($conn, "SELECT * FROM farmerlogin WHERE email='$email'");
$count = mysqli_num_rows($res);

// If the farmer exists, generate and send OTP
if ($count > 0) {
    $otp = rand(11111, 99999); // Generate a 5-digit OTP

    // Update the OTP in the database
    if (mysqli_query($conn, "UPDATE farmerlogin SET otp='$otp' WHERE email='$email'")) {
        // Prepare email content
        $subject = "OTP Verification for Agriculture Portal";
        $html = "Your OTP verification code for Agriculture Portal is: <strong>" . $otp . "</strong>";

        // Debugging: Check values before sending
        echo "Subject: " . $subject . "<br>";
        echo "To: " . $email . "<br>";
        echo "Message: " . $html . "<br>";

        // Send the email
        if (smtp_mailer($email, $subject, $html)) {
            echo "OTP sent successfully!";
        } else {
            echo "Failed to send OTP.";
        }
    } else {
        echo "Error updating OTP: " . mysqli_error($conn);
    }
} else {
    echo "Farmer not found.";
}

// Function to send email using PHPMailer
function smtp_mailer($to, $subject, $msg) {
    require_once("../smtp/class.phpmailer.php"); // Include PHPMailer class

    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPDebug = 2; // Set to 2 for debugging, set to 0 for production
    $mail->SMTPAuth = TRUE;
    $mail->SMTPSecure = 'ssl'; // Use SSL encryption
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 465; // Gmail SMTP port
    $mail->IsHTML(true); // Enable HTML email format
    $mail->CharSet = 'UTF-8'; // Set character encoding

    // Use the same Gmail credentials as in csend.php
    $mail->Username = "sharonwanjiku292@gmail.com"; // Your Gmail address
    $mail->Password = "crgbutiiiuajwfjo"; // Replace with your app password

    $mail->SetFrom("sharonwanjiku292@gmail.com", "Agriculture Portal"); // Sender email
    $mail->Subject = $subject; // Email subject
    $mail->Body = $msg; // Email body
    $mail->AddAddress($to); // Recipient email

    // Debugging: Check if variables are set properly
    if (empty($to)) {
        echo "Error: Recipient email is empty.";
        return false;
    }

    if (!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
        return false;
    } else {
        return true;
    }
}
?>
