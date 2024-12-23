<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "GET" || !isset($_GET['product_id'])) {
    header("Location: ./404.php");
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
$model = explode(' - ', $product['ten_sp'])[0];

if (isset($_SESSION['ten_dang_nhap'])) {
    $sql = "select * from Danh_sach_yeu_thich where thanh_vien = '".$_SESSION['ten_dang_nhap']."' and ma_sp = $product_id";
    $result = $conn->query($sql);
    $isFavorite = $result->num_rows > 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="../styles/public/custom.css" rel="stylesheet">
    <link href="../styles/public/product_detail.css" rel="stylesheet">
    <title>Chi tiết sản phẩm</title>
</head>
<body>
    <div class="page-wrapper">
        <header>
            <div class="row bg-primary align-items-center">
                <div class="logo col-lg-3 col-3 text-white d-flex justify-content-center align-items-center ps-3">
                    <a href="./product_list.php" class="text-white text-center">
                        <h1 class="fw-bold">Tech House</h1>
                    </a>
                </div>
                <div class="search-bar col d-flex align-items-center bg-secondary">
                    <input type="text" id="search-input" class="search-input bg-secondary border-0" 
                    placeholder="Tìm kiếm sản phẩm..">
                    <button type="button" class="search-btn border border-0 p-0 m-0"
                    id="search-btn">
                        <img src="../imgs/icons/search.png" alt="search" width="24" height="24">
                    </button>
                </div>
                <div class="login-cart col-lg-3 col-4 d-flex align-items-center justify-content-evenly">
                    <div class="login w-50 d-flex justify-content-center">
                        <?php
                        if (isset($_SESSION['ten_dang_nhap'])) {
                            echo 
                            '<a href="../member/user_info.php" class="fw-bold text-white">
                                <img src="../imgs/icons/user.png" alt="user" width="32" height="32">
                                <span>'.$_SESSION['ho_ten'].'</span></a>';
                            echo '
                            <div class="dropdown-content">
                                <div><a href="../member/user_info.php">Thông tin cá nhân</a></div>
                                <div><a href="../member/order_history_dashboard.php">Lịch sử mua hàng</a></div>
                                <div><a href="../member/cart.php">Giỏ hàng</a></div>
                                <div><a href="./logout.php">Đăng xuất</a></div>
                            </div>';
                        } else {
                            echo 
                            '<a href="./login.php" class="fw-bold text-white">
                                <img src="../imgs/icons/user.png" alt="user" width="32" height="32">
                                <span>Đăng nhập</span>
                            </a>';
                        }
                        ?>
                    </div>
                    <div class="cart w-50 d-flex justify-content-center">
                        <a href="../member/love_list.php" class="fw-bold text-white">
                          <svg
                          xmlns="http://www.w3.org/2000/svg"
                          viewBox="0 0 24 24"
                          width="30"
                          height="30"
                          stroke="white"
                          fill="none"
                          stroke-width="1"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          class="heart-icon me-1"
                          style="cursor: pointer;"
                          >
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                          </svg>
                            <span>Yêu thích</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="tabs row justify-content-between align-items-center bg-white p-3 ps-5 gap-3">
                <div class="tab col">
                    <a href="../index.php">
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
        <main class="p-3" data-product-id="<?php echo $product_id?>" data-product-type="<?php echo $product['phan_loai']?>">
            <div class="main-wrapper px-5">
                <div class="product-detail-wrapper row">
                    <div class="product-preview col-6">
                        <?php
                        echo '<img src="'.$product['hinh_anh'].'" alt="'.$product['ten_sp'].'">';
                        ?>
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
                            <p class="product-name m-0 fs-5" id="product-name">
                                <?php echo $product['ten_sp'];
                                if (isset($_SESSION['ten_dang_nhap'])) {
                                ?>
                                <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                width="20"
                                height="20"
                                <?php
                                if ($isFavorite) {
                                    echo 'fill="red" stroke="red"';
                                } else {
                                    echo 'fill="white" stroke="gray"';
                                }
                                ?>
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="heart-icon m-2"
                                style="cursor: pointer;"
                                id="favorite-icon"
                                >
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                </svg>
                                <?php
                                }
                                ?>
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
                                '<p class="new-price m-0">'.number_format($product['gia_thanh'] * (1 - $product['sale_off']), 0, '.', '.').'đ</p>
                                <p class="old-price m-0">'.number_format($product['gia_thanh'], 0, '.', '.').'đ</p>
                                <div class="discount d-flex align-items-center">
                                    '.$product['sale_off'] * 100 .'% OFF
                                </div>';
                            } else {
                                echo '<p class="new-price m-0">'.number_format($product['gia_thanh'], 0, '.', '.').'đ</p>';
                            }
                            ?>
                        </div>
                        <?php
                        if ($product['phan_loai'] == 1) {
                            $stmt = $conn->prepare("select * from mobile where ma_sp = ?");
                            $stmt->bind_param("i", $product_id);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $spec = $result->fetch_assoc();
                            $stmt->close();
                            $conn->next_result();

                            $stmt = $conn->prepare("select * from mobile m join san_pham s on m.ma_sp = s.ma_sp
                            where m.bo_nho = ? and substring_index(s.ten_sp, ' - ', 1) = ?");
                            $stmt->bind_param("ss", $spec['bo_nho'], $model);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $similarProducts = [];
                            while ($row = $result->fetch_assoc()) {
                                array_push($similarProducts, $row);
                            }
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
                            
                            $stmt = $conn->prepare("select * from tablet t join san_pham s on t.ma_sp = s.ma_sp
                            where s.mau_sac = ? and t.bo_nho = ? and substring_index(s.ten_sp, ' - ', 1) = ?");
                            $stmt->bind_param("sss", $product['mau_sac'], $spec['bo_nho'], $model);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $similarProducts = [];
                            while ($row = $result->fetch_assoc()) {
                                array_push($similarProducts, $row);
                            }
                        } else if ($product['phan_loai'] == 0) {
                            $stmt = $conn->prepare("select * from laptop where ma_sp = ?");
                            $stmt->bind_param("i", $product_id);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $spec = $result->fetch_assoc();
                            $stmt->close();
                            $conn->next_result();

                            $stmt = $conn->prepare("select * from laptop l join san_pham s on l.ma_sp = s.ma_sp 
                            where l.ram = ? and l.bo_nho = ? and substring_index(ten_sp, ' - ', 1) = ?");
                            $stmt->bind_param("sss", $spec['ram'], $spec['bo_nho'], $model);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $similarProducts = [];
                            while ($row = $result->fetch_assoc()) {
                                array_push($similarProducts, $row);
                            }
                        }
                        ?>
                        <div class="form">
                            <div class="colors-wrapper row">
                                <p class="m-0 mb-2">Màu</p>
                                <div class="colors d-flex gap-3">
                                    <?php
                                    if ($product['phan_loai'] == 1 || $product['phan_loai'] == 2) {
                                        foreach ($similarProducts as $product) {
                                            echo 
                                            '<a href="./product_detail.php?product_id='.$product['ma_sp'].'">
                                                <div class="color '.strtolower($product['mau_sac']).'"></div>
                                            </a>';
                                        }
                                    } else {
                                        echo 
                                        '<a href="./product_detail.php?product_id='.$product_id.'">
                                            <div class="color '.strtolower($product['mau_sac']).'"></div>
                                        </a>';
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
                                        <div class="specification-wrapper">'.$spec['bo_nho'].'</div>';
                                    echo '</div>';
                                } else if ($product['phan_loai'] == 0) {
                                    echo 
                                    '<div class="ram col-6">
                                        <p class="m-0 mb-2">RAM</p>
                                        <div class="specification-wrapper">'.$spec['ram'].'</div>';
                                    echo '</div>';
                                    echo
                                    '<div class="memory col-6">
                                        <p class="m-0 mb-2">Dung lượng</p>
                                        <div class="specification-wrapper">'.$spec['bo_nho'].'</div>';
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="buttons d-flex gap-3">
                            <div class="quantity-wrapper col d-flex 
                            align-items-center justify-content-between
                            mx-auto">
                                <button class="btn" id="decrement">-</button>
                                <span class="text-center m-0" id="quantity"
                                style="vertical-align: middle; align-content: center;">1</span>
                                <button class="btn" id="increment">+</button>
                            </div>
                            <button class="btn btn-primary col 
                            fw-bold text-uppercase" id="add-to-cart">
                            Thêm vào giỏ hàng</button>
                            <button class="buy-now-btn btn 
                            col fw-bold text-uppercase" id="buynow-btn">
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
                                        
                                    } else if ($product['phan_loai'] == 2) {
                                        $stmt = $conn->prepare("select * from tablet where ma_sp = ?");
                                    } else if ($product['phan_loai'] == 0) {
                                        $stmt = $conn->prepare("select * from laptop where ma_sp = ?");
                                    } else if ($product['phan_loai'] == 3) {
                                        $stmt = $conn->prepare("select * from tai_nghe_bluetooth where ma_sp = ?");
                                    } else if ($product['phan_loai'] == 4) {
                                        $stmt = $conn->prepare("select * from ban_phim where ma_sp = ?");
                                    } else if ($product['phan_loai'] == 5) {
                                        $stmt = $conn->prepare("select * from sac_du_phong where ma_sp = ?");
                                    } else {
                                        $stmt = $conn->prepare("select * from op_lung where ma_sp = ?");
                                    }
                                    $stmt->bind_param("i", $product_id);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $spec = $result->fetch_assoc();
                                    $stmt->close();
                                    $conn->next_result();

                                    switch($product['phan_loai']):
                                        case 0:
                                            echo '<tr><td>Chip</td> <td>'.$spec['bo_xu_ly'].'</td></tr>';
                                            echo '<tr><td>Kích thước màn hình</td> <td>'.$spec['kich_thuoc_man_hinh'].'</td></tr>';
                                            echo '<tr><td>Công nghệ màn hình</td> <td>'.$spec['cong_nghe_man_hinh'].'</td></tr>';
                                            echo '<tr><td>Pin</td> <td>'.$spec['dung_luong_pin'].'</td></tr>';
                                            echo '<tr><td>Hệ điều hành</td> <td>'.$spec['he_dieu_hanh'].'</td></tr>';
                                            echo '<tr><td>Ram</td> <td>'.$spec['ram'].'</td></tr>';
                                            echo '<tr><td>Bộ nhớ</td> <td>'.$spec['bo_nho'].'</td></tr>';
                                            break;
                                        case 1:
                                        case 2:
                                            echo '<tr><td>Chip</td> <td>'.$spec['bo_xu_ly'].'</td></tr>';
                                            echo '<tr><td>Kích thước màn hình</td> <td>'.$spec['kich_thuoc_man_hinh'].'</td></tr>';
                                            echo '<tr><td>Công nghệ màn hình</td> <td>'.$spec['cong_nghe_man_hinh'].'</td></tr>';
                                            echo '<tr><td>Pin</td> <td>'.$spec['dung_luong_pin'].'</td></tr>';
                                            echo '<tr><td>Hệ điều hành</td> <td>'.$spec['he_dieu_hanh'].'</td></tr>';
                                            break;
                                        case 3:
                                            echo '<tr><td>Phạm vi</td> <td>'.$spec['pham_vi_ket_noi'].'</td></tr>';
                                            echo '<tr><td>Pin</td> <td>'.$spec['thoi_luong_pin'].'</td></tr>';
                                            echo '<tr><td>Chống nước</td> <td>'.$spec['chong_nuoc'].'</td></tr>';
                                            echo '<tr><td>Công nghệ âm thanh</td> <td>'.$spec['cong_nghe_am_thanh'].'</td></tr>';
                                            break;
                                        case 4:
                                            echo '<tr><td>Key cap</td> <td>'.$spec['key_cap'].'</td></tr>';
                                            echo '<tr><td>Số phím</td> <td>'.$spec['so_phim'].'</td></tr>';
                                            echo '<tr><td>Kết nối</td> <td>'.$spec['cong_ket_noi'].'</td></tr>';
                                            break;
                                        case 5:
                                            echo '<tr><td>Pin</td> <td>'.$spec['dung_luong_pin'].'</td></tr>';
                                            echo '<tr><td>Công suất</td> <td>'.$spec['cong_suat'].'</td></tr>';
                                            echo '<tr><td>Kết nối</td> <td>'.$spec['cong_ket_noi'].'</td></tr>';
                                            echo '<tr><td>Chất liệu</td> <td>'.$spec['chat_lieu'].'</td></tr>';
                                            break;
                                        case 6:
                                            echo '<tr><td>Chất liệu</td> <td>'.$spec['chat_lieu'].'</td></tr>';
                                            echo '<tr><td>Độ dày</td> <td>'.$spec['do_day'].'</td></tr>';
                                            break;
                                        default:
                                            break;
                                    endswitch;
                                    ?>
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
                                '<a class="product d-flex justify-content-evenly align-items-center mx-2 py-1 px-2" 
                                href="./product_detail.php?product_id='.$product['ma_sp'].'">
                                    <img src="'.$product['hinh_anh'].'" alt="'.$product['ten_sp'].'">
                                    <div class="content d-flex flex-column gap-3 overflow-hidden ms-1">
                                        <p class="m-0">'.$product['ten_sp'].'</p>
                                        <p class="price m-0">'.number_format($product['gia_thanh'] * (1 - $product['sale_off']), 0, '.', '.').'đ</p>
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
            <div class="row justify-content-evenly infomations">
                <div class="contact col-sm-3 col-7 pt-sm-4">
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
                <div class="category col-sm-4 col-5">
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
                <div class="other-info col-sm-4 col-5">
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="../node_modules/jquery/dist/jquery.min.js"></script>
<script src="../scripts/public/search.js"></script>
<script>
    const productInfoTabs = $('.product-info-tab');
    const descriptionContent = $('.description-content');
    const specificationContent = $('.specification-content');
    const rateContent = $('.rate-content');

    productInfoTabs.each(function() {
        $(this).on('click', function() {
            productInfoTabs.removeClass('selected');

            $(this).addClass('selected');

            if ($(this).text() === 'Mô tả sản phẩm') {
                descriptionContent.removeClass('d-none');
                specificationContent.addClass('d-none');
                rateContent.addClass('d-none');
            } else if ($(this).text() === 'Thông số kỹ thuật') {
                descriptionContent.addClass('d-none');
                specificationContent.removeClass('d-none');
                rateContent.addClass('d-none');
            } else {
                descriptionContent.addClass('d-none');
                specificationContent.addClass('d-none');
                rateContent.removeClass('d-none');
            }
        })
    })

    //quantity button
    $('#decrement').click(() => {
        let quantity = +$('#quantity').text();
        if (quantity > 1) {
            $('#quantity').text(quantity - 1);
        }
    });

    $('#increment').click(() => {
        let quantity = +$('#quantity').text();
        $('#quantity').text(quantity + 1);
    });

    //add to cart
    $("#add-to-cart").click((e) => {
        e.preventDefault();
        const productName = $("#product-name").text().split('-')[0].trim();
        const productId = $("main").attr('data-product-id');
        const productType = $("main").attr('data-product-type');
        const color = $(".color-input:checked").val();
        const memory = $("#memory").val();
        const ram = $("#ram").val() ?? 'nan';
        const quantity = +$("#quantity").text();
        console.log(productName, productId, productType, color, memory, ram, quantity);
        $.ajax({
            url: "../member/add_to_cart.php",
            type: "POST",
            data: {
                product_id: productId,
                quantity: quantity
            },
            success: (response) => {
                if (response == 'Chưa đăng nhập') {
                    window.location.href = './login.php';
                } else if (response == 'Thêm vào giỏ hàng thành công') {
                    $('#add-to-cart').animate({left: '+=20px'}, 100, () => {
                        $('#add-to-cart').animate({left: '-=40px'}, 100, () => {
                            $('#add-to-cart').animate({left: '+=20px'}, 100);
                        });
                    });
                } else {
                    alert('Thêm vào giỏ hàng thất bại');
                }
            }
        });
    })

    $('#buynow-btn').click(() => {
        let productId = <?php echo $product_id; ?>;
        let quantity = $('#quantity').text();
        console.log(quantity);
        window.location.href = `../member/checkout.php?product_id=${productId}&quantity=${quantity}&from_product=1`;
    })

    //pagination
    const ratePerPage = 5;
    const paginationLength = 3;
    const pagination = $('.pagination');
    const pageNumbers = $('.page-number');
    const rates = $('.rates-wrapper .rate');
    let currentPage = 1;

    function displayRates() {
        rates.each(function(index, rate) {
            if (index >= (currentPage - 1) * ratePerPage && index < currentPage * ratePerPage) {
                $(rate).removeClass('d-none');
            } else {
                $(rate).addClass('d-none');
            }
        })
    }

    function updatePagination() {
        const totalPages = Math.ceil(rates.length / ratePerPage);
        
        if (totalPages == 1) {
            pagination.addClass('d-none');
            return;
        }

        pagination.empty();

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
            const page = $('<button type="button"></button>').text(i).addClass('btn page-number');

            if (i == currentPage) {
                page.addClass('active');
            }

            page.on('click', (e) => {
                e.preventDefault();
                currentPage = i;
                displayRates();
                updatePagination();
            });

            pagination.append(page);
        }

        pagination.removeClass('d-none');
    }

    displayRates();
    updatePagination();

    $('#favorite-icon').click(() => {
        let fillAttr = $('#favorite-icon').attr('fill');
        let add = fillAttr == 'white';
        $.ajax({
            url: '../member/toggle_favorite.php',
            type: 'POST',
            data: {
                product_id: <?php echo $product_id; ?>,
                add: add
            },
            success: (response) => {
                if (response == "Thêm sản phẩm vào yêu thích thành công") {
                    $('#favorite-icon').attr('fill', 'red');
                    $('#favorite-icon').attr('stroke', 'red');
                } else if (response == "Xóa sản phẩm khỏi yêu thích thành công") {
                    $('#favorite-icon').attr('fill', 'white');
                    $('#favorite-icon').attr('stroke', 'gray');
                }
            }
        });
    })
</script>
</body>
</html>
<?php
$conn->close();
?>