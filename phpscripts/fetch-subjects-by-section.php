<?php
include 'database-connection.php';

$data = [];

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $section = isset($_GET['section']) ? mysqli_real_escape_string($con, $_GET['section']) : '';

    $query = "
        SELECT 
            si.subject_id,
            si.subject_code,
            si.subject_name,
            s.schedule_id,
            s.room,
            s.week_days,
            s.start_time,
            s.end_time,
            s.is_archived
        FROM 
            schedules s
        JOIN 
            subject_informations si ON s.subject_id = si.subject_id
        WHERE 
            s.section = '$section'
        AND s.is_archived != '1'
        ORDER BY si.subject_name ASC
    ";

    $result = mysqli_query($con, $query);

    if ($result) {
        $subjects = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $subjects[] = $row;
        }

        $data['status'] = "success";
        $data['subjects'] = $subjects;
    } else {
        $data['status'] = "error";
        $data['message'] = "Error fetching subjects: " . mysqli_error($con);
    }
} else {
    $data['status'] = "error";
    $data['message'] = "Invalid request method.";
}

// Return the data as JSON
echo json_encode($data);
?>