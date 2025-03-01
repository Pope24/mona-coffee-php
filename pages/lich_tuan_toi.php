<?php
require 'header.php';
$conn = mysqli_connect("localhost", "root", "Chinh@1234", "quanlychamcong");

$username = $_SESSION['username'];
$nextWeekStart = date("Y-m-d", strtotime("next Monday"));

// Truy vấn lấy danh sách đăng ký của nhân viên trong tuần tới
$sql = "SELECT dk.ngay, c.Gio_bat_dau, c.Gio_ket_thuc, dk.trang_thai, dk.created_at 
FROM dang_ky_gio_lam dk 
JOIN ca_lam_viec c ON dk.ca_id = c.ID 
WHERE dk.ma_nv = '$username'
AND dk.ngay BETWEEN '$nextWeekStart' AND DATE_ADD('$nextWeekStart', INTERVAL 6 DAY)
ORDER BY dk.ngay;
";
$result = mysqli_query($conn, $sql);

// Lưu dữ liệu vào mảng để dễ kiểm tra
$dang_ky_data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $dang_ky_data[$row['ngay']] = [
        'ca' => $row['Gio_bat_dau'] . " - " . $row['Gio_ket_thuc'],
        'trang_thai' => $row['trang_thai'],
        'created_at' => date("d/m/Y H:i:s", strtotime($row['created_at']))
    ];
}

?>

<div class="content-wrapper p-5 flex-column d-flex justify-content-center align-items-center">
    <h2>Thông tin đăng ký giờ làm cho tuần tới</h2>
    <table border="1" style="min-width: 750px; height: 400px">
        <tr>
            <th class="text-center">Ngày</th>
            <th class="text-center">Ca làm việc</th>
            <th class="text-center">Trạng thái</th>
            <th class="text-center">Thời gian đăng ký</th>
        </tr>
        <?php for ($i = 0; $i < 7; $i++):
            $ngay = date("Y-m-d", strtotime("+{$i} day", strtotime($nextWeekStart)));
            $data = $dang_ky_data[$ngay] ?? null;
            ?>
            <tr>
                <td class="text-center"><?= date("d/m/Y", strtotime($ngay)) ?></td>
                <td class="text-center">
                    <?= $data ? $data['ca'] : '<span style="color: red;">Chưa đăng ký</span>' ?>
                </td>
                <td class="text-center">
                    <?= $data ? $data['trang_thai'] : '-' ?>
                </td>
                <td class="text-center">
                    <?= $data ? $data['created_at'] : '-' ?>
                </td>
            </tr>
        <?php endfor; ?>
    </table>
    <div class="d-flex justify-content-center m-3">
        <button onclick="window.print()" class="btn btn-dark">In</button>
    </div>
</div>
