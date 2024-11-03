<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "GET" || !isset($_GET['product_id'])) {
    header("Location: ../index.php");
    exit();
}

$product_id = $_GET['product_id'];

$conn = new mysqli("localhost", "root", "", "tech_house_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM San_pham WHERE ma_sp = $product_id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    header("Location: ../index.php");
    exit();
}

$product = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="../styles/custom.css" rel="stylesheet">
    <link href="../styles/product_detail.css" rel="stylesheet">
    <title>Chi tiết sản phẩm</title>
</head>
<body>
    <div class="page-wrapper">
        <header>
            <div class="row bg-primary align-items-center">
                <div class="logo col-lg-3 col-4 text-white d-flex justify-content-center align-items-center ps-3">
                    <a href="../index.php" class="text-white">
                        <h1 class="fw-bold">Tech House</h1>
                    </a>
                </div>
                <div class="search-bar col d-flex align-items-center bg-secondary">
                    <img src="../imgs/icons/search.png" alt="search" width="24" height="24">
                    <input type="text" class="search-input bg-secondary border-0" placeholder="Tìm kiếm sản phẩm..">
                </div>
                <div class="login-cart col-lg-3 col-4 d-flex align-items-center justify-content-evenly">
                    <div class="login w-50">
                        <?php
                        if (isset($_SESSION['ten_dang_nhap'])) {
                            echo 
                            '<a href="./member/profile.html" class="fw-bold text-white">
                                <img src="../imgs/icons/user.png" alt="user" width="32" height="32">
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
                            '<a href="./login.php" class="fw-bold text-white">
                                <img src="../imgs/icons/user.png" alt="user" width="32" height="32">
                                Đăng nhập
                            </a>';
                        }
                        ?>
                    </div>
                    <div class="cart w-50">
                        <a href="#" class="fw-bold text-white">
                            <img src="../imgs/icons/cart.png" alt="user" width="32" height="32">
                            Giỏ hàng
                        </a>
                    </div>
                </div>
            </div>
            <div class="tabs row justify-content-between align-items-center bg-white p-3 ps-5">
                <div class="tab col">
                    <a href="./product_list.php">
                        <img src="../imgs/icons/house.png" alt="home" width="24" height="24">
                        Trang chủ
                    </a>
                </div>
                <div class="tab col">
                    <a href="./product_list.php?product_type=1">
                        <img src="../imgs/icons/phone_iphone.png" alt="phone" width="24" height="24">
                        Điện thoại
                    </a>
                </div>  
                <div class="tab col">
                    <a href="./product_list.php?product_type=0">
                        <img src="../imgs/icons/laptop_mac.png" alt="laptop" width="24" height="24">
                        Laptop
                    </a>
                </div>
                <div class="tab col">
                    <a href="./product_list.php?product_type=2">
                        <img src="../imgs/icons/tablet_android.png" alt="tablet" width="24" height="24">
                        Tablet
                    </a>
                </div>
                <div class="tab col">
                    <a href="./product_list.php?product_type=3">
                        <img src="../imgs/icons/gamepad.png" alt="other" width="24" height="24">
                        Phụ kiện
                        <img src="../imgs/icons/keyboard_arrow_down.png" alt="arrow-down" width="24" height="24">
                    </a>
                    <div class="dropdown-content">
                        <div><a href="./product_list.php?product_type=3">Tai nghe</a></div>
                        <div><a href="./product_list.php?product_type=4">Bàn phím</a></div>
                        <div><a href="./product_list.php?product_type=5">Sạc dự phòng</a></div>
                        <div><a href="./product_list.php?product_type=6">Ốp lưng</a></div>
                    </div>
                </div>
                <div class="col-4"></div>
            </div>
        </header>
        <main class="p-3" product-id="<?php echo $product_id?>" product-type="<?php echo $product['phan_loai']?>">
            <div class="main-wrapper px-5">
                <div class="product-detail-wrapper row">
                    <div class="product-preview col-6">
                        <?php
                        echo '<img src="'.$product['hinh_anh'].'" alt="'.$product['ten_sp'].'"width="100%" height="100%">';
                        ?>
                        <!-- <img src="../imgs/products/apple_macbook_pro_m1.png" alt="apple_macbook_pro_m1" 
                        width="100%" height="100%"> -->
                        <div class="features-wrapper px-3 mt-3">
                            <p class="feature-title fs-5">Chính sách cho sản phẩm</p>
                            <div class="features d-flex flex-wrap justify-content-between gap-3">
                                <div class="feature">
                                    <span class="medal-icon"></span>
                                    <p>Bảo hành 1 năm</p>
                                </div>
                                <div class="feature">
                                    <span class="truck-icon"></span>
                                    <p>Miễn phí vận chuyển</p>
                                </div>
                                <div class="feature">
                                    <span class="handshake-icon"></span>
                                    <p>Đảm bảo hoàn tiền 100%</p>
                                </div>
                                <div class="feature">
                                    <span class="headphones-icon"></span>
                                    <p>Dịch vụ hỗ trợ 24/7</p>
                                </div>
                                <div class="feature">
                                    <span class="creditcard-icon"></span>
                                    <p>Bảo mật thanh toán</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="product-detail col-6 d-flex flex-column gap-3">
                        <?php
                        $stmt = $conn->prepare("select count(*) as so_luong_danh_gia, avg(diem_danh_gia) as diem_danh_gia from Danh_gia where ma_sp = ? group by ma_sp");
                        $stmt->bind_param("i", $product_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $rate = $result->fetch_assoc();
                        $stmt->close();
                        $conn->next_result();
                        ?>
                        <div class="product-content">
                            <?php
                            if ($rate && $rate['so_luong_danh_gia'] > 0) {
                                echo '<a href="#product-information" class="stars d-flex gap-0">';
                                for ($i = 0; $i < round($rate['diem_danh_gia']); $i++) {
                                    echo '<span class="star-icon"></span>';
                                }
                                echo '<p class="rate m-0 fw-bold ms-2">'.round($rate['diem_danh_gia'], 1).' đánh giá</p>';
                                echo '<p class="rate-count m-0 fw-light ms-2">('.$rate['so_luong_danh_gia'].' đánh giá)</p>';
                                echo '</a>';
                            } else {
                                echo '<a href="#product-information" class="stars d-flex gap-0">';
                                echo '<p class="rate m-0 fw-bold ms-2 no-rate">Chưa có đánh giá</p>';
                                echo '</a>';
                            }
                            ?>
                            <!-- <a href="#product-information" class="stars d-flex gap-0">
                                <span class="star-icon"></span>
                                <span class="star-icon"></span>
                                <span class="star-icon"></span>
                                <span class="star-icon"></span>
                                <span class="star-icon"></span>
                                <p class="rate m-0 fw-bold ms-2">4.7 đánh giá</p>
                                <p class="rate-count m-0 fw-light ms-2">(21,671 đánh giá)</p>
                            </a> -->
                            <p class="product-name m-0 fs-5" id="product-name">
                                <?php echo $product['ten_sp']; ?>
                            </p>
                        </div>
                        <div class="fact d-flex justify-content-between">
                            <p class="m-0">Thương hiệu: <span class="fw-bold">
                                <?php echo $product['thuong_hieu']; ?>
                            </span></p>
                            <?php
                            if ($product['sl_ton_kho'] > 0) {
                                echo '<p class="m-0">Tình trạng: <span class="text-success">Còn hàng</span></p>';
                            } else {
                                echo '<p class="m-0">Tình trạng: <span class="text-success">Hết hàng</span></p>';
                            }
                            ?>
                        </div>
                        <div class="prices d-flex gap-3 align-items-center">
                            <?php 
                            if ($product['sale_off'] > 0) {
                                echo 
                                '<p class="new-price m-0">'.number_format($product['gia_thanh'] * (1 - $product['sale_off'])).'đ</p>
                                <p class="old-price m-0">'.number_format($product['gia_thanh']).'đ</p>
                                <div class="discount d-flex align-items-center">
                                    '.$product['sale_off'] * 100 .'% OFF
                                </div>';
                            } else {
                                echo '<p class="new-price m-0">'.number_format($product['gia_thanh']).'đ</p>';
                            }
                            ?>
                        </div>
                        <?php
                        if ($product['phan_loai'] == 1) {
                            $stmt = $conn->prepare("call Tim_mobile_theo_mau_ma(?)");
                            $stmt->bind_param("s", $product['ten_sp']);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $colors = [];
                            $memorys = [];
                            while ($row = $result->fetch_assoc()) {
                                if (!in_array($row['mau_sac'], $colors)) {
                                    array_push($colors, $row['mau_sac']);
                                }
                                if (!in_array($row['bo_nho'], $memorys)) {
                                    array_push($memorys, $row['bo_nho']);
                                }
                            }
                            $stmt->close();
                            $conn->next_result();
                        } else if ($product['phan_loai'] == 2) {
                            echo '<p>Chọn màu sắc và dung lượng</p>';
                            $stmt = $conn->prepare("call Tim_tablet_theo_mau_ma(?)");
                            $stmt->bind_param("s", $product['ten_sp']);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $colors = [];
                            $memorys = [];
                            while ($row = $result->fetch_assoc()) {
                                if (!in_array($row['mau_sac'], $colors)) {
                                    array_push($colors, $row['mau_sac']);
                                }
                                if (!in_array($row['bo_nho'], $memorys)) {
                                    array_push($memorys, $row['bo_nho']);
                                }
                            }
                            $stmt->close();
                            $conn->next_result();
                        } else if ($product['phan_loai'] == 0) {
                            $stmt = $conn->prepare("call Tim_laptop_theo_mau_ma(?)");
                            $stmt->bind_param("s", $product['ten_sp']);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $rams = [];
                            $memorys = [];
                            while ($row = $result->fetch_assoc()) {
                                if (!in_array($row['ram'], $rams)) {
                                    array_push($rams, $row['ram']);
                                }
                                if (!in_array($row['bo_nho'], $memorys)) {
                                    array_push($memorys, $row['bo_nho']);
                                }
                            }
                            $stmt->close();
                            $conn->next_result();
                        }
                        ?>
                        <div class="form">
                            <div class="colors-wrapper row">
                                <p class="m-0 mb-2">Màu</p>
                                <div class="colors d-flex gap-3">
                                    <?php
                                    if ($product['phan_loai'] == 1 || $product['phan_loai'] == 2) {
                                        foreach ($colors as $color) {
                                            echo 
                                            '<label>
                                                <input type="radio" name="color" value="'.strtolower($color).'" 
                                                class="color-input" checked>
                                                <div class="color '.strtolower($color).'"></div>
                                            </label>';
                                        }
                                    } else {
                                        echo 
                                        '<label>
                                            <input type="radio" name="color" value="'.strtolower($product['mau_sac']).'" 
                                            class="color-input" checked>
                                            <div class="color '.strtolower($product['mau_sac']).'"></div>
                                        </label>';
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="ram-memory row">
                                <?php
                                if ($product['phan_loai'] == 1 || $product['phan_loai'] == 2) {
                                    echo 
                                    '<div class="memory col-6">
                                        <p class="m-0 mb-2"> Dung lượng</p>
                                        <select name="memory" id="memory" class="form-select">';
                                        foreach ($memorys as $memory) {
                                            echo '<option value="'.$memory.'" selected>'.$memory.'</option>';
                                        }
                                        echo '</select>
                                    </div>';
                                } else if ($product['phan_loai'] == 0) {
                                    echo 
                                    '<div class="ram col-6">
                                        <p class="m-0 mb-2">RAM</p>
                                        <select name="ram" id="ram" class="form-select">';
                                        foreach ($rams as $ram) {
                                            echo '<option value="'.$ram.'" selected>'.$ram.'</option>';
                                        }
                                        echo '</select>
                                    </div>';
                                    echo
                                    '<div class="memory col-6">
                                        <p class="m-0 mb-2">Dung lượng</p>
                                        <select name="memory" id="memory" class="form-select">';
                                        foreach ($memorys as $memory) {
                                            echo '<option value="'.$memory.'" selected>'.$memory.'</option>';
                                        }
                                        echo '</select>
                                    </div>';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="buttons d-flex gap-3">
                            <div class="quantity-wrapper col d-flex 
                            align-items-center justify-content-between
                            mx-auto">
                                <button class="btn" id="decrement">-</button>
                                <input type="text" class="text-center m-0" id="quantity" value="1" min="1" readonly>
                                <button class="btn" id="increment">+</button>
                            </div>
                            <button class="btn btn-primary col 
                            fw-bold text-uppercase" id="add-to-cart">
                            Thêm vào giỏ hàng</button>
                            <button class="buy-now-btn btn 
                            col fw-bold text-uppercase">
                            Mua ngay</button>
                        </div>
                    </div>
                </div>
                <div class="product-information my-3 p-3 pb-0" id="product-information">
                    <div class="product-info-tabs d-flex justify-content-center gap-3">
                        <div class="product-info-tab text-uppercase text-center py-2 selected">Mô tả sản phẩm</div>
                        <div class="product-info-tab text-uppercase py-2 text-center">Thông số kỹ thuật</div>
                        <div class="product-info-tab text-uppercase py-2 text-center">Đánh giá</div>
                    </div>
                    <div class="content description-content py-3">
                        <?php
                        $descriptions = explode("\n", $product['mo_ta']);
                        foreach ($descriptions as $description) {
                            echo '<p class="m-0 mb-3">'.$description.'</p>';
                        }
                        ?>
                    </div>
                    <div class="content specification-content py-3 d-none">
                        <div class="table-reponsive">
                            <table class="table table-bordered m-0">
                                <tbody>
                                    <?php
                                    if ($product['phan_loai'] == 1) {
                                        $stmt = $conn->prepare("select * from mobile where ma_sp = ?");
                                        $stmt->bind_param("i", $product_id);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        $spec = $result->fetch_assoc();
                                        $stmt->close();
                                        $conn->next_result();
                                    } else if ($product['phan_loai'] == 2) {
                                        $stmt = $conn->prepare("select * from tablet where ma_sp = ?");
                                        $stmt->bind_param("i", $product_id);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        $spec = $result->fetch_assoc();
                                        $stmt->close();
                                        $conn->next_result();
                                    }
                                    ?>
                                    <tr>
                                        <td>Chip</td>
                                        <td>
                                            <?php
                                            if ($product['phan_loai'] == 1) {
                                                echo $spec['bo_xu_ly'];
                                            } else {
                                                echo 'NAN';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Kích thước màn hình</td>
                                        <td>
                                            <?php
                                            if ($product['phan_loai'] == 1) {
                                                echo $spec['kich_thuoc_man_hinh'];
                                            } else {
                                                echo 'NAN';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Công nghệ màn hình</td>
                                        <td>
                                            <?php
                                            if ($product['phan_loai'] == 1) {
                                                echo $spec['cong_nghe_man_hinh'];
                                            } else {
                                                echo 'NAN';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Pin</td>
                                        <td>
                                            <?php
                                            if ($product['phan_loai'] == 1) {
                                                echo $spec['dung_luong_pin'];
                                            } else {
                                                echo 'NAN';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Hệ điều hành</td>
                                        <td>
                                            <?php
                                            if ($product['phan_loai'] == 1) {
                                                echo $spec['he_dieu_hanh'];
                                            } else {
                                                echo 'NAN';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="content rate-content py-3 d-none">
                        <div class="rates-wrapper">
                            <?php
                            $stmt = $conn->prepare("select * 
                            from Danh_gia join Tai_khoan on Danh_gia.thanh_vien = Tai_khoan.ten_dang_nhap 
                            where ma_sp = ?
                            order by Danh_gia.thoi_diem_danh_gia desc");
                            $stmt->bind_param("i", $product_id);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            if ($result->num_rows > 0) {
                                while ($rate = $result->fetch_assoc()) {
                                    echo 
                                    '<div class="rate">
                                        <div class="rate-header d-flex gap-3 align-items-center">
                                            <p class="m-0 fw-bold">'.$rate['ho_va_ten'].'</p>
                                            <p class="m-0">'.$rate['thoi_diem_danh_gia'].'</p>
                                            <div class="stars d-flex gap-0">';
                                            for ($i = 0; $i < $rate['diem_danh_gia']; $i++) {
                                                echo '<span class="star-icon"></span>';
                                            }
                                            echo '</div>
                                        </div>
                                        <p class=" m-0">'.$rate['noi_dung'].'</p>
                                    </div>';
                                }
                            } else {
                                echo '<p class="m-0 text-center">Chưa có đánh giá nào cho sản phẩm này</p>';
                            }
                            $stmt->close();
                            $conn->next_result();
                            ?>
                        </div>
                        <div class="pagination d-flex mx-auto 
                        justify-content-center align-items-center gap-3 mt-3">
                        </div>
                    </div>
                </div>
                <div class="products-suggestion row">
                    <div class="common-brand my-4 container">
                        <p class="m-0 text-uppercase fw-bold">Sản phẩm cùng thương hiệu</p>
                        <div class="products mt-3 d-flex justify-content-start">
                            <?php
                            $stmt = $conn->prepare("select * from San_pham where thuong_hieu = ? and ma_sp != ? order by San_pham.gia_thanh desc limit 4");
                            $stmt->bind_param("si", $product['thuong_hieu'], $product_id);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            while ($product = $result->fetch_assoc()) {
                                echo 
                                '<a class="product d-flex justify-content-evenly align-items-center mx-2" 
                                href="./product_detail.php?product_id='.$product['ma_sp'].'">
                                    <img src="'.$product['hinh_anh'].'" alt="'.$product['ten_sp'].'" width="50%" height="50%">
                                    <div class="content d-flex flex-column gap-3 overflow-hidden ms-1">
                                        <p class="m-0">'.$product['ten_sp'].'</p>
                                        <p class="price m-0">'.number_format($product['gia_thanh'] * (1 - $product['sale_off'])).'đ</p>
                                    </div>
                                </a>';
                            }
                            $stmt->close();
                            $conn->next_result();
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <footer class="row bg-primary text-white p-3 justify-content-center">
            <div class="row justify-content-evenly">
                <div class="col-3 pt-4">
                    <h5>Tổng đài hỗ trợ</h5>
                    <div class="phone-wrapper">
                        <img src="../imgs/icons/call_icon.png" alt="phone" width="24" height="24">
                        <span>Gọi mua:</span>
                    </div>
                    <p>1922-6067 (8:00 - 21:30)</p>
                    <div class="phone-wrapper">
                        <img src="../imgs/icons/call_icon.png" alt="phone" width="24" height="24">
                        <span>Bảo hành:</span>
                    </div>
                    <p>1922-6068 (8:00 - 21:30)</p>
                    <div class="phone-wrapper">
                        <img src="../imgs/icons/call_icon.png" alt="phone" width="24" height="24">
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
<script src="../node_modules/jquery/dist/jquery.min.js"></script>
<script>
    const productInfoTabs = document.querySelectorAll('.product-info-tab');
    const descriptionContent = document.querySelector('.description-content');
    const specificationContent = document.querySelector('.specification-content');
    const rateContent = document.querySelector('.rate-content');

    productInfoTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            productInfoTabs.forEach(tab => {
                tab.classList.remove('selected');
            });
            tab.classList.add('selected');
            console.log(tab.textContent);
            if (tab.textContent === 'Mô tả sản phẩm') {
                descriptionContent.classList.remove('d-none');
                specificationContent.classList.add('d-none');
                rateContent.classList.add('d-none');
            } else if (tab.textContent === 'Thông số kỹ thuật') {
                descriptionContent.classList.add('d-none');
                specificationContent.classList.remove('d-none');
                rateContent.classList.add('d-none');
            } else {
                descriptionContent.classList.add('d-none');
                specificationContent.classList.add('d-none');
                rateContent.classList.remove('d-none');
            }
        });
    });

    //quantity button
    $('#decrement').click(() => {
        if ($('#quantity').val() > 1) {
            $('#quantity').val(parseInt($('#quantity').val()) - 1);
        }
    });

    $('#increment').click(() => {
        $('#quantity').val(parseInt($('#quantity').val()) + 1);
    });

    //add to cart
    $("#add-to-cart").click((e) => {
        e.preventDefault();
        const productName = $("#product-name").text().split('-')[0].trim();
        const productId = $("main").attr('product-id');
        const productType = $("main").attr('product-type');
        const color = $(".color-input:checked").val();
        const memory = $("#memory").val();
        const ram = $("#ram").val() ?? 'nan';
        const quantity = $("#quantity").val();
        console.log(productName, productId, productType, color, memory, ram, quantity);
        $.ajax({
            url: "../member/add_to_cart.php",
            type: "POST",
            data: {
                product_id: productId,
                product_name: productName,
                product_type: productType,
                color: color,
                memory: memory,
                ram: ram,
                quantity: quantity
            },
            success: (response) => {
                console.log(response);
                alert(response);
            }
        });
    })

    //pagination
    const ratePerPage = 3;
    const paginationLength = 3;
    const pagination = document.querySelector('.pagination');
    const pageNumbers = document.querySelectorAll('.page-number');
    const rates = document.querySelectorAll('.rates-wrapper .rate');
    let currentPage = 1;

    function displayRates() {
        rates.forEach((rate, index) => {
            if (index >= (currentPage - 1) * ratePerPage && index < currentPage * ratePerPage) {
                rate.classList.remove('d-none');
            } else {
                rate.classList.add('d-none');
            }
        });
    }

    function updatePagination() {
        const totalPages = Math.ceil(rates.length / ratePerPage);
        
        if (totalPages == 1) {
            pagination.classList.add('d-none');
            return;
        }

        pagination.innerHTML = '';

        const halfWindow = Math.floor(paginationLength / 2);
        let startPage = Math.max(1, currentPage - halfWindow);
        let endPage = Math.min(totalPages, currentPage + halfWindow);

        if (currentPage - halfWindow < 1) {
            endPage = Math.min(totalPages, endPage + (halfWindow - (currentPage - 1)));
        }
    
        if (currentPage + halfWindow > totalPages) {
            startPage = Math.max(1, startPage - (currentPage + halfWindow - totalPages));
        }

        for (let i = startPage; i <= endPage; i++) {
            const page = document.createElement('button');
            page.classList.add('btn', 'page-number');
            page.textContent = i;

            if (i == currentPage) {
                page.classList.add('active');
            }

            page.addEventListener('click', (e) => {
                e.preventDefault();
                currentPage = i;
                displayRates();
                updatePagination();
            });
            pagination.appendChild(page);
        }

        pagination.classList.remove('d-none');
    }

    displayRates();
    updatePagination();
</script>
</html>
<?php
$conn->close();
?>