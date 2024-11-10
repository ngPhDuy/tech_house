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

$conn = new mysqli('localhost', 'root', '', 'tech_house_db');
if ($conn->connect_error) {
    die('Kết nối thất bại: ' . $conn->connect_error);
}

$product_id = $_POST['product_id'];
$category = $_POST['category'];

switch ($category) {
    case '0':
        $sql = "DELETE FROM laptop WHERE ma_sp = $product_id";
        break;
    case '1':
        $sql = "DELETE FROM mobile WHERE ma_sp = $product_id";
        break;
    case '2':
        $sql = "DELETE FROM tablet WHERE ma_sp = $product_id";
        break;
    case '3':
        $sql = "DELETE FROM tai_nghe_bluetooth WHERE ma_sp = $product_id";
        break;
    case '4':
        $sql = "DELETE FROM ban_phim WHERE ma_sp = $product_id";
        break;
    case '5':
        $sql = "DELETE FROM sac_du_phong WHERE ma_sp = $product_id";
        break;
    case '6':
        $sql = "DELETE FROM op_lung WHERE ma_sp = $product_id";
        break;
}

if ($conn->query($sql) === TRUE) {
    $sql = "DELETE FROM san_pham WHERE ma_sp = $product_id";
    if ($conn->query($sql) === TRUE) {
        echo 'Xoá sản phẩm thành công';
        exit();
    } else {
        echo 'Xoá sản phẩm thất bại. Lỗi: ' . $conn->error;
    }
} else {
    echo 'Xoá sản phẩm thất bại. Lỗi: ' . $conn->error;
}

$conn->close();
?>