<?php require 'header.php'?>
<?php
$conn = mysqli_connect("localhost", "root", "Chinh@1234", "quanlychamcong");

if (!$conn) {
    die("Kết nối database thất bại: " . mysqli_connect_error());
}

$username = $_SESSION['username'];
$nextWeekStart = date("Y-m-d", strtotime("next Monday"));
$ca_lam_viec = mysqli_query($conn, "SELECT * FROM ca_lam_viec");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ngay_nghi = $_POST['ngay_nghi'] ?? [];
    $dang_ky = $_POST['dang_ky'];

    foreach ($dang_ky as $ngay => $ma_ca) {
        if (!in_array($ngay, $ngay_nghi)) {
            // Kiểm tra xem nhân viên đã đăng ký ca làm việc ngày này chưa
            $check_sql = "SELECT COUNT(*) as count FROM dang_ky_gio_lam WHERE ma_nv = '$username' AND ngay = '$ngay'";
            $result = mysqli_query($conn, $check_sql);
            $row = mysqli_fetch_assoc($result);

            if ($row['count'] > 0) {
                echo "<script>alert('Bạn đã đăng ký ca làm cho ngày $ngay rồi!'); window.location.href='dang_ky_gio_lam.php';</script>";
                exit; // Dừng script nếu đã có ca đăng ký trước đó
            }

            // Nếu chưa đăng ký, tiến hành INSERT
            $sql = "INSERT INTO dang_ky_gio_lam (ma_nv, ngay, ca_id) VALUES ('$username', '$ngay', '$ma_ca')";
            mysqli_query($conn, $sql);
        }
    }
    echo "<script>alert('Đăng ký thành công!'); window.location.href='lich_tuan_toi.php';</script>";
}
?>

<div class="content-wrapper p-5 flex-column d-flex justify-content-center align-items-center">
    <h2>Đăng ký giờ làm cho tuần tiếp theo</h2>
    <form method="post" id="workScheduleForm">
        <table border="1" style="min-width: 750px; height: 400px">
            <tr>
                <th class="text-center">Ngày</th>
                <th class="text-center">Ca làm việc</th>
                <th class="text-center">Đăng ký nghỉ</th>
            </tr>
            <?php for ($i = 0; $i < 7; $i++):
                $ngay = date("Y-m-d", strtotime("+{$i} day", strtotime($nextWeekStart))); ?>
                <tr>
                    <td class="text-center"><?= date("d/m/Y", strtotime($ngay)) ?></td>
                    <td>
                        <select class="w-100 h-100 border-0 shift-select" name="dang_ky[<?= $ngay ?>]">
                            <option value="">-- Chọn ca --</option>
                            <?php
                            mysqli_data_seek($ca_lam_viec, 0);
                            while ($row = mysqli_fetch_assoc($ca_lam_viec)): ?>
                                <option value="<?= $row['ID'] ?>"><?= $row['Gio_bat_dau'] ?> - <?= $row['Gio_ket_thuc'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </td>
                    <td class="text-center">
                        <input type="checkbox" class="day-off" name="ngay_nghi[]" value="<?= $ngay ?>">
                    </td>
                </tr>
            <?php endfor; ?>
        </table>
        <div class="d-flex justify-content-center m-3">
            <button type="submit" class="btn btn-dark">Đăng ký</button>
        </div>
    </form>
</div>

<script>
    document.getElementById('workScheduleForm').addEventListener('submit', function(event) {
        let selects = document.querySelectorAll('.shift-select');
        let checkboxes = document.querySelectorAll('.day-off');

        let selectedShifts = 0;
        let checkedDaysOff = 0;

        selects.forEach(select => {
            if (select.value !== "") {
                selectedShifts++;
            }
        });

        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                checkedDaysOff++;
            }
        });

        if (selectedShifts !== 7) {
            alert("Bạn phải chọn ca làm việc cho tất cả các ngày!");
            event.preventDefault();
            return false;
        }

        if (checkedDaysOff > 2) {
            alert("Bạn chỉ được đăng ký tối đa 2 ngày nghỉ!");
            event.preventDefault();
            return false;
        }
    });
</script>
