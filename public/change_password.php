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
$old_password = $_POST['old_pwd'];
$new_password = $_POST['new_pwd'];

$conn = new mysqli('localhost', 'root', '', 'tech_house_db');

if ($conn->connect_error) {
    die('Kết nối thất bại: ' . $conn->connect_error);
}

$stmt = $conn->prepare('SELECT mat_khau FROM tai_khoan WHERE ten_dang_nhap = ?');
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (password_verify($old_password, $row['mat_khau']) === false) {
    echo 'Mật khẩu cũ không đúng';
    exit();
}

$new_password = password_hash($new_password, PASSWORD_DEFAULT);
$stmt = $conn->prepare('UPDATE tai_khoan SET mat_khau = ? WHERE ten_dang_nhap = ?');
$stmt->bind_param('ss', $new_password, $username);
$stmt->execute();

echo 'Đổi mật khẩu thành công';

$stmt->close();
$conn->close();
?>