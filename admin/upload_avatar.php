<?php
session_start();

if (!isset($_SESSION['ten_dang_nhap'])) {
    header('Location: login.php');
    exit();
}

if ($_SESSION['phan_loai_tk'] != 'nv') {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'tech_house_db');
if ($conn->connect_error) {
    die('Connection failed: '.$conn->connect_error);
}

if (isset($_FILES['avatar']) && $_FILES['avatar']['name'] != '') {
    $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
    $new_name = $_SESSION['ten_dang_nhap'].'.'.$ext;
    $destination = '../imgs/avatars/'.$new_name;

    if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $destination)) {
        echo "Có lỗi xảy ra khi tải ảnh lên";
        exit();
    }

    $avatar = $new_name;
    $stmt = $conn->prepare("update tai_khoan set avatar = ? where ten_dang_nhap = ?");
    $stmt->bind_param('ss', $avatar, $_SESSION['ten_dang_nhap']);
    $stmt->execute();
    $_SESSION['avatar'] = $avatar;
    echo "Cập nhật thành công";
    exit();
}

echo "Không có ảnh nào được chọn";
$conn->close();
exit();
?>
