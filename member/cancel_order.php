<?php
session_start();

if (!isset($_SESSION['ten_dang_nhap'])) {
    header('Location: ../login.php');
    exit();
}

if (!isset($_POST['order_id'])) {
    header('Location: ../index.php');
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'tech_house_db');
if ($conn->connect_error) {
    die('Kết nối thất bại: ' . $conn->connect_error);
}

$order_id = $_POST['order_id'];
$stmt = $conn->prepare('select don_hang.thanh_vien from don_hang where don_hang.ma_don_hang = ?');
$stmt->bind_param('i', $order_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0 || $_SESSION['ten_dang_nhap'] != $result->fetch_assoc()['thanh_vien']) {
    header('Location: ../index.php');
    exit();
}

$stmt = $conn->prepare('UPDATE don_hang SET tinh_trang = 4 WHERE ma_don_hang = ?');
$stmt->bind_param('i', $order_id);
if ($stmt->execute()) {
    echo 'Hủy đơn hàng thành công';
} else {
    echo 'Hủy đơn hàng thất bại';
}
?>