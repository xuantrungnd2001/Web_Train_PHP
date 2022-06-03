<?php
session_start();
include "check_login.php";
include "connect_db.php";

//get submit task info
$task_id = (int)$_GET['id'];
$select_task = $conn->prepare("SELECT * FROM tasks WHERE task_id = :task_id");
$select_task->execute([':task_id' => $task_id]);
$select_task->setFetchMode(PDO::FETCH_OBJ);
$task = $select_task->fetch();
if (!$task) {
    Header("HTTP/1.1 404 Not Found");
    die("Not found");
}
$folder = $task->task_folder; //get task folder
$get_folder = scandir($folder);
foreach ($get_folder as $file) {
    $file_ext = end(explode(".", $file));
    $allowed_ext = array('jpg', 'png', 'doc', 'docx', 'pdf', 'jpeg');
    if (in_array($file_ext, $allowed_ext)) {
        if ($file_ext == 'pdf') $output .= '<div class="col-md-6"><iframe src="' . htmlspecialchars($folder) . '/' . htmlspecialchars($file) . '" width="100%" style="height:500px"></iframe></div>';
    }
}

//submit task
if (isset($_POST['submit_task'])) {
    $task_name = $task->task_name;
    $task_overview = $task->task_overview;
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
    $folder = 'submit/'  . str_replace($block, '_', $task_name) . '/' . $_SESSION['account'];
    $file_destination = $folder . '/' . $file_name_new;
    mkdir($folder, 0777, true);
    if (in_array($file_type, array_keys($extensions))) {
        if ($file_size <= 20971520) {
            $select_submit = $conn->prepare("SELECT * FROM submit_tasks WHERE task_id = :task_id AND user_account = :user_account");
            $select_submit->execute([':task_id' => $task_id, ':user_account' => $_SESSION['account']]);
            $select_submit->setFetchMode(PDO::FETCH_OBJ);
            //check if user submit task
            if ($select_submit->rowCount() == 0) {
                $command = $conn->prepare("INSERT INTO submit_tasks (task_id,user_account,file_destination,folder) VALUE (:task_id,:user_account,:file_destination,:folder)");
                $command->execute([
                    ':task_id' => (int)$_GET['id'],
                    ':user_account' => $_SESSION['account'],
                    ':file_destination' => $file_destination,
                    ':folder' => $folder
                ]);
            } else {
                $abc = $select_submit->fetch()->folder;
                $delete_files = glob($abc . '/*');
                foreach ($delete_files as $delete_file) {
                    if (is_file($delete_file)) {
                        unlink($delete_file); // delete file
                    }
                }
                $command = $conn->prepare("UPDATE submit_tasks SET file_destination = :file_destination,folder = :folder WHERE task_id = :task_id AND user_account = :user_account");
                $command->execute([
                    ':task_id' => (int)$_GET['id'],
                    ':user_account' => $_SESSION['account'],
                    ':file_destination' => $file_destination,
                    ':folder' => $folder
                ]);
            }
            //move file to folder
            if ($command->rowCount()) {
                if ($file_ext === 'zip') {
                    $zip = new ZipArchive;
                    if ($zip->open($file_tmp) === TRUE) {
                        $zip->extractTo($folder);
                        $zip->close();
                        $_SESSION['submit_tasks'] = true;
                        move_uploaded_file($file_tmp, $file_destination);
                    }
                } else {
                    move_uploaded_file($file_tmp, $file_destination);
                    $_SESSION['submit_tasks'] = true;
                }
            } else {
                $_SESSION['submit_tasks'] = false;
            }
        } else {
            $_SESSION['submit_tasks'] = false;
        }
    } else {
        $_SESSION['submit_tasks'] = false;
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
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <h4 class="page-title">Nộp bài tập</h4>
                            </div>
                            <?php
                            if (isset($_SESSION['submit_tasks'])) {
                                if ($_SESSION['submit_tasks']) {
                                    echo '<div class="alert alert-success" role="alert">
                                    <i class="dripicons-checkmark mr-2"></i> Nộp bài tập <strong>thành công</strong>
                                </div>';
                                } else {
                                    echo '<div class="alert alert-danger" role="alert">
                                    <i class="dripicons-wrong mr-2"></i> Nộp bài tập <strong>thất bại</strong>
                                </div>';
                                }
                                unset($_SESSION['submit_tasks']);
                            }
                            ?>
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
                                                            href="<?php echo htmlspecialchars($task->task_destination); ?>"
                                                            download>
                                                            Tải đề bài
                                                        </a>
                                                    </p>
                                                    <p class="text-muted mb-2 font-16"><strong>Mô tả<br>
                                                        </strong><span
                                                            class="ml-2"><?php echo htmlspecialchars($task->task_overview); ?></span>
                                                    </p>
                                                </div>
                                                <form action="task.php?id=<?php echo htmlspecialchars($_GET['id']); ?>"
                                                    name="form1" method="POST" enctype="multipart/form-data">
                                                    <p class="text-muted mb-2 font-16"><strong>Nộp bài<br>
                                                    </p>

                                                    <div class="fallback">
                                                        <input name="file" type="file" />
                                                    </div>
                                                    <button type="submit" class="btn btn-success mt-2"
                                                        name="submit_task"><i class="mdi mdi-content-save"></i>
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