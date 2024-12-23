<?php
session_start();

if (!isset($_SESSION['ten_dang_nhap'])) {
    echo 'Chưa đăng nhập';
    exit();
}

if (!$_SERVER['REQUEST_METHOD'] === 'POST') {
    echo '../public/404.php';
    exit();
}

$username = $_SESSION['ten_dang_nhap'];
$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];

$conn = new mysqli('localhost', 'root', '', 'tech_house_db');

if ($conn->connect_error) {
    die('Kết nối thất bại: ' . $conn->connect_error);
    exit();
}

$stmt = 'call Them_vao_gio_hang(?, ?, ?)';
$stmt = $conn->prepare($stmt);
$stmt->bind_param('sii', $username, $product_id, $quantity);

try {
    $stmt->execute();
} catch (Exception $e) {
    echo 'Thất bại';
    exit();
}

$stmt->close();
$conn->close();

echo 'Thêm vào giỏ hàng thành công';
?>
