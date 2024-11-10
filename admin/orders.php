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

$stmt = 'select * from don_hang join tai_khoan on don_hang.thanh_vien = tai_khoan.ten_dang_nhap';
$result = $conn->query($stmt);

$orders = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech house</title>

    <link rel="stylesheet" href="../styles/admin/orders.css">
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
            <div class="h3 mb-3">Đơn hàng</div>
            <div class="d-flex flex-column gap-3">
                <div id="utilities" class="d-flex justify-content-between">
                    <div class="filter-button btn border border-secondary d-flex gap-2">
                        <svg width="24" height="24" viewBox="0 0 27 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M2.06216 4.48145H24.8793C25.0854 4.48149 25.2871 4.5262 25.4597 4.61012C25.6323 4.69404 25.7684 4.81356 25.8514 4.9541C25.9345 5.09464 25.9609 5.25014 25.9274 5.40166C25.8939 5.55319 25.8021 5.69419 25.6629 5.80749L16.9372 12.9622C16.7556 13.107 16.6557 13.2984 16.6583 13.4966V19.0976C16.6599 19.2292 16.6169 19.3589 16.5333 19.4748C16.4498 19.5907 16.3283 19.689 16.1801 19.7606L11.9301 21.8684C11.7707 21.9467 11.5859 21.9915 11.3953 21.9981C11.2046 22.0047 11.015 21.973 10.8465 21.9061C10.678 21.8393 10.5367 21.7399 10.4376 21.6183C10.3385 21.4967 10.2852 21.3575 10.2833 21.2153V13.4966C10.2858 13.2984 10.186 13.107 10.0044 12.9622L1.27857 5.80749C1.13946 5.69419 1.04757 5.55319 1.0141 5.40166C0.980636 5.25014 1.00704 5.09464 1.09009 4.9541C1.17313 4.81356 1.30925 4.69404 1.48184 4.61012C1.65444 4.5262 1.85607 4.48149 2.06216 4.48145V4.48145Z"
                                stroke="#191C1F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div>Lọc</div>
                    </div>

                    <div class="w-75 d-flex justify-content-between">
                        <div class="searchbar">
                            <input type="text" placeholder="Nhập mã đơn hàng..." name="search">
                            <button type="button"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </div>
                <?php 
                if (count($orders) > 0) {
                ?>
                <table id="table-list-product" class="table table-hover align-middle">
                    <thead>
                        <tr class="align-middle">
                            <th>Mã ĐH</th>
                            <th>Tên khách hàng</th>
                            <th class="w-35">Địa chỉ</th>
                            <th>Tạo lúc</th>
                            <th>Tổng giá</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($orders as $order) {
                        ?>
                        <tr class="order" data-id="<?php echo $order['ma_don_hang']; ?>">
                            <td><?php echo $order['ma_don_hang']; ?></td>
                            <td><?php echo $order['ho_va_ten']; ?></td>
                            <td><?php echo $order['dia_chi']; ?></td>
                            <td><?php echo $order['thoi_diem_dat_hang']; ?></td>
                            <td><?php echo $order['tong_gia']; ?></td>
                            <td>
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
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
                <?php
                } else {
                ?>
                <div class="text-center">Không có đơn hàng nào</div>
                <?php
                }
                ?>
            </div>

        </div>


        <!-- Dont have footer! -->
        <div id="footer"></div>
    </div>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
crossorigin="anonymous"></script>
<script src="../node_modules/jquery/dist/jquery.min.js"></script>
<script>
    $("tr").each(function () {
        $(this).click(function () {
            window.location.href = `./order_detail.php?order_id=${$(this).attr('data-id')}`;
        });
    });
</script>
</html>