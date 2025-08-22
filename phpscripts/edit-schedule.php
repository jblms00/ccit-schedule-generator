<?php
session_start();

include "database-connection.php";
include "check-login.php";

$data = [];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $schedule_id = mysqli_real_escape_string($con, $_POST['schedule_id']);
    $room = mysqli_real_escape_string($con, $_POST['room']);
    $week_days = mysqli_real_escape_string($con, $_POST['week_days']);
    $start_time = mysqli_real_escape_string($con, $_POST['start_time']);
    $end_time = mysqli_real_escape_string($con, $_POST['end_time']);

    if (empty($room)) {
        $data['status'] = "error";
        $data['message'] = "Room is required.";
    } else if (empty($week_days)) {
        $data['status'] = "error";
        $data['message'] = "Week days is required.";
    } else if (empty($start_time || $end_time)) {
        $data['status'] = "error";
        $data['message'] = "School semester is required.";
    } else {
        $update_query = "UPDATE schedules SET room = '$room', week_days = '$week_days', start_time = '$start_time', end_time = '$end_time' WHERE schedule_id = '$schedule_id'";
        $result = mysqli_query($con, $update_query);

        if ($result) {
            $data['status'] = "success";
            $data['message'] = "Subject updated successfully.";
        } else {
            $data['status'] = "error";
            $data['message'] = "Failed to update subject. Please try again.";
        }
    }
} else {
    $data['status'] = "error";
    $data['message'] = "Invalid request method.";
}

echo json_encode($data);
?>