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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $productName = $_POST['productName'];
    $color = $_POST['color'];
    $brand = $_POST['brand'];
    $category = $_POST['category'];
    $stock = $_POST['stock'];
    $price = $_POST['price'];
    $discount = $_POST['discount'];
    $os = $_POST['os'];
    $memory = $_POST['memory'];
    $processor = $_POST['processor'];
    $battery = $_POST['battery'];
    $screenSize = $_POST['screenSize'];
    $screenTech = $_POST['screenTech'];
    $productImage = $_POST['productImage'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("UPDATE san_pham SET ten_sp = ?, mau_sac = ?, thuong_hieu = ?, sl_ton_kho = ?, gia_thanh = ?, sale_off = ?, mo_ta = ?, hinh_anh = ? WHERE ma_sp = ?");
    $stmt->bind_param('sssiidssi', $productName, $color, $brand, $stock, $price, $discount, $description, $productImage, $product_id);
    $stmt->execute();

    $stmt = $conn->prepare("UPDATE mobile SET he_dieu_hanh = ?, bo_nho = ?, bo_xu_ly = ?, dung_luong_pin = ?, kich_thuoc_man_hinh = ?, cong_nghe_man_hinh = ? WHERE ma_sp = ?");
    $stmt->bind_param('ssssssi', $os, $memory, $processor, $battery, $screenSize, $screenTech, $product_id);
    $stmt->execute();

    echo 'Chỉnh sửa sản phẩm thành công';

    $stmt->close();
    $conn->close();
    exit();
}

$product_id = $_GET['product_id'];

$sql = "SELECT * FROM san_pham join mobile on san_pham.ma_sp = mobile.ma_sp WHERE san_pham.ma_sp = '$product_id'";
$result = $conn->query($sql);
$product = $result->fetch_assoc();

