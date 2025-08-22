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
            <table class="display table-bordered table-striped table-light border border-opacity-50" id="subjectsTable">
                <thead>
                    <tr>
                        <th></th>
                        <th>Subject Code</th>
                        <th>Subject Name</th>
                        <th>Year</th>
                        <th>Semester</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </main>
    <?php include "../includes/components/toast.php"; ?>
    <?php include "../includes/components/modal.php"; ?>
    <div class="modal fade" id="sectionsModal" tabindex="-1" aria-labelledby="sectionsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header pb-3">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="number" class="form-control" id="numberOfSections"
                            placeholder="Enter number of sections">
                    </div>
                    <div class="mb-3">
                        <input type="number" class="form-control" id="hoursPerClass"
                            placeholder="Enter number of hour per class" min="1" max="5">
                    </div>
                    <div class="mb-3">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="selectedSubjectsTableBody"></tbody>
                        </table>
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-primary w-50 generate-schedules">Generate</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="manageSubjectModal" tabindex="-1" aria-labelledby="manageSubjectModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 text-light" id="modalTitle">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>
    <!-- JS -->
    <?php include "../includes/js-scripts.php"; ?>
    <script src="../assets/js/navbar.js"></script>
    <script src="../assets/js/user/manage-subjects.js"></script>
    <script src="../assets/js/edit-profile.js"></script>
</body>

</html>