<?php
session_start();

if (!isset($_SESSION['ten_dang_nhap']) || $_SESSION['phan_loai_tk'] != 'tv') {
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'tech_house_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$thanh_vien = $_SESSION['ten_dang_nhap'];
$ma_sp = $_POST['product_id'];
$them_vao = $_POST['add'];

if ($them_vao == 'true') {
    $sql = "INSERT INTO Danh_sach_yeu_thich (thanh_vien, ma_sp) VALUES ('$thanh_vien', '$ma_sp')";
} else {
    $sql = "DELETE FROM Danh_sach_yeu_thich WHERE thanh_vien = '$thanh_vien' AND ma_sp = '$ma_sp'";
}

if ($conn->query($sql) === TRUE) {
    if ($them_vao == 'true') {
        echo "Thêm sản phẩm vào yêu thích thành công";
    } else {
        echo "Xóa sản phẩm khỏi yêu thích thành công";
    }
} else {
    echo "Error";
}

$conn->close();
?>