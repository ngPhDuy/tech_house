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

// Kết nối đến cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'tech_house_db');
if ($conn->connect_error) {
    die('Kết nối thất bại: ' . $conn->connect_error);
}

// Lấy ID sản phẩm từ query string
$product_id = $_GET['product_id'];

// Lấy thông tin cơ bản của sản phẩm
$sql = "SELECT * FROM san_pham WHERE ma_sp = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product_baseInfo = $result->fetch_assoc();

if ($product_baseInfo) {
    // Lấy loại sản phẩm
    $product_type = $product_baseInfo['phan_loai'];

    // Lấy tên bảng kỹ thuật tương ứng với loại sản phẩm
    $table_name = product_db_name($product_type);

    if ($table_name) {
        // Lấy thông tin kỹ thuật từ bảng sản phẩm tương ứng
        $sql = "SELECT * FROM $table_name WHERE ma_sp = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product_techInfo = $result->fetch_assoc();
    } else {
        // Nếu không có bảng tương ứng, thiết lập thông tin kỹ thuật là null
        $product_techInfo = null;
    }
} else {
    // Nếu không tìm thấy sản phẩm
    $product_baseInfo = null;
    $product_techInfo = null;
}

// Đóng statement và kết nối
$stmt->close();
$conn->close();


function product_db_name($type)
{
    switch ($type) {
        case 0:
            return 'Laptop';
        case 1:
            return 'Mobile';
        case 2:
            return 'Tablet';
        case 3:
            return 'Tai_nghe_bluetooth';
        case 4:
            return 'Ban_phim';
        case 5:
            return 'Sac_du_phong';
        case 6:
            return 'Op_lung';
        default:
            return $type;
    }
}



function translateKeyToVN($key)
{
    switch ($key) {
        case "key_cap":
            return "Loại keycaps";

        case "so_phim":
            return "Số phím";

        case "cong_ket_noi":
            return "Cổng kết nối";

        case "chat_lieu":
            return "Chất liệu";

        case "do_day":
            return "Độ dày";

        case "bo_xu_ly":
            return "Bộ xử lý";

        case "dung_luong_pin":
            return "Dung lượng pin";

        case "kich_thuoc_man_hinh":
            return "Kích thước màn hình";

        case "cong_nghe_man_hinh":
            return "Công nghệ màn hình";

        case "he_dieu_hanh":
            return "Hệ điều hành";

        case "ram":
            return "RAM";

        case "bo_nho":
            return "Bộ nhớ";

        case "pham_vi_ket_noi":
            return "Phạm vi kết nối";

        case "thoi_luong_pin":
            return "Thời lượng pin";

        case "chong_nuoc":
            return "Chống nước";

        case "cong_nghe_am_thanh":
            return "Công nghệ âm thanh";

        case "cong_suat":
            return "Công suất";

        default:
            return "";
    }
}

function translateKeyToEN($key)
{
    switch ($key) {
        case "ten_sp":
            return "productName";
        case "mau_sac":
            return "color";
        case "thuong_hieu":
            return "brand";
        case "phan_loai":
            return "category";
        case "sl_ton_kho":
            return "stock";
        case "gia_thanh":
            return "price";
        case "sale_off":
            return "discount";
        case "he_dieu_hanh":
            return "os"; // Operating system
        case "bo_nho":
            return "memory";
        case "bo_xu_ly":
            return "processor";
        case "dung_luong_pin":
            return "capacity";
        case "kich_thuoc_man_hinh":
            return "screenSize";
        case "cong_nghe_man_hinh":
            return "screenTech";
        case "ram":
            return "ram";
        case "hinh_anh":
            return "productImage";
        case "mo_ta":
            return "description";
        case "pham_vi_ket_noi":
            return "connectionRange";
        case "chong_nuoc":
            return "waterResistance"; // Water proof
        case "thoi_luong_pin":
                return "batteryLife";
        case "cong_nghe_am_thanh":
            return "soundTech";
        case "key_cap":
            return "keyCap";
        case "so_phim":
            return "keyNumber";
        case "cong_ket_noi":
            return "connectionType";
        case "chat_lieu":
            return "material";
        case "do_day":
            return "thickness";
        case "cong_suat":
            return "battery";
        default:
            return $key;
    }
}



