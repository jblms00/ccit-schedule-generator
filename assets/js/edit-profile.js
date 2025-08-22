$(document).ready(function () {
    editProfile();
});

function editProfile() {
    $(document).on("click", ".edit-prof", function () {
        var form = $("#editProfileForm");
        var floatingName = $("#floatingName").val();
        var floatingPassword = $("#floatingPassword").val();
        var floatingConfirmPassword = $("#floatingConfirmPassword").val();

        $.ajax({
            method: "POST",
            url: "../phpscripts/edit-profile.php",
            data: {
                floatingName: floatingName,
                floatingPassword: floatingPassword,
                floatingConfirmPassword: floatingConfirmPassword,
            },
            dataType: "json",
            success: function (response) {
                // Clear previous validation errors and messages
                form.find(".is-invalid").removeClass("is-invalid");
                form.find(".invalid-feedback").remove();
                form.find(".error-message").remove();

                if (response.status === "success") {
                    // Display success message
                    var successMessage = $(
                        "<div class='alert alert-success p-2 text-center m-0 mt-4 success-message'>Profile updated successfully!</div>"
                    );
                    form.append(successMessage);

                    // Disable form elements and prevent clicks
                    $("button, input").prop("disabled", true);
                    $("a")
                        .addClass("disabled")
                        .on("click", function (e) {
                            e.preventDefault();
                        });

                    // Hide success message and reset form
                    successMessage.fadeOut(3000, function () {
                        form[0].reset();
                        $("button, input").prop("disabled", false);
                        $("a").removeClass("disabled").off("click");
                        location.reload();
                    });

                    displaySchedule();
                } else {
                    if (response.message) {
                        var input = form.find(
                            "[id='" + response.messageKey + "']"
                        );
                        if (input.length > 0) {
                            input.addClass("is-invalid");
                            input.after(
                                "<div class='invalid-feedback'>" +
                                    response.message +
                                    "</div>"
                            );
                        } else {
                            var errorMessage = $(
                                `<div class='alert alert-danger p-2 text-center m-0 mt-4 error-message'>${response.message}</div>`
                            );
                            form.append(errorMessage);
                            errorMessage.fadeOut(5000);
                        }
                    } else {
                        var generalErrorMessage = $(
                            `<div class='alert alert-danger p-2 text-center m-0 mt-4 error-message'>An unexpected error occurred. Please try again.</div>`
                        );
                        form.append(generalErrorMessage);
                        generalErrorMessage.fadeOut(5000);
                    }
                }
            },
            error: function (xhr, status, error) {
                console.log(error);
            },
        });
    });
}
