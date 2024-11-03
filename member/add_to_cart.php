<?php
session_start();

if (!isset($_SESSION['ten_dang_nhap'])) {
    echo '../public/login.php';
    exit();
}

if (!$_SERVER['REQUEST_METHOD'] === 'POST') {
    echo '../public/404.php';
    exit();
}

$username = $_SESSION['ten_dang_nhap'];
$productName = $_POST['product_name'];
$productType = (int)$_POST['product_type'];
$color = $_POST['color'];
$memory = $_POST['memory'];
$ram = $_POST['ram'];
$quantity = $_POST['quantity'];

$conn = new mysqli('localhost', 'root', '', 'tech_house_db');

if ($conn->connect_error) {
    echo '../public/404.php';
    exit();
}

if ($productType == 0) {
    $stmt = 'select san_pham.ma_sp from san_pham join laptop on san_pham.ma_sp = laptop.ma_sp 
    where substring_index(san_pham.ten_sp, "-", 1) = ? and lower(san_pham.mau_sac) = ? and laptop.bo_nho = ? and laptop.ram = ?';

    $stmt = $conn->prepare($stmt);
    $stmt->bind_param('ssss', $productName, $color, $memory, $ram);
} else if ($productType == 1) {
    $stmt = 'select san_pham.ma_sp from san_pham join mobile on san_pham.ma_sp = mobile.ma_sp 
    where substring_index(san_pham.ten_sp, "-", 1) = ? and lower(san_pham.mau_sac) = ? and mobile.bo_nho = ?';

    $stmt = $conn->prepare($stmt);
    $stmt->bind_param('sss', $productName, $color, $memory);
} else if ($productType == 2) {
    $stmt = 'select san_pham.ma_sp from san_pham join tablet on san_pham.ma_sp = tablet.ma_sp 
    where substring_index(san_pham.ten_sp, "-", 1) = ? and lower(san_pham.mau_sac) = ? and tablet.bo_nho = ?';

    $stmt = $conn->prepare($stmt);
    $stmt->bind_param('sss', $productName, $color, $memory);
} else {
    $stmt = 'select ma_sp from san_pham where ten_sp = ? and lower(mau_sac) = ?';

    $stmt = $conn->prepare($stmt);
    $stmt->bind_param('ss', $productName, $color);
}

$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if ($result->num_rows == 0) {
    echo 'Không tìm thấy sản phẩm';
    exit();
}

$row = $result->fetch_assoc();
$productID = $row['ma_sp'];
$stmt = 'call Them_vao_gio_hang(?, ?, ?)';
$stmt = $conn->prepare($stmt);
$stmt->bind_param('sii', $username, $productID, $quantity);
try {
    $stmt->execute();
} catch (Exception $e) {
    echo 'Thất bại';
    exit();
}

$stmt->close();
$conn->close();

echo 'Thành công';
?>
