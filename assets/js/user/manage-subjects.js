const toastMessage = $("#liveToast .toast-body p");
let selectedCheckboxes = new Set();

const dataTable = $("#subjectsTable").DataTable({
    autoWidth: true,
    scrollX: true,
    paging: false,
    columns: [
        { width: "5%" },
        { width: "15%" },
        { width: "40%" },
        { width: "15%" },
        { width: "15%" },
        { width: "10%" },
    ],
    createdRow: function (row, data, dataIndex) {
        $(row).attr("data-subject-id", data[6]);
        $(row).attr("data-year-level", data[3]);
        $(row).attr("data-instructor-name", data[7]);
        $(row).attr("data-has-lab", data[8]);
    },
    drawCallback: function (settings) {
        $(".subject-checkbox").each(function () {
            const checkboxValue = $(this).val();
            if (selectedCheckboxes.has(checkboxValue)) {
                $(this).prop("checked", true);
            } else {
                $(this).prop("checked", false);
            }
        });
    },
    data: [],
    language: {
        emptyTable: "No matching records found",
    },
});

$(document).ready(function () {
    displaySubjectOnTable();
    generateAutomatedSchedule();
    manageModal();

    $(document).on("change", ".subject-checkbox", function () {
        var checkboxValue = $(this).val();

        if ($(this).is(":checked")) {
            selectedCheckboxes.add(checkboxValue);
        } else {
            selectedCheckboxes.delete(checkboxValue);
        }

        var anyChecked = selectedCheckboxes.size > 0;
        var $container = $(".dt-layout-cell.dt-layout-start");

        $container.find(".generate-btn-container").remove();

        if (anyChecked) {
            $container.append(`
                <div class="generate-btn-container">
                    <button type="button" class="btn btn-primary" id="generateScheduleBtn">Generate Schedule</button>
                </div>
            `);
        }
    });

    $(document).on("click", "#generateScheduleBtn", function () {
        var selectedSubjects = $(".subject-checkbox:checked")
            .map(function () {
                var row = $(this).closest("tr");
                var subjectId = row.data("subject-id");
                var subjectCode = row.find("td").eq(1).text();
                var subjectName = row.find("td").eq(2).text();
                var yearLevel = row.data("year-level");
                var hasLab = row.data("has-lab");

                return {
                    subject_id: subjectId,
                    code: subjectCode,
                    name: subjectName,
                    year: yearLevel,
                    has_lab: hasLab,
                };
            })
            .get();
        var selectedSubjectsTableBody = $("#selectedSubjectsTableBody");
        selectedSubjectsTableBody.empty();

        if (selectedSubjects.length > 0) {
            selectedSubjects.forEach((subject) => {
                selectedSubjectsTableBody.append(`
                    <tr data-subject-id="${subject.subject_id}" data-has-lab="${subject.has_lab}" data-year-level="${subject.year}" data-subject-name="${subject.name}">
                        <td>${subject.code}</td>
                        <td>${subject.name}</td>
                        <td><i class="fa-solid fa-square-xmark remove-subject" style="cursor: pointer;"></i></td>
                    </tr>
                `);
            });
        } else {
            selectedSubjectsTableBody.append(`
                <tr>
                    <td colspan="3" class="text-center text-danger">No subjects selected</td>
                </tr>
            `);
        }

        $("#sectionsModal").modal("show");
    });

    $(document).on("click", ".remove-subject", function () {
        $(this).closest("tr").remove();

        var selectedSubjectsTableBody = $("#selectedSubjectsTableBody");
        var remainingRows = selectedSubjectsTableBody.children("tr").length;

        if (remainingRows === 0) {
            selectedSubjectsTableBody.append(`
                <tr>
                    <td colspan="3" class="text-center text-danger">No subjects selected</td>
                </tr>
            `);
        }
    });
});

function displaySubjectOnTable() {
    $.ajax({
        url: "../phpscripts/get-subjects.php",
        type: "GET",
        dataType: "json",
        success: function (response) {
            if (response.status === "success") {
                var tableBody = $("#subjectsTable tbody");
                tableBody.empty();

                var subjectsResult = response.subjects.map((subject) => {
                    return [
                        `<input type="checkbox" class="subject-checkbox" value="${subject.subject_id}">`,
                        subject.subject_code,
                        subject.subject_name,
                        subject.school_year,
                        subject.school_semester,
                        `
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-primary btn-sm edit-modal">Edit</button>
                                <button type="button" class="btn btn-danger btn-sm delete-modal">Delete</button>
                            </div>
                        `,
                        subject.subject_id,
                        subject.instructor_name
                            ? subject.instructor_name
                            : "None",
                        subject.has_lab,
                    ];
                });
                dataTable.clear().rows.add(subjectsResult).draw();
            } else {
                console.error(response.message);
            }
        },
        error: function (xhr, status, error) {
            console.error(error);
        },
    });
}

