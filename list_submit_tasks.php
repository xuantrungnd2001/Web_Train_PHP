<?php
include "check_login.php";
include "connect_db.php";
include "check_admin.php";
$task_id = $_GET['id'];
$select_tasks = $conn->prepare("SELECT * FROM submit_tasks Where task_id = :task_id");
$select_tasks->execute(array(
    ':task_id' => $task_id
));
$select_tasks->setFetchMode(PDO::FETCH_OBJ);
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

                                <h4 class="page-title">Danh sách bài làm</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <!-- end row-->
                    <div class="row">
                        <?php foreach ($select_tasks as $select_task) { ?>
                        <div class="col-md-6 col-xl-3">

                            <div class="card d-block">
                                <div class="card-body">
                                    <h4 class="mt-0">
                                        <a href="submit_task.php?id=<?php echo htmlspecialchars($select_task->task_id); ?>&account=<?php echo htmlspecialchars($select_task->user_account); ?>"
                                            class="text-title"><?php echo htmlspecialchars($select_task->user_account); ?></a>
                                    </h4>
                                </div>

                            </div>
                        </div>
                        <?php } ?>

                    </div>




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