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

$sql = "SELECT * FROM Tai_khoan join Nhan_vien on Tai_khoan.ten_dang_nhap = Nhan_vien.ten_dang_nhap 
WHERE Tai_khoan.ten_dang_nhap = '" . $_SESSION['ten_dang_nhap'] . "'";

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
        <a href="./customers.php">
            <div>
                <span>
                    Thành viên
                </span>
            </div>
        </a>
        <a href="./account_setting.php" class="nav-active">
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
                        <li class="d-none"><a class="dropdown-item text-primary" href="./account_setting.php">Thông tin tài khoản</a></li>
                        <li><a class="dropdown-item text-danger" href="../public/logout.php">Đăng xuất</a></li>
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
                        <li><a class="dropdown-item text-primary" href="./admin_account_edit.php">Chỉnh sửa thông tin</a></li>
                        <li><a class="dropdown-item text-danger" href="../public/logout.php">Đăng xuất</a></li>
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
                        <div class="info-box col-4 my-3">
                            <div class="info-type">
                                Tên đăng nhập
                            </div>
                            <div class="info-value">
                                <?php echo $row['ten_dang_nhap'] ?? "..."; ?>
                            </div>
                        </div>

                        <div class="info-box col-4 my-3">
                            <div class="info-type">
                                Họ và tên
                            </div>
                            <div class="info-value">
                                <?php echo $row['ho_va_ten'] ?? "..."; ?>
                            </div>
                        </div>

                        <div class="info-box col-4 my-3">
                            <div class="info-type">
                                Căn cước công dân
                            </div>
                            <div id="active-box" class="info-value">
                                <?php echo $row['cccd'] ?? "..."; ?>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex">
                        <div class="info-box col-4 my-3">
                            <div class="info-type">
                                Giới tính
                            </div>
                            <div class="info-value">
                                <?php echo $row['gioi_tinh'] ?? "..."; ?>
                            </div>
                        </div>

                        <div class="info-box col-4 my-3">
                            <div class="info-type">
                                Ngày sinh
                            </div>
                            <div class="info-value">
                                <?php
                                if ($row['ngay_sinh']){
                                    echo date_format(date_create((string)$row['ngay_sinh']), "d/m/Y");

                                }else{
                                    echo "...";
                                }
                                
                                ?>
                            </div>
                        </div>

                        <div class="info-box col-4 my-3">
                            <div class="info-type">
                                Địa chỉ
                            </div>
                            <div class="info-value">
                                <?php echo $row['dia_chi'] ?? "..."; ?>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex">
                        <div class="info-box col-4 my-3">
                            <div class="info-type">
                                Số điện thoại
                            </div>
                            <div class="info-value">
                                <?php echo $row['sdt'] ?? "..."; ?>
                            </div>
                        </div>

                        <div class="info-box col-4 my-3">
                            <div class="info-type">
                                Email
                            </div>
                            <div class="info-value">
                                <?php echo $row['email'] ?? "..."; ?>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex">
                        <div class="info-box col-4 my-3">
                            <div class="info-type">
                                Phân loại
                            </div>
                            <div class="info-value">
                                <?php echo $row['phan_loai_tk'] == "nv" ? "Nhân viên" : ($row['phan_loai_tk'] ?? "..."); ?>
                            </div>
                        </div>

                        <div class="info-box col-4 my-3">
                            <div class="info-type">
                                Thời điểm mở tài khoản
                            </div>
                            <div class="info-value">
                                <?php
                                if ($row['thoi_diem_mo_tk']){
                                    echo date_format(date_create((string)$row['thoi_diem_mo_tk']), "d/m/Y");

                                }else{
                                    echo "...";
                                }
                                
                                ?>
                            </div>
                        </div>

                    </div>
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
<script src="../node_modules/jquery/dist/jquery.min.js"></script>
</html>