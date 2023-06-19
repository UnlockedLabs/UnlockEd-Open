<style>
body { 
    background: url('./images/hands.jpg') no-repeat center center fixed; 
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
}
</style>

<!-- Page content -->
<div class="page-content">

    <!-- Main content -->
    <div class="content-wrapper">

        <!-- Content area -->
        <div class="content d-flex justify-content-center align-items-center">

            <!-- Registration form -->
            <form method="post" action="create_user.php" class="flex-fill animated fadeInUp">
                <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                        <div class="card mb-0">
                            <div class="card-body">
                                <div class="text-center mb-3">
                                    <i class="icon-plus3 icon-2x text-success border-success border-3 rounded-round p-3 mb-3 mt-1"></i>
                                    <h3 class="mb-0">Create account</h3>
                                    <span class="d-block text-warning">User uniqueness is based on: <br>Organizational User Identifier if provided, email if it is not and username if neither of those are provided.</span>
                                    <span class="d-block text-muted">Starred fields (*) are required.</span>
                                    
                                </div>

                                <div class="form-group form-group-feedback form-group-feedback-right">
                                    <input type="text" name="username" class="form-control" value="<?php if (isset($user->username)) echo $user->username; ?>" placeholder="User Name" required>
                                    <div class="form-control-feedback">
                                        <i class="icon-user-plus text-muted"></i>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group form-group-feedback form-group-feedback-right">
                                            <input type="password" name="password" class="form-control" value='' placeholder="Create password" required>
                                            <div class="form-control-feedback">
                                                <i class="icon-user-lock text-muted"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group form-group-feedback form-group-feedback-right">
                                            <input type="password" name="repeat_password" class="form-control" value='' placeholder="Repeat password" required>
                                            <div class="form-control-feedback">
                                                <i class="icon-user-lock text-muted"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group form-group-feedback form-group-feedback-right">
                                            <input type="email" name="email" class="form-control" value="<?php if (isset($user->email)) echo $user->email; ?>" placeholder="Your email (optional)">
                                            <div class="form-control-feedback">
                                                <i class="icon-mention text-muted"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group form-group-feedback form-group-feedback-right">
                                            <input type="text" name="oid" class="form-control" value="<?php if (isset($user->OID)) echo $user->OID; ?>" placeholder="Organizational User Identifier (Optional)">
                                            <div class="form-control-feedback">
                                                <i class="icon-vcard text-muted"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn bg-teal-400 btn-labeled btn-labeled-right"><b><i class="icon-plus3"></i></b> Create account</button>
                                <a href="index.php?login=1" class="btn bg-primary-400 btn-labeled btn-labeled-right"><b><i class="icon-plus3"></i></b> Return to Login</a>

                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <!-- /registration form -->

        </div>
        <!-- /content area -->

    </div>
    <!-- /main content -->

</div>
<!-- /page content -->
<script>document.querySelector("body").classList.add("bg-white");</script>

<script>
document.addEventListener('DOMContentLoaded', function() {

    $('form').on('submit', function(e) {

        var error = '';

        if (!e.target.username.value.trim()) {
            error += 'Must Supply User Name. ';
        }

        if (!e.target.password.value.trim()) {
            error += 'Must Supply Password. ';
        }

        if (!e.target.repeat_password.value.trim()) {
            error += 'Must Supply Repeat Password. ';
        }

        if (e.target.password.value.trim() != e.target.repeat_password.value.trim()) {
            error += 'Password and repeat password do not match. ';
        }

        if (error) {
            ul.errorSwalAlert("Info Warning!", error);
            e.preventDefault();
        }

    });

});
</script>