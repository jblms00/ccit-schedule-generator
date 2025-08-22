<?php
include 'database-connection.php';

$data = [];

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $query = "
        SELECT 
            s.schedule_id, 
            s.section, 
            s.room, 
            s.week_days, 
            s.start_time, 
            s.end_time, 
            si.*
        FROM 
            schedules s
        JOIN 
            subject_informations si ON s.subject_id = si.subject_id
        WHERE s.is_archived = '1'
    ";

    $result = mysqli_query($con, $query);

    if ($result) {
        $archivedSchedules = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $archivedSchedules[] = $row;
        }

        $data['status'] = "success";
        $data['archivedSchedules'] = $archivedSchedules;
    } else {
        $data['status'] = "error";
        $data['message'] = "Error fetching archived schedules: " . mysqli_error($con);
    }
} else {
    $data['status'] = "error";
    $data['message'] = "Invalid request method.";
}

echo json_encode($data);
?>