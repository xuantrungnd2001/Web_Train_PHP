<?php
session_start();
include "check_login.php";
include "connect_db.php";

//get submitted task info
$task_id = (int)$_GET['id'];
$select_task = $conn->prepare("SELECT * FROM submit_tasks WHERE task_id = :task_id AND user_account = :user_account");
$select_task->execute([':task_id' => $task_id, ':user_account' => $_GET['account']]);
$select_task->setFetchMode(PDO::FETCH_OBJ);
$task = $select_task->fetch();
if (!$task) {
    Header("HTTP/1.1 404 Not Found");
    die("Not found");
}
//get submmitted task file
$folder = $task->folder;
$get_folder = scandir($folder);
foreach ($get_folder as $file) {
    $file_ext = end(explode(".", $file));
    if ($file_ext == 'pdf')
        $output .= '<div class="col-md-6"><iframe src="' . $folder . '/' . $file . '" width="100%" style="height:500px"></iframe></div>';
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
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <h4 class="page-title">Nộp bài tập</h4>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row ">
                                        <?php echo $output; ?>
                                        <div class="col-xl">
                                            <div class="form-group mt-3 mt-xl-0">
                                                <div class="text mt-3 ">
                                                    <p class="text-muted mb-2 font-16"><a
                                                            href="<?php echo $task->file_destination ?>" download>
                                                            Tải bài làm
                                                        </a>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-left">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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

    <!-- plugin js -->
    <script src="assets/js/vendor/dropzone.min.js"></script>
    <!-- init js -->
    <script src="assets/js/ui/component.fileupload.js"></script>

</body>

</html>