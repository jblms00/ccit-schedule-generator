<?php
session_start();

include "database-connection.php";
include "check-login.php";

$user_data = check_login($con);
$logged_in_user = $user_data['user_id'];
$current_password = base64_decode($user_data['user_password']);

$data = [];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = $_POST['floatingName'];
    $password = $_POST['floatingPassword'];
    $confirmPassword = $_POST['floatingConfirmPassword'];

    if (empty($name)) {
        $data['status'] = "error";
        $data['message'] = "Please enter your name.";
        $data['messageKey'] = 'floatingName';
    } else if (empty($password)) {
        $data['status'] = "error";
        $data['message'] = "Please enter your password.";
        $data['messageKey'] = 'floatingPassword';
    } else if (empty($confirmPassword)) {
        $data['status'] = "error";
        $data['message'] = "Please re-type your password.";
        $data['messageKey'] = 'floatingConfirmPassword';
    } else if ($current_password != $password) {
        $data['status'] = "error";
        $data['message'] = "Wrong password. Please try again!";
        $data['messageKey'] = 'floatingConfirmPassword';
    } else if ($password != $confirmPassword) {
        $data['status'] = "error";
        $data['message'] = "Password does not match. Please try again!";
        $data['messageKey'] = 'floatingConfirmPassword';
    } else {
        $update_query = "UPDATE users_accounts SET user_name = '$name' WHERE user_id = '$logged_in_user'";
        $update_result = mysqli_query($con, $update_query);

        if ($update_result) {
            $data['status'] = "success";
            $data['message'] = "Profile updated successfully.";
        } else {
            $data['status'] = "error";
            $data['message'] = "Failed to update profile. Please try again later.";
        }
    }
} else {
    $data['status'] = "error";
    $data['message'] = "Invalid request method. Please try again later.";
}

echo json_encode($data);
?>