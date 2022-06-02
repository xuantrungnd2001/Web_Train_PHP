<?php
session_start();
include "check_login.php";
include "connect_db.php";
include "check_admin.php";

//get new task info, task_name unique
if (isset($_POST['tasks'])) {
    $task_name = $_POST['task_name'];
    $task_overview = $_POST['task_overview'];
    $file = $_FILES['file'];
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_size = filesize($file_tmp);
    $fileinfo = finfo_open(FILEINFO_MIME_TYPE);
    $file_type = finfo_file($fileinfo, $file_tmp);
    $extensions = array(
        'image/jpeg' => 'jpeg',
        'image/jpg' => 'jpg',
        'image/png' => 'png',
        'application/pdf' => 'pdf',
        'application/msword' => 'doc',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
        'application/zip' => 'zip'
    );
    $file_ext = $extensions[$file_type];
    $file_name_new = md5("trungdx1" . explode(".", $file_name)[0]) . '.' . $extensions[$file_type];
    $block = array(' ', '/');
    $folder = 'tasks/' . str_replace($block, '_', $task_name);
    $file_destination = $folder . '/' . $file_name_new;
    mkdir($folder);
    if (in_array($file_type, array_keys($extensions))) {
        if ($file_size <= 20971520) {
            $insert = $conn->prepare("INSERT INTO tasks (task_name,task_overview,task_folder,task_destination) VALUES (:task_name,:task_overview,:task_folder,:task_destination)");
            $insert->execute(array(
                ':task_name' => $task_name,
                ':task_overview' => $task_overview,
                ':task_folder' => $folder,
                ':task_destination' => $file_destination
            ));
            if ($insert->rowCount()) {
                if ($file_ext == 'zip') {
                    move_uploaded_file($file_tmp, $file_destination);
                    $zip = new ZipArchive;
                    if ($zip->open($file_destination) === TRUE) {
                        $_SESSION['add_tasks'] = true;
                        $zip->extractTo($folder);
                        $zip->close();
                    } else {
                        $_SESSION['add_tasks'] = false;
                    }
                } else {
                    move_uploaded_file($file_tmp, $file_destination);
                    $_SESSION['add_tasks'] = true;
                }
            } else {
                $_SESSION['add_tasks'] = false;
            }
        } else {
            $_SESSION['add_tasks'] = false;
        }
    } else {
        $_SESSION['add_tasks'] = false;
    }
} ?>
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

                                <h4 class="page-title">Thêm bài tập</h4>
                            </div>
                            <?php
                            if (isset($_SESSION['add_tasks'])) {
                                if ($_SESSION['add_tasks']) {
                                    echo '<div class="alert alert-success" role="alert">
                                    <i class="dripicons-checkmark mr-2"></i> Tạo bài tập <strong>thành công</strong>
                                </div>';
                                } else {
                                    echo '<div class="alert alert-danger" role="alert">
                                    <i class="dripicons-wrong mr-2"></i> Tạo bài tập <strong>thất bại</strong>
                                </div>';
                                }
                                unset($_SESSION['add_tasks']);
                            }
                            ?>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row justify-content-center">
                        <div class="col-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row ">

                                        <div class="col-xl">
                                            <div class="form-group mt-3 mt-xl-0">
                                                <form action="add_tasks.php" name="form1" method="POST"
                                                    enctype="multipart/form-data">
                                                    <div class="form-group">
                                                        <label for="projectname">Tiêu đề</label>
                                                        <input name="task_name" type="text" id="projectname"
                                                            class="form-control" placeholder="Nhập tiêu đề" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="project-overview">Mô tả</label>
                                                        <textarea class="form-control" id="project-overview" rows="5"
                                                            name="task_overview" placeholder="Nhập mô tả..."
                                                            required></textarea>
                                                    </div>
                                                    <div class="fallback">
                                                        <input name="file" type="file" />
                                                    </div>
                                                    <button type="submit" class="btn btn-success mt-2" name="tasks"><i
                                                            class="mdi mdi-content-save"></i>
                                                        Lưu</button>
                                                </form>
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

    <script src="assets/js/vendor.min.js"></script>
    <script src="assets/js/app.min.js"></script>

    <script src="assets/js/vendor/dropzone.min.js"></script>
    <script src="assets/js/ui/component.fileupload.js"></script>

</body>

</html>