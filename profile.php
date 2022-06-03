<?php
include "check_login.php";
include "connect_db.php";

//get profile
if (!isset($_GET['id'])) $_GET['id'] = $_SESSION['id'];
$result = $conn->prepare("SELECT * FROM users WHERE id=:id");
$result->execute([
    ':id' => (int)$_GET['id']
]);
$result->setFetchMode(PDO::FETCH_OBJ);
$user = $result->fetch();

if (!$user) {
    header("HTTP/1.1 404 Not Found");
    die("account not found");
}

//delete message
if (isset($_GET['delete'])) {
    $delete_mess = $conn->prepare("DELETE FROM messages WHERE id=:id AND sender_id=:sender_id");
    $delete_mess->execute([
        ':id' => (int)$_GET['delete'],
        ':sender_id' => $_SESSION['id']
    ]);
    $_SESSION['active'] = 'message';
}

//send message
if (isset($_POST['send'])) {
    $send = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, content,time) VALUES (:sender_id, :receiver_id, :content,now())");
    $send->execute([
        ':sender_id' => $_SESSION['id'],
        ':receiver_id' => $user->id,
        ':content' => $_POST['content']
    ]);
    $_SESSION['active'] = 'message';
}

//show message
if ($_GET['id'] !== $_SESSION['id']) {
    $messages = $conn->prepare("SELECT * FROM messages WHERE (receiver_id=:id OR sender_id=:id) AND (receiver_id!=sender_id) ORDER BY id");
} else {
    $messages = $conn->prepare("SELECT * FROM messages WHERE receiver_id=:id AND sender_id=:id ORDER BY id");
}
$messages->execute([
    ':id' => (int)$_GET['id']
]);
$messages->setFetchMode(PDO::FETCH_OBJ);

//update profile
if (isset($_POST['update']) && ((int)$_SESSION['role'] === 1 || $_SESSION['id'] === $user->id)) {
    if (((md5("trungdx1" . $_POST['old_password']) === $user->password) || ((int)$_SESSION['role'] === 1)) && ($_POST['new_password'] === $_POST['new_password_confirm'])) {
        $update = $conn->prepare("UPDATE users SET email=:email, phone_number=:phone_number,password=:new_password WHERE id=:id");
        $update->execute([
            ':email' => $_POST['email'],
            ':phone_number' => $_POST['phone_number'],
            ':new_password' => md5("trungdx1" . $_POST['new_password']),
            ':id' => $user->id
        ]);
        if ($update) $_SESSION['update_user'] = true;
    } else {
        $_SESSION['update_user'] = false;
    }
}

