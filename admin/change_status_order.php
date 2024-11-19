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

if ($_SERVER['REQUEST_METHOD'] != 'GET') {
    exit();
}

$order_id = $_GET['order_id'];
$order_status = $_GET['order_status'];

if ($order_status != 3) {
    $sql = "update don_hang set tinh_trang = $order_status where ma_don_hang = $order_id";
} else {
    $sql = "update don_hang set tinh_trang = $order_status, thoi_diem_nhan_hang = now() where ma_don_hang = $order_id";
}

if ($conn->query($sql) === TRUE) {
    header('Location: order_detail.php?order_id=' . $order_id);
} else {
    echo 'Cập nhật trạng thái đơn hàng thất bại';
}

$conn->close();
?>