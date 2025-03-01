<?php
$id=$_GET['x'];
$conn=mysqli_connect('localhost','root',"Chinh@1234",'quanlychamcong')or die("Kết nối database không thành công");
$sql="delete from nghi_viec where ID='$id'";
$results=mysqli_query($conn, $sql) or die("Câu truy vấn sai!");
if($results==true)
{
     header("Location:ql_nghi_viec.php");
}
?>