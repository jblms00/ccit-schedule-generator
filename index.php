<?php session_start();
include "phpscripts/database-connection.php";
include "phpscripts/check-login.php";
?>
<!doctype html>
<html lang="en">

<head>
    <?php include "includes/header.php"; ?>
</head>

<body class="landing-pg">
    <?php include "includes/navbar.php"; ?>
    <div class="main-container">
        <div class="banner animation-fade-in">
            <form id="loginAccount">
                <div class="row mb-3">
                    <div class="col">
                        <h1 class="animation-downwards">Welcome to CCIT Automated Scheduling System</h1>
                    </div>
                </div>
                <div class="row mb-2 animation-downwards">
                    <div class="col">
                        <img src="assets/images/ccitLogo.png" alt="logo" class="img-fluid" width="150" height="150">
                    </div>
                </div>
                <div class="row mb-2 animation-left">
                    <div class="col">
                        <input type="email" class="form-control" id="userEmail" placeholder="name@example.com">
                    </div>
                </div>
                <div class="row mb-2 animation-right">
                    <div class="col">
                        <input type="password" class="form-control" id="userPassword" placeholder="Password">
                    </div>
                </div>
                <div class="row animation-upwards">
                    <div class="col">
                        <button type="submit" role="button" class="btn btn-primary">Login</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="container-fluid" id="aboutUs">
            <div class="row">
                <div class="col-6">
                    <h1 class="text-center fw-bold text-uppercase animation-left"
                        style="margin: 60px 0; font-family: 'Poppins', sans-serif;">Key
                        Features
                    </h1>
                    <div class="card-container gap-5">
                        <div class="card features animation-left">
                            <div class="card-title">
                                Automated Conflict-Free Scheduling
                            </div>
                            <div class="card-description">
                                <p>The system auto-generates schedules, assigning days, times, and rooms for each
                                    subject without conflicts, ensuring efficiency.</p>
                            </div>
                        </div>
                        <div class="card features animation-left">
                            <div class="card-title">
                                Subject & Section Customization
                            </div>
                            <div class="card-description">
                                <p>Users select subjects, specify weekly hours per subject, and define the number of
                                    sections to generate schedules tailored to their needs.</p>
                            </div>
                        </div>
                        <div class="card features animation-left">
                            <div class="card-title">
                                Room Assignment with Compatibility
                            </div>
                            <div class="card-description">
                                <p>Rooms are automatically assigned based on subject requirements, such as labs or
                                    lecture halls, and adjusted for room availability.</p>
                            </div>
                        </div>
                        <div class="card features animation-left">
                            <div class="card-title">
                                Smart Time Distribution
                            </div>
                            <div class="card-description">
                                <p>The system spreads class hours evenly across available weekdays, balancing schedules
                                    for both students and instructors.</p>
                            </div>
                        </div>
                        <div class="card features animation-left">
                            <div class="card-title">
                                User Notifications for Schedule Issues
                            </div>
                            <div class="card-description">
                                <p>If scheduling conflicts or capacity issues arise, the system alerts users to adjust
                                    inputs or preferences.</p>
                            </div>
                        </div>
                        <div class="card features animation-left">
                            <div class="card-title">
                                Intuitive Schedule Review & Export
                            </div>
                            <div class="card-description">
                                <p>Users can review, adjust, and export the final schedule for easy sharing with
                                    students and faculty.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6" style="background-color: var(--color2);">
                    <h1 class="text-center fw-bold text-uppercase text-light animation-right"
                        style="margin: 60px 0; font-family: 'Poppins', sans-serif;">How It Works
                    </h1>
                    <div class="card-container gap-5">
                        <div class="card hiw animation-right">
                            <div class="card-title">
                                Subject and Section Setup
                            </div>
                            <div class="card-description">
                                <p>Users select subjects, specify weekly hours per subject, and define the number of
                                    sections, allowing the system to create a tailored schedule based on these inputs.
                                </p>
                            </div>
                        </div>
                        <div class="card hiw animation-right">
                            <div class="card-title">
                                Automated Time Slot Allocation
                            </div>
                            <div class="card-description">
                                <p>The system calculates the necessary time slots based on each subject's required hours
                                    and spreads these slots across available weekdays.</p>
                            </div>
                        </div>
                        <div class="card hiw animation-right">
                            <div class="card-title">
                                Room and Time Assignment
                            </div>
                            <div class="card-description">
                                <p>Each subject is assigned to a compatible room and time slot, ensuring availability
                                    and preventing conflicts.</p>
                            </div>
                        </div>
                        <div class="card hiw animation-right">
                            <div class="card-title">
                                Conflict Resolution
                            </div>
                            <div class="card-description">
                                <p>If any conflicts arise, the system auto-adjusts schedules, reassigning conflicting
                                    slots to maintain a seamless timetable.</p>
                            </div>
                        </div>
                        <div class="card hiw animation-right">
                            <div class="card-title">
                                Review and Fine-Tuning
                            </div>
                            <div class="card-description">
                                <p>The generated schedule is displayed for review, with options for users to manually
                                    adjust or add notes to specific classes.</p>
                            </div>
                        </div>
                        <div class="card hiw animation-right">
                            <div class="card-title">
                                Finalization and Export
                            </div>
                            <div class="card-description">
                                <p>After review, the finalized schedule can be exported or printed, making it easy to
                                    share with students and faculty.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section id="footer">
        <div class="container">
            <div class="row">
                <div class="col text-center animation-left">
                    <img src="assets/images/ccitLogo.png" height="120" width="120" alt="img">
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 mt-2 mt-sm-2 text-center text-white animation-right">
                    <p>&copy 2024 PRMSU Iba Main Campus All rights reserved.
                </div>
                </hr>
            </div>
        </div>
    </section>
    <!-- Toast -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="liveToast" class="toast text-white bg-light" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-body text-center">
                <p class="mb-0 fw-bold"></p>
            </div>
        </div>
    </div>
    <!-- JS -->
    <?php include "includes/js-scripts.php"; ?>
    <script src="assets/js/navbar.js"></script>
    <script src="assets/js/login.js"></script>
</body>

</html>