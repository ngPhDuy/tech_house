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

$order_id = $_GET['order_id'];

$sql = "UPDATE don_hang SET tinh_trang = 1 WHERE ma_don_hang = $order_id";
if ($conn->query($sql) === TRUE) {
    $sql = "INSERT INTO Duyet_don_hang (ma_don_hang, nhan_vien, thoi_diem_duyet) VALUES ($order_id, '" . $_SESSION['ten_dang_nhap'] . "', NOW())";
    if ($conn->query($sql) === TRUE) {
        header('Location: order_detail.php?order_id=' . $order_id);
    } else {
        echo 'Lỗi: ' . $conn->error;
    }
} else {
    echo 'Lỗi: ' . $conn->error;
}

$conn->close();
?>