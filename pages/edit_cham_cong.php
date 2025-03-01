<?php
$id = $_GET['x'];
$conn = mysqli_connect("localhost","root","Chinh@1234","quanlychamcong");

$sql = " UPDATE cham_cong SET Tinh_trang ='Nghỉ việc' where ID_cham_cong = '$id'";
$result = mysqli_query($conn, $sql);
    if ($result == true) {
        header("Location:ql_cham_cong.php");
    }

