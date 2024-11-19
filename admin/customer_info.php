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

    <style>
        .info-value {
            font-size: 1rem;
        }
    </style>
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
        <a href="./account_setting.php">
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
                <div class="fs-3">Thông tin khách hàng</div>
                <div class="dropdown">
                <?php
                if ($row['active_status'] == '1') {
                    echo '<button class="btn btn-danger" type="button"
                    id="disable-account">Khóa tài khoản</button>';
                } else {
                    echo '<button class="btn btn-success" type="button"
                    id="able-account">Mở khóa tài khoản</button>';
                }
                ?>
                </div>
            </div>

            <div class="product-info d-flex gap-3 info-wrapper align-items-center gap-3">
                <div class="d-flex flex-column justify-content-center align-items-center gap-2 col-3">
                    <?php 
                    if ($row['avatar'] != NULL) {
                        echo '<img src="../imgs/avatars/' . $row['avatar'] . '" 
                        alt="avatar" class="user-avatar border rounded-circle" width="150" height="150">';
                    } else {
                        echo '<img src="../imgs/avatars/default.png" 
                    alt="avatar" class="user-avatar border rounded-circle" width="150" height="150">';
                    }
                    ?>
                </div>
                <div class="d-flex flex-column col justify-content-evenly">
                    <div class="d-flex">
                        <div class="info-box col-4">
                            <div class="info-type">
                                Tên đăng nhập
                            </div>
                            <div class="info-value">
                                <?php echo $row['ten_dang_nhap']; ?>
                            </div>
                        </div>

                        <div class="info-box col-4">
                            <div class="info-type">
                                Họ và tên
                            </div>
                            <div class="info-value">
                                <?php echo $row['ho_va_ten']; ?>
                            </div>
                        </div>

                        <div class="info-box col-4">
                            <div class="info-type">
                                Trạng thái tài khoản
                            </div>
                            <div class="info-value">
                                <?php if ($row['active_status'] == '1') {
                                    echo '<p class="text-success m-0">Đang hoạt động</p>';
                                } else {
                                    echo '<p class="text-danger m-0">Đã khóa</p>';
                                } ?>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex">
                        <div class="info-box col-4">
                            <div class="info-type">
                                Số điện thoại
                            </div>
                            <div class="info-value">
                                <?php if ($row['sdt'] == '') {
                                    echo 'Chưa cập nhật';
                                } else {
                                    echo $row['sdt'];
                                } ?>
                            </div>
                        </div>

                        <div class="info-box col-4">
                            <div class="info-type">
                                Email
                            </div>
                            <div class="info-value">
                                <?php if ($row['email'] == '') {
                                    echo 'Chưa cập nhật';
                                } else {
                                    echo $row['email'];
                                } ?>
                            </div>
                        </div>



                        <div class="info-box col-4">
                            <div class="info-type">
                                Địa chỉ
                            </div>
                            <div class="info-value">
                                <?php if ($row['dia_chi'] == '') {
                                    echo 'Chưa cập nhật';
                                } else {
                                    echo $row['dia_chi'];
                                } ?>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex">

                        <div class="info-box col-4">
                            <div class="info-type">
                                Thời điểm mở tài khoản
                            </div>
                            <div class="info-value">
                                <?php echo $row['thoi_diem_mo_tk']; ?>
                            </div>
                        </div>

                        <div class="info-box col-4">
                            <div class="info-type">
                                Thời điểm huỷ tài khoản
                            </div>
                            <div class="info-value">
                                <?php if ($row['thoi_diem_huy_tk'] == NULL) {
                                    echo 'Chưa khóa';
                                } else {
                                    echo $row['thoi_diem_huy_tk'];
                                } ?>
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
            <div class="pagination mt-3 d-flex justify-content-center d-none">
                <div class="page-numbers d-flex justify-content-center gap-2">
                    <a href="#" class="page-number">01</a>
                    <a href="#" class="page-number">02</a>
                    <a href="#" class="page-number">03</a>
                    <a href="#" class="page-number">04</a>
                    <a href="#" class="page-number">05</a>
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
    let paginationLength = 5;
    let ordersPerPage = 5;
    let currentPage = 1;
    let orders = document.querySelectorAll('.order');
    const pagination = document.querySelector('.pagination');
    const pageNumbers = document.querySelector('.page-numbers');

    function displayOrders() {
        console.log(currentPage);
        orders.forEach((product, index) => {
            const start = (currentPage - 1) * ordersPerPage;
            const end = currentPage * ordersPerPage;
            if (index >= start && index < end) {
                product.classList.remove('d-none');
            } else {
                product.classList.add('d-none');
            }
        });
    }

    function updatePagination() {
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
            const pageNumber = document.createElement('div');
            pageNumber.classList.add('page-number');
            pageNumber.textContent = i;
            if (i === currentPage) {
                pageNumber.classList.add('active');
            }

            pageNumber.addEventListener('click', (e) => {
                e.preventDefault();
                currentPage = i;
                displayOrders();
                updatePagination();
            });

            pageNumbers.appendChild(pageNumber);
        }

        pagination.classList.remove('d-none');
    }

    displayOrders();
    updatePagination();

    $("tr.order").each(function () {
        $(this).click(function () {
            window.location.href = "./order_detail.php?order_id=" + $(this).attr('data-id');
        });
    });

    $("#disable-account").click(function () {
        $.ajax({
            url: '../admin/change_status.php',
            type: 'POST',
            data: {
                username: '<?php echo $_GET['username']; ?>',
                active: '0'
            },
            success: function (response) {
                if (response == 'Thành công') {
                    location.reload();
                }
            }
        });
    });

    $('#able-account').click(function () {
        $.ajax({
            url: '../admin/change_status.php',
            type: 'POST',
            data: {
                username: '<?php echo $_GET['username']; ?>',
                active: '1'
            },
            success: function (response) {
                if (response == 'Thành công') {
                    location.reload();
                }
            }
        });
    });
</script>
</html>