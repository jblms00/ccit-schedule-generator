<?php
session_start();

include "database-connection.php";

$data = [];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user_id = $_POST['user_id'];

    if (empty($user_id)) {
        $data['status'] = "error";
        $data['message'] = "User ID is required.";
    } else {
        $query = "DELETE FROM users_accounts WHERE user_id = '$user_id'";
        if (mysqli_query($con, $query)) {
            $data['status'] = "success";
            $data['message'] = "User deleted successfully.";
        } else {
            $data['status'] = "error";
            $data['message'] = "Failed to delete user. Please try again later.";
        }
    }
} else {
    $data['status'] = "error";
    $data['message'] = "Invalid request method.";
}

echo json_encode($data);
?>