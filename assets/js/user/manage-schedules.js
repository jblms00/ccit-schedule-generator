const loggedInUserId = $("body").data("user-id");
const loggedInUserName = $("body").data("user-name");
const toastMessage = $("#liveToast .toast-body p");

$(document).ready(function () {
    displaySchedulesPerSection();
    manageModal();
    deleteSchedule();
    showArchivedSchedules();

    $("#selectAllCheckbox").change(function () {
        const isChecked = $(this).is(":checked");
        $(".schedule-checkbox").prop("checked", isChecked);
        toggleButtonContainer();
    });

    $(document).on("change", ".schedule-checkbox", function () {
        const allChecked =
            $(".schedule-checkbox").length ===
            $(".schedule-checkbox:checked").length;
        $("#selectAllCheckbox").prop("checked", allChecked);
        toggleButtonContainer();
    });
});

function toggleButtonContainer() {
    var $container = $(".dt-layout-cell.dt-layout-start");
    $container.find(".generate-btn-container").remove();

    const anyChecked = $(".schedule-checkbox:checked").length > 0;
    const allChecked =
        $(".schedule-checkbox").length ===
        $(".schedule-checkbox:checked").length;

    if (allChecked) {
        $container.append(`
            <div class="generate-btn-container">
                <button type="button" class="btn btn-danger" id="deleteAllButton">Delete All Schedule</button>
            </div>
        `);
    } else if (anyChecked) {
        $container.append(`
            <div class="generate-btn-container">
                <button type="button" class="btn btn-danger" id="deleteSelectedButton">Delete Selected Schedule</button>
            </div>
        `);
    }
}

var dataTable = $("#schedulesTable").DataTable({
    autoWidth: true,
    scrollX: true,
    paging: false,
    columns: [
        { width: "3%" },
        { width: "15%" },
        { width: "15%" },
        { width: "10%" },
    ],
    createdRow: function (row, data, dataIndex) {
        $(row).attr("data-section", data[1]);
    },
    data: [],
    language: {
        emptyTable: "No matching records found",
    },
});

function displaySchedulesPerSection() {
    $.ajax({
        url: "../phpscripts/fetch-schedules-by-section.php",
        type: "GET",
        dataType: "json",
        success: function (response) {
            if (response.status === "success") {
                var tableBody = $("#schedulesTable tbody");
                tableBody.empty();

                var schedulesResult = response.schedules.map((schedule) => {
                    return [
                        `<input type="checkbox" class="schedule-checkbox" value="${schedule.section}">`,
                        schedule.section,
                        schedule.datetime_added,
                        `<div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-primary btn-sm w-50" onclick="viewSubjects('${schedule.section}')">View</button>
                            <button type="button" class="btn btn-warning btn-sm w-50" onclick="printSchedule('${schedule.section}')">Print Schedule</button>
                        </div>`,
                    ];
                });

                dataTable.clear().rows.add(schedulesResult).draw();

                if (response.hasArchived) {
                    $(".dt-layout-cell.dt-layout-start").append(
                        '<button type="button" class="btn btn-primary btn-sm" id="archivedSchedulesButton">View Archived Schedules</button>'
                    );
                }
            } else {
                console.error(response.message);
                toastMessage
                    .text(response.message)
                    .addClass("text-danger")
                    .removeClass("text-success");
                $("#liveToast").toast("show");
            }
        },
        error: function (xhr, status, error) {
            console.error(error);
        },
    });
}

function viewSubjects(section) {
    $("#subjectsModal span.section").text(section);

    $.ajax({
        url: "../phpscripts/fetch-subjects-by-section.php",
        type: "GET",
        dataType: "json",
        data: { section: section },
        success: function (response) {
            if (response.status === "success") {
                var subjectsTableBody = $("#subjectsTable tbody");
                subjectsTableBody.empty();

                response.subjects.forEach((subject) => {
                    var isLabLec = subject.room.replace(/[0-9]/g, "");

                    subjectsTableBody.append(`
                        <tr data-schedule-id="${
                            subject.schedule_id
                        }" data-start-time="${
                        subject.start_time
                    }" data-end-time="${subject.end_time}">
                            <td>${subject.subject_code} - ${isLabLec}</td>
                            <td>${subject.subject_name || "N/A"}</td>
                            <td>${subject.room}</td>
                            <td>${subject.week_days}</td>
                            <td>${formatTime(
                                subject.start_time
                            )} - ${formatTime(subject.end_time)}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" class="btn btn-primary btn-sm edit-modal" data-id="${
                                        subject.schedule_id
                                    }">Edit</button>
                                    <button type="button" class="btn btn-danger btn-sm delete-modal" data-id="${
                                        subject.schedule_id
                                    }">Delete</button>
                                    <button type="button" class="btn btn-secondary btn-sm archived-schedule" data-id="${
                                        subject.schedule_id
                                    }">Archived</button>
                                </div>
                            </td>
                        </tr>
                    `);
                });

                $("#subjectsModal").modal("show");
            } else {
                console.error(response.message);
                toastMessage.text(response.message).show();
            }
        },
        error: function (xhr, status, error) {
            console.error(error);
            toastMessage.text("Error fetching subjects").show();
        },
    });
}

