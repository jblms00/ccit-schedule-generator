<?php
session_start();
include("database-connection.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../assets/vendor/autoload.php';

$data = [];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user_email = $_POST['userEmail'];
    $user_password = $_POST['userPassword'];

    $get_users_query = "SELECT user_id, user_name, user_email, user_password FROM users_accounts WHERE user_email = '$user_email' AND user_status = 'active'";
    $get_users_result = mysqli_query($con, $get_users_query);
    $fetch_users = mysqli_fetch_assoc($get_users_result);

    if ($get_users_result && mysqli_num_rows($get_users_result) <= 0) {
        $data['status'] = "error";
        $data['message'] = "No user found";
    } else if (empty($user_email) && empty($user_password)) {
        $data['status'] = "error";
        $data['message'] = "Please enter your email and password";
    } else if (empty($user_password)) {
        $data['status'] = "error";
        $data['message'] = "Please enter your password";
    } else if ($user_email != $fetch_users['user_email']) {
        $data['status'] = "error";
        $data['message'] = "Incorrect email";
    } else if (base64_encode($user_password) != $fetch_users['user_password']) {
        $data['status'] = "error";
        $data['message'] = "Incorrect password";
    } else {
        $verification_id = rand(100000, 999999);
        $verification_code = rand(100000, 999999);
        $user_id = $fetch_users['user_id'];

        $insert_code_query = "INSERT INTO account_verification (verification_id, user_id, verification_code) VALUES ('$verification_id', '$user_id', '$verification_code')";
        $insert_code_result = mysqli_query($con, $insert_code_query);

        if ($insert_code_result) {
            send2FACode($user_email, $fetch_users['user_name'], $verification_code);

            $data['userId'] = $user_id;
            $data['status'] = "2fa_required";
            $data['message'] = "A verification code has been sent to your email.";
        } else {
            $data['status'] = "error";
            $data['message'] = "Error sending verification to your email";
        }
    }
}

echo json_encode($data);

function send2FACode($user_email, $user_name, $verification_code)
{
    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'web.prmsu@gmail.com';
        $mail->Password = 'caou dpwt lbrm zisf';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('web.prmsu@gmail.com', 'CCIT Support Team');
        $mail->addAddress($user_email, $user_name);

        $mail->isHTML(true);
        $mail->Subject = "Verify Your Account - CCIT";
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; color: #333;'>
                <h2 style='color: #45a29e;'>Hello, $user_name!</h2>
                <p>We received a request to log into your CCIT account. To complete the login process, please use the verification code below:</p>
                <p style='font-size: 18px; font-weight: bold; color: #45a29e;'>$verification_code</p>
                <p>If you didn't request this, please ignore this email or contact support.</p>
                <br>
                <p style='margin-bottom: 0;'>Thank you,<br>
                <strong>CCIT Support Team</strong></p>
                <p style='margin-bottom: 0; font-size: 12px; color: #888;'>This is an automated message, please do not reply directly to this email.</p>
            </div>";
        $mail->send();
    } catch (Exception $e) {
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
    }
}
?>