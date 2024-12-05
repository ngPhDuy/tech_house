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

$order_id = $_GET['order_id'];
$stmt = $conn->prepare('select * from don_hang dh
join tai_khoan tk on dh.thanh_vien = tk.ten_dang_nhap
where dh.ma_don_hang = ?');
$stmt->bind_param('s', $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if ($order == null) {
    header('Location: orders.php');
    exit();
}

$stmt = $conn->prepare('select * from chi_tiet_don_hang ctdh 
join san_pham sp on ctdh.ma_sp = sp.ma_sp where ctdh.ma_don_hang = ?');
$stmt->bind_param('s', $order_id);
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);

$stmt = $conn->prepare('select * from tai_khoan join nhan_vien on tai_khoan.ten_dang_nhap = nhan_vien.ten_dang_nhap where tai_khoan.ten_dang_nhap = ?');
$stmt->bind_param('s', $_SESSION['ten_dang_nhap']);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$stmt->close();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech house</title>

    <link rel="stylesheet" href="../styles/admin/order_detail.css">
    <link rel="stylesheet" href="../styles/admin/layout.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Nunito+Sans' rel='stylesheet'>
</head>

<body>
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
        <a href="./products.php">
            <div>
                <span>
                    Sản phẩm
                </span>
            </div>
        </a>
        <a href="./orders.php"  class="nav-active">
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
        <div id="left_section">
            <div id="hamburger-menu" class="d-block d-md-none">
                <button class="btn" type="button">
                    <i class="fa fa-bars"></i>
                </button>
            </div>
        </div>

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
                            <?php
                            if ($row['avatar'] == NULL) {
                                echo '<img id="profile_avatar" src="../imgs/avatars/default.png" alt="avatar">';
                            } else {
                                echo '<img id="profile_avatar" src="../imgs/avatars/' . $row['avatar'] . '" alt="avatar">';
                            }
                            ?>
                            <div id="profile_text" class="ms-3">
                                <div id="profile_name">
                                    <?php
                                    echo $_SESSION['ho_ten'];
                                    ?>
                                </div>
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

    <div id="body_section">

        <div id="main_wrapper" class="px-3">
            <div class="h3 mb-3">Thông tin đơn hàng</div>

            <div class="product-info d-flex flex-column gap-3">
                <div class="info-wrapper">
                    <div class="d-flex justify-content-between">
                        <div class="fs-4">Thông tin cơ bản</div>
                        <?php if ($order['tinh_trang'] != 4 && $order['tinh_trang'] != 3) {
                        ?>
                        <div class="dropdown">
                            <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                Thao tác
                            </button>
                            <ul class="dropdown-menu">
                                <?php
                                if ($order['tinh_trang'] == 0) {
                                    echo '<li><a class="dropdown-item" href="confirm_order.php?order_id=' . $order_id . '">Duyệt đơn hàng</a></li>';
                                    echo '<li><a class="dropdown-item" href="change_status_order.php?order_id=' . $order_id . '&order_status=4">Hủy đơn hàng</a></li>';
                                } else {
                                    echo '<li><a class="dropdown-item" href="change_status_order.php?order_id=' . $order_id . '&order_status=2">Đang vận chuyển</a></li>';
                                    echo '<li><a class="dropdown-item" href="change_status_order.php?order_id=' . $order_id . '&order_status=3">Đã giao thành công</a></li>';
                                }
                                ?>
                                <!-- <li><a class="dropdown-item" href="#">Duyệt đơn hàng</a></li>
                                <li><a class="dropdown-item" href="#">Đang đóng gói</a></li>
                                <li><a class="dropdown-item" href="#">Đang vận chuyển</a></li>
                                <li><a class="dropdown-item" href="#">Đã giao thành công</a></li>
                                <li><a class="dropdown-item" href="#">Hủy đơn hàng</a></li> -->
                            </ul>
                        </div>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="info-grid">
                        <div class="info-box">
                            <div class="info-type">
                                Mã đơn hàng
                            </div>
                            <div class="info-value">
                                <?php echo $order['ma_don_hang']; ?>
                            </div>
                        </div>

                        <div class="info-box">
                            <div class="info-type">
                                Tên khách hàng
                            </div>
                            <div class="info-value">
                                <?php echo $order['ho_va_ten']; ?>
                            </div>
                        </div>

                        <div class="info-box">
                            <div class="info-type">
                                Tổng giá
                            </div>
                            <div class="info-value">
                                <?php
                                echo number_format($order['tong_gia'], 0, '.', '.').' VND'; 
                                ?>
                            </div>
                        </div>

                        <div class="info-box">
                            <div class="info-type">
                                Tình trạng
                            </div>
                            <div class="info-value">
                                <?php
                                    if ($order['tinh_trang'] == 0) {
                                        echo '<div class="status-pending">Đợi duyệt</div>';
                                    } else if ($order['tinh_trang'] == 1) {
                                        echo '<div class="status-confirmed">Đã duyệt</div>';
                                    } else if ($order['tinh_trang'] == 2) {
                                        echo '<div class="status-shipping">Đang giao</div>';
                                    } else if ($order['tinh_trang'] == 3) {
                                        echo '<div class="status-complete">Đã giao</div>';
                                    } else {
                                        echo '<div class="status-cancel">Đã hủy</div>';
                                    }
                                ?>
                            </div>
                        </div>

                        <div class="info-box">
                            <div class="info-type">
                                Thời điểm đặt
                            </div>
                            <div class="info-value">
                                <?php echo $order['thoi_diem_dat_hang']; ?>
                            </div>
                        </div>

                        <div class="info-box">
                            <div class="info-type">
                                Nhân viên duyệt
                            </div>
                            <div class="info-value">
                                <?php
                                if ($order['tinh_trang'] == '0') {
                                    echo 'Chưa có';
                                    $duyet = null;
                                } else {
                                    $stmt = $conn->prepare('select * from duyet_don_hang join tai_khoan on duyet_don_hang.nhan_vien = tai_khoan.ten_dang_nhap
                                    where ma_don_hang = ?');
                                    $stmt->bind_param('s', $order_id);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    if ($result->num_rows > 0) {
                                        $duyet = $result->fetch_assoc();
                                        echo $duyet['ho_va_ten'];
                                    } else {
                                        $duyet = null;
                                        echo 'Chưa có';
                                    }
                                    $stmt->close();
                                }
                                ?>
                            </div>
                        </div>

                        <div class="info-box">
                            <div class="info-type">
                                Ngày duyệt
                            </div>
                            <div class="info-value">
                                <?php
                                if ($duyet == null) {
                                    echo 'Chưa có';
                                } else {
                                    echo $duyet['thoi_diem_duyet'];
                                }
                                ?>
                            </div>
                        </div>

                        <div class="info-box">
                            <div class="info-type">
                                Thời điểm nhận
                            </div>
                            <div class="info-value">
                                <?php
                                if ($order['thoi_diem_nhan_hang'] == null) {
                                    echo 'Chưa nhận hàng';
                                } else {
                                    echo $order['thoi_diem_nhan_hang'];
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                </div>

                <div id="addtitional-info" class="info-wrapper">
                    <div class="fs-4 mb-2">Danh sách sản phẩm</div>

                        <table class="table align-middle text-center">
                            <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Tên sản phẩm</th>
                                    <th scope="col">SL</th>
                                    <th scope="col">Đơn giá</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($products as $product) {
                                    echo '<tr>';
                                    echo '<td>
                                    <a href="./product_detail.php?product_id=' . $product['ma_sp'] . '&category=' . $product['phan_loai'] . '">
                                        <img src="' . $product['hinh_anh'] . '" alt="image">
                                    </a>
                                    </td>';
                                    echo '<td>' . $product['ten_sp'] . '</td>';
                                    echo '<td>' . $product['so_luong'] . '</td>';
                                    echo '<td>' . $product['don_gia'] . '</td>';
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="../node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../scripts/admin/toggle_sidebar.js"></script>
</body>

</html>
<?php
$conn->close();
?>