function formatTime(time) {
    const [hours, minutes] = time.split(":");
    const hour = parseInt(hours, 10);
    const ampm = hour >= 12 ? "PM" : "AM";
    const formattedHour = hour % 12 || 12;
    return `${formattedHour}:${minutes} ${ampm}`;
}

function manageModal() {
    var modal = $("#manageScheduleModal");

    $(document).on("click", ".edit-modal", function () {
        $("#subjectsModal").modal("hide");
        var row = $(this).closest("tr");
        var scheduleId = row.data("schedule-id");
        var section = row.closest(".modal").find("span.section").text();
        var room = row.find("td").eq(2).text();
        var subject = row.find("td").eq(1).text();
        var weekDays = row.find("td").eq(3).text();
        var startTime = row.data("start-time");
        var endTime = row.data("end-time");

        var modalContent = `
            <div class="input-group mb-3">
                <span class="input-group-text">Subject</span>
                <input type="text" class="form-control" id="subjectInput" value="${subject}" aria-label="Subject" disabled>
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text">Section</span>
                <input type="text" class="form-control" id="sectionInput" value="${section}" aria-label="Section" disabled>
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text">Room</span>
                <input type="text" class="form-control" id="roomInput" value="${room}" aria-label="Room">
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text">Week Days</span>
                <input type="text" class="form-control" id="weekDaysInput" value="${weekDays}" aria-label="Week Days">
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text">Start Time</span>
                <input type="time" class="form-control" id="startTimeInput" value="${startTime}" aria-label="Start Time">
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text">End Time</span>
                <input type="time" class="form-control" id="endTimeInput" value="${endTime}" aria-label="End Time">
            </div>
            <div class="text-center">
                <button type="button" class="btn btn-primary btn-sm save-edit" data-schedule-id="${scheduleId}">Save changes</button>
            </div>
        `;
        modal.find(".modal-body").html(modalContent);
        modal.find("#modalTitle").text("Edit Schedule");
        modal.modal("show");
    });

    $(document).on("click", ".save-edit", function () {
        var scheduleId = $(this).data("schedule-id");
        var room = $("#roomInput").val();
        var weekDays = $("#weekDaysInput").val();
        var startTime = $("#startTimeInput").val();
        var endTime = $("#endTimeInput").val();

        $.ajax({
            url: "../phpscripts/edit-schedule.php",
            type: "POST",
            data: {
                schedule_id: scheduleId,
                room: room,
                week_days: weekDays,
                start_time: startTime,
                end_time: endTime,
            },
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    displaySchedulesPerSection();
                    toastMessage
                        .text(response.message)
                        .addClass("text-success")
                        .removeClass("text-danger");
                    $("#liveToast").toast("show");
                    modal.modal("hide");
                } else {
                    toastMessage
                        .text(response.message)
                        .addClass("text-danger")
                        .removeClass("text-success");
                    $("#liveToast").toast("show");
                }
            },
        });
    });

    $(document).on("click", ".delete-modal", function () {
        $("#subjectsModal").modal("hide");
        var row = $(this).closest("tr");
        var scheduleId = row.data("schedule-id");
        var section = row.find("td").eq(1).text();
        var modalContent = `
            <p class="mb-3">Are you sure you want to delete the schedule for "<strong>${section}</strong>"?</p>
            <div class="text-end">
                <button type="button" class="btn btn-danger btn-sm confirm-delete" data-schedule-id="${scheduleId}">Delete</button>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
            </div>
        `;
        modal.find(".modal-body").html(modalContent);
        modal.find("#modalTitle").text("Delete Schedule");
        modal.modal("show");
    });

    $(document).on("click", ".confirm-delete", function () {
        var scheduleId = $(this).data("schedule-id");

        $.ajax({
            url: "../phpscripts/delete-schedule.php",
            type: "POST",
            data: { schedule_id: scheduleId },
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    displaySchedulesPerSection();
                    toastMessage
                        .text(response.message)
                        .addClass("text-success")
                        .removeClass("text-danger");
                    $("#liveToast").toast("show");
                    modal.modal("hide");
                } else {
                    toastMessage
                        .text(response.message)
                        .addClass("text-danger")
                        .removeClass("text-success");
                    $("#liveToast").toast("show");
                }
            },
        });
    });

    $(document).on("click", ".archived-schedule", function () {
        var scheduleId = $(this).data("id");

        $.ajax({
            url: "../phpscripts/archived-schedule.php",
            type: "POST",
            data: { schedule_id: scheduleId },
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    toastMessage
                        .text(response.message)
                        .removeClass("text-danger")
                        .addClass("text-success");
                    $("#liveToast").toast("show");

                    location.reload();
                } else {
                    toastMessage
                        .text(response.message)
                        .addClass("text-danger")
                        .removeClass("text-success");
                    $("#liveToast").toast("show");
                }
            },
            error: function (xhr, status, error) {
                console.error(error);
                toastMessage.text("Error archiving schedule").show();
            },
        });
    });
}

