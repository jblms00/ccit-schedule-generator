<?php
session_start();

include ("database-connection.php");

$data = [];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user_id = $_POST['user_id'];

    if (empty($user_id)) {
        $data['status'] = "error";
        $data['message'] = "User ID is required.";
    } else {
        $query = "SELECT user_id, user_name, user_email, user_password, user_status FROM users_accounts WHERE user_id = '$user_id'";
        $result = mysqli_query($con, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            $user['user_password'] = base64_decode($user['user_password']);
            $data['status'] = "success";
            $data['user'] = $user;
        } else {
            $data['status'] = "error";
            $data['message'] = "User not found.";
        }
    }
} else {
    $data['status'] = "error";
    $data['message'] = "Invalid request method.";
}

echo json_encode($data);
?>