<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("HTTP/1.1 405 Method Not Allowed", true, 405);
    exit();
}

$ten_dang_nhap = $_POST['ten_dang_nhap'];
$ho_va_ten = $_POST['ho_va_ten'];
$dia_chi = $_POST['dia_chi'];
$email = $_POST['email'];
$sdt = $_POST['sdt'];

$conn = new mysqli('localhost', 'root', '', 'tech_house_db');
if ($conn->connect_error) {
    die('Connection failed: '.$conn->connect_error);
}

if (isset($_FILES['avatar']) && $_FILES['avatar']['name'] != '') {
    $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
    $new_name = $ten_dang_nhap.'.'.$ext;
    $destination = '../imgs/avatars/'.$new_name;

    if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $destination)) {
        echo "Có lỗi xảy ra khi tải ảnh lên";
        exit();
    }

    $avatar = $new_name;
    $stmt = $conn->prepare("update tai_khoan set ho_va_ten = ?, dia_chi = ?, email = ?, sdt = ?, avatar = ? where ten_dang_nhap = ?");
    $stmt->bind_param('ssssss', $ho_va_ten, $dia_chi, $email, $sdt, $avatar, $ten_dang_nhap);
    $stmt->execute();
} else {
    $stmt = $conn->prepare("update tai_khoan set ho_va_ten = ?, dia_chi = ?, email = ?, sdt = ? where ten_dang_nhap = ?");
    $stmt->bind_param('sssss', $ho_va_ten, $dia_chi, $email, $sdt, $ten_dang_nhap);
    $stmt->execute();
}

$tempArr = explode(" ", $ho_va_ten);
$_SESSION['ho_ten'] = $tempArr[count($tempArr) - 2] . " " . $tempArr[count($tempArr) - 1];

echo "Cập nhật thành công";
$conn->close();
exit();
?>