$conn->close();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech House - Thêm sản phẩm</title>

    <link rel="stylesheet" href="../../styles/admin/product_add.css">
    <link rel="stylesheet" href="../../styles/admin/layout.css">

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
        <a href="../homepage.php">
            <div>
                <span>
                    Trang chủ
                </span>
            </div>
        </a>
        <a href="../products.php" class="nav-active">
            <div>
                <span>
                    Sản phẩm
                </span>
            </div>
        </a>
        <a href="../orders.php">
            <div>
                <span>
                    Đơn hàng
                </span>
            </div>
        </a>
        <a href="../customers.php">
            <div>
                <span>
                    Thành viên
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
                    <img id="notification_icon" src="../../imgs/icons/notification-icon.png" 
                    width="30" height="30" alt="Notification utility">
                </button>

                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Noti 1</a></li>
                    <li><a class="dropdown-item" href="#">Noti 1</a></li>
                    <li><a class="dropdown-item" href="#">Noti 1</a></li>
                </ul>
            </div>

            <div id="profile">
                <div id="profile_account">
                    <img id="profile_avatar" src="../../imgs/avatars/default.png" alt="avatar">
                    <div id="profile_text">
                        <div id="profile_name">Dung Bui</div>
                        <div id="profile_role">Admin</div>
                    </div>
                </div>

                <div id="profile_dropdown" class="dropdown">
                    <a class="btn dropdown-bs-toggle p-0 rounded-circle" href="#" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <svg width="30" height="30" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M10 19.1C15.0258 19.1 19.1 15.0258 19.1 10C19.1 4.97421 15.0258 0.9 10 0.9C4.97421 0.9 0.9 4.97421 0.9 10C0.9 15.0258 4.97421 19.1 10 19.1Z"
                                stroke="#5C5C5C" stroke-width="0.2" />
                            <path
                                d="M10 10.7929L7.73162 8.14645C7.56425 7.95118 7.29289 7.95118 7.12553 8.14645C6.95816 8.34171 6.95816 8.65829 7.12553 8.85355L9.69695 11.8536C9.86432 12.0488 10.1357 12.0488 10.303 11.8536L12.8745 8.85355C13.0418 8.65829 13.0418 8.34171 12.8745 8.14645C12.7071 7.95118 12.4358 7.95118 12.2684 8.14645L10 10.7929Z"
                                fill="#565656" />
                            <mask id="mask0_62_4256" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="7" y="8"
                                width="6" height="4">
                                <path
                                    d="M10 10.7929L7.73162 8.14645C7.56425 7.95118 7.29289 7.95118 7.12553 8.14645C6.95816 8.34171 6.95816 8.65829 7.12553 8.85355L9.69695 11.8536C9.86432 12.0488 10.1357 12.0488 10.303 11.8536L12.8745 8.85355C13.0418 8.65829 13.0418 8.34171 12.8745 8.14645C12.7071 7.95118 12.4358 7.95118 12.2684 8.14645L10 10.7929Z"
                                    fill="white" />
                            </mask>
                            <g mask="url(#mask0_62_4256)">
                            </g>
                        </svg>

                    </a>

                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

    <!-- body content -->
    <div id="body_section" class="mb-5">
        <div id="main_wrapper" class="px-5">
            <div class="h2 mb-3">Chỉnh sửa sản phẩm</div>

            <form class="info d-flex flex-column gap-3">
                <!-- Basic Information -->
                <div class="info-wrapper container">
                    <div class="fs-4 mb-2">Thông tin cơ bản</div>
                    <div class="row">
                        <div class="col input-box">
                            <label for="productName" class="form-label">Tên sản phẩm</label>
                            <input type="text" class="form-control" id="productName" name="productName"
                            value="<?php echo $product['ten_sp']; ?>">
                        </div>
                        <div class="col input-box">
                            <label for="color" class="form-label">Màu sắc</label>
                            <select class="form-select" id="color" name="color">
                                <option value="Black" <?php if ($product['mau_sac'] == 'Black') echo 'selected'; ?>>Đen</option>
                                <option value="White" <?php if ($product['mau_sac'] == 'White') echo 'selected'; ?>>Trắng</option>
                                <option value="Purple" <?php if ($product['mau_sac'] == 'Purple') echo 'selected'; ?>>Tím</option>
                                <option value="Gold" <?php if ($product['mau_sac'] == 'Gold') echo 'selected'; ?>>Vàng</option>
                                <option value="Silver" <?php if ($product['mau_sac'] == 'Silver') echo 'selected'; ?>>Bạc</option>
                                <option value="Pink" <?php if ($product['mau_sac'] == 'Pink') echo 'selected'; ?>>Hồng</option>
                                <option value="Gray" <?php if ($product['mau_sac'] == 'Gray') echo 'selected'; ?>>Xám</option>
                            </select>
                        </div>
                        <div class="col input-box">
                            <label for="brand" class="form-label">Hãng</label>
                            <input type="text" class="form-control" id="brand" name="brand"
                            value="<?php echo $product['thuong_hieu']; ?>">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-3 input-box">
                            <label for="category" class="form-label">Phân loại</label>
                            <!-- <select class="form-select" id="category" name="category" >
                                <option value="0">Laptop</option>
                                <option value="1">Mobile</option>
                                <option value="2">Tablet</option>
                                <option value="3">Tai nghe</option>
                                <option value="4">Bàn phím</option>
                                <option value="5">Sạc dự phòng</option>
                                <option value="6">Ốp lưng</option>
                            </select> -->
                            <input type="text" class="form-control" id="category" name="category"
                            value="<?php 
                            switch ($product['phan_loai']) {
                                case 0:
                                    echo 'Laptop';
                                    break;
                                case 1:
                                    echo 'Mobile';
                                    break;
                                case 2:
                                    echo 'Tablet';
                                    break;
                                case 3:
                                    echo 'Tai nghe';
                                    break;
                                case 4:
                                    echo 'Bàn phím';
                                    break;
                                case 5:
                                    echo 'Sạc dự phòng';
                                    break;
                                case 6:
                                    echo 'Ốp lưng';
                                    break;
                            }
                            ?>" readonly>
                        </div>
                        <div class="col-3 input-box">
                            <label for="stock" class="form-label">Số lượng tồn kho</label>
                            <!-- <input type="number" class="form-control" id="stock" name="stock" > -->
                            <input type="number" class="form-control" id="stock" name="stock"
                            value="<?php echo $product['sl_ton_kho']; ?>">
                        </div>
                        <div class="col-4 input-box">
                            <label for="price" class="form-label">Giá thành</label>
                            <!-- <input type="number" class="form-control" id="price" name="price" > -->
                            <input type="number" class="form-control" id="price" name="price"
                            value="<?php echo $product['gia_thanh']; ?>">
                        </div>
                        <div class="col-2 input-box">
                            <label for="discount" class="form-label">Sales off (%)</label>
                            <!-- <input type="number" class="form-control" id="discount" name="discount" > -->
                            <input type="number" class="form-control" id="discount" name="discount"
                            value="<?php echo $product['sale_off']*100; ?>">
                        </div>
                    </div>
                </div>

                <!-- Technical Information -->
                <div class="info-wrapper container">
                    <div class="fs-4 mb-2">Thông tin kỹ thuật</div>
                    <div class="specification">
                        <div class="col input-box">
                            <label for="os" class="form-label">Hệ điều hành</label>
                            <input type="text" class="form-control" id="os" name="os"
                            value="<?php echo $product['he_dieu_hanh']; ?>">
                        </div>
                        <div class="col input-box">
                            <label for="memory" class="form-label">Bộ nhớ</label>
                            <input type="text" class="form-control" id="memory" name="memory"
                            value="<?php echo $product['bo_nho']; ?>">
                        </div>
                        <div class="col input-box">
                            <label for="processor" class="form-label">Bộ xử lý</label>
                            <input type="text" class="form-control" id="processor" name="processor"
                            value="<?php echo $product['bo_xu_ly']; ?>">
                        </div>
                        <div class="col input-box">
                            <label for="battery" class="form-label">Dung lượng pin</label>
                            <input type="text" class="form-control" id="battery" name="battery"
                            value="<?php echo $product['dung_luong_pin']; ?>">
                        </div>
                        <div class="col input-box">
                            <label for="screenSize" class="form-label">Kích thước màn hình</label>
                            <input type="text" class="form-control" id="screenSize" name="screenSize"
                            value="<?php echo $product['kich_thuoc_man_hinh']; ?>">
                        </div>
                        <div class="col input-box">
                            <label for="screenTech" class="form-label">Công nghệ màn hình</label>
                            <input type="text" class="form-control" id="screenTech" name="screenTech"
                            value="<?php echo $product['cong_nghe_man_hinh']; ?>">
                        </div>
                    </div>
                </div>

                <!-- Image and Description -->
                <div class="info-wrapper container">
                    <div class="fs-4 mb-2">Hình ảnh và Mô tả</div>
                    <div class="row">
                        <div class="col-12 input-box">
                            <label for="productImage" class="form-label">Hình ảnh sản phẩm</label>
                            <input type="text" class="form-control" id="productImage" name="productImage"
                            value="<?php echo $product['hinh_anh']; ?>">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12 input-box">
                            <label for="description" class="form-label">Mô tả sản phẩm</label>
                            <textarea class="form-control" id="description" name="description" rows="5" ><?php echo $product['mo_ta']; ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="text-center mt-4">
                    <button type="button" class="btn btn-lg btn-primary"
                    id="submit-btn">Lưu sản phẩm</button>
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
    $(document).ready(function () {
        $('#submit-btn').click(function () {
            var productName = $('#productName').val();
            var color = $('#color').val();
            var brand = $('#brand').val();
            var category = $('#category').val();
            var stock = $('#stock').val();
            var price = $('#price').val();
            var discount = $('#discount').val();
            var os = $('#os').val();
            var memory = $('#memory').val();
            var processor = $('#processor').val();
            var battery = $('#battery').val();
            var screenSize = $('#screenSize').val();
            var screenTech = $('#screenTech').val();
            var productImage = $('#productImage').val();
            var description = $('#description').val();

            $.ajax({
                url: 'mobile_edit.php',
                type: 'POST',
                data: {
                    product_id: '<?php echo $product_id; ?>',
                    productName: productName,
                    color: color,
                    brand: brand,
                    category: category,
                    stock: stock,
                    price: price,
                    discount: discount,
                    os: os,
                    memory: memory,
                    processor: processor,
                    battery: battery,
                    screenSize: screenSize,
                    screenTech: screenTech,
                    productImage: productImage,
                    description: description
                },
                success: function (response) {
                    if (response == 'Chỉnh sửa sản phẩm thành công') {
                        $('#message-modal .modal-body p').text('Chỉnh sửa sản phẩm thành công');
                        $('#message-modal').modal('show');
                    } else {
                        $('#message-modal .modal-body p').text('Chỉnh sửa sản phẩm thất bại');
                        $('#message-modal').modal('show');
                    }
                }
            });
        });
    });
</script>
</html>
