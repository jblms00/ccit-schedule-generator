<?php
session_start();
include "database-connection.php";

$data = [];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['selected_sections'])) {
        $selected_sections = $_POST['selected_sections'];

        if (empty($selected_sections) || !is_array($selected_sections)) {
            $data['status'] = "error";
            $data['message'] = "No schedules selected for deletion.";
        } else {
            $sections = implode(",", array_map(function ($section) use ($con) {
                return "'" . mysqli_real_escape_string($con, $section) . "'";
            }, $selected_sections));

            $query = "DELETE FROM schedules WHERE section IN ($sections)";

            if (mysqli_query($con, $query)) {
                $data['status'] = "success";
                $data['message'] = "Selected schedules deleted successfully.";
            } else {
                $data['status'] = "error";
                $data['message'] = "Failed to delete selected schedules. Please try again later.";
            }
        }
    } elseif (isset($_POST['delete_all']) && $_POST['delete_all'] == 'true') {
        $query = "DELETE FROM schedules";

        if (mysqli_query($con, $query)) {
            $data['status'] = "success";
            $data['message'] = "All schedules deleted successfully.";
        } else {
            $data['status'] = "error";
            $data['message'] = "Failed to delete all schedules. Please try again later.";
        }
    } else {
        $data['status'] = "error";
        $data['message'] = "Invalid request.";
    }
} else {
    $data['status'] = "error";
    $data['message'] = "Invalid request method.";
}

echo json_encode($data);
?>