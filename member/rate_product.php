<?php
session_start();

if (!isset($_SESSION['ten_dang_nhap'])) {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    exit();
}

$rating = $_POST['rating'];
$product_id = $_POST['product_id'];
$username = $_POST['username'];
$feedback = $_POST['feedback'];
$order_id = $_POST['order_id'];

$conn = new mysqli('localhost', 'root', '', 'tech_house_db');
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$sql = "INSERT INTO Danh_gia (thoi_diem_danh_gia, thanh_vien, ma_dh, ma_sp, diem_danh_gia, noi_dung) VALUES (NOW(), '$username', $order_id, $product_id, $rating, '$feedback')";

if ($conn->query($sql) === TRUE) {
    echo 'Đánh giá thành công';
} else {
    echo 'Đánh giá thất bại';
}

$conn->close();
?>