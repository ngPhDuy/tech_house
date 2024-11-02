<?php
session_start();

$conn = new mysqli("localhost", "root", "", "tech_house_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="./styles/custom.css" rel="stylesheet">
    <link href="./styles/index.css" rel="stylesheet">
    <title>Trang chủ</title>
</head>
<body>
    <div class="page-wrapper">
        <header>
            <div class="row bg-primary align-items-center">
                <div class="logo col-lg-3 col-4 text-white d-flex justify-content-center align-items-center ps-3">
                    <a href="./index.php" class="text-white">
                        <h1 class="fw-bold">Tech House</h1>
                    </a>
                </div>
                <div class="search-bar col d-flex align-items-center bg-secondary">
                    <img src="./imgs/icons/search.png" alt="search" width="24" height="24">
                    <input type="text" class="search-input bg-secondary border-0" placeholder="Tìm kiếm sản phẩm..">
                </div>
                <div class="login-cart col-lg-3 col-4 d-flex align-items-center justify-content-evenly">
                    <div class="login w-50">
                        <?php
                        if (isset($_SESSION['ten_dang_nhap'])) {
                            echo 
                            '<a href="./member/profile.html" class="fw-bold text-white">
                                <img src="./imgs/icons/user.png" alt="user" width="32" height="32">
                                '.$_SESSION['ho_ten'].'</a>';
                            echo '
                            <div class="dropdown-content">
                                <div><a href="./member/profile.html">Thông tin cá nhân</a></div>
                                <div><a href="./member/change_password.html">Đổi mật khẩu</a></div>
                                <div><a href="./member/order_history.html">Lịch sử mua hàng</a></div>
                                <div><a href="./member/logout.php">Đăng xuất</a></div>
                            </div>';
                        } else {
                            echo 
                            '<a href="./public/login.php" class="fw-bold text-white">
                                <img src="./imgs/icons/user.png" alt="user" width="32" height="32">
                                Đăng nhập
                            </a>';
                        }
                        ?>
                    </div>
                    <div class="cart w-50">
                        <a href="#" class="fw-bold text-white">
                            <img src="./imgs/icons/cart.png" alt="user" width="32" height="32">
                            Giỏ hàng
                        </a>
                    </div>
                </div>
            </div>
            <div class="tabs row justify-content-between align-items-center bg-white p-3 ps-5">
                <div class="tab col">
                    <a href="./public/product_list.php">
                        <img src="./imgs/icons/house.png" alt="home" width="24" height="24">
                        Trang chủ
                    </a>
                </div>
                <div class="tab col">
                    <a href="./public/product_list.php?product_type=1">
                        <img src="./imgs/icons/phone_iphone.png" alt="phone" width="24" height="24">
                        Điện thoại
                    </a>
                </div>  
                <div class="tab col">
                    <a href="./public/product_list.php?product_type=0">
                        <img src="./imgs/icons/laptop_mac.png" alt="laptop" width="24" height="24">
                        Laptop
                    </a>
                </div>
                <div class="tab col">
                    <a href="./public/product_list.php?product_type=2">
                        <img src="./imgs/icons/tablet_android.png" alt="tablet" width="24" height="24">
                        Tablet
                    </a>
                </div>
                <div class="tab col">
                    <a href="./public/product_list.php?product_type=3">
                        <img src="./imgs/icons/gamepad.png" alt="other" width="24" height="24">
                        Phụ kiện
                        <img src="./imgs/icons/keyboard_arrow_down.png" alt="arrow-down" width="24" height="24">
                    </a>
                    <div class="dropdown-content">
                        <div><a href="./public/product_list.php?product_type=3">Tai nghe</a></div>
                        <div><a href="./public/product_list.php?product_type=4">Bàn phím</a></div>
                        <div><a href="./public/product_list.php?product_type=5">Sạc dự phòng</a></div>
                        <div><a href="./public/product_list.php?product_type=6">Ốp lưng</a></div>
                    </div>
                </div>
                <div class="col-4"></div>
            </div>
        </header>
        <main class="d-flex flex-column justify-content-center align-items-center">
            <div class="banner container p-3 mb-3">
                <img src="./imgs/icons/banner.png" alt="banner" width="100%" height="100%">
                <div class="banner-content">
                    <h2>Đồng hồ thông minh</h2>
                    <p>Giảm giá đến 80%</p>
                </div>
            </div>
            <div class="products row justify-content-center">
                <div class="products-line">
                    <div class="title d-flex justify-content-between">
                        <p class="title1 fw-bold fs-5">Điện thoại nổi bật</p>
                        <a class="m-0" href="./public/product_list.php?product_type=1">
                            Xem tất cả <img src="./imgs/icons/arrow_right.png" alt="all" width="18" height="18">
                        </a>
                    </div>
                    <div class="line d-flex justify-content-center gap-3 p-4">
                        <?php
                        $sql = "SELECT * FROM san_pham WHERE phan_loai = 1 LIMIT 5";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo 
                                '<a class="product bg-white" href="./public/product_detail.php?product_id='.$row['ma_sp'].'">
                                    <img class="product-img d-block mx-auto" src="'.$row['hinh_anh'].'" alt="'.$row['ten_sp'].'" width="50px" height="50px">
                                    <div class="product-info">
                                        <p>'.$row['ten_sp'].'</p>
                                        <p>'.number_format($row['gia_thanh'] * (1 - $row['sale_off']), 0, ",", ".").' đ</p>
                                        <p><span class="star-icon"></span>5.0</p>
                                    </div>
                                </a>';
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="products-line">
                    <div class="title d-flex justify-content-between">
                        <p class="title1 fw-bold fs-5">Laptop nổi bật</p>
                        <a class="m-0" href="./public/product_list.php?product_type=0">
                            Xem tất cả <img src="./imgs/icons/arrow_right.png" alt="all" width="18" height="18">
                        </a>
                    </div>
                    <div class="line d-flex justify-content-center gap-3 p-4">
                    <?php
                        $sql = "SELECT * FROM san_pham WHERE phan_loai = 0 LIMIT 5";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo 
                                '<a class="product bg-white" href="./public/product_detail.php?product_id='.$row['ma_sp'].'">
                                    <img class="product-img d-block mx-auto" src="'.$row['hinh_anh'].'" alt="'.$row['ten_sp'].'" width="50px" height="50px">
                                    <div class="product-info">
                                        <p>'.$row['ten_sp'].'</p>
                                        <p>'.number_format($row['gia_thanh'] * (1 - $row['sale_off']), 0, ",", ".").' đ</p>
                                        <p><span class="star-icon"></span>5.0</p>
                                    </div>
                                </a>';
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="products-line">
                    <div class="title d-flex justify-content-between">
                        <p class="title1 fw-bold fs-5">Tablet nổi bật</p>
                        <a class="m-0" href="./public/product_list.php?product_type=0">
                            Xem tất cả <img src="./imgs/icons/arrow_right.png" alt="all" width="18" height="18">
                        </a>
                    </div>
                    <div class="line d-flex justify-content-center gap-3 p-4">
                    <?php
                        $sql = "SELECT * FROM san_pham WHERE phan_loai = 2 LIMIT 5";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo 
                                '<a class="product bg-white" href="./public/product_detail.php?product_id='.$row['ma_sp'].'">
                                    <img class="product-img d-block mx-auto" src="'.$row['hinh_anh'].'" alt="'.$row['ten_sp'].'" width="50px" height="50px">
                                    <div class="product-info">
                                        <p>'.$row['ten_sp'].'</p>
                                        <p>'.number_format($row['gia_thanh'] * (1 - $row['sale_off']), 0, ",", ".").' đ</p>
                                        <p><span class="star-icon"></span>5.0</p>
                                    </div>
                                </a>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </main>
        <footer class="row bg-primary text-white p-3 justify-content-center">
            <div class="row justify-content-evenly">
                <div class="col-3 pt-4">
                    <h5>Tổng đài hỗ trợ</h5>
                    <div class="phone-wrapper">
                        <img src="./imgs/icons/call_icon.png" alt="phone" width="24" height="24">
                        <span>Gọi mua:</span>
                    </div>
                    <p>1922-6067 (8:00 - 21:30)</p>
                    <div class="phone-wrapper">
                        <img src="./imgs/icons/call_icon.png" alt="phone" width="24" height="24">
                        <span>Bảo hành:</span>
                    </div>
                    <p>1922-6068 (8:00 - 21:30)</p>
                    <div class="phone-wrapper">
                        <img src="./imgs/icons/call_icon.png" alt="phone" width="24" height="24">
                        <span>Khiếu nại:</span>
                    </div>
                    <p>1922-6069 (8:00 - 21:30)</p>
                </div>
                <!-- <div class="col-1"></div> -->
                <div class="category col-4">
                    <h5>Danh mục sản phẩm</h5>
                    <ul class="d-flex flex-column gap-1">
                        <li><a href="#">Điện thoại</a></li>
                        <li><a href="#">Laptop</a></li>
                        <li><a href="#">Tablet</a></li>
                        <li><a href="#">Tai nghe</a></li>
                        <li><a href="#">Bàn phím</a></li>
                        <li><a href="#">Sạc dự phòng</a></li>
                        <li><a href="#">Bao da, ốp lưng</a></li>
                    </ul>
                </div>
                <div class="other-info col-4">
                    <h5>Các thông tin khác</h5>
                    <ul class="d-flex flex-column gap-1">
                        <li><a href="#">Giới thiệu công ty</a></li>
                        <li><a href="#">Chính sách bảo hành</a></li>
                        <li><a href="#">Góp ý, khiếu nại</a></li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <p class="text-center m-0">© 2024 Tech House. All rights reserved.</p>
            </div>
        </footer>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</html>