function deleteSchedule() {
    $(document).on("click", "#deleteSelectedButton", function () {
        const selectedSections = $(".schedule-checkbox:checked")
            .map(function () {
                return $(this).val();
            })
            .get();

        $.ajax({
            url: "../phpscripts/delete-schedules.php",
            type: "POST",
            dataType: "json",
            data: { selected_sections: selectedSections },
            success: function (response) {
                toastMessage
                    .text(response.message)
                    .removeClass("text-danger")
                    .addClass("text-success");
                $("#liveToast").toast("show");
                displaySchedulesPerSection();
            },
            error: function (xhr, status, error) {
                console.error(error);
            },
        });
    });

    $(document).on("click", "#deleteAllButton", function () {
        $.ajax({
            url: "../phpscripts/delete-schedules.php",
            type: "POST",
            dataType: "json",
            data: { delete_all: true },
            success: function (response) {
                toastMessage
                    .text(response.message)
                    .removeClass("text-danger")
                    .addClass("text-success");
                $("#liveToast").toast("show");
                displaySchedulesPerSection();
            },
            error: function (xhr, status, error) {
                console.error(error);
            },
        });
    });
}

function showArchivedSchedules() {
    $(document).on("click", "#archivedSchedulesButton", function () {
        $.ajax({
            url: "../phpscripts/fetch-archived-schedules.php",
            type: "GET",
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    var tableBody = $("#archivedSchedulesTable tbody");
                    tableBody.empty();

                    response.archivedSchedules.forEach(function (schedule) {
                        var row = `
                            <tr data-schedule-id="${schedule.schedule_id}">
                                <td><input type="checkbox" class="select-schedule" data-schedule-id="${
                                    schedule.schedule_id
                                }"></td>
                                <td>${schedule.subject_code}</td>
                                <td>${schedule.subject_name}</td>
                                <td>${schedule.section}</td>
                                <td>${schedule.room}</td>
                                <td>${schedule.week_days}</td>
                                <td>${formatTime(
                                    schedule.start_time
                                )} - ${formatTime(schedule.end_time)}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <button type="button" class="btn btn-secondary btn-sm unarchive-schedule">Unarchived</button>
                                    </div>
                                </td>
                            </tr>
                        `;
                        tableBody.append(row);
                    });

                    // Show the modal with the table of archived schedules
                    $("#archivedSchedulesModal").modal("show");
                } else {
                    console.error(response.message);
                    toastMessage
                        .text(response.message)
                        .addClass("text-danger")
                        .removeClass("text-success");
                    $("#liveToast").toast("show");
                }
            },
            error: function (xhr, status, error) {
                console.error(error);
            },
        });
    });

    $(document).on("click", "#selectAllSchedules", function () {
        var isChecked = $(this).prop("checked");
        $(".select-schedule").prop("checked", isChecked);
        toggleUnarchiveButton();
    });

    $(document).on("click", ".select-schedule", function () {
        toggleUnarchiveButton();
    });

    function toggleUnarchiveButton() {
        var isAnyChecked = $(".select-schedule:checked").length > 0;
        $("#unarchiveSelectedButton")
            .toggle(isAnyChecked)
            .removeClass("d-none");
    }

    $(document).on("click", "#unarchiveSelectedButton", function () {
        var selectedSchedules = [];

        $(".select-schedule:checked").each(function () {
            selectedSchedules.push($(this).data("schedule-id"));
        });

        if (selectedSchedules.length > 0) {
            $.ajax({
                url: "../phpscripts/unarchive-selected-schedule.php",
                type: "POST",
                data: { schedule_ids: selectedSchedules },
                dataType: "json",
                success: function (response) {
                    if (response.status === "success") {
                        toastMessage
                            .text(response.message)
                            .removeClass("text-danger")
                            .addClass("text-success");
                        $("#liveToast").toast("show");

                        location.reload();
                    } else {
                        console.error(response.message);
                        toastMessage
                            .text(response.message)
                            .addClass("text-danger")
                            .removeClass("text-success");
                        $("#liveToast").toast("show");
                    }
                },
                error: function (xhr, status, error) {
                    console.error(error);
                },
            });
        } else {
            toastMessage
                .text("Please select at least one schedule to unarchive.")
                .addClass("text-danger")
                .removeClass("text-success");
            $("#liveToast").toast("show");
        }
    });

    $(document).on("click", ".unarchive-schedule", function () {
        var row = $(this).closest("tr");
        var scheduleId = row.data("schedule-id");

        $.ajax({
            url: "../phpscripts/unarchive-schedule.php",
            type: "POST",
            data: { schedule_id: scheduleId },
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    toastMessage
                        .text(response.message)
                        .removeClass("text-danger")
                        .addClass("text-success");
                    $("#liveToast").toast("show");

                    location.reload();
                } else {
                    console.error(response.message);
                    toastMessage
                        .text(response.message)
                        .addClass("text-danger")
                        .removeClass("text-success");
                    $("#liveToast").toast("show");
                }
            },
            error: function (xhr, status, error) {
                console.error(error);
            },
        });
    });
}

