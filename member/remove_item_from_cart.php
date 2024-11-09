<?php
session_start();

if (!isset($_SESSION['ten_dang_nhap'])) {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    exit();
}

$product_id = $_POST['product_id'];
$username = $_SESSION['ten_dang_nhap'];

$conn = new mysqli('localhost', 'root', '', 'tech_house_db');
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
$sql = "DELETE FROM gio_hang WHERE thanh_vien = '$username' AND ma_sp = $product_id";
if ($conn->query($sql) === TRUE) {
    echo 'Xóa sản phẩm khỏi giỏ hàng thành công';
} else {
    echo 'Xóa sản phẩm khỏi giỏ hàng thất bại';
}
?>