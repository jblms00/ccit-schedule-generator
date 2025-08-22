<?php
session_start();

include "database-connection.php";

$data = [];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $subject_id = $_POST['subject_id'];

    if (empty($subject_id)) {
        $data['status'] = "error";
        $data['message'] = "Subject ID is required.";
    } else {
        $query = "DELETE FROM subject_informations WHERE subject_id = '$subject_id'";
        if (mysqli_query($con, $query)) {
            $data['status'] = "success";
            $data['message'] = "Subject deleted successfully.";
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