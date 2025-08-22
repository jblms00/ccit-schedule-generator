<?php
include 'database-connection.php';

$data = [];
$weekDaysOptions = ["Monday to Tuesday", "Monday to Wednesday", "Tuesday to Wednesday", "Tuesday to Thursday", "Wednesday to Thursday", "Wednesday to Friday", "Thursday to Friday"];
$lecRooms = ["LEC1", "LEC2", "LEC3", "LEC4", "LEC5", "LEC6", "LEC7", "LEC8", "LEC9", "LEC10"];
$labRooms = ["LAB1", "LAB2", "LAB3", "LAB4", "LAB5"];
$startTime = new DateTime();
$startTime->setTime(7, 0, 0);
$schedules = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $numberOfSections = $_POST['numberOfSections'];
    $hoursPerClass = intval($_POST['hoursPerClass']);
    $selectedSubjects = $_POST['selectedSubjects'];

    if (empty($numberOfSections) || $numberOfSections === "0") {
        $data['status'] = "error";
        $data['message'] = "Please specify a valid number of sections. This field cannot be empty or zero.";
    } elseif ($numberOfSections > 10) {
        $data['status'] = "error";
        $data['message'] = "The maximum number of sections allowed is 10.";
    } elseif (empty($hoursPerClass) || $hoursPerClass === "0") {
        $data['status'] = "error";
        $data['message'] = "Please enter a valid number of hours per class. This field cannot be empty or zero.";
    } elseif ($hoursPerClass > 5) {
        $data['status'] = "error";
        $data['message'] = "The maximum hours per class allowed is 5.";
    } elseif (empty($selectedSubjects) || !is_array($selectedSubjects) || count($selectedSubjects) === 0) {
        $data['status'] = "error";
        $data['message'] = "Please select at least one subject. This field cannot be empty.";
    } else {
        foreach ($selectedSubjects as $subject) {
            $subjectId = mysqli_real_escape_string($con, $subject['subject_id']);
            $subjectCode = mysqli_real_escape_string($con, $subject['subject_code']);
            $hasLab = mysqli_real_escape_string($con, $subject['has_lab']);
            $yearLevelRaw = mysqli_real_escape_string($con, $subject['year_level']);
            preg_match('/(\d+)/', $yearLevelRaw, $matches);
            $yearLevel = !empty($matches) ? $matches[0] : null;

            for ($i = 0; $i < $numberOfSections; $i++) {
                $sectionName = "BSIT " . $yearLevel . chr(65 + $i);

                if (hasDuplicateSubject($con, $subjectId, $sectionName)) {
                    $sectionName = generateNewSectionName($con, $subjectId, $yearLevel); // Generate new section name if duplicate
                }

                if (strpos($subjectCode, 'NSTP') !== false) {
                    $weekDays = "Saturday";
                    $lectureStartTime = clone $startTime;
                } else {
                    $weekDays = $weekDaysOptions[array_rand($weekDaysOptions)];
                    $lectureStartTime = clone $startTime;
                }

                if ($hasLab == '1') {
                    $lecEndTime = clone $lectureStartTime;
                    $lecEndTime->add(new DateInterval('PT1H30M'));

                    $labStartTime = clone $lecEndTime;
                    $labEndTime = clone ($labStartTime);
                    $labEndTime->add(new DateInterval('PT1H30M'));

                    if (!hasConflict($con, $lecRooms[array_rand($lecRooms)], $weekDays, $lectureStartTime->format('H:i'), $lecEndTime->format('H:i'))) {
                        $schedules[] = [
                            'subject_id' => $subjectId,
                            'subject_code' => $subjectCode,
                            'year_level' => $yearLevel,
                            'has_lab' => $hasLab,
                            'section_name' => $sectionName,
                            'week_days' => $weekDays,
                            'room' => $lecRooms[array_rand($lecRooms)],
                            'start_time' => $lectureStartTime->format('H:i'),
                            'end_time' => $lecEndTime->format('H:i'),
                        ];
                        insertSchedule($con, end($schedules));
                    }

                    if (!hasConflict($con, $labRooms[array_rand($labRooms)], $weekDays, $labStartTime->format('H:i'), $labEndTime->format('H:i'))) {
                        $schedules[] = [
                            'subject_id' => $subjectId,
                            'subject_code' => $subjectCode,
                            'year_level' => $yearLevel,
                            'has_lab' => $hasLab,
                            'section_name' => $sectionName,
                            'week_days' => $weekDays,
                            'room' => $labRooms[array_rand($labRooms)],
                            'start_time' => $labStartTime->format('H:i'),
                            'end_time' => $labEndTime->format('H:i'),
                        ];
                        insertSchedule($con, end($schedules));
                    }
                    $endTime = clone $labEndTime;
                } else {
                    $endTime = clone $lectureStartTime;
                    $endTime->add(new DateInterval('PT' . $hoursPerClass . 'H'));

                    if (!hasConflict($con, $lecRooms[array_rand($lecRooms)], $weekDays, $lectureStartTime->format('H:i'), $endTime->format('H:i'))) {
                        $schedules[] = [
                            'subject_id' => $subjectId,
                            'subject_code' => $subjectCode,
                            'year_level' => $yearLevel,
                            'has_lab' => $hasLab,
                            'section_name' => $sectionName,
                            'week_days' => $weekDays,
                            'room' => $lecRooms[array_rand($lecRooms)],
                            'start_time' => $lectureStartTime->format('H:i'),
                            'end_time' => $endTime->format('H:i'),
                        ];
                        insertSchedule($con, end($schedules));
                    }
                }
                $startTime = clone $endTime;
            }
        }
        $data['status'] = "success";
        $data['message'] = "Schedules generated successfully.";
        $data['schedules'] = $schedules;
    }
} else {
    $data['status'] = "error";
    $data['message'] = "Invalid request method. Please try again later.";
}

echo json_encode($data);

function hasConflict($con, $room, $weekDays, $startTime, $endTime)
{
    $query = "
        SELECT * FROM schedules 
        WHERE room = '$room' 
          AND week_days = '$weekDays' 
          AND (
                (start_time < '$endTime' AND end_time > '$startTime')
              )
    ";
    $result = mysqli_query($con, $query);
    return mysqli_num_rows($result) > 0;
}

function hasDuplicateSubject($con, $subjectId, $sectionName)
{
    $query = "SELECT * FROM schedules WHERE subject_id = '$subjectId' AND section = '$sectionName'";
    $result = mysqli_query($con, $query);
    return mysqli_num_rows($result) > 0;
}

function generateNewSectionName($con, $subjectId, $yearLevel)
{
    $i = 0;
    do {
        $sectionName = "BSIT " . $yearLevel . chr(65 + $i);
        $i++;
    } while (hasDuplicateSubject($con, $subjectId, $sectionName));
    return $sectionName;
}

function insertSchedule($con, $schedule)
{
    $scheduleId = random_int(100000, 999999);
    $query = "INSERT INTO schedules (schedule_id, subject_id, section, room, week_days, start_time, end_time, datetime_added) 
              VALUES ('$scheduleId', '{$schedule['subject_id']}', '{$schedule['section_name']}', '{$schedule['room']}', '{$schedule['week_days']}', '{$schedule['start_time']}', '{$schedule['end_time']}', NOW())";
    if (!mysqli_query($con, $query)) {
        throw new Exception("Error inserting schedule: " . mysqli_error($con));
    }
}
?>