//delete account
if (isset($_POST['delete']) && ((int)$_SESSION['role'] === 1)) {
    $delete = $conn->prepare("DELETE FROM users WHERE id=:id");
    $delete->execute([
        ':id' => $user->id
    ]);
    header("Location: profile.php");
    exit();
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
    <link href="assets/css/app-creative.css" rel="stylesheet" type="text/css" id="light-style" />


</head>


<body class="loading" data-layout="topnav"
    data-layout-config='{"layoutBoxed":false,"darkMode":false,"showRightSidebarOnStart": true}'>
    <div class="wrapper">
        <div class="content-page">
            <div class="content">
                <?php include "top_bar.php" ?>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <h4 class="page-title">Thông tin cá nhân</h4>
                                <?php
                                if (isset($_SESSION['update_user'])) {
                                    if ($_SESSION['update_user']) {
                                        echo '<div class="alert alert-success" role="alert">
                                    <i class="dripicons-checkmark mr-2"></i> Cập nhật taì khoản <strong>thành công</strong>
                                </div>';
                                    } else {
                                        echo '<div class="alert alert-danger" role="alert">
                                    <i class="dripicons-wrong mr-2"></i> Cập nhật tài khoản <strong>thất bại</strong>
                                </div>';
                                    }
                                    unset($_SESSION['update_user']);
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-xl-8 col-lg">
                            <div class="card">
                                <div class="card-body">
                                    <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
                                        <li class="nav-item">
                                            <a href="#aboutme" data-toggle="tab" aria-expanded="true"
                                                class="nav-link rounded-0 <?php if ($_SESSION['active'] !== 'message') echo 'active'; ?>">
                                                About
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#message" data-toggle="tab" aria-expanded="true"
                                                class="nav-link rounded-0  <?php if ($_SESSION['active'] === 'message') echo 'active'; ?>">
                                                Message
                                            </a>
                                        </li>
                                        <?php if ((int)$_SESSION['role'] === 1 || $_GET['id'] === $_SESSION['id'])
                                            echo '<li class="nav-item">
                                        <a href="#settings" data-toggle="tab" aria-expanded="false"
                                            class="nav-link rounded-0">
                                            Settings
                                        </a>
                                    </li>'; ?>
                                    </ul>
                                    <div class="tab-content align-self-center">
                                        <div class="tab-pane <?php if ($_SESSION['active'] !== 'message') {
                                                                    echo 'active';
                                                                    unset($_SESSION['active']);
                                                                } ?>" id="aboutme">
                                            <div class="col-xl col-lg">
                                                <div class="card text-center">
                                                    <div class="card-body justify-content-md-center">
                                                        <img src="assets/images/users/avatar.png"
                                                            class="rounded-circle avatar-lg img-thumbnail"
                                                            alt="profile-image">
                                                        <div class="text-center mt-3 ">
                                                            <p class="text-muted mb-2 font-13"><strong>Họ và tên
                                                                    :</strong> <span
                                                                    class="ml-2"><?php echo htmlspecialchars($user->name); ?></span>
                                                            </p>
                                                            <p class="text-muted mb-2 font-13"><strong>Tài khoản
                                                                    :</strong> <span
                                                                    class="ml-2"><?php echo htmlspecialchars($user->account); ?></span>
                                                            </p>
                                                            <p class="text-muted mb-2 font-13"><strong>Số điện thoại
                                                                    :</strong><span
                                                                    class="ml-2"><?php echo htmlspecialchars($user->phone_number); ?></span>
                                                            </p>

                                                            <p class="text-muted mb-2 font-13"><strong>Email :</strong>
                                                                <span
                                                                    class="ml-2 "><?php echo htmlspecialchars($user->email); ?></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane <?php if ($_SESSION['active'] === 'message') {
                                                                    echo 'active';
                                                                    unset($_SESSION['active']);
                                                                } ?>" id="message">
                                            <div class="col-xl-12 col-lg-12 order-lg-2 order-xl-1">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <ul class="conversation-list" data-simplebar
                                                            style="max-height: 537px">
                                                            <?php
                                                            foreach ($messages as $message) { ?>
                                                            <li
                                                                class="clearfix <?php if ($message->receiver_id === $user->id) echo 'odd'; ?>">
                                                                <div class="chat-avatar">
                                                                    <img src="assets/images/users/avatar.png"
                                                                        class="rounded" alt="" />
                                                                    <i><?php echo date_format(date_create($message->time), "H:i"); ?></i>
                                                                </div>
                                                                <div class="conversation-text">
                                                                    <div class="ctext-wrap">
                                                                        <i><?php if ($message->receiver_id === $user->id) echo htmlspecialchars($_SESSION['name']);
                                                                                else echo htmlspecialchars($user->name); ?></i>
                                                                        <p>
                                                                            <?php echo htmlspecialchars($message->content); ?>
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                                <?php if ($message->receiver_id === $user->id) echo '<div class="conversation-actions dropdown">
                                                                    <button class="btn btn-sm btn-link"
                                                                        data-toggle="dropdown" aria-expanded="false"><i
                                                                            class="uil uil-ellipsis-v"
                                                                            visible></i></button><div class="dropdown-menu ">
                                                                        <a class="dropdown-item" href="#">Edit</a>
                                                                        <a class="dropdown-item" href="profile.php?id=' . htmlspecialchars($_GET['id']) . '&delete=' . htmlspecialchars($message->id) . '">Delete</a>
                                                                    </div></div>'; ?>

                                                            </li>
                                                            <?php }
                                                            ?>
                                                        </ul>
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="mt-2 bg-light p-3 rounded">
                                                                    <form class="needs-validation" novalidate=""
                                                                        name="chat-form" id="chat-form" method="POST"
                                                                        action="profile.php?id=<?php echo htmlspecialchars($user->id); ?>">
                                                                        <div class="row">
                                                                            <div class="col mb-2 mb-sm-0">
                                                                                <input type="text"
                                                                                    class="form-control border-0"
                                                                                    placeholder="Enter your text"
                                                                                    required="" name="content">
                                                                                <div class="invalid-feedback">
                                                                                    Please enter your messsage
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-auto">
                                                                                <div class="btn-group">
                                                                                    <button type="submit" name="send"
                                                                                        class="btn btn-success chat-send btn-block"><i
                                                                                            class='uil uil-message'></i></button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if ((int)$_SESSION['role'] === 1 || $_GET['id'] === $_SESSION['id'])
                                            include "profile_settings.php" ?>
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