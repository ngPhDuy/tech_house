<?php
session_start();

if (!isset($_SESSION['ten_dang_nhap']) || $_SESSION['phan_loai_tk'] != 'tv') {
    header('Location: ../public/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET' || !isset($_GET['order_id'])) {
    //error 404
    header('Location: ../public/404.html');
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'tech_house_db');

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$order_id = $_GET['order_id'];

$stmt = "select * from don_hang where ma_don_hang = $order_id";
$result = $conn->query($stmt);
$order = $result->fetch_assoc();

$stmt = "select * from chi_tiet_don_hang c join san_pham s on c.ma_sp = s.ma_sp 
where ma_don_hang = $order_id";
$result = $conn->query($stmt);
$order_details = $result->fetch_all(MYSQLI_ASSOC);

$stmt = "select dg.ma_sp from danh_gia dg where dg.ma_dh = $order_id";
$result = $conn->query($stmt);
$ratings = $result->fetch_all(MYSQLI_ASSOC);
$ratings = array_column($ratings, 'ma_sp');

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="../styles/public/custom.css" rel="stylesheet">
    <link href="../styles/member/order_history_detail.css" rel="stylesheet">
    <title>Chi tiết đơn hàng</title>
</head>
<body>
    <div class="page-wrapper">
        <header>
            <div class="row bg-primary align-items-center">
                <div class="logo col-lg-3 col-3 text-white d-flex justify-content-center align-items-center ps-3">
                    <a href="../public/product_list.php" class="text-white text-center">
                        <h1 class="fw-bold">Tech House</h1>
                    </a>
                </div>
                <div class="search-bar col d-flex align-items-center bg-secondary">
                    <img src="../imgs/icons/search.png" alt="search" width="24" height="24">
                    <input type="text" id="search-input" class="search-input bg-secondary border-0" 
                    placeholder="Tìm kiếm sản phẩm.." link-to="../public/product_list.php">
                </div>
                <div class="login-cart col-lg-3 col-4 d-flex align-items-center justify-content-evenly">
                    <div class="login w-50">
                        <?php
                        if (isset($_SESSION['ten_dang_nhap'])) {
                            echo 
                            '<a href="./user_info.php" class="fw-bold text-white">
                                <img src="../imgs/icons/user.png" alt="user" width="32" height="32">
                                '.$_SESSION['ho_ten'].'</a>';
                            echo '
                            <div class="dropdown-content">
                                <div><a href="./user_info.php">Thông tin cá nhân</a></div>
                                <div><a href="./change_password.html">Đổi mật khẩu</a></div>
                                <div><a href="./order_history_dashboard.php">Lịch sử mua hàng</a></div>
                                <div><a href="../public/logout.php">Đăng xuất</a></div>
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
                        <a href="./love_list.php" class="fw-bold text-white">
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
                            Yêu thích
                        </a>
                    </div>
                </div>
            </div>
            <div class="tabs row justify-content-between align-items-center bg-white p-3 ps-5">
                <div class="tab col">
                    <a href="../index.php">
                        <img src="../imgs/icons/house.png" alt="home" width="24" height="24">
                        Trang chủ
                    </a>
                </div>
                <div class="tab col">
                    <a href="../public/product_list.php?product_type=1">
                        <img src="../imgs/icons/phone_iphone.png" alt="phone" width="24" height="24">
                        Điện thoại
                    </a>
                </div>  
                <div class="tab col">
                    <a href="../public/product_list.php?product_type=0">
                        <img src="../imgs/icons/laptop_mac.png" alt="laptop" width="24" height="24">
                        Laptop
                    </a>
                </div>
                <div class="tab col">
                    <a href="../public/product_list.php?product_type=2">
                        <img src="../imgs/icons/tablet_android.png" alt="tablet" width="24" height="24">
                        Tablet
                    </a>
                </div>
                <div class="tab col">
                    <a href="../public/product_list.php?product_type=3">
                        <img src="../imgs/icons/gamepad.png" alt="other" width="24" height="24">
                        Phụ kiện
                        <img src="../imgs/icons/keyboard_arrow_down.png" alt="arrow-down" width="24" height="24">
                    </a>
                    <div class="dropdown-content">
                        <div><a href="../public/product_list.php?product_type=3">Tai nghe</a></div>
                        <div><a href="../public/product_list.php?product_type=4">Bàn phím</a></div>
                        <div><a href="../public/product_list.php?product_type=5">Sạc dự phòng</a></div>
                        <div><a href="../public/product_list.php?product_type=6">Ốp lưng</a></div>
                    </div>
                </div>
                <div class="col-4"></div>
            </div>
        </header>
        <main>
            <div class="hello ps-3 fw-bold">
                <h3>
                    <?php
                    echo 'Xin chào, '.$_SESSION['ho_ten'];
                    ?>
                </h3>
            </div>
            <div class="container-fluid mt-3">
                <div class="d-flex">
                    <nav class="col-md-2 sidebar d-none d-md-block">
                        <div class="sidebar-sticky">
                            <ul class="nav border rounded-3 flex-column">
                                <li class="nav-item">
                                    <a href="user_info.php">
                                        <!-- <img src="../imgs/icons/setting_white.png" alt="setting" width="22" height="22"> -->
                                        <span class="setting-icon"></span>
                                        <span>Thông tin cá nhân</span>
                                    </a>
                                </li>
                                <li class="nav-item active">
                                    <a href="./order_history_dashboard.php">
                                        <img src="../imgs/icons/order_history_white.png" alt="order_history" width="22" height="22">
                                        <span>Lịch sử mua hàng</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./cart.php">
                                        <!-- <img src="../imgs/icons/shopping_cart.png" alt="shopping_cart" width="22" height="22"> -->
                                        <span class="cart-icon"></span>
                                        <span>Giỏ hàng</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./logout.php">
                                        <!-- <img src="../imgs/icons/log-out.png" alt="log-out" width="22" height="22"> -->
                                        <span class="log-out-icon"></span>
                                        <span>Đăng xuất</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                    <div class="col-md-9 ml-sm-auto col-lg-10 px-4">
                        <div class="container mb-5 container-order">
                            <div class="card p-3 border border-secondary rounded-3">
                                <div class="row border-bottom pb-2">
                                    <span class="col-3">
                                        <a href="#" onclick="window.history.back(); return false;" class="order-details-back text-decoration-none">&larr;</a>
                                        CHI TIẾT ĐƠN HÀNG
                                    </span>
                                </div>
                                <div>
                                    <div class="d-flex justify-content-between align-items-center mt-3 order-summary">
                                        <div>
                                            <h5>
                                                <?php
                                                echo 'Đơn hàng #'.$order['ma_don_hang'];
                                                ?>
                                            </h5>
                                            <p>Tạo ngày 
                                                <?php
                                                //17/03/2024, 08:00
                                                //original format: 2024-03-17 08:00:00
                                                echo date('d/m/Y, H:i', strtotime($order['thoi_diem_dat_hang']));
                                                ?>
                                            </p>
                                            <?php
                                            switch($order['tinh_trang']) {
                                                case 0:
                                                    echo '<span class="badge bg-primary">Đang chờ xác nhận</span>';
                                                    break;
                                                case 1:
                                                    echo '<span class="badge bg-info">Đã xác nhận</span>';
                                                    break;
                                                case 2:
                                                    echo '<span class="badge bg-warning">Đang giao hàng</span>';
                                                    break;
                                                case 3:
                                                    echo '<span class="badge bg-success">Đã giao hàng</span>';
                                                    break;
                                                case 4:
                                                    echo '<span class="badge bg-danger">Đã hủy</span>';
                                                    break;
                                            }
                                            ?>
                                        </div>
                                        <div class="order-price">
                                            <?php
                                            echo number_format($order['tong_gia'], 0, '', '.').' VND';
                                            ?>
                                        </div>
                                    </div>
                                    <div class="progress-container mt-4 d-none d-md-flex">
                                        <div class="progress-track">
                                            <div class="step active">
                                                <span class="checkmark">&#10003;</span>
                                            </div>
                                            <?php
                                            if ($order['tinh_trang'] > 0 && $order['tinh_trang'] != 4) {
                                                echo '<div class="line active"></div>';
                                                echo '<div class="step active">';
                                                echo '<span class="checkmark">&#10003;</span>';
                                                echo '</div>';
                                            } else {
                                                echo '<div class="line"></div>';
                                                echo '<div class="step"></div>';
                                            }

                                            if ($order['tinh_trang'] > 1 && $order['tinh_trang'] != 4) {
                                                echo '<div class="line active"></div>';
                                                echo '<div class="step active">';
                                                echo '<span class="checkmark">&#10003;</span>';
                                                echo '</div>';
                                            } else {
                                                echo '<div class="line"></div>';
                                                echo '<div class="step"></div>';
                                            }

                                            if ($order['tinh_trang'] > 2 && $order['tinh_trang'] != 4) {
                                                echo '<div class="line active"></div>';
                                                echo '<div class="step active">';
                                                echo '<span class="checkmark">&#10003;</span>';
                                                echo '</div>';
                                            } else {
                                                echo '<div class="line"></div>';
                                                echo '<div class="step"></div>';
                                            }
                                            ?>
                                        </div>
                                        <div class="progress-labels">
                                            <div class="label">
                                                <img src="../imgs/icons/order_placed.png" alt="Order placed" width="22" height="22">
                                                <div class="title">Order placed</div>
                                            </div>
                                            <div class="label">
                                                <img src="../imgs/icons/packaging.png" alt="Packaging" width="22" height="22">
                                                <div class="title">Packaging</div>
                                            </div>
                                            <div class="label">
                                                <img src="../imgs/icons/on_the_road.png" alt="On the road" width="22" height="22">
                                                <div class="title">On The Road</div>
                                            </div>
                                            <div class="label">
                                                <img src="../imgs/icons/delivered.png" alt="Delivered" width="22" height="22">
                                                <div class="title">Delivered</div>
                                            </div>
                                        </div>
                                    </div>      
                                </div>
                                <h6 class="mt-4">Sản phẩm:</h6>
                                <table class="table table-borderless">
                                    <thead>
                                        <tr>
                                            <th class="w-50"></th>
                                            <th class="col w-20">Đơn giá (VND)</th>
                                            <th class="col w-10">SL</th>
                                            <th class="col w-10">Tổng (VND)</th>
                                            <?php
                                            if ($order['tinh_trang'] == 3) {
                                                echo '<th class="col w-10"></th>';
                                            }
                                            ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($order_details as $order_detail) {
                                            echo '<tr>';
                                            echo '<td>';
                                            echo '<a class="d-flex" href="../public/product_detail.php?product_id='.$order_detail['ma_sp'].'">';
                                            echo '<img src="'.$order_detail['hinh_anh'].'" class="product-image me-3 d-none d-sm-block" alt="Product Image" width="80" height="80">';
                                            echo '<div>';
                                            echo '<p class="mb-1 product-name">ĐIỆN THOẠI</p>';
                                            echo '<p class="product-description">'.$order_detail['ten_sp'].'</p>';
                                            echo '</div>';
                                            echo '</a>';
                                            echo '</td>';
                                            echo '<td>'.number_format($order_detail['don_gia'], 0, '', '.').'</td>';
                                            echo '<td>'.$order_detail['so_luong'].'</td>';
                                            echo '<td>'.number_format($order_detail['don_gia'] * $order_detail['so_luong'], 0, '', '.').'</td>';
                                            if ($order['tinh_trang'] == 3) {
                                                if (in_array($order_detail['ma_sp'], $ratings)) {
                                                    echo '<td></td>';
                                                } else {
                                                    echo '<td><button type="button" class="btn btn-primary rate-btn" 
                                                    product-name="'.$order_detail['ten_sp'].'"
                                                    product-id="'.$order_detail['ma_sp'].'"
                                                    product-img="'.$order_detail['hinh_anh'].'">Đánh giá</button></td>';
                                                }
                                            }
                                            echo '</tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <?php
                                if ($order['tinh_trang'] == 0) {
                                    echo '<div class="d-flex justify-content-end mt-3">';
                                    echo '<button data-id="'.$_GET['order_id'].'" class="btn btn-danger text-white fw-bold" id="cancel-btn">HỦY ĐƠN HÀNG</button>';
                                    echo '</div>';
                                }
                                ?>
                                </div>
                            </div>
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
        <div class="modal fade" id="rateModal" tabindex="-1" aria-labelledby="rateModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rateModalLabel">Đánh giá sản phẩm</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="d-flex flex-column justify-content-center align-items-center">
                            <div class="mb-3 d-flex justify-content-center flex-column align-items-center">
                                <p class="m-0 mb-3 text-center fw-bold" id="rate-title"></p>
                                <img src="../imgs/products/Google_Pixel_6_Pro.png" alt="Product Image" 
                                width="80" height="80" class="d-block mx-auto mb-3">
                                <select id="rating" class="form-select">
                                    <option value="5">5 sao</option>
                                    <option value="4">4 sao</option>
                                    <option value="3">3 sao</option>
                                    <option value="2">2 sao</option>
                                    <option value="1">1 sao</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="feedback" class="form-label">Nội dung đánh giá</label>
                                <textarea id="feedback" class="form-control feedback-box" placeholder="Write down your feedback about our product & services"></textarea>
                            </div>
                            <button type="button" class="btn btn-primary" id="submit-rating-btn">GỬI ĐÁNH GIÁ</button>
                        </form>
                    </div>
                </div>
            </div> 
        </div>
        <div class="modal" id="cancel-modal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Xác nhận hủy đơn hàng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="m-0">Bạn có chắc chắn muốn hủy đơn hàng này?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="button" class="btn btn-danger" id="confirm-cancel-btn">Hủy đơn hàng</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" id="message-modal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <p class="m-0 fs-5 text-center p-3"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="../node_modules/jquery/dist/jquery.min.js"></script>
<script src="../scripts/search.js"></script>
<script>
    const rateBtns = document.querySelectorAll('.rate-btn');
    
    rateBtns.forEach(rateBtn => {
        rateBtn.addEventListener('click', () => {
            const productName = rateBtn.getAttribute('product-name');
            const productId = rateBtn.getAttribute('product-id');
            const productImg = rateBtn.getAttribute('product-img');

            $('#rate-title').text(productName);
            $('#rateModal img').attr('src', productImg);
            $('#rateModal').attr('data-id', productId);
            $('#rateModal').modal('show');
        });
    });

    $('#submit-rating-btn').click(() => {
        const productId = $('#rateModal').attr('data-id');
        const rating = $('#rating').val();
        const feedback = $('#feedback').val();

        $.ajax({
            url: './rate_product.php',
            type: 'POST',
            data: {
                product_id: productId,
                rating: rating,
                feedback: feedback,
                order_id: <?php echo $_GET['order_id']; ?>,
                username: '<?php echo $_SESSION['ten_dang_nhap']; ?>'
            },
            success: function(response) {
                if (response == 'Đánh giá thành công') {
                    $('#message-modal .modal-body p').text(response);
                    $('#message-modal .modal-body p').css('color', 'green');
                    $('#message-modal').modal('show');
                    $('#rateModal').modal('hide');
                    $(`button[product-id=${productId}]`).parent().html('');
                } else {
                    $('#message-modal .modal-body p').text(response);
                    $('#message-modal .modal-body p').css('color', 'red');
                    $('#message-modal').modal('show');
                    $('#rateModal').modal('hide');
                }
            }
        });
    })

    $('#cancel-btn').click(function() {
        $('#cancel-modal').modal('show');
    });

    $("#confirm-cancel-btn").click(function() {
        const orderId = $('#cancel-btn').attr('data-id');
        $.ajax({
            url: '../member/cancel_order.php',
            type: 'POST',
            data: {order_id: orderId},
            success: function(response) {
                if (response = "Hủy đơn hàng thành công") {
                    $('#message-modal .modal-body p').text(response);
                    $('#message-modal .modal-body p').css('color', 'green');
                    $('#message-modal').modal('show');
                    $('#cancel-modal').modal('hide');
                    setTimeout(() => {
                        //reload
                        location.reload();
                    }, 700);
                } else {
                    $('#message-modal .modal-body p').text(response);
                    $('#message-modal .modal-body p').css('color', 'red');
                    $('#message-modal').modal('show');
                    $('#cancel-modal').modal('hide');
                }
            }
        });
    });
</script>
</html>