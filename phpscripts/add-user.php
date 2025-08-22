<?php
session_start();
include ("database-connection.php");

function generateRandomId()
{
    return str_pad(mt_rand(1, 999999999), 9, '0', STR_PAD_LEFT);
}

$data = [];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($name)) {
        $data['status'] = "error";
        $data['message'] = "Please enter the name.";
        $data['messageKey'] = 'newName';
    } else if (empty($email)) {
        $data['status'] = "error";
        $data['message'] = "Please enter the email.";
        $data['messageKey'] = 'newEmail';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $data['status'] = "error";
        $data['message'] = "Invalid email format.";
        $data['messageKey'] = 'newEmail';
    } else if (empty($password)) {
        $data['status'] = "error";
        $data['message'] = "Please enter the password.";
        $data['messageKey'] = 'newPassword';
    } else {
        $user_id = generateRandomId();
        $password = base64_encode($password);
        $query = "INSERT INTO users_accounts (user_id, user_name, user_email, user_password, date_created) VALUES ('$user_id', '$name', '$email', '$password', NOW())";

        if (mysqli_query($con, $query)) {
            $data['status'] = "success";
        } else {
            $data['status'] = "error";
            $data['message'] = "Failed to add user. Please try again later.";
        }
    }
} else {
    $data['status'] = "error";
    $data['message'] = "Invalid request method. Please try again later.";
}

echo json_encode($data);
?>