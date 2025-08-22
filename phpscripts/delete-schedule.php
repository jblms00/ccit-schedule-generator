<?php
session_start();

include "database-connection.php";

$data = [];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $schedule_id = $_POST['schedule_id'];

    if (empty($schedule_id)) {
        $data['status'] = "error";
        $data['message'] = "Schedule ID is required.";
    } else {
        $query = "DELETE FROM schedules WHERE schedule_id = '$schedule_id'";
        if (mysqli_query($con, $query)) {
            $data['status'] = "success";
            $data['message'] = "Schedule deleted successfully.";
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