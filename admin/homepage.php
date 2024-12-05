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

$stmt = $conn->prepare('select * from tai_khoan join nhan_vien on tai_khoan.ten_dang_nhap = nhan_vien.ten_dang_nhap where tai_khoan.ten_dang_nhap = ?');
$stmt->bind_param('s', $_SESSION['ten_dang_nhap']);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$stmt = $conn->prepare('select count(*) as so_thanh_vien from thanh_vien');
$stmt->execute();
$result = $stmt->get_result();
$cus_count = $result->fetch_assoc()['so_thanh_vien'];

$stmt = $conn->prepare('select count(*) as so_don_hang from don_hang');
$stmt->execute();
$result = $stmt->get_result();
$order_count = $result->fetch_assoc()['so_don_hang'];

$stmt = $conn->prepare('select count(*) as so_don_hang from don_hang where tinh_trang = 0');
$stmt->execute();
$result = $stmt->get_result();
$pending_count = $result->fetch_assoc()['so_don_hang'];

$stmt = $conn->prepare('select * from don_hang join tai_khoan
on don_hang.thanh_vien = tai_khoan.ten_dang_nhap order by thoi_diem_dat_hang desc limit 10');
$stmt->execute();
$result = $stmt->get_result();
$recent_orders = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech house</title>

    <link rel="stylesheet" href="../styles/admin/index.css">
    <link rel="stylesheet" href="../styles/admin/layout.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Nunito+Sans' rel='stylesheet'>
</head>

<body>
    <div id="sidebar">
        <div id="logo">
            Tech House
        </div>
        <a href="#" class="nav-active">
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
        <div id="main_wrapper" class="px-5">
            <div class="d-flex flex-column gap-3">
                <div>
                    <div class="h4 border-5 mb-3">Thống kê</div>
                    <div id="card-list" class="mb-3 container d-flex justify-content-between gap-3">
                        <!-- Total User Card -->
                        <div class="my-card">
                            <div class="card-body">
                                <div class="card-content">
                                    <div class="card-name">Thành viên</div>
                                    <div class="card-quantity">
                                        <?php echo $cus_count; ?>
                                    </div>
                                </div>
                                <img src="../imgs/icons/user-icon.png" alt="user icon on card"
                                    width="60" height="60">
                            </div>
                            <div class="card-note">
                                <span class="text-success"><i class="fa-solid fa-chart-line"></i> 8.5%</span>
                                Tăng so với năm trước
                            </div>
                        </div>

                        <!-- Total Order Card -->
                        <div class="my-card">
                            <div class="card-body">
                                <div class="card-content">
                                    <div class="card-name">Tổng đơn</div>
                                    <div class="card-quantity">
                                        <?php echo $order_count; ?>
                                    </div>
                                </div>
                                <img src="../imgs/icons/order-icon.png" alt="order icon on card"
                                    width="60" height="60">
                            </div>
                            <div class="card-note">
                                <span class="text-danger"><i class="fa-solid fa-chart-line"></i> 8.5%</span>
                                Giảm so với năm trước
                            </div>
                        </div>

                        <!-- Total Pending Card -->
                        <div class="my-card">
                            <div class="card-body">
                                <div class="card-content">
                                    <div class="card-name">Đợi duyệt</div>
                                    <div class="card-quantity">
                                        <?php echo $pending_count; ?>
                                    </div>
                                </div>
                                <img src="../imgs/icons/pending-icon.png" alt="pending icon on card"
                                    width="60" height="60">
                            </div>
                            <div class="card-note">
                                <span class="text-success"><i class="fa-solid fa-chart-line"></i> 8.5%</span>
                                Tăng so với năm trước
                            </div>
                        </div>

                    </div>
                </div>

                <div>
                    <div class="h4 border-5 mb-3">Đơn hàng gần đây</div>
                    <div id="deal-list">
                        <table id="deal-table" class="table">
                            <thead>
                                <tr>
                                    <th>Mã đơn hàng</th>
                                    <th>Khách hàng</th>
                                    <th>Thời điểm đặt hàng</th>
                                    <th>Địa chỉ</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($recent_orders as $order) {
                                    echo "<tr class='order' data-id='{$order['ma_don_hang']}'>";
                                    echo '<td>' . $order['ma_don_hang'] . '</td>';
                                    echo '<td>' . $order['ho_va_ten'] . '</td>';
                                    echo '<td>' . $order['thoi_diem_dat_hang'] . '</td>';
                                    echo '<td>' . $order['dia_chi'] . '</td>';
                                    echo '<td>';
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
                                    echo '</td>';
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div id="footer"></div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
<script src="../node_modules/jquery/dist/jquery.min.js"></script>
<script src="../scripts/admin/toggle_sidebar.js"></script>
<script>
    $('tr.order').each(function() {
        $(this).click(function() {
            window.location.href = `./order_detail.php?order_id=${$(this).attr('data-id')}`;
        });
    })
</script>
</body>
</html>