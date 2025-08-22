const toastMessage = $("#liveToast .toast-body p");

$(document).ready(function () {
    userLogin();
    handle2FA();
});

function userLogin() {
    $(document).on("submit", "#loginAccount", function (event) {
        event.preventDefault();
        var form = $(this);
        var userEmail = form.find("#userEmail").val();
        var userPassword = form.find("#userPassword").val();

        $.ajax({
            method: "POST",
            url: "phpscripts/user-login.php",
            data: { userEmail, userPassword },
            dataType: "json",
            success: function (response) {
                if (response.status === "2fa_required") {
                    $(".landing-pg .banner").html(get2FAForm());
                    $("#verifyingUserId").val(response.userId);
                } else {
                    toastMessage
                        .text(response.message)
                        .addClass("text-danger")
                        .removeClass("text-success");
                    $("#liveToast").toast("show");
                }
            },
            error: function (xhr, status, error) {
                console.log(error);
            },
        });
    });
}

function get2FAForm() {
    return `
        <div class="box-container">
            <form id="2faForm" class="needs-validation" novalidate>
                <h3 class="text-uppercase fw-bold">Enter 2FA Code</h3>
                <div class="mb-3">
                    <h1 class="mb-4">Verification Code</h1>
                    <input type="hidden" id="verifyingUserId" class="form-control">
                    <input type="text" id="2faCode" class="form-control" placeholder="Enter your 2FA code" required>
                    <div class="invalid-feedback fw-semibold">Please enter the 2FA code sent to you.</div>
                </div>
                <div class="row mb-4">
                    <div class="col text-center">
                        <button type="submit" class="btn btn-primary">Verify</button>
                    </div>
                </div>
            </form>
        </div>
    `;
}

function handle2FA() {
    $(document).on("submit", "#2faForm", function (event) {
        event.preventDefault();
        var form = $(this);
        var code = form.find("#2faCode").val();
        var verifyingUserId = form.find("#verifyingUserId").val();

        if (!form[0].checkValidity()) {
            form.addClass("was-validated");
            return;
        }

        $.ajax({
            method: "POST",
            url: "phpscripts/verify-2fa.php",
            data: { code: code, verifyingUserId: verifyingUserId },
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

                    toastMessage.fadeOut(3000, function () {
                        if (response.user_type === "user") {
                            window.location.href = "user/homepage";
                        } else {
                            toastMessage
                                .text("Account not found")
                                .addClass("text-danger")
                                .removeClass("text-success");
                            $("#liveToast").toast("show");
                        }
                    });
                } else {
                    toastMessage
                        .text(response.message)
                        .addClass("text-danger")
                        .removeClass("text-success");
                    $("#liveToast").toast("show");
                }
            },
            error: function (xhr, status, error) {
                console.log("AJAX Error:", status, error);
            },
        });
    });
}
