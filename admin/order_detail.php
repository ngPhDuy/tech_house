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
    <link href='https://fonts.googleapis.com/css?family=Nunito Sans' rel='stylesheet'>
</head>

<body>
    <!-- @@@@@@@@@@@@@@@@@@@@@@@ -->
    <!-- dont change code here -->

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

            <div id="profile">
                <div id="profile_account">
                    <img id="profile_avatar" src="../imgs/avatars/default.png" alt="avatar">
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

    <div id="body_section">

        <div id="main_wrapper" class="px-5">
            <div class="h3 mb-3">Thông tin đơn hàng</div>

            <div class="product-info d-flex flex-column gap-3">
                <div class="info-wrapper container">
                    <div class="d-flex justify-content-between">
                        <div class="fs-4">Thông tin cơ bản</div>
                        <div class="dropdown">
                            <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                Thao tác
                            </button>
                            <ul class="dropdown-menu">
                                <?php
                                if ($order['tinh_trang'] == 0) {
                                    echo '<li><a class="dropdown-item" href="confirm_order.php?order_id=' . $order_id . '">Duyệt đơn hàng</a></li>';
                                    echo '<li><a class="dropdown-item" href="confirm_order.php?order_id=' . $order_id . '">Hủy đơn hàng</a></li>';
                                } else if ($order['tinh_trang'] == 4) {
                                } else {
                                    echo '<li><a class="dropdown-item" href="confirm_order.php?order_id=' . $order_id . '">Đang đóng gói</a></li>';
                                    echo '<li><a class="dropdown-item" href="confirm_order.php?order_id=' . $order_id . '">Đang vận chuyển</a></li>';
                                    echo '<li><a class="dropdown-item" href="confirm_order.php?order_id=' . $order_id . '">Đã giao thành công</a></li>';
                                }
                                ?>
                                <!-- <li><a class="dropdown-item" href="#">Duyệt đơn hàng</a></li>
                                <li><a class="dropdown-item" href="#">Đang đóng gói</a></li>
                                <li><a class="dropdown-item" href="#">Đang vận chuyển</a></li>
                                <li><a class="dropdown-item" href="#">Đã giao thành công</a></li>
                                <li><a class="dropdown-item" href="#">Hủy đơn hàng</a></li> -->
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="info-box col-3">
                            <div class="info-type">
                                Mã đơn hàng
                            </div>
                            <div class="info-value">
                                <?php echo $order['ma_don_hang']; ?>
                            </div>
                        </div>

                        <div class="info-box col-7">
                            <div class="info-type">
                                Tên khách hàng
                            </div>
                            <div class="info-value">
                                <?php echo $order['ho_va_ten']; ?>
                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="info-box col-3">
                            <div class="info-type">
                                Tổng giá
                            </div>
                            <div class="info-value">
                                <?php
                                echo number_format($order['tong_gia'], 0, '.', '.').' VND'; 
                                ?>
                            </div>
                        </div>

                        <div class="info-box col-3">
                            <div class="info-type">
                                Tình trạng
                            </div>
                            <div class="info-value">
                                <?php
                                if ($order['tinh_trang'] == 0) {
                                    echo 'Đợi duyệt';
                                } else if ($order['tinh_trang'] == 1) {
                                    echo 'Đã xác nhận';
                                } else if ($order['tinh_trang'] == 2) {
                                    echo 'Đang giao';
                                } else if ($order['tinh_trang'] == 3) {
                                    echo 'Đã giao';
                                } else {
                                    echo 'Đã hủy';
                                }
                                ?>
                            </div>
                        </div>


                        <div class="info-box col-3">
                            <div class="info-type">
                                Thời điểm đặt
                            </div>
                            <div class="info-value">
                                <?php echo $order['thoi_diem_dat_hang']; ?>
                            </div>
                        </div>


                    </div>

                    <div class="row">
                        <div class="info-box col-3">
                            <div class="info-type">
                                Nhân viên duyệt
                            </div>
                            <div class="info-value">
                                <?php
                                if ($order['tinh_trang'] == '0') {
                                    echo 'Chưa có';
                                    $duyet = null;
                                } else {
                                    $stmt = $conn->prepare('select * from duyet_don_hang where ma_don_hang = ?');
                                    $stmt->bind_param('s', $order_id);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $duyet = $result->fetch_assoc();
                                    echo $duyet['nhan_vien'];
                                    $stmt->close();
                                }
                                ?>
                            </div>
                        </div>

                        <div class="info-box col-3">
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

                        <div class="info-box col-3">
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

                <div id="addtitional-info" class="info-wrapper container">
                    <div class="fs-4 mb-2">Danh sách sản phẩm</div>

                        <table class="table align-middle text-center">
                            <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Tên sản phẩm</th>
                                    <th scope="col">Số lượng</th>
                                    <th scope="col">Đơn giá</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($products as $product) {
                                    echo '<tr>';
                                    echo '<td><img src="' . $product['hinh_anh'] . '" alt="image"></td>';
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


        <!-- Dont have footer! -->
        <div id="footer" class="mb-5"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>
<?php
$conn->close();
?>