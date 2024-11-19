<?php
session_start();

if (!isset($_SESSION['ten_dang_nhap'])) {
    exit();
}

if ($_SESSION['phan_loai_tk'] != 'nv') {
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'tech_house_db');
if ($conn->connect_error) {
    die('Kết nối thất bại: ' . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    exit();
}

$username = $_POST['username'];
$active = $_POST['active'];

if ($active == '1') {
    $sql = "UPDATE thanh_vien set active_status = 1, thoi_diem_huy_tk = NULL WHERE ten_dang_nhap = '$username'";
} else {
    $now = date('Y-m-d H:i:s');
    $sql = "UPDATE thanh_vien set active_status = 0, thoi_diem_huy_tk = '$now' WHERE ten_dang_nhap = '$username'";
}

if ($conn->query($sql) === TRUE) {
    echo 'Thành công';
} else {
    echo 'Cập nhật trạng thái tài khoản thất bại. Lỗi: ' . $conn->error;
}

$conn->close();

?>