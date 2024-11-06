<?php
session_start();

if (!isset($_SESSION['ten_dang_nhap']) || $_SESSION['phan_loai_tk'] != 'tv') {
    header('Location: ../public/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET' || !isset($_GET['username'])) {
    //error 404
    header('Location: ../public/404.html');
    exit();
    
}

$conn = new mysqli('localhost', 'root', '', 'tech_house_db');

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$username = $_GET['username'];
$stmt = $conn->prepare('select d.*, count(c.ma_sp) as so_luong_sp 
from don_hang d join chi_tiet_don_hang c on d.ma_don_hang = c.ma_don_hang 
where thanh_vien = ? group by c.ma_don_hang;');
$stmt->bind_param('s', $username);
$stmt->execute();
$orders = $stmt->get_result();

if ($orders->num_rows === 0) {
    $orders = [];
} else {
    $orders = $orders->fetch_all(MYSQLI_ASSOC);
}

$stmt = $conn->prepare('select * from tai_khoan where ten_dang_nhap = ?');
$stmt->bind_param('s', $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="../styles/public/custom.css" rel="stylesheet">
    <link href="../styles/member/order_history_dashboard.css" rel="stylesheet">
    <title>Người dùng</title>
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
                            '<a href="#" class="fw-bold text-white">
                                <img src="../imgs/icons/user.png" alt="user" width="32" height="32">
                                '.$_SESSION['ho_ten'].'</a>';
                            echo '
                            <div class="dropdown-content">
                                <div><a href="./user_info.php">Thông tin cá nhân</a></div>
                                <div><a href="./change_password.html">Đổi mật khẩu</a></div>
                                <div><a href="./order_history_dashboard.php?username='.$_SESSION['ten_dang_nhap'].'">Lịch sử mua hàng</a></div>
                                <div><a href="./logout.php">Đăng xuất</a></div>
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
                    <a href="../public/product_list.php">
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
        <main class="pb-3">
            <div class="hello ps-3 pe-5 fw-bold container-fluid">
                <div class="d-flex justify-content-between align-items-center">
                    <h3>
                        <?php
                        echo 'Xin chào, '.$_SESSION['ho_ten'];
                        ?>
                    </h3>
                    <div class="search-order ms-auto">
                        <input type="text" class="search-order-input" placeholder="Tìm kiếm đơn hàng..." id="search-order-btn">
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-3">
                <div class="d-flex">
                    <nav class="col-md-2 sidebar d-none d-md-block">
                        <div class="sidebar-sticky">
                            <ul class="nav border rounded-3 flex-column">
                                <li class="nav-item">
                                    <a href="./user_info.php">
                                        <img src="../imgs/icons/setting.png" alt="setting" width="22" height="22">
                                        Thông tin cá nhân
                                    </a>
                                </li>
                                <li class="nav-item active">
                                    <a href="order_history_dashboard.html">
                                        <img src="../imgs/icons/order_history.png" alt="order_history" width="22" height="22">
                                        Lịch sử mua hàng
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#">
                                        <img src="../imgs/icons/shopping_cart.png" alt="shopping_cart" width="22" height="22">
                                        Giỏ hàng
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#">
                                        <img src="../imgs/icons/log-out.png" alt="log-out" width="22" height="22">
                                        Đăng xuất
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                    <div class="col-md-10 ml-sm-auto px-4">
                        <div class="card p-3 border rounded-3">
                            <div class="row fw-bold border-bottom pb-2">
                                <div class="col">LỊCH SỬ MUA HÀNG</div>
                            </div>
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th class="w-20">Mã đơn hàng</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày mua</th>
                                        <th>Tổng tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (count($orders) === 0) {
                                        echo '<tr><td colspan="4" class="text-center text-gray">Bạn chưa có đơn hàng nào</td></tr>';
                                    } else {
                                        foreach ($orders as $order) {
                                            echo '<tr class="order" order-id="'.$order['ma_don_hang'].'" 
                                            order-time="'.$order['thoi_diem_dat_hang'].'"';
                                            switch ($order['tinh_trang']) {
                                                case 0://chờ xác nhận
                                                    echo 'order-status="chờ xác nhận"';
                                                    break;
                                                case 1://đã xác nhận
                                                    echo 'order-status="đã xác nhận"';
                                                    break;
                                                case 2://đang giao
                                                    echo 'order-status="đang giao"';
                                                    break;
                                                case 3://đã giao
                                                    echo 'order-status="đã hoàn thành"';
                                                    break;
                                                case 4://đã hủy
                                                    echo 'order-status="đã hủy"';
                                                    break;
                                            }
                                            echo '>';
                                            echo '<td class="ps-3"><a href="./order_history_detail.php?order_id='.$order['ma_don_hang'].'">'.$order['ma_don_hang'].'</a></td>';
                                            switch ($order['tinh_trang']) {
                                                case 0://chờ xác nhận
                                                    echo '<td><span class="text-secondary">Chờ xác nhận</span></td>';
                                                    break;
                                                case 1://đã xác nhận
                                                    echo '<td><span class="text-info">Đã xác nhận</span></td>';
                                                    break;
                                                case 2://đang giao
                                                    echo '<td><span class="text-warning">Đang giao</span></td>';
                                                    break;
                                                case 3://đã giao
                                                    echo '<td><span class="text-success">Đã hoàn thành</span></td>';
                                                    break;
                                                case 4://đã hủy
                                                    echo '<td><span class="text-danger">Đã hủy</span></td>';
                                                    break;
                                            }
                                            echo '<td>'.$order['thoi_diem_dat_hang'].'</td>';
                                            echo '<td>'.number_format($order['tong_gia'], 0, ",", ".").' VND ('.$order['so_luong_sp'].' sản phẩm)</td>';
                                            echo '</tr>';
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="pagination mt-3 d-none">
                            <div class="page-numbers d-flex justify-content-center gap-2">
                                <a href="#" class="page-number">01</a>
                                <a href="#" class="page-number">02</a>
                                <a href="#" class="page-number">03</a>
                                <a href="#" class="page-number">04</a>
                                <a href="#" class="page-number">05</a>
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
    const ordersPerPage = 5;
    const paginationLength = 5;
    let orders = document.querySelectorAll('.order');
    const oldOrders = [...orders];

    ///pagination
    const pagination = document.querySelector('.pagination');
    const pageNumbers = document.querySelector('.page-numbers');
    const numberOfPages = Math.ceil(orders.length / ordersPerPage);
    let currentPage = 1;
    
    function displayOrders() {
        let orders = document.querySelectorAll('.order');
        orders.forEach((order, index) => {
            const start = (currentPage - 1) * ordersPerPage;
            const end = start + ordersPerPage;
            if (index >= start && index < end) {
                order.style.display = '';
            } else {
                order.style.display = 'none';
            }
        });
    }

    function displayPagination() {
        const totalPages = Math.ceil(orders.length / ordersPerPage);

        if (totalPages == 1) {
            pagination.classList.add('d-none');
            return;
        }

        pageNumbers.innerHTML = '';

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
            const pageNumber = document.createElement('a');
            pageNumber.href = '#';
            pageNumber.classList.add('page-number');
            pageNumber.textContent = i;
            if (i === currentPage) {
                pageNumber.classList.add('active');
            }

            pageNumber.addEventListener('click', (e) => {
                e.preventDefault();
                currentPage = i;
                displayOrders();
                displayPagination();
            });

            pageNumbers.appendChild(pageNumber);
        }

        pagination.classList.remove('d-none');
    }

    
    ///search
    $("#search-order-btn").on('input', function() {
        const searchValue = $(this).val().toLowerCase();
        console.log(searchValue);

        if (searchValue === '') {
            orders = [...oldOrders];
            currentPage = 1;
            $('tbody').empty();
            orders.forEach(order => {
                $('tbody').append(order);
            });

            displayOrders();
            displayPagination();
            return;
        }
        
        orders = [];
        oldOrders.forEach(order => {
            const orderId = order.getAttribute('order-id');
            const orderTime = order.getAttribute('order-time');
            const orderStatus = order.getAttribute('order-status');

            if (orderId.includes(searchValue) || orderTime.includes(searchValue) || orderStatus.includes(searchValue)) {
                orders = [...orders, order];
            }
        });

        currentPage = 1;
        $('tbody').empty();
        orders.forEach(order => {
            $('tbody').append(order);
        });

        displayOrders();
        displayPagination();
    });

    displayOrders();
    displayPagination();
</script>
</html>
<?php
$conn->close();
?>