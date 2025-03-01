<?php require 'header.php'?>
<?php
date_default_timezone_set("Asia/Ho_Chi_Minh");
function getPublicIP() {
    $ch = curl_init("https://api64.ipify.org?format=json");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    return $data['ip'] ?? 'Không lấy được IP';
}
$username = $_SESSION['username'];
$today = date("Y-m-d");
$currentTime = date("H:i:s");
$conn = mysqli_connect("localhost","root","chinh@240203","quanlychamcong");
$sql = " select * from cham_cong where Ngay='$today' and Ma_nv = '$username'";
$kq_con = mysqli_query($conn, $sql);
$dem = mysqli_num_rows($kq_con);
$ip_current = getPublicIP();
$company_ip_range = '116.110.68.56';
if ($dem > 0) {
    echo "<script>
        let xacNhan = confirm('Bạn muốn chấm công ra về tại $currentTime ? ($ip_current)');
        if ('$ip_current' !== '$company_ip_range') {
            alert('Bạn không ở trong mạng WiFi công ty. $ip_current Vui lòng kết nối WiFi công ty để chấm công!');
            window.location.href = 'nv_trang_ca_nhan.php';
            exit();
        }
        else { 
            window.location.href = 'nv_cham_cong_ve.php';
        }
    </script>";
} else {
    if (strpos($ip_current, $company_ip_range) !== 0) {
        echo "<script>alert('Bạn không ở trong mạng WiFi công ty. $ip_current Vui lòng kết nối WiFi công ty để chấm công!');</script>";
        echo "<script>window.location.href = 'nv_trang_ca_nhan.php';</script>";
        exit();
    }
    $sql = "INSERT INTO cham_cong (Ma_nv, Ngay, Tinh_trang, gio_di_lam)
            VALUES ('$username', '$today', 'Đi làm', '$currentTime')";
    $result = mysqli_query($conn, $sql);
    if ($result == true) {
        echo "<script> alert('Chấm công thành công, chúc bạn 1 ngày làm việc vui vẻ.');</script>";
        echo "<script> window.location = 'nv_bang_cham_cong.php';</script>";
    }
    else {
        echo "<script> alert();</script>";
    }
}
?>