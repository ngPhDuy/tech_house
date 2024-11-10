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

$sql = "SELECT * FROM tai_khoan join thanh_vien on tai_khoan.ten_dang_nhap = thanh_vien.ten_dang_nhap 
WHERE tai_khoan.ten_dang_nhap = '" . $_GET['username'] . "'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$sql = "select * from don_hang where thanh_vien = '" . $_GET['username'] . "'";
$result = $conn->query($sql);
$orders = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech house</title>

    <link rel="stylesheet" href="../styles/admin/customer_info.css">
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
        <a href="./orders.php">
            <div>
                <span>
                    Đơn hàng
                </span>
            </div>
        </a>
        <a href="./customers.php" class="nav-active">
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
            <div class="d-flex justify-content-start align-items-center gap-3 mb-3">
                <div class="fs-3">Thông tin khách hàng</div>
                <div class="dropdown">
                    <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                        Thao tác
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Chỉnh sửa thông tin</a></li>
                        <?php
                        if ($row['active_status'] == '1') {
                            echo '<li><button class="dropdown-item text-danger" type="button">Khóa tài khoản</button></li>';
                        } else {
                            echo '<li><button class="dropdown-item text-success" type="button">Mở khóa tài khoản</button></li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>

            <div class="product-info d-flex gap-3 info-wrapper align-items-center gap-3">
                <div class="d-flex flex-column justify-content-center align-items-center gap-2 col-3">
                    <img src="../imgs/avatars/default.png" alt="avatar" class="user-avatar" width="150" height="150">
                    <div class="user-name fs-3">
                        Bùi Tiến Dũng
                    </div>
                </div>
                <div class="d-flex flex-column col justify-content-evenly">
                    <div class="d-flex">
                        <div class="info-box col-4">
                            <div class="info-type">
                                Tên đăng nhập
                            </div>
                            <div class="info-value">
                                ABCDXYZ
                            </div>
                        </div>

                        <div class="info-box col-4">
                            <div class="info-type">
                                Họ và tên
                            </div>
                            <div class="info-value">
                                Bùi Tiến Dũng
                            </div>
                        </div>

                        <div class="info-box col-4">
                            <div class="info-type">
                                Trạng thái tài khoản
                            </div>
                            <div class="info-value">
                                Active
                            </div>
                        </div>
                    </div>

                    <div class="d-flex">
                        <div class="info-box col-4">
                            <div class="info-type">
                                Số điện thoại
                            </div>
                            <div class="info-value">
                                09854321
                            </div>
                        </div>

                        <div class="info-box col-4">
                            <div class="info-type">
                                Email
                            </div>
                            <div class="info-value">
                                Dung@gamil.com
                            </div>
                        </div>



                        <div class="info-box col-4">
                            <div class="info-type">
                                Địa chỉ
                            </div>
                            <div class="info-value">
                                Thủ Đức, Thủ Đức, Thủ Đức, HCM
                            </div>
                        </div>
                    </div>

                    <div class="d-flex">
                        <div class="info-box col-4">
                            <div class="info-type">
                                Phân loại
                            </div>
                            <div class="info-value">
                                Nhân viên hạng 1
                            </div>
                        </div>

                        <div class="info-box col-4">
                            <div class="info-type">
                                Thời điểm mở tài khoản
                            </div>
                            <div class="info-value">
                                22/22/2222
                            </div>
                        </div>

                        <div class="info-box col-4">
                            <div class="info-type">
                                Thời điểm huỷ tài khoản
                            </div>
                            <div class="info-value">
                                22/22/2222
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <?php
            if (count($orders) == 0) {
                echo '<div class="fs-4 mt-4 text-center"
                style="font-style: italic">Chưa có đơn hàng nào được đặt</div>';
            } else {
            ?>
            <div id="addtitional-info" class="info-wrapper container mt-3">
                <div class="fs-4 mb-2">Lịch sử đặt hàng</div>
                    <table class="table align-middle text-center">
                        <thead>
                            <tr>
                                <th>Mã ĐH</th>
                                <th>Tạo lúc</th>
                                <th>Tổng giá</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($orders as $order) {
                                echo '<tr class="order" data-id="' . $order['ma_don_hang'] . '">';
                                echo '<td>' . $order['ma_don_hang'] . '</td>';
                                echo '<td>' . $order['thoi_diem_dat_hang'] . '</td>';
                                echo '<td>' . number_format($order['tong_gia'], 0, '.', '.') . ' VND</td>';
                                switch ($order['tinh_trang']) {
                                    case '0':
                                        echo '<td class="d-flex justify-content-center"><div class="status-pending">Đang chờ</div></td>';
                                        break;
                                    case '1':
                                        echo '<td class="d-flex justify-content-center"><div class="status-confirmed">Đã duyệt</div></td>';
                                        break;
                                    case '2':
                                        echo '<td class="d-flex justify-content-center"><div class="status-shipping">Đang giao hàng</div></td>';
                                        break;
                                    case '3':
                                        echo '<td class="d-flex justify-content-center"><div class="status-complete">Đã giao</div></td>';
                                        break;
                                    case '4':
                                        echo '<td class="d-flex justify-content-center"><div class="status-cancel">Đã hủy</div></td>';
                                        break;
                                }
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php
            }
            ?>
        </div>


        <!-- Dont have footer! -->
        <div id="footer" class="mb-5"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

</body>
<script src="../node_modules/jquery/dist/jquery.min.js"></script>
<script>
    $("tr.order").each(function () {
        $(this).click(function () {
            window.location.href = "./order_detail.php?order_id=" + $(this).attr('data-id');
        });
    });
</script>
</html>