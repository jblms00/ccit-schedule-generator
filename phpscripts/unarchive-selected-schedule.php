<?php
include 'database-connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['schedule_ids']) && is_array($_POST['schedule_ids'])) {
    $schedule_ids = $_POST['schedule_ids'];

    $schedule_ids = array_map('intval', $schedule_ids);

    $ids_string = implode(',', $schedule_ids);

    $query = "UPDATE schedules SET is_archived = '0' WHERE schedule_id IN ($ids_string)";

    if (mysqli_query($con, $query)) {
        $data['status'] = 'success';
        $data['message'] = 'Selected schedules successfully unarchived!';
    } else {
        $data['status'] = 'error';
        $data['message'] = 'Error unarchiving schedules: ' . mysqli_error($con);
    }
} else {
    $data['status'] = 'error';
    $data['message'] = 'Invalid request. No schedules selected.';
}

echo json_encode($data);
?>