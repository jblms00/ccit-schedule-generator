<?php
session_start();
include("database-connection.php");

$data = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $verification_code = $_POST['code'];
    $verifying_user_id = $_POST['verifyingUserId'];

    if (empty($verification_code)) {
        $data['status'] = "error";
        $data['message'] = "Please enter the verification code sent to your email";
    } else {
        $confirm_code_query = "SELECT * FROM account_verification WHERE verification_code = '$verification_code'";
        $confirm_code_result = mysqli_query($con, $confirm_code_query);

        if ($confirm_code_result && mysqli_num_rows($confirm_code_result) > 0) {
            $fetch_code = mysqli_fetch_assoc($confirm_code_result);

            if ($fetch_code['verification_code'] === $verification_code) {
                $user_id = $fetch_code['user_id'];

                $user_query = "SELECT user_email, user_type FROM users_accounts WHERE user_id = '$user_id'";
                $user_result = mysqli_query($con, $user_query);
                $fetch_user = mysqli_fetch_assoc($user_result);

                if ($user_result && mysqli_num_rows($user_result) > 0) {
                    $user_email = $fetch_user['user_email'];
                    $user_type = $fetch_user['user_type'];

                    $update_query = "UPDATE account_verification SET is_verified = 'true' WHERE verification_code = '$verification_code'";
                    $update_result = mysqli_query($con, $update_query);

                    if ($update_result) {
                        $_SESSION['user_email'] = $user_email;
                        $data['user_type'] = $user_type;
                        $data['status'] = "success";
                        $data['message'] = "2FA verified successfully!";
                    } else {
                        $data['status'] = "error";
                        $data['message'] = "Failed to update verification status.";
                    }
                } else {
                    $data['status'] = "error";
                    $data['message'] = "User not found.";
                }
            } else {
                $data['status'] = "error";
                $data['message'] = "Invalid verification code. Please double-check and try again.";
            }
        } else {
            $data['status'] = "error";
            $data['message'] = "Invalid verification code. Please double-check and try again.";
        }
    }
} else {
    $data['status'] = "error";
    $data['message'] = "Invalid request method";
}

echo json_encode($data);
