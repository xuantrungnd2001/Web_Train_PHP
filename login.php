<?php
session_start();
include "connect_db.php";
if (isset($_POST['submit'])) {
    $account = $_POST['account'];
    $password = $_POST['password'];
    $get_info = $conn->prepare("SELECT * FROM users WHERE account = :account AND password = :password");
    $get_info->execute([
        ':account' => $account,
        ':password' => md5("trungdx1" . $password)
    ]);
    $get_info->setFetchMode(PDO::FETCH_OBJ);
    if ($result = $get_info->fetch()) {
        $_SESSION['id'] = $result->id;
        $_SESSION['account'] = $result->account;
        $_SESSION['password'] = $result->password;
        $_SESSION['name'] = $result->name;
        $_SESSION['login'] = true;
        $_SESSION['role'] = $result->role;
        header("Location: profile.php");
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Quản lý sinh viên</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- App css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/app-creative.min.css" rel="stylesheet" type="text/css" id="light-style" />
    <link href="assets/css/app-creative-dark.min.css" rel="stylesheet" type="text/css" id="dark-style" />

</head>

<body class="authentication-bg" data-layout-config='{"darkMode":false}'>

    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-body p-4">
                            <div class="text-center w-75 m-auto">
                                <h4 class="text-dark-50 text-center mt-0 font-weight-bold">Sign In</h4>
                            </div>
                            <form action="login.php" method="POST">
                                <div class="form-group">
                                    <label for="account">Account</label>
                                    <input name="account" class="form-control" type="text" id="account" required=""
                                        placeholder="Enter your account">
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <div class="input-group input-group-merge">
                                        <input name="password" type="password" id="password" class="form-control"
                                            placeholder="Enter your password">
                                        <div class="input-group-append" data-password="false">
                                            <div class="input-group-text">
                                                <span class="password-eye"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-0 text-center">
                                    <button name="submit" class="btn btn-primary" type="submit"> Log In </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer footer-alt">
        Tuan 5 - TrungDX1
    </footer>

    <script src="assets/js/vendor.min.js"></script>
    <script src="assets/js/app.min.js"></script>

</body>

</html>