<?php
session_start();

include ("database-connection.php");

$data = [];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user_id = $_POST['user_id'];
    $account_status = $_POST['account_status'];

    if (empty($user_id)) {
        $data['status'] = "error";
        $data['message'] = "User ID is required.";
    } else if (empty($account_status)) {
        $data['status'] = "error";
        $data['message'] = "Please select account status.";
        $data['messageKey'] = 'newName';
    } else {
        $query = "UPDATE users_accounts SET user_status = '$account_status' WHERE user_id = '$user_id'";
        if (mysqli_query($con, $query)) {
            $data['status'] = "success";
            $data['message'] = "Account status updated successfully.";
        } else {
            $data['status'] = "error";
            $data['message'] = "Failed to update account status. Please try again later.";
        }
    }
} else {
    $data['status'] = "error";
    $data['message'] = "Invalid request method.";
}

echo json_encode($data);
?>