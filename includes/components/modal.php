<div class="modal fade" id="modalProfile" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editProfileForm">
                    <div class="row mb-3">
                        <div class="col">
                            <h5 class="fw-bold text-center text-light">Edit Profile</h5>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="floatingName"
                                    value="<?php echo $user_data['user_name']; ?>" placeholder="Name">
                                <label for="floatingName">Name</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <div class="form-floating">
                                <input type="email" class="form-control" id="floatingEmail"
                                    value="<?php echo $user_data['user_email']; ?>" placeholder="Email" disabled>
                                <label for="floatingEmail">Email</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="floatingPassword" placeholder="Password">
                                <label for="floatingPassword">Password</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="floatingConfirmPassword"
                                    placeholder="ConfirmPassword">
                                <label for="floatingConfirmPassword">Confirm Password</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col text-center">
                            <button type="button" role="button" class="btn btn-primary edit-prof"
                                style="width: 50%;">Save changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>