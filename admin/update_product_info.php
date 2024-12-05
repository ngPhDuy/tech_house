<?php
session_start();

if (!isset($_SESSION['ten_dang_nhap'])) {
    header('Location: ../public/login.php');
    exit();
}

if ($_SESSION['phan_loai_tk'] != 'nv') {
    header('Location: ../index.php');
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'tech_house_db');
if ($conn->connect_error) {
    die('Connection failed: '.$conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $productName = $_POST['productName'];
    $color = $_POST['color'];
    $brand = $_POST['brand'];
    $stock = $_POST['stock'];
    $price = $_POST['price'];
    $discount = $_POST['discount'];
    $productImage = $_POST['productImage'];
    $description = $_POST['description'];

    // Lấy thông tin cơ bản của sản phẩm
    $sql = "SELECT * FROM san_pham WHERE ma_sp = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    if (!$stmt->execute()) {
        echo "Không thể lấy thông tin sản phẩm";
        exit();
    }
    $result = $stmt->get_result();
    $product_baseInfo = $result->fetch_assoc();

    if ($product_baseInfo) {
        $product_type = $product_baseInfo['phan_loai'];  // Loại sản phẩm
    } else {
        echo "Không thể lấy thông tin sản phẩm";
        exit();
    }

    // Cập nhật thông tin cơ bản cho sản phẩm trong bảng san_pham
    $stmt = $conn->prepare("UPDATE San_pham SET ten_sp = ?, mau_sac = ?, thuong_hieu = ?, sl_ton_kho = ?, gia_thanh = ?, sale_off = ?, mo_ta = ?, hinh_anh = ? WHERE ma_sp = ?");
    $stmt->bind_param('sssiidssi', $productName, $color, $brand, $stock, $price, $discount, $description, $productImage, $product_id);
    if (!$stmt->execute()) {
        echo "Lỗi cập nhật thông tin cơ bản";
        exit();
    }

    // Cập nhật thông tin sản phẩm trong bảng tương ứng dựa trên loại sản phẩm
    switch ($product_type) {
        case 0: // Laptop
            $os = $_POST['os']; // Hệ điều hành
            $memory = $_POST['memory']; // Bộ nhớ
            $processor = $_POST['processor']; // Bộ xử lý
            $capacity = $_POST['capacity']; // Dung lượng pin
            $screenSize = $_POST['screenSize']; // Kích thước màn hình
            $screenTech = $_POST['screenTech']; // Công nghệ màn hình
            $ram = $_POST['ram']; // Ram

            // Sửa tên cột trong câu lệnh UPDATE theo đúng bảng Laptop
            $stmt = $conn->prepare("UPDATE Laptop SET he_dieu_hanh = ?, bo_nho = ?, bo_xu_ly = ? , dung_luong_pin = ?, kich_thuoc_man_hinh = ?, cong_nghe_man_hinh = ?, ram = ? WHERE ma_sp = ?");
            $stmt->bind_param('sssssssi', $os, $memory, $processor, $capacity, $screenSize, $screenTech, $ram, $product_id);
            if (!$stmt->execute()) {
                echo "Lỗi cập nhật thông tin tech";
                exit();
            }
            break;

        case 1: // Mobile
            $os = $_POST['os']; // Hệ điều hành
            $memory = $_POST['memory']; // Bộ nhớ
            $processor = $_POST['processor']; // Bộ xử lý
            $capacity = $_POST['capacity']; // Dung lượng pin
            $screenSize = $_POST['screenSize']; // Kích thước màn hình
            $screenTech = $_POST['screenTech']; // Công nghệ màn hình

            // Sửa tên cột trong câu lệnh UPDATE theo đúng bảng Laptop
            $stmt = $conn->prepare("UPDATE Mobile SET bo_xu_ly = ?, dung_luong_pin = ?, kich_thuoc_man_hinh = ?, cong_nghe_man_hinh = ?, he_dieu_hanh = ?, bo_nho = ? WHERE ma_sp = ?");
            
            $stmt->bind_param('ssssssi', $processor, $capacity, $screenSize, $screenTech, $os, $memory, $product_id);

            if (!$stmt->execute()) {
                echo "Lỗi cập nhật thông tin tech";
                exit();
            }
            break;

        case 2: // Tablet
            $os = $_POST['os']; // Hệ điều hành
            $memory = $_POST['memory']; // Bộ nhớ
            $processor = $_POST['processor']; // Bộ xử lý
            $capacity = $_POST['capacity']; // Dung lượng pin
            $screenSize = $_POST['screenSize']; // Kích thước màn hình
            $screenTech = $_POST['screenTech']; // Công nghệ màn hình

            // Sửa tên cột trong câu lệnh UPDATE theo đúng bảng Laptop
            $stmt = $conn->prepare("UPDATE Tablet SET bo_xu_ly = ?, dung_luong_pin = ?, kich_thuoc_man_hinh = ?, cong_nghe_man_hinh = ?, he_dieu_hanh = ?, bo_nho = ? WHERE ma_sp = ?");
            
            $stmt->bind_param('ssssssi', $processor, $capacity, $screenSize, $screenTech, $os, $memory, $product_id);

            if (!$stmt->execute()) {
                echo "Lỗi cập nhật thông tin tech";
                exit();
            }
            break;

        case 3: // Tai nghe Bluetooth
            $connectionRange = $_POST['connectionRange']; // Phạm vi kết nối
            $batteryLife = $_POST['batteryLife']; // Thời gian sử dụng pin
            $waterProof = $_POST['waterResistance']; // Chống nước
            $soundTech = $_POST['soundTech']; // Công nghệ âm thanh

            // Sửa tên cột trong câu lệnh UPDATE theo đúng bảng Tai_nghe_bluetooth
            $stmt = $conn->prepare("UPDATE Tai_nghe_bluetooth SET pham_vi_ket_noi = ?, thoi_luong_pin = ?, chong_nuoc = ?, cong_nghe_am_thanh = ? WHERE ma_sp = ?");
            $stmt->bind_param('ssssi', $connectionRange, $batteryLife, $waterProof, $soundTech, $product_id);

            if (!$stmt->execute()) {
                echo "Lỗi cập nhật thông tin tech";
                exit();
            }
            break;

        case 4: // Bàn phím
            $keyCap = $_POST['keyCap']; // Loại phím (cơ, màng)
            $keyNumber = $_POST['keyNumber']; // Số phím
            $connectionType = $_POST['connectionType']; // Loại kết nối (USB, Bluetooth)

            // Sửa tên cột trong câu lệnh UPDATE theo đúng bảng Ban_phim
            $stmt = $conn->prepare("UPDATE Ban_phim SET key_cap = ?, so_phim = ?, cong_ket_noi = ? WHERE ma_sp = ?");
            $stmt->bind_param('sssi', $keyCap, $keyNumber, $connectionType, $product_id);
            if (!$stmt->execute()) {
                echo "Lỗi cập nhật thông tin tech";
                exit();
            }
            break;

        case 5: // Sạc dự phòng
            $capacity = $_POST['capacity']; // Dung lượng pin sạc
            $battery = $_POST['battery']; // Công suất
            $connectionType = $_POST['connectionType']; // Kết nối
            $material = $_POST['material']; // Chất liệu

            // Sửa tên cột trong câu lệnh UPDATE theo đúng bảng Sac_du_phong
            $stmt = $conn->prepare("UPDATE Sac_du_phong SET dung_luong_pin = ?, cong_suat = ?, cong_ket_noi = ?, chat_lieu = ? WHERE ma_sp = ?");
            $stmt->bind_param('ssssi', $capacity, $battery, $connectionType, $material, $product_id);
            if (!$stmt->execute()) {
                echo "Lỗi cập nhật thông tin tech";
                exit();
            }
            break;

        case 6: // Ốp lưng
            $material = $_POST['material']; // Chất liệu
            $thickness = $_POST['thickness']; // Độ dày

            // Sửa tên cột trong câu lệnh UPDATE theo đúng bảng Op_lung
            $stmt = $conn->prepare("UPDATE Op_lung SET chat_lieu = ?, do_day = ? WHERE ma_sp = ?");
            $stmt->bind_param('ssi', $material, $thickness, $product_id);
            if (!$stmt->execute()) {
                echo "Lỗi cập nhật thông tin tech";
                exit();
            }
            break;

        default:
            echo "Loại sản phẩm không hợp lệ";
            exit();
    }

    echo "Chỉnh sửa sản phẩm thành công";
}

$stmt->close();
$conn->close();