function getCategoryName($category)
{
    switch ($category) {
        case '0':
            return 'Laptop';
            break;
        case '1':
            return 'Mobile';
            break;
        case '2':
            return 'Tablet';
            break;
        case '3':
            return 'Tai nghe bluetooth';
            break;
        case '4':
            return 'Ban phim';
            break;
        case '5':
            return 'Sac du phong';
            break;
        case '6':
            return 'Op lung';
            break;

        default:
            return "";
    }
}

?>


<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech House - Thêm sản phẩm</title>

    <link rel="stylesheet" href="../styles/admin/product_add.css">
    <link rel="stylesheet" href="../styles/admin/layout.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Nunito Sans' rel='stylesheet'>
</head>

<body>
    <!-- side bar -->
    <div id="sidebar">
        <div id="logo">
            Tech House
        </div>
        <a href="./homepage.php">
            <div>
                <span>
                    Trang chủ
                </span>
            </div>
        </a>
        <a href="./products.php" class="nav-active">
            <div>
                <span>
                    Sản phẩm
                </span>
            </div>
        </a>
        <a href="./orders.php">
            <div>
                <span>
                    Đơn hàng
                </span>
            </div>
        </a>
        <a href="./customers.php">
            <div>
                <span>
                    Thành viên
                </span>
            </div>
        </a>
        <a href="./account_setting.php">
            <div>
                <span>
                    Tài khoản
                </span>
            </div>
        </a>

    </div>

    <div id="header">
        <div id="left_section"></div>

        <div id="right_section">
            <div id="notification_utility" class="dropdown">
                <button class="btn dropdown-bs-toggle p-1" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <img id="notification_icon" src="../imgs/icons/notification-icon.png"
                        width="30" height="30" alt="Notification utility">
                </button>

                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Noti 1</a></li>
                    <li><a class="dropdown-item" href="#">Noti 1</a></li>
                    <li><a class="dropdown-item" href="#">Noti 1</a></li>
                </ul>
            </div>

             <div id="profile" class="me-2">
                <div id="profile_dropdown" class="dropdown">
                    <a class="btn dropdown-bs-toggle" href="#" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <div id="profile_account">
                            <img id="profile_avatar" src="../imgs/avatars/default.png" alt="avatar">
                            <div id="profile_text">
                                <div id="profile_name">Dung Bui</div>
                                <div id="profile_role">Admin</div>
                            </div>
                        </div>

                    </a>

                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item text-primary" href="./account_setting.php">Thông tin tài khoản</a></li>
                        <li><a class="dropdown-item text-danger" href="../public/logout.php">Đăng xuất</a></li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

    <!-- body content -->
    <div id="body_section" class="mb-5">
        <div id="main_wrapper" class="px-5">
            <div class="h2 mb-3">Chỉnh sửa sản phẩm</div>

            <form id="productForm" class="info d-flex flex-column gap-3">
            <input type="hidden" name="product_id"
            value="<?php echo $product_baseInfo['ma_sp']; ?>" readonly>
                <!-- Basic Information -->
                <div class="info-wrapper container">
                    <div class="fs-4 mb-2">Thông tin cơ bản</div>
                    <div class="row">
                        <div class="col input-box">
                            <label for="productName" class="form-label">Tên sản phẩm</label>
                            <input type="text" class="form-control" id="productName" name="productName"
                                value="<?php echo htmlspecialchars($product_baseInfo['ten_sp']); ?>" required>
                        </div>
                        <div class="col input-box">
                            <label for="color" class="form-label">Màu sắc</label>
                            <select class="form-select" id="color" name="color" required>
                                <option value="Black" <?php if ($product_baseInfo['mau_sac'] == 'Black') echo 'selected'; ?>>Đen</option>
                                <option value="White" <?php if ($product_baseInfo['mau_sac'] == 'White') echo 'selected'; ?>>Trắng</option>
                                <option value="Purple" <?php if ($product_baseInfo['mau_sac'] == 'Purple') echo 'selected'; ?>>Tím</option>
                                <option value="Gold" <?php if ($product_baseInfo['mau_sac'] == 'Gold') echo 'selected'; ?>>Vàng</option>
                                <option value="Silver" <?php if ($product_baseInfo['mau_sac'] == 'Silver') echo 'selected'; ?>>Bạc</option>
                                <option value="Pink" <?php if ($product_baseInfo['mau_sac'] == 'Pink') echo 'selected'; ?>>Hồng</option>
                                <option value="Gray" <?php if ($product_baseInfo['mau_sac'] == 'Gray') echo 'selected'; ?>>Xám</option>
                            </select>
                        </div>
                        <div class="col input-box">
                            <label for="brand" class="form-label">Hãng</label>
                            <input type="text" class="form-control" id="brand" name="brand"
                                value="<?php echo htmlspecialchars($product_baseInfo['thuong_hieu']); ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-3 input-box">
                            <label for="category" class="form-label">Phân loại</label>
                            <input disabled type="text" class="form-control" id="category" name="category"
                                value="<?php echo getCategoryName($product_baseInfo['phan_loai']); ?>" />
                        </div>
                        <div class="col-3 input-box">
                            <label for="stock" class="form-label">Số lượng tồn kho</label>
                            <input type="number" class="form-control" id="stock" name="stock"
                                value="<?php echo $product_baseInfo['sl_ton_kho']; ?>" required>
                        </div>
                        <div class="col-3 input-box">
                            <label for="price" class="form-label">Giá thành</label>
                            <input type="number" class="form-control" id="price" name="price"
                                value="<?php echo $product_baseInfo['gia_thanh']; ?>" required>
                        </div>
                        <div class="col-3 input-box">
                            <label for="discount" class="form-label">Giảm giá (%)</label>
                            <input type="number" class="form-control" id="discount" name="discount"
                                value="<?php echo $product_baseInfo['sale_off']; ?>" required>
                        </div>
                    </div>
                </div>

                <!-- Technical Information -->
                <div class="info-wrapper container">
                    <div class="fs-4 mb-2">Thông tin kỹ thuật</div>
                    <div class="specification">
                        <?php foreach ($product_techInfo as $key => $value): ?>
                            <?php if ($key === 'ma_sp') continue; ?>
                            <div class="col input-box">
                                <label for="<?php echo translateKeyToEN($key); ?>" class="form-label">
                                    <?php echo translateKeyToVN($key); ?>
                                </label>
                                <input type="text" class="form-control" id="<?php echo translateKeyToEN($key); ?>" name="<?php echo translateKeyToEN($key); ?>"
                                    value="<?php echo htmlspecialchars($value); ?>" />
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Image and Description -->
                <div class="info-wrapper container">
                    <div class="fs-4 mb-2">Hình ảnh và Mô tả</div>
                    <div class="row">
                        <div class="col-12 input-box">
                            <label for="productImage" class="form-label">Hình ảnh sản phẩm</label>
                            <input type="text" class="form-control" id="productImage" name="productImage"
                                value="<?php echo htmlspecialchars($product_baseInfo['hinh_anh']); ?>" />
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12 input-box">
                            <label for="description" class="form-label">Mô tả sản phẩm</label>
                            <textarea class="form-control" id="description" name="description" rows="5"><?php echo htmlspecialchars($product_baseInfo['mo_ta']); ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-lg btn-primary">Lưu sản phẩm</button>
                </div>
            </form>


        </div>
    </div>

    <div class="modal" tabindex="-1" id="message-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <p class="m-0 fs-5 text-center p-3"></p>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
<script src="../node_modules/jquery/dist/jquery.min.js"></script>

<script>
    $('#productForm').submit(function(e) {
        e.preventDefault(); // Ngừng hành động gửi form mặc định

        var formData = $(this).serialize(); // Lấy dữ liệu từ form

        $.ajax({
            type: 'POST',
            url: './product_edit_ajax.php', // URL của tệp xử lý
            data: formData, // Dữ liệu gửi đi
            success: function(response) {
                var res = JSON.parse(response); // Chuyển đổi dữ liệu JSON trả về

                // Hiển thị modal với thông báo từ phản hồi
                if (res.success) {
                    $('#message-modal .modal-body p').text(res.success); // Hiển thị thông báo thành công
                    $('#message-modal').modal('show'); // Mở modal
                } else if (res.error) {
                    $('#message-modal .modal-body p').text(res.error); // Hiển thị thông báo lỗi
                    $('#message-modal').modal('show'); // Mở modal
                }
            },
            error: function() {
                $('#message-modal .modal-body p').text('Có lỗi xảy ra!'); // Thông báo lỗi nếu AJAX gặp sự cố
                $('#message-modal').modal('show'); // Mở modal lỗi
            }
        });
    });
</script>

</html>