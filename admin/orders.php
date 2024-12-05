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

$stmt = $conn->prepare('select * from tai_khoan join nhan_vien on tai_khoan.ten_dang_nhap = nhan_vien.ten_dang_nhap where tai_khoan.ten_dang_nhap = ?');
$stmt->bind_param('s', $_SESSION['ten_dang_nhap']);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$stmt->close();

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
        <div id="main_wrapper" class="px-5">
            <div class="h3 mb-3">Đơn hàng</div>
            <div class="d-flex flex-column gap-3">
                <div id="utilities" class="d-flex justify-content-between">
                    <div class="filter-button btn border border-secondary d-flex gap-2" id="filter-button">
                        <svg width="24" height="24" viewBox="0 0 27 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M2.06216 4.48145H24.8793C25.0854 4.48149 25.2871 4.5262 25.4597 4.61012C25.6323 4.69404 25.7684 4.81356 25.8514 4.9541C25.9345 5.09464 25.9609 5.25014 25.9274 5.40166C25.8939 5.55319 25.8021 5.69419 25.6629 5.80749L16.9372 12.9622C16.7556 13.107 16.6557 13.2984 16.6583 13.4966V19.0976C16.6599 19.2292 16.6169 19.3589 16.5333 19.4748C16.4498 19.5907 16.3283 19.689 16.1801 19.7606L11.9301 21.8684C11.7707 21.9467 11.5859 21.9915 11.3953 21.9981C11.2046 22.0047 11.015 21.973 10.8465 21.9061C10.678 21.8393 10.5367 21.7399 10.4376 21.6183C10.3385 21.4967 10.2852 21.3575 10.2833 21.2153V13.4966C10.2858 13.2984 10.186 13.107 10.0044 12.9622L1.27857 5.80749C1.13946 5.69419 1.04757 5.55319 1.0141 5.40166C0.980636 5.25014 1.00704 5.09464 1.09009 4.9541C1.17313 4.81356 1.30925 4.69404 1.48184 4.61012C1.65444 4.5262 1.85607 4.48149 2.06216 4.48145V4.48145Z"
                                stroke="#191C1F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div>Lọc</div>
                    </div>

                    <div class="w-75 d-flex justify-content-between">
                        <div class="searchbar" id="searchbar">
                            <input type="text" placeholder="Nhập tên khách hàng, địa chỉ" name="search">
                            <button type="button"><i class="fa fa-search" id="search-button"></i></button>
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
                            <th class="w-30">Địa chỉ</th>
                            <th>Tạo lúc</th>
                            <th>Tổng giá</th>
                            <th class="w-15">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody id="orders-list">
                        <?php
                        foreach ($orders as $order) {
                        ?>
                        <tr class="order" data-id="<?php echo $order['ma_don_hang']; ?>"
                        data-category="<?php echo $order['tinh_trang']; ?>"
                        data-address="<?php echo $order['dia_chi']; ?>"
                        data-name="<?php echo $order['ho_va_ten']; ?>">
                            <td><?php echo $order['ma_don_hang']; ?></td>
                            <td><?php echo $order['ho_va_ten']; ?></td>
                            <td><?php echo $order['dia_chi']; ?></td>
                            <td><?php echo $order['thoi_diem_dat_hang']; ?></td>
                            <td><?php echo number_format($order['tong_gia'], 0, '.', '.'); ?></td>
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
                <div class="pagination mt-3 d-none">
                    <div class="page-numbers d-flex justify-content-center gap-2">
                        <a href="#" class="page-number">01</a>
                        <a href="#" class="page-number">02</a>
                        <a href="#" class="page-number">03</a>
                        <a href="#" class="page-number">04</a>
                        <a href="#" class="page-number">05</a>
                    </div>
                </div>
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

        <div class="filter-modal-wrapper d-none">
        <div class="filter-modal">
            <div class="filter-modal-content d-flex gap-3 justify-content-center">
                <div class="category-filter col-10 px-3">
                    <p class="m-0 fw-bold text-uppercase mb-3">Phân loại</p>
                    <div class="categories">
                        <div class="category">
                            <input class="d-block" type="checkbox" name="category" id="0">
                            <label for="0">Chờ duyệt</label>
                        </div>
                        <div class="category">
                            <input type="checkbox" name="category" id="1">
                            <label for="1">Đã xác nhận</label>
                        </div>
                        <div class="category">
                            <input type="checkbox" name="category" id="2">
                            <label for="2">Đang giao hàng</label>
                        </div>
                        <div class="category">
                            <input type="checkbox" name="category" id="3">
                            <label for="3">Đã giao hàng</label>
                        </div>
                        <div class="category">
                            <input type="checkbox" name="category" id="4">
                            <label for="4">Đã hủy</label>
                        </div> 
                    </div>
                </div>
            </div>
            <div class="filter-modal-footer d-flex justify-content-center mx-auto mt-3">
                <button class="btn btn-primary">Áp dụng</button>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
crossorigin="anonymous"></script>
<script src="../node_modules/jquery/dist/jquery.min.js"></script>
<script src="../scripts/admin/toggle_sidebar.js"></script>
<script src="../scripts/public/pagination.js"></script>
<script>
    const paginationLength = 5;
    const ordersPerPage = 10;
    let orders = Array.from($('.order'));
    let oldOrders = orders;
    let categoriesFilter = [];
    let paginationFunc = pagination(paginationLength, ordersPerPage, $(orders));

    paginationFunc(1);

    $(document).on('click', '.order', function () {
        window.location.href = `./order_detail.php?order_id=${$(this).attr('data-id')}`;
    });

    $('#filter-button').click((e) => {
        console.log('click');
        e.preventDefault();
        $('.filter-modal-wrapper').removeClass('d-none');
    });

    $('.filter-modal-footer button').click((e) => {
        e.preventDefault();
        $('.filter-modal-wrapper').addClass('d-none');

        categoriesFilter = [];
        $('.category input:checked').each((index, category) => {
            categoriesFilter.push($(category).attr('id'));
        });

        orders = Array.from(oldOrders);

        if (categoriesFilter.length > 0) {
            orders = orders.filter(order => {
                return categoriesFilter.includes($(order).attr('data-category'));
            });
        }

        $('#orders-list').empty();

        orders.forEach(order => {
            $('#orders-list').append(order);
        });

        let paginationFunc = pagination(paginationLength, ordersPerPage, $(orders));
        paginationFunc(1);
    });

    $('.filter-modal-wrapper').click((e) => {
        let filterModalWrapper = document.querySelector('.filter-modal-wrapper');
        if (e.target === filterModalWrapper) {
            filterModalWrapper.classList.add('d-none');
        }
    });

    $('#search-button').click((e) => {
        e.preventDefault();
        let searchValue = $('#searchbar input').val().toLowerCase().trim();

        console.log(searchValue);

        orders = Array.from(oldOrders);
        orders = orders.filter(order => {
            return $(order).attr('data-address').toLowerCase().includes(searchValue) ||
                $(order).attr('data-name').toLowerCase().includes(searchValue);
        });

        $('#orders-list').empty();

        orders.forEach(order => {
            $('#orders-list').append(order);
        });

        let paginationFunc = pagination(paginationLength, ordersPerPage, $(orders));
        paginationFunc(1);
    });
</script>
</body>
</html>