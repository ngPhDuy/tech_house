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

$sql = 'SELECT * FROM tai_khoan join thanh_vien on tai_khoan.ten_dang_nhap = thanh_vien.ten_dang_nhap';
$result = $conn->query($sql);

$conn->close();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech house</title>

    <link rel="stylesheet" href="../styles/admin/customer.css">
    <link rel="stylesheet" href="../styles/admin/layout.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Nunito Sans' rel='stylesheet'>
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
            <div class="h3 mb-3">Tất cả thành viên</div>
            <div class="d-flex flex-column gap-3">
                <div id="utilities" class="d-flex justify-content-center w-100">
                    <div class="w-100 d-flex justify-content-center">
                        <div class="searchbar">
                            <input type="text" placeholder="Nhập tên khách hàng..." name="search">
                            <button type="button"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </div>

                <table id="table-list-product" class="table table-hover align-middle">
                    <thead>
                        <tr class="align-middle">
                            <th class="w-20">Tài khoản</th>
                            <th class="w-30">Tên khách hàng</th>
                            <th class="w-35">Address</th>
                            <th>Số điện thoại</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<tr class="customer" data-id="' . $row['ten_dang_nhap'] . '">';
                                echo '<td>' . $row['ten_dang_nhap'] . '</td>';
                                echo '<td>' . $row['ho_va_ten'] . '</td>';
                                echo '<td>' . $row['dia_chi'] . '</td>';
                                echo '<td>' . $row['sdt'] . '</td>';
                                echo '</tr>';
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>

        </div>


        <!-- Dont have footer! -->
        <div id="footer"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>
<script src="../node_modules/jquery/dist/jquery.min.js"></script>
<script>
    $("tr.customer").each(function () {
        $(this).click(function () {
            window.location.href = 'customer_info.php?username=' + $(this).attr('data-id');
        });
    });
</script>
</html>