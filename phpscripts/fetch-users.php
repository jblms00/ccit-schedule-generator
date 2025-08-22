<?php
session_start();

include "database-connection.php";

$data = [];


$query = "SELECT * FROM users_accounts WHERE user_type != 'admin'";
$result = mysqli_query($con, $query);

if ($result) {
    $users = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
    $data['status'] = "success";
    $data['users'] = $users;
} else {
    $data['status'] = "error";
    $data['message'] = "Failed to fetch users. Please try again later.";
}


echo json_encode($data);
?>