<?php
include 'database-connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['schedule_id'])) {
    $schedule_id = $_POST['schedule_id'];

    $query = "UPDATE schedules SET is_archived = '0' WHERE schedule_id = '$schedule_id'";

    if (mysqli_query($con, $query)) {
        $data['status'] = 'success';
        $data['message'] = 'Schedule successfully unarchived!';
    } else {
        $data['status'] = 'error';
        $data['message'] = 'Error unarchiving schedule: ' . mysqli_error($con);
    }
} else {
    $data['status'] = 'error';
    $data['message'] = 'Invalid request';
}

echo json_encode($data);
?>