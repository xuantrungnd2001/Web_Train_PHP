<div class="tab-pane" id="settings">
    <form action="profile.php?id=<?php echo htmlspecialchars($user->id) ?>" method="POST">
        <h5 class="mb-4 text-uppercase"><i class="mdi mdi-account-circle mr-1"></i> Personal Info</h5>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Email</label>
                    <input name="email" value="<?php echo htmlspecialchars($user->email); ?>" type="text"
                        class="form-control" placeholder="Email">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Số điện thoại</label>
                    <input name="phone_number" value="<?php echo htmlspecialchars($user->phone_number); ?>" type="text"
                        class="form-control" placeholder="Số điện thoại">
                </div>
            </div>
        </div>
        <div class="row">

            <div class="col-md-6">
                <?php if (empty($_SESSION['role'])) {
                    echo '<div class="form-group">
                    <label>Mật khẩu cũ</label>
                    <input name="old_password" type="password" class="form-control" placeholder="Mật khẩu">
                </div>';
                } ?>

            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Mật khẩu mới</label>
                    <input name="new_password" type="password" class="form-control" placeholder="Mật khẩu">
                </div>
            </div>

        </div>
        <div class="row justify-content-end">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Xác nhận mật khẩu</label>
                    <input name="new_password_confirm" type="password" class="form-control"
                        placeholder="Xác nhận mật khẩu">
                </div>
            </div>
        </div>
        <div class="text-right">
            <button name="update" type="submit" class="btn btn-success mt-2"><i class="mdi mdi-content-save"></i>
                Lưu</button>
            <?php if (!empty($_SESSION['role'])) {
                echo '<button name="delete" type="submit" class="btn btn-danger mt-2" ><i
                                                        class="mdi mdi-delete"></i>Xóa</button>';
            } ?>

        </div>
    </form>
</div>