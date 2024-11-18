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

    $productName = $_POST['productName'];
    $color = $_POST['color'];
    $brand = $_POST['brand'];
    $category = $_POST['category'];
    $stock = $_POST['stock'];
    $price = $_POST['price'];
    $discount = $_POST['discount'];
    $description = $_POST['description'];
    $imgUrl = $_POST['img'];

    if ($category == 0) {
        $os = $_POST['os'];
        $memory = $_POST['memory'];
        $processor = $_POST['processor'];
        $battery = $_POST['battery'];
        $screenSize = $_POST['screenSize'];
        $screenTech = $_POST['screenTech'];
        $ram = $_POST['ram'];

        $stmt = $conn->prepare('call Them_laptop(? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,?)');
        $stmt->bind_param('sssiidsssssssss', $productName, $brand, $imgUrl, $stock, $price, $discount, $description, $color, $processor, $battery, $screenSize, $screenTech, $os, $ram, $memory);
    } else if ($category == 1) {
        $os = $_POST['os'];
        $memory = $_POST['memory'];
        $processor = $_POST['processor'];
        $battery = $_POST['battery'];
        $screenSize = $_POST['screenSize'];
        $screenTech = $_POST['screenTech'];
        
        $stmt = $conn->prepare('call Them_mobile(? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,?)');
        $stmt->bind_param('sssiidssssssss', $productName, $brand, $imgUrl, $stock, $price, $discount, $description, $color, $processor, $battery, $screenSize, $screenTech, $os, $memory);
    } else if ($category == 2) {
        $os = $_POST['os'];
        $memory = $_POST['memory'];
        $processor = $_POST['processor'];
        $battery = $_POST['battery'];
        $screenSize = $_POST['screenSize'];
        $screenTech = $_POST['screenTech'];

        $stmt = $conn->prepare('call Them_tablet(? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,?)');
        $stmt->bind_param('sssiidssssssss', $productName, $brand, $imgUrl, $stock, $price, $discount, $description, $color, $processor, $battery, $screenSize, $screenTech, $os, $memory);
    } else if ($category == 3) {
        $battery = $_POST['battery'];
        $radius = $_POST['radius'];
        $waterProof = $_POST['waterProof'];
        $technology = $_POST['technology'];

        $stmt = $conn->prepare('call Them_tai_nghe_blue_tooth(? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,?)');
        $stmt->bind_param('sssiidssssss', $productName, $brand, $imgUrl, $stock, $price, $discount, $description, $color, $radius, $battery, $waterProof, $technology);
    } else if ($category == 4) {
        $keycap = $_POST['keycap'];
        $keyNumber = $_POST['keyNumber'];
        $connection = $_POST['connection'];

        $stmt = $conn->prepare('call Them_ban_phim(? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,?)');
        $stmt->bind_param('sssiidsssis', $productName, $brand, $imgUrl, $stock, $price, $discount, $description, $color, $keycap, $keyNumber, $connection);
    } else if ($category == 5) {
        $capacity = $_POST['capacity'];
        $battery = $_POST['battery'];
        $connection = $_POST['connection'];
        $material = $_POST['material'];

        $stmt = $conn->prepare('call Them_sac_du_phong(? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,?)');
        $stmt->bind_param('sssiidssssss', $productName, $brand, $imgUrl, $stock, $price, $discount, $description, $color, $capacity, $battery, $connection, $material);
    } else if ($category == 6) {
        $material = $_POST['material'];
        $width = $_POST['width'];

        $stmt = $conn->prepare('call Them_op_lung(? ,? ,? ,? ,? ,? ,? ,? ,? ,?)');
        $stmt->bind_param('sssiidssss', $productName, $brand, $imgUrl, $stock, $price, $discount, $description, $color, $material, $width);
    }

    if ($stmt->execute()) {
        echo 'Thêm sản phẩm thành công';
    } else {
        echo 'Thêm sản phẩm thất bại';
    }
    $stmt->close();
    $conn->close();

    exit();
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
            <div class="h2 mb-3">Thêm sản phẩm mới</div>

            <form class="info d-flex flex-column gap-3">
                <!-- Basic Information -->
                <div class="info-wrapper container">
                    <div class="fs-4 mb-2">Thông tin cơ bản</div>
                    <div class="row">
                        <div class="col input-box">
                            <label for="productName" class="form-label">Tên sản phẩm</label>
                            <input type="text" class="form-control" id="productName" name="productName" 
                            placeholder="Mẫu mã - Bộ nhớ - (RAM) - Màu sắc">
                        </div>
                        <div class="col input-box">
                            <label for="color" class="form-label">Màu sắc</label>
                            <select class="form-select" id="color" name="color" >
                                <option value="Black">Đen</option>
                                <option value="White">Trắng</option>
                                <option value="Purple">Tím</option>
                                <option value="Gold">Vàng</option>
                                <option value="Silver">Bạc</option>
                                <option value="Pink">Hồng</option>
                                <option value="Gray">Xám</option>
                            </select>
                        </div>
                        <div class="col input-box">
                            <label for="brand" class="form-label">Hãng</label>
                            <input type="text" class="form-control" id="brand" name="brand" >
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-3 input-box">
                            <label for="category" class="form-label">Phân loại</label>
                            <select class="form-select" id="category" name="category" >
                                <option value="0">Laptop</option>
                                <option value="1">Mobile</option>
                                <option value="2">Tablet</option>
                                <option value="3">Tai nghe</option>
                                <option value="4">Bàn phím</option>
                                <option value="5">Sạc dự phòng</option>
                                <option value="6">Ốp lưng</option>
                            </select>
                        </div>
                        <div class="col-3 input-box">
                            <label for="stock" class="form-label">Số lượng tồn kho</label>
                            <input type="number" class="form-control" id="stock" name="stock" >
                        </div>
                        <div class="col-4 input-box">
                            <label for="price" class="form-label">Giá thành</label>
                            <input type="number" class="form-control" id="price" name="price" >
                        </div>
                        <div class="col-2 input-box">
                            <label for="discount" class="form-label">Sales off (%)</label>
                            <input type="number" class="form-control" id="discount" name="discount" >
                        </div>
                    </div>
                </div>

                <!-- Technical Information -->
                <div class="info-wrapper container">
                    <div class="fs-4 mb-2">Thông tin kỹ thuật</div>
                    <div class="specification">
                        <div class="col input-box">
                            <label for="os" class="form-label">Hệ điều hành</label>
                            <input type="text" class="form-control" id="os" name="os" >
                        </div>
                        <div class="col input-box">
                            <label for="memory" class="form-label">Bộ nhớ</label>
                            <input type="text" class="form-control" id="memory" name="memory" >
                        </div>
                        <div class="col input-box">
                            <label for="processor" class="form-label">Bộ xử lý</label>
                            <input type="text" class="form-control" id="processor" name="processor" >
                        </div>
                        <div class="col input-box">
                            <label for="battery" class="form-label">Dung lượng pin</label>
                            <input type="text" class="form-control" id="battery" name="battery" >
                        </div>
                        <div class="col input-box">
                            <label for="screenSize" class="form-label">Kích thước màn hình</label>
                            <input type="text" class="form-control" id="screenSize" name="screenSize" >
                        </div>
                        <div class="col input-box">
                            <label for="screenTech" class="form-label">Công nghệ màn hình</label>
                            <input type="text" class="form-control" id="screenTech" name="screenTech" >
                        </div>
                        <div class="col input-box">
                            <label for="ram" class="form-label">Ram</label>
                            <input type="text" class="form-control" id="ram" name="ram" >
                        </div>
                        <div class="col input-box">
                            <label for="radius" class="form-label">Phạm vi kết nối</label>
                            <input type="text" class="form-control" id="radius" name="radius" >
                        </div>
                        <div class="col input-box">
                            <label for="waterProof" class="form-label">Chống nước</label>
                            <input type="text" class="form-control" id="waterProof" name="waterProof" >
                        </div>
                        <div class="col input-box">
                            <label for="technology" class="form-label">Công nghệ âm thanh</label>
                            <input type="text" class="form-control" id="technology" name="technology" >
                        </div>
                        <div class="col input-box">
                            <label for="keycap" class="form-label">Loại phím</label>
                            <input type="text" class="form-control" id="keycap" name="keycap" >
                        </div>
                        <div class="col input-box">
                            <label for="keyNumber" class="form-label">Số phím</label>
                            <input type="text" class="form-control" id="keyNumber" name="keyNumber" >
                        </div>
                        <div class="col input-box">
                            <label for="connection" class="form-label">Cổng kết nối</label>
                            <input type="text" class="form-control" id="connection" name="connection" >
                        </div>
                        <div class="col input-box">
                            <label for="capacity" class="form-label">Công suất</label>
                            <input type="text" class="form-control" id="capacity" name="capacity" >
                        </div>
                        <div class="col input-box">
                            <label for="material" class="form-label">Chất liệu</label>
                            <input type="text" class="form-control" id="material" name="material" >
                        </div>
                        <div class="col input-box">
                            <label for="width" class="form-label">Độ dày</label>
                            <input type="text" class="form-control" id="width" name="width" >
                        </div>
                    </div>
                </div>

                <!-- Image and Description -->
                <div class="info-wrapper container">
                    <div class="fs-4 mb-2">Hình ảnh và Mô tả</div>
                    <div class="row">
                        <div class="col-12 input-box">
                            <label for="productImage" class="form-label">Hình ảnh sản phẩm</label>
                            <input type="text" class="form-control" id="productImage" name="productImage" >
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12 input-box">
                            <label for="description" class="form-label">Mô tả sản phẩm</label>
                            <textarea class="form-control" id="description" name="description" rows="5" ></textarea>
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
    $('document').ready(function() {
        $('#category').change();
    });

    let specification_inputs = $('.specification input');
    $('#category').change(function() {
        specification_inputs.val('');
        let category = $('#category').val();
        if (category == 0) {
            // hiển thị các trường thông tin của laptop: os, memory, processor, ram, screen size, screen tech, battery
            $('#os').parent().show();
            $('#memory').parent().show();
            $('#processor').parent().show();
            $('#ram').parent().show();
            $('#screenSize').parent().show();
            $('#screenTech').parent().show();
            $('#battery').parent().show();
            $('#radius').parent().hide();
            $('#waterProof').parent().hide();
            $('#technology').parent().hide();
            $('#keycap').parent().hide();
            $('#keyNumber').parent().hide();
            $('#connection').parent().hide();
            $('#capacity').parent().hide();
            $('#material').parent().hide();
            $('#width').parent().hide();
        } else if (category == 1 || category == 2) {
            // hiển thị các trường thông tin của mobile và tablet: os, memory, processor, screen size, screen tech, battery
            $('#os').parent().show();
            $('#memory').parent().show();
            $('#processor').parent().show();
            $('#ram').parent().hide();
            $('#screenSize').parent().show();
            $('#screenTech').parent().show();
            $('#battery').parent().show();
            $('#radius').parent().hide();
            $('#waterProof').parent().hide();
            $('#technology').parent().hide();
            $('#keycap').parent().hide();
            $('#keyNumber').parent().hide();
            $('#connection').parent().hide();
            $('#capacity').parent().hide();
            $('#material').parent().hide();
            $('#width').parent().hide();
        } else if (category == 3) {
            // hiển thị các trường thông tin của tai nghe: radius, battery, water proof, technology
            $('#os').parent().hide();
            $('#memory').parent().hide();
            $('#processor').parent().hide();
            $('#ram').parent().hide();
            $('#screenSize').parent().hide();
            $('#screenTech').parent().hide();
            $('#battery').parent().show();
            $('#radius').parent().show();
            $('#waterProof').parent().show();
            $('#technology').parent().show();
            $('#keycap').parent().hide();
            $('#keyNumber').parent().hide();
            $('#connection').parent().hide();
            $('#capacity').parent().hide();
            $('#material').parent().hide();
            $('#width').parent().hide();
        } else if (category == 4) {
            // hiển thị các trường thông tin của bàn phím: keycap, key number, connection
            $('#os').parent().hide();
            $('#memory').parent().hide();
            $('#processor').parent().hide();
            $('#ram').parent().hide();
            $('#screenSize').parent().hide();
            $('#screenTech').parent().hide();
            $('#battery').parent().hide();
            $('#radius').parent().hide();
            $('#waterProof').parent().hide();
            $('#technology').parent().hide();
            $('#keycap').parent().show();
            $('#keyNumber').parent().show();
            $('#connection').parent().show();
            $('#capacity').parent().hide();
            $('#material').parent().hide();
            $('#width').parent().hide();
        } else if (category == 5) {
            // hiển thị các trường thông tin của sạc dự phòng: capacity, battery, connection, material
            $('#os').parent().hide();
            $('#memory').parent().hide();
            $('#processor').parent().hide();
            $('#ram').parent().hide();
            $('#screenSize').parent().hide();
            $('#screenTech').parent().hide();
            $('#battery').parent().show();
            $('#radius').parent().hide();
            $('#waterProof').parent().hide();
            $('#technology').parent().hide();
            $('#keycap').parent().hide();
            $('#keyNumber').parent().hide();
            $('#connection').parent().show();
            $('#capacity').parent().show();
            $('#material').parent().show();
            $('#width').parent().hide();
        } else if (category == 6) {
            // hiển thị các trường thông tin của ốp lưng: material, width
            $('#os').parent().hide();
            $('#memory').parent().hide();
            $('#processor').parent().hide();
            $('#ram').parent().hide();
            $('#screenSize').parent().hide();
            $('#screenTech').parent().hide();
            $('#battery').parent().hide();
            $('#radius').parent().hide();
            $('#waterProof').parent().hide();
            $('#technology').parent().hide();
            $('#keycap').parent().hide();
            $('#keyNumber').parent().hide();
            $('#connection').parent().hide();
            $('#capacity').parent().hide();
            $('#material').parent().show();
            $('#width').parent().show();
        }
    });

    $('#submit-btn').click(function() {
        let productName = $('#productName').val();
        let color = $('#color').val();
        let brand = $('#brand').val();
        let category = $('#category').val();
        let stock = $('#stock').val();
        let price = $('#price').val();
        let discount = $('#discount').val() / 100;
        let os = $('#os').val();
        let memory = $('#memory').val();
        let processor = $('#processor').val();
        let battery = $('#battery').val();
        let screenSize = $('#screenSize').val();
        let screenTech = $('#screenTech').val();
        let ram = $('#ram').val();
        let img = $('#productImage').val();
        let description = $('#description').val();
        let radius = $('#radius').val();
        let waterProof = $('#waterProof').val();
        let technology = $('#technology').val();
        let keycap = $('#keycap').val();
        let keyNumber = $('#keyNumber').val();
        let connection = $('#connection').val();
        let capacity = $('#capacity').val();
        let material = $('#material').val();
        let width = $('#width').val();

        if (productName === '' || color === '' || brand === '' || 
        category === '' || stock === '' || price === '' || discount === '' ) {
            console.log(productName + '-' + color + '-' + brand + '-' + category + '-' + stock + '-' + price + '-' + discount);
            $('.modal-body p').text('Vui lòng điền đầy đủ thông tin');
            $('.modal-body p').css('color', 'red');
            $('#message-modal').modal('show');

            return;
        }

        if (category === 0) {
            if (os === '' || memory === '' || processor === '' || ram === '' || screenSize === '' || screenTech === '' || battery === '') {
                $('.modal-body p').text('Vui lòng điền đầy đủ thông số kỹ thuật');
                $('.modal-body p').css('color', 'red');
                $('#message-modal').modal('show');

                return;
            }
        } else if (category === 1 || category === 2) {
            if (os === '' || memory === '' || processor === '' || screenSize === '' || screenTech === '' || battery === '') {
                $('.modal-body p').text('Vui lòng điền đầy đủ thông số kỹ thuật');
                $('.modal-body p').css('color', 'red');
                $('#message-modal').modal('show');

                return;
            }
        } else if (category === 3) {
            if (battery === '' || radius === '' || waterProof === '' || technology === '') {
                $('.modal-body p').text('Vui lòng điền đầy đủ thông số kỹ thuật');
                $('.modal-body p').css('color', 'red');
                $('#message-modal').modal('show');

                return;
            }
        } else if (category === 4) {
            if (keycap === '' || keyNumber === '' || connection === '') {
                $('.modal-body p').text('Vui lòng điền đầy đủ thông số kỹ thuật');
                $('.modal-body p').css('color', 'red');
                $('#message-modal').modal('show');

                return;
            }
        } else if (category === 5) {
            if (capacity === '' || battery === '' || connection === '' || material === '') {
                $('.modal-body p').text('Vui lòng điền đầy đủ thông số kỹ thuật');
                $('.modal-body p').css('color', 'red');
                $('#message-modal').modal('show');

                return;
            }
        } else if (category === 6) {
            if (material === '' || width === '') {
                $('.modal-body p').text('Vui lòng điền đầy đủ thông số kỹ thuật');
                $('.modal-body p').css('color', 'red');
                $('#message-modal').modal('show');

                return;
            }
        }

        if (img === '') {
            $('.modal-body p').text('Vui lòng chọn hình ảnh sản phẩm');
            $('.modal-body p').css('color', 'red');
            $('#message-modal').modal('show');

            return;
        }

        if (description === '') {
            $('.modal-body p').text('Vui lòng điền mô tả sản phẩm');
            $('.modal-body p').css('color', 'red');
            $('#message-modal').modal('show');

            return;
        }

        let formData = {};
        formData.productName = productName;
        formData.color = color;
        formData.brand = brand;
        formData.category = category;
        formData.stock = stock;
        formData.price = price;
        formData.discount = discount;
        formData.description = description;
        formData.img = img;

        switch (category) {
            case '0':
                formData.os = os;
                formData.memory = memory;
                formData.processor = processor;
                formData.battery = battery;
                formData.screenSize = screenSize;
                formData.screenTech = screenTech;
                formData.ram = ram;
                break;
            case '1':
            case '2':
                formData.os = os;
                formData.memory = memory;
                formData.processor = processor;
                formData.battery = battery;
                formData.screenSize = screenSize;
                formData.screenTech = screenTech;
                break;
            case '3':
                formData.battery = battery;
                formData.radius = radius;
                formData.waterProof = waterProof;
                formData.technology = technology;
                break;
            case '4':
                formData.keycap = keycap;
                formData.keyNumber = keyNumber;
                formData.connection = connection;
                break;
            case '5':
                formData.capacity = capacity;
                formData.battery = battery;
                formData.connection = connection;
                formData.material = material;
                break;
            case '6':
                formData.material = material;
                formData.width = width;
                break;
        }
        console.log(formData);

        $.ajax({
            url: 'product_add.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                console.log("asd");
                console.log(response);
                if (response === 'Thêm sản phẩm thành công') {
                    $('.modal-body p').text('Thêm sản phẩm thành công');
                    $('.modal-body p').css('color', 'green');
                    $('#message-modal').modal('show');
                } else {
                    $('.modal-body p').text('Thêm sản phẩm thất bại');
                    $('.modal-body p').css('color', 'red');
                    $('#message-modal').modal('show');
                }
            }
        });
    });
</script>
</html>
