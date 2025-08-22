<?php
session_start();

include "database-connection.php";

$data = [];


$query = "SELECT * FROM subject_informations ORDER BY school_year ASC";
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
    $data['message'] = "Failed to fetch subjects. Please try again later.";
}


echo json_encode($data);
?>