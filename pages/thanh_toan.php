<?php
$id = $_GET['x'];
$conn = mysqli_connect("localhost", "root", "", "quanlychamcong");
$sql = " UPDATE nhan_luong SET Tinh_trang ='Đã thanh toán' where ID = '$id'";
$result = mysqli_query($conn, $sql);
if ($result == true) {
    header("Location:ql_bang_luong.php");
}
