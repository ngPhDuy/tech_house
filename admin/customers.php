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

$sql = 'SELECT * FROM tai_khoan join thanh_vien on tai_khoan.ten_dang_nhap = thanh_vien.ten_dang_nhap';
$result = $conn->query($sql);

$stmt->close();
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
            <div class="h3 mb-3">Tất cả thành viên</div>
            <div class="d-flex flex-column gap-3">
                <div id="utilities" class="d-flex justify-content-center w-100">
                    <div class="w-100 d-flex justify-content-center">
                        <div class="searchbar" id="searchbar">
                            <input type="text" placeholder="Nhập tên khách hàng..." name="search">
                            <button type="button"><i class="fa fa-search" id="search-button"></i></button>
                        </div>
                    </div>
                </div>

                <table id="table-list-product" class="table table-hover align-middle">
                    <thead>
                        <tr class="align-middle">
                            <th class="w-20">Tài khoản</th>
                            <th class="w-30">Tên khách hàng</th>
                            <th class="w-35">Địa chỉ</th>
                            <th>Số điện thoại</th>
                        </tr>
                    </thead>
                    <tbody id="customers-list">
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<tr class="customer" data-id="' . $row['ten_dang_nhap'] . '"
                                data-name="' . $row['ho_va_ten'] . '" data-address="' . $row['dia_chi'] . '"
                                data-phone="' . $row['sdt'] . '">';
                                echo '<td>' . $row['ten_dang_nhap'] . '</td>';
                                echo '<td>' . $row['ho_va_ten'] . '</td>';
                                if ($row['dia_chi'] == NULL) {
                                    echo '<td>Chưa cập nhật</td>';
                                } else {
                                    echo '<td>' . $row['dia_chi'] . '</td>';
                                }
                                echo '<td>' . $row['sdt'] . '</td>';
                                echo '</tr>';
                            }
                        }
                        ?>
                    </tbody>
                </table>
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


        <!-- Dont have footer! -->
        <div id="footer"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

<script src="../node_modules/jquery/dist/jquery.min.js"></script>
<script src="../scripts/admin/toggle_sidebar.js"></script>
<script src="../scripts/public/pagination.js"></script>
<script>
    const paginationLength = 5;
    const customersPerPage = 10;
    let customers = Array.from($('.customer'));
    let oldCustomers = customers;
    let paginationFunc = pagination(paginationLength, customersPerPage, $(customers));

    paginationFunc(1);

    $(document).on('click', 'tr.customer', function () {
        window.location.href = 'customer_info.php?username=' + $(this).attr('data-id');
    });

    $('#search-button').click((e) => {
        e.preventDefault();
        let searchValue = $('#searchbar input').val().toLowerCase().trim();

        console.log(searchValue);

        customers = Array.from(oldCustomers);

        if (searchValue === '') {
            paginationFunc = pagination(paginationLength, customersPerPage, $(customers));
            paginationFunc(1);
            return;
        }

        customers = customers.filter(customer => {
            return $(customer).attr('data-name').toLowerCase().includes(searchValue) ||
                $(customer).attr('data-address').toLowerCase().includes(searchValue) ||
                $(customer).attr('data-id').toLowerCase().includes(searchValue) ||
                $(customer).attr('data-phone').toLowerCase().includes(searchValue);
        });

        paginationFunc = pagination(paginationLength, customersPerPage, $(customers));
        paginationFunc(1);
    });
</script>
</body>
</html>