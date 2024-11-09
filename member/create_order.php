<?php
session_start();

if (!isset($_SESSION['ten_dang_nhap'])) {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    exit();
}

if (!isset($_POST['product_id']) || !isset($_POST['quantity'])) {
    $username = $_POST['username'];
    $total_price = $_POST['total_price'];

    $conn = new mysqli('localhost', 'root', '', 'tech_house_db');
    if ($conn->connect_error) {
        die('Kết nối không thành công ' . $conn->connect_error);
    }
    $sql = "call Tao_don_hang_tu_gio_hang('$username', $total_price)";
    if ($conn->query($sql) === TRUE) {
        echo 'Tạo đơn hàng thành công';
    } else {
        echo 'Tạo đơn hàng thất bại';
    }
} else {
    $username = $_POST['username'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $total_price = $_POST['total_price'];

    $conn = new mysqli('localhost', 'root', '', 'tech_house_db');
    if ($conn->connect_error) {
        die('Kết nối không thành công ' . $conn->connect_error);
    }
    $sql = "call Tao_don_hang_mot_sp('$username', $product_id, $quantity, $total_price)";
    if ($conn->query($sql) === TRUE) {
        echo 'Tạo đơn hàng thành công';
    } else {
        echo 'Tạo đơn hàng thất bại';
    }
}

$conn->close();
?>