<?php
include 'database-connection.php';

$data = [];

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $query = "
        SELECT 
            s.schedule_id, 
            s.subject_id, 
            si.subject_code, 
            si.subject_name, 
            si.instructor_name, 
            s.section, 
            s.room, 
            s.week_days, 
            s.start_time, 
            s.end_time
        FROM 
            schedules s
        JOIN 
            subject_informations si ON s.subject_id = si.subject_id
        WHERE s.is_archived = '0'
    ";

    $result = mysqli_query($con, $query);

    if ($result) {
        $schedules = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $schedules[] = $row;
        }

        $data['status'] = "success";
        $data['schedules'] = $schedules;
    } else {
        $data['status'] = "error";
        $data['message'] = "Error fetching schedules: " . mysqli_error($con);
    }
} else {
    $data['status'] = "error";
    $data['message'] = "Invalid request method.";
}

echo json_encode($data);
?>