function generateAutomatedSchedule() {
    $(document).on("click", ".generate-schedules", function () {
        var numberOfSections = $("#numberOfSections").val();
        var hoursPerClass = parseInt($("#hoursPerClass").val());
        var selectedSubjects = [];

        // Loop through selected subjects
        $("#selectedSubjectsTableBody tr").each(function () {
            var subjectId = $(this).data("subject-id");
            var yearLevel = $(this).data("year-level");
            var subjectCode = $(this).find("td").eq(0).text();
            var hasLab = $(this).data("has-lab");

            // Check if subjectId and subjectCode are valid
            if (subjectId && subjectCode) {
                selectedSubjects.push({
                    subject_id: subjectId,
                    year_level: yearLevel,
                    subject_code: subjectCode,
                    has_lab: hasLab,
                });
            }
        });

        // Validation: Check if selectedSubjects is empty
        if (selectedSubjects.length === 0) {
            toastMessage
                .text("Please select at least one subject.")
                .addClass("text-danger")
                .removeClass("text-success");
            $("#liveToast").toast("show");
            return; // Exit the function early
        }

        $.ajax({
            url: "../phpscripts/save-schedule.php",
            type: "POST",
            data: {
                numberOfSections: numberOfSections,
                hoursPerClass: hoursPerClass,
                selectedSubjects: selectedSubjects,
            },
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    toastMessage
                        .text(response.message)
                        .addClass("text-success")
                        .removeClass("text-danger");
                    $("#liveToast").toast("show");

                    $("button, input").prop("disabled", true);
                    $("a")
                        .addClass("disabled")
                        .on("click", function (e) {
                            e.preventDefault();
                        });

                    setTimeout(function () {
                        location.reload();
                    }, 3000);
                } else {
                    toastMessage
                        .text(response.message)
                        .addClass("text-danger")
                        .removeClass("text-success");
                    $("#liveToast").toast("show");
                }
            },
            error: function (error) {
                console.log(error);
            },
        });
    });
}

function formatTime(date) {
    let hours = date.getHours();
    const minutes = date.getMinutes();
    const ampm = hours >= 12 ? "PM" : "AM";
    hours = hours % 12;
    hours = hours ? hours : 12;
    return hours + ":" + (minutes < 10 ? "0" + minutes : minutes) + " " + ampm;
}

function manageModal() {
    var modal = $("#manageSubjectModal");

    $(document).on("click", ".edit-modal", function () {
        var row = $(this).closest("tr");
        var subjectId = row.data("subject-id");
        var subjectCode = row.find("td").eq(1).text();
        var subjectName = row.find("td").eq(2).text();
        var schoolYear = row.find("td").eq(3).text();
        var schoolSemester = row.find("td").eq(4).text();
        var hasLab = row.data("has-lab");
        var instructorName = row.data("instructor-name");

        var modalContent = `
            <div class="input-group mb-3">
                <span class="input-group-text">Subject Code</span>
                <input type="text" class="form-control" id="subjectCodeInput" value="${subjectCode}" aria-label="Subject Code">
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text">Subject Name</span>
                <input type="text" class="form-control" id="subjectNameInput" value="${subjectName}" aria-label="Subject Name">
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text">Insturctor Name</span>
                <input type="text" class="form-control" id="instructorNameInput" value="${instructorName}" aria-label="Subject Name">
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text">School Year</span>
                <select class="form-control" id="schoolYearInput" aria-label="School Year">
                    <option ${
                        schoolYear === "1st Year" ? "selected" : ""
                    }>1st Year</option>
                    <option ${
                        schoolYear === "2nd Year" ? "selected" : ""
                    }>2nd Year</option>
                    <option ${
                        schoolYear === "3rd Year" ? "selected" : ""
                    }>3rd Year</option>
                    <option ${
                        schoolYear === "4th Year" ? "selected" : ""
                    }>4th Year</option>
                </select>
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text">School Semester</span>
                <select class="form-control" id="schoolSemesterInput" aria-label="School Semester">
                    <option ${
                        schoolSemester === "1st Semester" ? "selected" : ""
                    }>1st Semester</option>
                    <option ${
                        schoolSemester === "2nd Semester" ? "selected" : ""
                    }>2nd Semester</option>
                    <option ${
                        schoolSemester === "Summer/Mid-Yr" ? "selected" : ""
                    }>Summer/Mid-Yr</option>
                </select>
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text">Has Laboratory</span>
                <select class="form-control" id="hasLabInput" aria-label="School Semester">
                    <option ${hasLab === 1 ? "selected" : ""}>Yes</option>
                    <option ${hasLab === 0 ? "selected" : ""}>No</option>
                </select>
            </div>
            <div class="text-center">
                <button type="button" class="btn btn-primary btn-sm save-edit" data-subject-id="${subjectId}">Save changes</button>
            </div>
        `;
        modal.find(".modal-body").html(modalContent);
        modal.find("#modalTitle").text("Edit Subject");
        modal.modal("show");
    });

    $(document).on("click", ".save-edit", function () {
        var subjectId = $(this).data("subject-id");
        var subjectCode = $("#subjectCodeInput").val();
        var subjectName = $("#subjectNameInput").val();
        var schoolYear = $("#schoolYearInput").val();
        var schoolSemester = $("#schoolSemesterInput").val();
        var hasLab = $("#hasLabInput").val();
        var instructorName = $("#instructorNameInput").val();

        $.ajax({
            url: "../phpscripts/edit-subjects.php",
            type: "POST",
            data: {
                subject_id: subjectId,
                subject_code: subjectCode,
                subject_name: subjectName,
                school_year: schoolYear,
                school_semester: schoolSemester,
                has_lab: hasLab,
                instructor_name: instructorName,
            },
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    displaySubjectOnTable();
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
        var row = $(this).closest("tr");
        var subjectId = row.data("subject-id");
        var subjectName = row.find("td").eq(2).text();
        var modalContent = `
            <p class="mb-3 text-light">Are you sure you want to delete the subject "<strong>${subjectName}</strong>"?</p>
            <div class="text-end">
                <button type="button" class="btn btn-danger btn-sm confirm-delete data-subject-id="${subjectId}"">Delete</button>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
            </div>
        `;
        modal.find(".modal-body").html(modalContent);
        modal.find("#modalTitle").text("Delete Subject");
        modal.modal("show");
    });

    $(document).on("click", ".confirm-delete", function () {
        var subjectId = $(this).data("subject-id");

        $.ajax({
            url: "../phpscripts/delete-subjects.php",
            type: "POST",
            data: { subject_id: subjectId },
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    displaySubjectOnTable();
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
}
