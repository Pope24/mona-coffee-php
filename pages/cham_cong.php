<?php
$manv = $_GET['Ma_nv'];
$today = date("Y/m/d");
$conn = mysqli_connect("localhost","root","Chinh@1234","quanlychamcong");
$sql = " select * from cham_cong where Ngay='$today' and Ma_nv = '$manv'";
$kq_con = mysqli_query($conn, $sql);
$dem = mysqli_num_rows($kq_con);

if ($dem > 0) {
    echo "<script> alert('Đã chấm công hôm nay');</script>";
    echo "<script> window.location = 'ql_cham_cong.php';</script>";
    exit();
} else {
    $sql = "INSERT INTO cham_cong VALUES(ID_cham_cong,'$manv','$today','Đi làm')";
    $result = mysqli_query($conn, $sql);
    if ($result == true) {
        header("Location:ql_cham_cong.php");
    }
}
