<?php require 'header.php'?>
<?php
date_default_timezone_set("Asia/Ho_Chi_Minh");

$username = $_SESSION['username'];
$today = date("Y-m-d");
$currentTime = date("H:i:s");

$conn = mysqli_connect("localhost", "root", "Chinh@1234", "quanlychamcong");
$sql = "UPDATE cham_cong SET gio_nghi = '$currentTime' WHERE Ngay = '$today' AND Ma_nv = '$username'";
$result = mysqli_query($conn, $sql);

if ($result) {
    echo "<script> alert('Chấm công ra về thành công!'); window.location.href = 'nv_trang_ca_nhan.php'; </script>";
} else {
    echo "<script> alert('Lỗi khi chấm công ra về!'); window.location.href = 'nv_trang_ca_nhan.php'; </script>";
}
?>
