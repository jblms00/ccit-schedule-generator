<?php
session_start();

include "database-connection.php";
include "check-login.php";

$data = [];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $subjectId = mysqli_real_escape_string($con, $_POST['subject_id']);
    $subjectCode = mysqli_real_escape_string($con, $_POST['subject_code']);
    $subjectName = mysqli_real_escape_string($con, $_POST['subject_name']);
    $schoolYear = mysqli_real_escape_string($con, $_POST['school_year']);
    $schoolSemester = mysqli_real_escape_string($con, $_POST['school_semester']);
    $hasLab = mysqli_real_escape_string($con, $_POST['has_lab']);
    $instructorName = mysqli_real_escape_string($con, $_POST['instructor_name']);

    if (empty($subjectCode)) {
        $data['status'] = "error";
        $data['message'] = "Subject code is required.";
    } else if (empty($subjectName)) {
        $data['status'] = "error";
        $data['message'] = "Subject name is required.";
    } else if (empty($schoolYear)) {
        $data['status'] = "error";
        $data['message'] = "School year is required.";
    } else if (empty($schoolSemester)) {
        $data['status'] = "error";
        $data['message'] = "School semester is required.";
    } else if (empty($hasLab)) {
        $data['status'] = "error";
        $data['message'] = "Please indicate if the subject includes a laboratory component.";
    } else if (empty($instructorName) || $instructorName === "None") {
        $data['status'] = "error";
        $data['message'] = "Instructor name is required.";
    } else {
        $update_query = "
            UPDATE subject_informations 
            SET subject_code = '$subjectCode', 
                subject_name = '$subjectName', 
                school_year = '$schoolYear', 
                school_semester = '$schoolSemester', 
                instructor_name = '$instructorName', 
                has_lab = '$hasLab' 
            WHERE subject_id = '$subjectId'
        ";

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