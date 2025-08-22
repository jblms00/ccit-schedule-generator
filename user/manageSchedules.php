<?php session_start();
include "../phpscripts/database-connection.php";
include "../phpscripts/check-login.php";
$user_data = check_login($con);
?>
<!doctype html>
<html lang="en">

<head>
    <?php include "../includes/header.php"; ?>
    <style>
        body {
            background-color: var(--color3);
        }
    </style>
</head>

<body class="user-pg" data-user-id="<?php echo $user_data['user_id']; ?>"
    data-user-name="<?php echo $user_data['user_name']; ?>">
    <?php include "../includes/components/navbar.php"; ?>
    <main class="container" style="padding: 5rem 0;">
        <div class="table-container animation-left">
            <table class="display table-bordered table-striped table-light border border-opacity-50"
                id="schedulesTable">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAllCheckbox"></th>
                        <th>Section</th>
                        <th>Date and Time Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </main>
    <?php include "../includes/components/toast.php"; ?>
    <?php include "../includes/components/modal.php"; ?>
    <!-- Modal -->
    <div class="modal fade" id="manageScheduleModal" tabindex="-1" aria-labelledby="manageSubjeccheduleLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalTitle">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="subjectsModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-light">Subjects for Section <span class="section"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-md table-bordered" id="subjectsTable">
                        <thead>
                            <tr>
                                <th>Subject Code</th>
                                <th>Subject Name</th>
                                <th>Room</th>
                                <th>Weekdays</th>
                                <th>Time</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Subject rows will be injected here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="archivedSchedulesModal" tabindex="-1" aria-labelledby="archivedSchedulesModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-light">Archived Schedules</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <button type="button" id="unarchiveSelectedButton" class="btn btn-primary mb-3 d-none">Unarchive
                        Selected</button>
                    <table id="archivedSchedulesTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="selectAllSchedules"></th>
                                <th>Subject Code</th>
                                <th>Subject Name</th>
                                <th>Section</th>
                                <th>Room</th>
                                <th>Weekdays</th>
                                <th>Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody><!-- Archived schedules will be dynamically added here --></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- JS -->
    <?php include "../includes/js-scripts.php"; ?>
    <script src="../assets/js/navbar.js"></script>
    <script src="../assets/js/user/manage-schedules.js"></script>
    <script src="../assets/js/edit-profile.js"></script>
</body>

</html>