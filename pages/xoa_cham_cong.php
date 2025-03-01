<?php
$id=$_GET['x'];
$cn=mysqli_connect('localhost','root',"",'quanlychamcong')or die("Kết nối database không thành công");
$sql="delete from cham_cong where ID_cham_cong='$id'";
$ketqua=mysqli_query($cn, $sql) or die("Câu truy vấn sai!");
if($ketqua==true)
{
     header("Location: ql_cham_cong.php");
}
?>