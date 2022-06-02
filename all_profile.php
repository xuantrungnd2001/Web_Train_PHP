<?php
include "check_login.php";
include "connect_db.php";
$select_users = $conn->prepare("SELECT * FROM users");
$select_users->execute();
$select_users->setFetchMode(PDO::FETCH_OBJ);
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
                                <h4 class="page-title">Danh sách sinh viên</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row justify-content-center">
                        <div class="col-8">
                            <div class="card">
                                <div class="card-body">
                                    <?php if ($_SESSION['role'] == 1) echo '<div class="row mb-2">
                                        <div class="col-sm-4">
                                            <a href="add_user.php" class="btn btn-success mb-2"><i
                                                    class="mdi mdi-plus-circle mr-2"></i> Tạo tài khoản</a>
                                        </div>
                                    </div>' ?>
                                    <div class="table-responsive">
                                        <table class="table table-centered table-striped dt-responsive nowrap w-100"
                                            id="products">
                                            <thead>
                                                <tr>
                                                    <th>Tên sinh viên</th>
                                                    <?php if ($_SESSION['role'] == 1) echo '<th style="width: 75px;">Action</th>' ?>
                                                    <th>Tên sinh viên</th>
                                                    <?php if ($_SESSION['role'] == 1) echo '<th style="width: 75px;">Action</th>' ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $num = 0;
                                                foreach ($select_users as $user) {
                                                    if ($num % 2 === 0) echo '<tr>';
                                                ?>
                                                <td class="table-user">
                                                    <img src="assets/images/users/avatar.png" alt="table-user"
                                                        class="mr-2 rounded-circle">
                                                    <a href="profile.php?id=<?php echo $user->id ?>"
                                                        class="text-body
                                                        font-weight-semibold"><?php echo htmlspecialchars($user->name) ?></a></a>
                                                </td>

                                                <?php if ($_SESSION['role'] == 1) echo '<td><a href="profile.php?id=' . $user->id . '"
                                                        class="action-icon"> <i
                                                            class="mdi mdi-square-edit-outline"></i></a></td>'; ?>

                                                <?php
                                                    if ($num % 2 === 1) echo '</tr>';
                                                    $num++;
                                                } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div> <!-- end card-body-->
                            </div> <!-- end card-->
                        </div> <!-- end col -->
                    </div>
                    <!-- end row -->

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