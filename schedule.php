<?php
session_start();
include "phpscripts/database-connection.php";
include "phpscripts/check-login.php";
$currentYear = date("Y");

$firstSemesterStart = "$currentYear-07-29";
$firstSemesterEnd = "$currentYear-11-29";

$secondSemesterStart = ($currentYear + 1) . "-01-06";
$secondSemesterEnd = ($currentYear + 1) . "-05-09";

$midClassStart = ($currentYear + 1) . "-05-26";
$midClassEnd = ($currentYear + 1) . "-07-24";

function formatDate($date)
{
   return date("F j, Y", strtotime($date));
}
?>
<!doctype html>
<html lang="en">

<head>
   <?php include "includes/header.php"; ?>
</head>

<body class="landing-pg">
   <?php include "includes/navbar.php"; ?>
   <main class="container" style="padding: 5rem 0;">
      <div class="container text-center mb-5 animation-downwards">
         <h5 class="fw-bold">Important Dates</h5>
         <div class="d-flex flex-wrap justify-content-center align-items-center gap-3">
            <div class="idates bg-success-subtle rounded-3 p-2 fw-semibold">
               <?php echo formatDate($firstSemesterStart) . " to " . formatDate($firstSemesterEnd) . " - First Semester"; ?>
            </div>

            <div class="idates bg-success-subtle rounded-3 p-2 fw-semibold">
               <?php echo formatDate($secondSemesterStart) . " to " . formatDate($secondSemesterEnd) . " - Second Semester"; ?>
            </div>

            <div class="idates bg-success-subtle rounded-3 p-2 fw-semibold">
               <?php echo formatDate($midClassStart) . " to " . formatDate($midClassEnd) . " - Mid Class"; ?>
            </div>
         </div>
      </div>
      <div class="calendar-container animation-left">
         <div id="calendar"></div>
      </div>
   </main>
   <section id="footer">
      <div class="container">
         <div class="row">
            <div class="col text-center animation-left">
               <img src="assets/images/ccitLogo.png" height="120" width="120" alt="img">
            </div>
         </div>
         <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2 mt-sm-2 text-center text-white animation-right">
               <p>&copy 2024 PRMSU Iba Main Campus - BSINFOTECH Program. All rights reserved.</p>
            </div>
            </hr>
         </div>
      </div>
   </section>
   <!-- Schedule Details Modal -->
   <div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-body">
               <div class="input-group mb-3">
                  <span class="input-group-text w-25 fw-bold" id="basic-addon1">Subject</span>
                  <input type="text" class="form-control" id="modalSubject" placeholder="Subject" disabled>
               </div>
               <div class="input-group mb-3">
                  <span class="input-group-text w-25 fw-bold" id="basic-addon2">Instructor</span>
                  <input type="text" class="form-control" id="modalInstructor" placeholder="Instructor" disabled>
               </div>
               <div class="input-group mb-3">
                  <span class="input-group-text w-25 fw-bold" id="basic-addon3">Section</span>
                  <input type="text" class="form-control" id="modalSection" placeholder="Section" disabled>
               </div>
               <div class="input-group mb-3">
                  <span class="input-group-text w-25 fw-bold" id="basic-addon4">Room</span>
                  <input type="text" class="form-control" id="modalRoom" placeholder="Room" disabled>
               </div>
               <div class="input-group">
                  <span class="input-group-text w-25 fw-bold" id="basic-addon5">Time</span>
                  <input type="text" class="form-control" id="modalTime" placeholder="Time" disabled>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- JS -->
   <?php include "includes/js-scripts.php"; ?>
   <script src="assets/js/navbar.js"></script>
   <script src="assets/js/display-schedules.js"></script>
   <script src="assets/js/login.js"></script>
</body>

</html>