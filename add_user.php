<?php
include "check_login.php";
include "connect_db.php";
include "check_admin.php";

//get new user info, account unique
if (isset($_POST['save'])) {
    if ($_POST['password'] === $_POST['repassword']) {
        $insert = $conn->prepare("INSERT INTO users (account, password, name,email,phone_number,role) VALUES (:account, :password, :name,:email,:phone_number,0)");
        $insert->execute([
            ':account' => $_POST['account'],
            ':password' => md5("trungdx1" . $_POST['password']),
            ':name' => $_POST['name'],
            ':email' => $_POST['email'],
            ':phone_number' => $_POST['phone_number']
        ]);
        if ($insert->rowCount()) {
            $_SESSION['add_user'] = true;
        } else {
            $_SESSION['add_user'] = false;
        }
    } else {
        $_SESSION['add_user'] = false;
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

    <!-- third party css -->
    <link href="assets/css/vendor/dataTables.bootstrap4.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/vendor/responsive.bootstrap4.css" rel="stylesheet" type="text/css" />
    <!-- third party css end -->

    <!-- App css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/app-creative.min.css" rel="stylesheet" type="text/css" id="light-style" />


</head>


<body class="loading" data-layout="topnav"
    data-layout-config='{"layoutBoxed":false,"darkMode":false,"showRightSidebarOnStart": true}'>
    <!-- Begin page -->

    <div class="wrapper">
        <div class="content-page">
            <div class="content">

                <?php include "top_bar.php" ?>
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <h4 class="page-title">Thêm sinh viên</h4>
                            </div>
                            <?php
                            if (isset($_SESSION['add_user'])) {
                                if ($_SESSION['add_user']) {
                                    echo '<div class="alert alert-success" role="alert">
                                    <i class="dripicons-checkmark mr-2"></i> Tạo taì khoản <strong>thành công</strong>
                                </div>';
                                } else {
                                    echo '<div class="alert alert-danger" role="alert">
                                    <i class="dripicons-wrong mr-2"></i> Tạo tài khoản <strong>thất bại</strong>
                                </div>';
                                }
                                unset($_SESSION['add_user']);
                            }
                            ?>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl col-lg">
                            <div class="card">
                                <div class="card-body">
                                    <div class="tab-content align-self-center">
                                        <!-- end tab-pane -->
                                        <div class="tab-pane active" id="settings">
                                            <form action="add_user.php" method="POST">
                                                <h5 class="mb-4 text-uppercase"><i
                                                        class="mdi mdi-account-circle mr-1"></i> Thông tin tài khoản
                                                </h5>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="account">Tên đăng nhập</label>
                                                            <input type="text" class="form-control" id="account"
                                                                name="account" placeholder="Tên đăng nhập">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="name">Họ và tên</label>
                                                            <input type="text" class="form-control" id="name"
                                                                name="name" placeholder="Họ và tên">
                                                        </div>
                                                    </div>
                                                    <!-- end col -->
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="email">Email</label>
                                                            <input type="text" class="form-control" id="email"
                                                                name="email" placeholder="Email">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="phone_number">Số điện thoại</label>
                                                            <input type="text" class="form-control" id="phone_number"
                                                                name="phone_number" placeholder="Số điện thoại">
                                                        </div>
                                                    </div>
                                                    <!-- end col -->
                                                </div>
                                                <!-- end row -->
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="password">Mật khẩu</label>
                                                            <input type="password" class="form-control" id="password"
                                                                name="password" placeholder="Mật khẩu">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="re_password">Xác nhận mật khẩu</label>
                                                            <input type="password" class="form-control" id="repassword"
                                                                name="repassword" placeholder="Xác nhận mật khẩu">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="text-right">
                                                    <button type="submit" class="btn btn-success mt-2" name="save"><i
                                                            class="mdi mdi-content-save"></i> Lưu</button>
                                                </div>
                                            </form>
                                        </div>

                                    </div>

                                </div>
                            </div>
                            <!-- end card -->
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row-->

                </div>
            </div>
        </div>
    </div>
    <div class="rightbar-overlay"></div>
    <!-- /Right-bar -->

    <!-- bundle -->
    <script src="assets/js/vendor.min.js"></script>
    <script src="assets/js/app.min.js"></script>

    <!-- third party js -->
    <script src="assets/js/vendor/jquery.dataTables.min.js"></script>
    <script src="assets/js/vendor/dataTables.bootstrap4.js"></script>
    <script src="assets/js/vendor/dataTables.responsive.min.js"></script>
    <script src="assets/js/vendor/responsive.bootstrap4.min.js"></script>
    <script src="assets/js/vendor/dataTables.checkboxes.min.js"></script>
    <!-- third party js ends -->

    <!-- demo app -->
    <script src="assets/js/pages/demo.customers.js"></script>

</body>

</html>