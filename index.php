<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include './views/components/head.php';
    ?>
    <title>Home page</title>
</head>

<body>
    <?php
    session_start();

    if (isset($_SESSION['message'])) {
        echo '<div class="alert alert-primary alert-dismissible position-absolute top-0 end-0" style="z-index: 9999;" role="alert">
        ' . $_SESSION['message'] . '
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
        unset($_SESSION['message']);
    }

    include 'views/components/header.php'
    ?>

    <div class="container my-5 h-70">
        <div class="row">
            <div class="col-lg-6 mb-4">
                <form action="php/auth.php" method="post">
                    <h1 class="text-center">Log in</h1>

                    <div class="mb-3">
                        <label for="username" class="form-label">Username:</label>
                        <input type="text" id="username" name="username" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" id="password" name="password" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary" value="login">Submit</button>
                </form>
            </div>

            <div class="col-lg-6 mb-4">
                <form action="php/register.php" method="post">
                    <h1 class="text-center">Registration</h1>

                    <div class="mb-3">
                        <label for="fullname" class="form-label">Full Name:</label>
                        <input type="text" id="fullname" name="fullname" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label">Username:</label>
                        <input type="text" id="username" name="username" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" id="password" name="password" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password:</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                    </div>

                    <input type="submit" class="btn btn-primary" value="Register">
                </form>
            </div>
        </div>
    </div>

    <?php
    include 'views/components/footer.php'
    ?>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>