function printSchedule(section) {
    $.ajax({
        url: "../phpscripts/fetch-schedule-details.php",
        type: "POST",
        data: { section: section },
        dataType: "json",
        success: function (response) {
            if (response.status === "success") {
                var printWindow = window.open("", "", "height=600,width=800");
                var content = `
                    <html>
                    <head>
                        <style>
                            @media print {
                                @page {
                                    margin: 20px;
                                }
                                body {
                                    margin: 0;
                                }
                                .no-print {
                                    display: none;
                                }
                            }
                            body {
                                font-family: 'Times New Roman', serif;
                                font-size: 14px;
                                margin: 20px;
                            }
                            table {
                                width: 100%;
                                border-collapse: collapse;
                                margin-top: 20px;
                            }
                            th, td {
                                border: 1px solid #000;
                                padding: 10px;
                                text-align: center;
                            }
                            th {
                                background-color: #f2f2f2;
                            }
                            h2 {
                                text-align: center;
                            }
                            h3 {
                                text-align: center;
                                margin-top: 40px;
                            }
                            .logo {
                                text-align: center;
                            }
                            .user-details {
                                margin: 20px 0;
                            }
                            .row {
                                display: flex;
                                align-items: center;
                                justify-content: space-between;
                            }
                        </style>
                    </head>
                    <body>
                `;

                content += `
                    <div class="logo">
                        <img src="../assets/images/prmsuLogo.png" height="120" width="120">
                        <img src="../assets/images/ccitLogo.png" height="120" width="120">
                    </div>
                    <div class="user-details">
                        <div class="row">
                            <div class="col">
                                <p>Generated by: ${loggedInUserName}</p>
                            </div>
                            <div class="col">
                                <p>Date Printed: ${new Date().toLocaleString()}</p>
                            </div>
                        </div>
                    </div>
                    <h3>Schedule</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Subject Code</th>
                                <th>Professor</th>
                                <th>Weekdays</th>
                                <th>Time</th>
                                <th>Room</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                response.schedules.forEach(function (schedule) {
                    var isLabLec = schedule.room.replace(/[0-9]/g, "");

                    content += `
                        <tr>
                            <td>${schedule.subject_code} - ${isLabLec}</td>
                            <td>${schedule.instructor_name || "N/A"}</td>
                            <td>${schedule.week_days}</td>
                            <td>${formatTime(
                                schedule.start_time
                            )} - ${formatTime(schedule.end_time)}</td>
                            <td>${schedule.room || "N/A"}</td>
                        </tr>
                    `;
                });

                content += `
                        </tbody>
                    </table>
                </body>
                </html>
                `;

                printWindow.document.write(content);
                printWindow.document.close();
                printWindow.print();
            } else {
                console.error(response.message);
                toastMessage
                    .text(response.message)
                    .addClass("text-danger")
                    .removeClass("text-success");
                $("#liveToast").toast("show");
            }
        },
        error: function (xhr, status, error) {
            console.error(error);
        },
    });
}
