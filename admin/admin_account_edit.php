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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = $_SESSION['ten_dang_nhap'];

    $fullname = $_POST['fullname'] ?? $row['ho_va_ten'];


    $cccd = (isset($_POST['cccd']) && strlen($_POST['cccd']) <= 12) ? $_POST['cccd'] : $row['cccd'];
    
    $gender = (isset($_POST['gender']) && strlen($_POST['gender']) <= 3) ? $_POST['gender'] : $row['gioi_tinh'];



    if ($timestamp = strtotime($_POST['birthdate'])) {
        $birthdate = date('Y-m-d', $timestamp);
    } else {
        $birthdate = $row['ngay_sinh'];
    }

    $address = $_POST['address'] ?? $row['dia_chi'];

    $pattern = "/\d$/";

    $phone = preg_match($pattern, $_POST['phone']) ? $_POST['phone'] : $row['sdt'];


    $email = isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ? $_POST['email'] : $row['email'];


    $conn = new mysqli('localhost', 'root', '', 'tech_house_db');
    if ($conn->connect_error) {
        die('Kết nối thất bại: ' . $conn->connect_error);
    }

    // Cập nhật bảng Tai_khoan
    $sql_tai_khoan = "UPDATE Tai_khoan SET ho_va_ten = ?, email = ?, sdt = ?, dia_chi = ? WHERE ten_dang_nhap = ?";
    $stmt_tai_khoan = $conn->prepare($sql_tai_khoan);
    $stmt_tai_khoan->bind_param("sssss", $fullname, $email, $phone, $address, $username);
    $stmt_tai_khoan->execute();

    // Cập nhật bảng Nhan_vien
    $sql_nhan_vien = "UPDATE Nhan_vien SET cccd = ?, gioi_tinh = ?, ngay_sinh = ? WHERE ten_dang_nhap = ?";
    $stmt_nhan_vien = $conn->prepare($sql_nhan_vien);
    $stmt_nhan_vien->bind_param("ssss", $cccd, $gender, $birthdate, $username);
    $stmt_nhan_vien->execute();

    // Đóng statement
    $stmt_tai_khoan->close();
    $stmt_nhan_vien->close();

    $conn->close();


    header('Location: ./account_setting.php');
}

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
                        <li><a class="dropdown-item text-primary" href="./account_setting.php">Thông tin tài khoản</a></li>
                        <li><a class="dropdown-item text-danger" href="../public/logout.php">Đăng xuất</a></li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

    <div id="body_section">

        <div id="main_wrapper" class="px-5">
            <div class="d-flex justify-content-start align-items-center gap-3 mb-3">
                <div class="fs-3">Cập nhật thông tin tài khoản</div>
            </div>

            <form action="./admin_account_edit.php" method="post">
                <div class="product-info d-flex gap-3 info-wrapper align-items-center gap-3">
                    <div class="d-flex flex-column justify-content-center align-items-center gap-2 col-3">
                        <img src="../imgs/avatars/default.png" alt="avatar" class="user-avatar" width="150" height="150">
                        <div class="user-name fs-3">
                            UserName
                        </div>
                    </div>

                    <div class="d-flex flex-column col justify-content-evenly">
                        <div class="row">
                            <div class="col-4 input-box">
                                <label for="username" class="form-label">Tên đăng nhập</label>
                                <input disabled type="text" class="form-control" id="username" name="username"
                                    value="<?php echo $row['ten_dang_nhap'] ?? '...'; ?>" required />
                            </div>
                            <div class="col-4 input-box">
                                <label for="fullname" class="form-label">Họ và tên</label>
                                <input type="text" class="form-control" id="fullname" name="fullname"
                                    value="<?php echo $row['ho_va_ten'] ?? '...'; ?>" required />
                            </div>
                            <div class="col-4 input-box">
                                <label for="cccd" class="form-label">Căn cước công dân</label>
                                <input type="text" class="form-control" id="cccd" name="cccd"
                                    value="<?php echo $row['cccd'] ?? '...'; ?>" required />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-4 input-box">
                                <label for="gender" class="form-label">Giới tính</label>
                                <input type="text" class="form-control" id="gender" name="gender"
                                    value="<?php echo $row['gioi_tinh'] ?? '...'; ?>" required />
                            </div>

                            <div class="col-4 input-box">
                                <label for="birthdate" class="form-label">Ngày sinh</label>
                                <input type="date" class="form-control" id="birthdate" name="birthdate"
                                    value="<?php echo $row['ngay_sinh'] ? date('Y-m-d', strtotime($row['ngay_sinh'])) : ''; ?>" required />
                            </div>

                            <div class="col-4 input-box">
                                <label for="address" class="form-label">Địa chỉ</label>
                                <input type="text" class="form-control" id="address" name="address"
                                    value="<?php echo $row['dia_chi'] ?? '...'; ?>" required />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-4 input-box">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    value="<?php echo $row['sdt'] ?? '...'; ?>" required />
                            </div>

                            <div class="col-4 input-box">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="<?php echo $row['email'] ?? '...'; ?>" required />
                            </div>
                        </div>
                    </div>


                </div>

                <!-- Submit Button -->
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-lg btn-primary">Lưu thay đổi</button>
                </div>
            </form>

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