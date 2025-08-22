<?php
include 'database-connection.php';

$data = [];

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $query = "
        SELECT 
            s.section, 
            s.datetime_added
        FROM 
            schedules s
        JOIN 
            subject_informations si ON s.subject_id = si.subject_id
        WHERE s.is_archived = '0'
        GROUP BY 
            s.section
    ";

    $result = mysqli_query($con, $query);

    if ($result) {
        $schedules = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $datetime = new DateTime($row['datetime_added']);
            $row['datetime_added'] = $datetime->format('F j, Y \a\t g:i A');

            $schedules[] = $row;
        }

        $archivedQuery = "
            SELECT COUNT(*) AS archived_count 
            FROM schedules 
            WHERE is_archived = '1'
        ";

        $archivedResult = mysqli_query($con, $archivedQuery);
        $archivedData = mysqli_fetch_assoc($archivedResult);
        $hasArchived = $archivedData['archived_count'] > 0;

        $data['status'] = "success";
        $data['schedules'] = $schedules;
        $data['hasArchived'] = $hasArchived;
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