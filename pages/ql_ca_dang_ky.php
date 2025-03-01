<?php
require 'header.php';
$conn = mysqli_connect("localhost", "root", "Chinh@1234", "quanlychamcong");

$nextWeekStart = date("Y-m-d", strtotime("Monday next week"));

// Lấy danh sách đăng ký giờ làm trong tuần tới
$sql = "SELECT dk.id, dk.ma_nv, nv.Hoten, dk.ngay, c.Gio_bat_dau, c.Gio_ket_thuc, dk.trang_thai 
        FROM dang_ky_gio_lam dk
        JOIN ca_lam_viec c ON dk.ca_id = c.ID
        JOIN nhan_vien nv ON dk.ma_nv = nv.Ma_nv
        WHERE dk.ngay BETWEEN '$nextWeekStart' AND DATE_ADD('$nextWeekStart', INTERVAL 6 DAY)
        ORDER BY dk.ngay, dk.ma_nv";

$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Lỗi truy vấn SQL: " . mysqli_error($conn));
}
// Xử lý khi admin phê duyệt hoặc từ chối
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $updateQuery = "UPDATE dang_ky_gio_lam SET trang_thai = '$status' WHERE id = '$id'";
    mysqli_query($conn, $updateQuery);

    echo "<script>alert('Cập nhật trạng thái thành công!'); window.location.href='ql_ca_dang_ky.php';</script>";
}

?>

<div class="content-wrapper p-5">
    <h2 class="text-center mb-4">Duyệt lịch làm tuần tới</h2>
    <table border="1" class="table table-bordered">
        <tr>
            <th class="text-center">Nhân viên</th>
            <th class="text-center">Ngày</th>
            <th class="text-center">Ca làm việc</th>
            <th class="text-center">Trạng thái</th>
            <th class="text-center">Hành động</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td class="text-center"><?= $row['Hoten'] ?> (<?= $row['ma_nv'] ?>)</td>
                <td class="text-center"><?= date("d/m/Y", strtotime($row['ngay'])) ?></td>
                <td class="text-center"><?= $row['Gio_bat_dau'] ?> - <?= $row['Gio_ket_thuc'] ?></td>
                <td class="text-center">
                    <span class="badge bg-<?= $row['trang_thai'] === 'Đã duyệt' ? 'success' : ($row['trang_thai'] === 'Chờ duyệt' ? 'warning' : 'danger') ?>">
                        <?= $row['trang_thai'] ?>
                    </span>
                </td>
                <td class="text-center">
                    <?php if ($row['trang_thai'] === 'Chờ duyệt'): ?>
                        <form method="post" class="d-inline">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <input type="hidden" name="status" value="Đã duyệt">
                            <button type="submit" class="btn btn-success btn-sm">Duyệt</button>
                        </form>
                        <form method="post" class="d-inline">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <input type="hidden" name="status" value="Từ chối">
                            <button type="submit" class="btn btn-danger btn-sm">Từ chối</button>
                        </form>
                    <?php else: ?>
                        <button class="btn btn-secondary btn-sm" disabled>Đã xử lý</button>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>
