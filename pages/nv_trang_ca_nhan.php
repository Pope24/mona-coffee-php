<?php require 'header.php' ?>
<?php require 'table.html' ?>
<?php require_once "../src/db.php";
$username = $_SESSION['username'];
$bo_phan = $conn->query("SELECT * FROM bo_phan");
$ca_lam_viec = $conn->query("SELECT * FROM ca_lam_viec");
$nv = $conn->query("SELECT * FROM nhan_vien where Ma_nv = '$username'")->fetch_assoc(); ?>
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">
                            <?php
                                echo $username
                            ?>
                        </h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">
                                    <?php
                                        echo $username
                                    ?>
                                </a></li>
                            <li class="breadcrumb-item active">Thêm</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <?php
                            echo $username
                            ?>
                        </h3>
                    </div>
                    <form method="post">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Mã nhân viên</label>
                                        <input type="text" name="manv" class="form-control" required placeholder="Mã nhân viên"
                                               value="<?= htmlspecialchars($username ?? '', ENT_QUOTES, 'UTF-8') ?>" disabled>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Tên nhân viên</label>
                                        <input type="text" name="ten" class="form-control" required placeholder="Tên nhân viên"
                                               value="<?= htmlspecialchars($nv['Hoten'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Giới tính</label><br>

                                        <!-- Kiểm tra nếu $nv->Gioitinh là "Nam", thì radio "Nam" sẽ được checked -->
                                        <input type="radio" name="gioitinh" value="Nam" <?= ($nv['Gioitinh'] == "Nam") ? "checked" : "" ?>>
                                        <label>Nam</label>

                                        <!-- Kiểm tra nếu $nv->Gioitinh là "Nữ", thì radio "Nữ" sẽ được checked -->
                                        <input type="radio" name="gioitinh" value="Nữ" <?= ($nv['Gioitinh'] == "Nữ") ? "checked" : "" ?>>
                                        <label>Nữ</label>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Ngày sinh</label>
                                        <input type="date" name="ngay" required class="form-control"
                                               value="<?= isset($nv['Ngaysinh']) ? date('Y-m-d', strtotime($nv['Ngaysinh'])) : '' ?>">
                                    </div>
                                </div>

                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Quê quán</label>
                                        <input type="text" name="que" required class="form-control" placeholder="Quê quán" value="<?php
                                        echo $nv['Quequan'];
                                        ?>">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>SĐT</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                            </div>
                                            <input type="text" class="form-control" name="sdt" required value="<?php
                                            echo $nv['SDT'];
                                            ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Bộ phận</label>
                                        <?php
                                        // Lấy danh sách bộ phận
                                        $bophan_map = [];
                                        while ($row = $bo_phan->fetch_object()) {
                                            $bophan_map[$row->ID] = $row->Ten;
                                        }

                                        // Truy vấn nhân viên
                                        $nv_result = $conn->query("SELECT * FROM nhan_vien WHERE Ma_nv = '$username'");

                                        // Kiểm tra xem có dữ liệu hay không
                                        $nv = $nv_result->fetch_object();

                                        $bophan_name = 'Chưa xác định';
                                        if ($nv) { // Kiểm tra $nv có tồn tại không
                                            if (isset($bophan_map[$nv->ID_bophan])) {
                                                $bophan_name = htmlspecialchars($bophan_map[$nv->ID_bophan]);
                                            }
                                        }
                                        ?>

                                        <input type="text" name="bophan" class="form-control" value="<?php echo $bophan_name; ?>" disabled>

                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Ca làm việc</label>
                                        <?php
                                        // Tạo mảng lưu thông tin ca làm việc
                                        $ca_lam_map = [];

                                        while ($row = $ca_lam_viec->fetch_object()) {
                                            $ca_lam_map[$row->ID] = $row->Gio_bat_dau . ' đến ' . $row->Gio_ket_thuc;
                                        }

                                        // Xác định ca làm việc của nhân viên (nếu có)
                                        $ca_lam_text = isset($nv->ID_ca_lam) && isset($ca_lam_map[$nv->ID_ca_lam])
                                            ? htmlspecialchars($ca_lam_map[$nv->ID_ca_lam])
                                            : 'Chưa xác định';
                                        ?>

                                        <input type="text" name="calam" class="form-control" value="<?php echo $ca_lam_text; ?>" disabled>

                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Ngày làm việc</label>
                                        <input type="date" name="ngay_lam" required class="form-control"
                                               value="<?= isset($nv->Ngaylamviec) ? date('Y-m-d', strtotime($nv->Ngaylamviec)) : '' ?>"
                                               disabled
                                        >
                                    </div>
                                </div>
                            </div>
                            <button type="submit" name="btn" class="btn btn-primary">Cập nhật</button>
                        </div>
                        <?php
                        if (isset($_POST['btn'])) {

                            // Lấy dữ liệu từ form POST
                            $ten = trim($_POST['ten']);
                            $gt = trim($_POST['gioitinh']);
                            $ngay = trim($_POST['ngay']);
                            $qq = trim($_POST['que']);
                            $sdt = trim($_POST['sdt']);
                            $sql = "UPDATE nhan_vien 
                                SET Hoten = ?, Gioitinh = ?, Ngaysinh = ?, Quequan = ?, SDT = ? 
                                WHERE Ma_nv = ?";
                            if ($stmt = mysqli_prepare($conn, $sql)) {
                                mysqli_stmt_bind_param($stmt, "ssssss", $ten, $gt, $ngay, $qq, $sdt, $username);
                                $result = mysqli_stmt_execute($stmt);
                                if ($result) {
                                    echo "Cập nhật tài khoản $username thành công";
                                    echo "<meta http-equiv='refresh' content='0'>";
                                }
                                else {
                                    echo "Lỗi khi cập nhật: " . mysqli_error($conn);
                                }
                                mysqli_stmt_close($stmt);
                            } else {
                                echo "Lỗi trong quá trình chuẩn bị câu lệnh: " . mysqli_error($conn);
                            }
                            mysqli_close($conn);
                        }
                        ?>

                    </form>

                </div>
        </section>
    </div>

<?php require 'footer_ql.php' ?>