<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("HTTP/1.1 405 Method Not Allowed", true, 405);
    exit();
}

$ten_dang_nhap = $_SESSION['ten_dang_nhap'];
$product_id = $_POST['productId'];
$new_quantity = $_POST['newQuantity'];

$conn = new mysqli('localhost', 'root', '', 'tech_house_db');
if ($conn->connect_error) {
    die('Connection failed: '.$conn->connect_error);
}

$stmt = $conn->prepare('update gio_hang set so_luong = ? where thanh_vien = ? and ma_sp = ?');
$stmt->bind_param('iss', $new_quantity, $ten_dang_nhap, $product_id);

if ($stmt->execute()) {
    echo "Cập nhật số lượng thành công";
} else {
    echo "Cập nhật số lượng thất bại";
}
?>