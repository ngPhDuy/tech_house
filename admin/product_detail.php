<?php
session_start();

if (!isset($_SESSION['ten_dang_nhap'])) {
    header('Location: ../public/login.php');
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

$product_id = $_GET['product_id'];
$category = $_GET['category'];

switch ($category) {
    case '0':
        $stmt = $conn->prepare('select * from san_pham join laptop on san_pham.ma_sp = laptop.ma_sp where san_pham.ma_sp = ?');
        break;
    case '1':
        $stmt = $conn->prepare('select * from san_pham join mobile on san_pham.ma_sp = mobile.ma_sp where san_pham.ma_sp = ?');
        break;
    case '2':
        $stmt = $conn->prepare('select * from san_pham join tablet on san_pham.ma_sp = tablet.ma_sp where san_pham.ma_sp = ?');
        break;
    case '3':
        $stmt = $conn->prepare('select * from san_pham join Tai_nghe_bluetooth on san_pham.ma_sp = Tai_nghe_bluetooth.ma_sp where san_pham.ma_sp = ?');
        break;
    case '4':
        $stmt = $conn->prepare('select * from san_pham join Ban_phim on san_pham.ma_sp = Ban_phim.ma_sp where san_pham.ma_sp = ?');
        break;
    case '5':
        $stmt = $conn->prepare('select * from san_pham join Sac_du_phong on san_pham.ma_sp = Sac_du_phong.ma_sp where san_pham.ma_sp = ?');
        break;
    case '6':
        $stmt = $conn->prepare('select * from san_pham join Op_lung on san_pham.ma_sp = Op_lung.ma_sp where san_pham.ma_sp = ?');
        break;
}

$stmt->bind_param('s', $product_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row == null) {
    header('Location: products.php');
    exit();
}

$stmt->close();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech house</title>

    <link rel="stylesheet" href="../styles/admin/product_detail.css">
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
        <a href="./products.php" class="nav-active">
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
            <div class="mb-3 d-flex justify-content-between">
                <p class="m-0 fs-3">Thông tin sản phẩm</p>
                <div class="buttons d-flex gap-3 justify-content-end w-50">
                    <button class="btn btn-primary w-20" id="edit-btn">Chỉnh sửa</button>
                    <button class="btn btn-danger w-20">Xóa</button>
                </div>
            </div>

            <div class="product-info d-flex flex-column gap-3">
                
                <div class="info-wrapper container">
                    <div class="fs-4 mb-2">Thông tin cơ bản</div>
                    <div class="row">
                        <div class="info-box col-3">
                            <div class="info-type">
                                Mã sản phẩm
                            </div>
                            <div class="info-value">
                                <?php echo $row['ma_sp']; ?>
                            </div>
                        </div>

                        <div class="info-box col-7">
                            <div class="info-type">
                                Tên sản phẩm
                            </div>
                            <div class="info-value">
                                <?php echo $row['ten_sp']; ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="info-box col-3">
                            <div class="info-type">
                                Phân loại
                            </div>
                            <div class="info-value">
                                <?php
                                switch ($category) {
                                    case '0':
                                        echo 'Laptop';
                                        break;
                                    case '1':
                                        echo 'Mobile';
                                        break;
                                    case '2':
                                        echo 'Tablet';
                                        break;
                                    case '3':
                                        echo 'Tai nghe bluetooth';
                                        break;
                                    case '4':
                                        echo 'Ban phim';
                                        break;
                                    case '5':
                                        echo 'Sac du phong';
                                        break;
                                    case '6':
                                        echo 'Op lung';
                                        break;
                                }
                                ?>
                            </div>
                        </div>

                        <div class="info-box col-3">
                            <div class="info-type">
                                Hãng
                            </div>
                            <div class="info-value">
                                <?php echo $row['thuong_hieu']; ?>
                            </div>
                        </div>

                        <div class="info-box col-3">
                            <div class="info-type">
                                Màu sắc
                            </div>
                            <div class="info-value">
                                <?php echo $row['mau_sac']; ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="info-box col-3">
                            <div class="info-type">
                                Số lượng tồn kho
                            </div>
                            <div class="info-value">
                                <?php echo $row['sl_ton_kho']; ?>
                            </div>
                        </div>

                        <div class="info-box col-3">
                            <div class="info-type">
                                Giá thành
                            </div>
                            <div class="info-value">
                                <?php echo number_format($row['gia_thanh'], 0, '.', '.').' VND'; ?>
                            </div>
                        </div>



                        <div class="info-box col-3">
                            <div class="info-type">
                                Sales off
                            </div>
                            <div class="info-value">
                                <?php echo ($row['sale_off']*100).'%'; ?>
                            </div>
                        </div>
                    </div>

                </div>

                <div id="addtitional-info" class="info-wrapper container">
                    <div class="fs-4 mb-2">Thông tin kĩ thuật</div>
                    <div class="specification">
                        <?php
                        switch ($category) {
                            case '0':
                                echo '<div class="info-box">
                                            <div class="info-type">
                                                Bộ xử lý
                                            </div>
                                            <div class="info-value">
                                                '.$row['bo_xu_ly'].'
                                            </div>
                                        </div>
                                        <div class="info-box">
                                            <div class="info-type">
                                                Dung lượng pin
                                            </div>
                                            <div class="info-value">
                                                '.$row['dung_luong_pin'].'
                                            </div>
                                        </div>
                                        <div class="info-box">
                                            <div class="info-type">
                                                Kích thước màn hình
                                            </div>
                                            <div class="info-value">
                                                '.$row['kich_thuoc_man_hinh'].'
                                            </div>
                                        </div>
                                        <div class="info-box">
                                            <div class="info-type">
                                                Công nghệ màn hình
                                            </div>
                                            <div class="info-value">
                                                '.$row['cong_nghe_man_hinh'].'
                                            </div>
                                        </div>
                                        <div class="info-box">
                                            <div class="info-type">
                                                Hệ điều hành
                                            </div>
                                            <div class="info-value">
                                                '.$row['he_dieu_hanh'].'
                                            </div>
                                        </div>
                                        <div class="info-box">
                                            <div class="info-type">
                                                Ram
                                            </div>
                                            <div class="info-value">
                                                '.$row['ram'].'
                                            </div>
                                        </div>
                                        <div class="info-box">
                                            <div class="info-type">
                                                Bộ nhớ
                                            </div>
                                            <div class="info-value">
                                                '.$row['bo_nho'].'
                                            </div>
                                        </div>';
                                break;
                            case '1':
                            case '2':
                                echo '<div class="info-box">
                                            <div class="info-type">
                                                Bộ xử lý
                                            </div>
                                            <div class="info-value">
                                                '.$row['bo_xu_ly'].'
                                            </div>
                                        </div>
                                        <div class="info-box">
                                            <div class="info-type">
                                                Dung lượng pin
                                            </div>
                                            <div class="info-value">
                                                '.$row['dung_luong_pin'].'
                                            </div>
                                        </div>
                                        <div class="info-box">
                                            <div class="info-type">
                                                Kích thước màn hình
                                            </div>
                                            <div class="info-value">
                                                '.$row['kich_thuoc_man_hinh'].'
                                            </div>
                                        </div>
                                        <div class="info-box">
                                            <div class="info-type">
                                                Công nghệ màn hình
                                            </div>
                                            <div class="info-value">
                                                '.$row['cong_nghe_man_hinh'].'
                                            </div>
                                        </div>
                                        <div class="info-box">
                                            <div class="info-type">
                                                Hệ điều hành
                                            </div>
                                            <div class="info-value">
                                                '.$row['he_dieu_hanh'].'
                                            </div>
                                        </div>
                                        <div class="info-box">
                                            <div class="info-type">
                                                Bộ nhớ
                                            </div>
                                            <div class="info-value">
                                                '.$row['bo_nho'].'
                                            </div>
                                        </div>';
                                break;
                            case '3':
                                echo '<div class="row">
                                        <div class="info-box">
                                            <div class="info-type">
                                                Phạm vi kết nối
                                            </div>
                                            <div class="info-value">
                                                '.$row['pham_vi_ket_noi'].'
                                            </div>
                                        </div>
                                        <div class="info-box">
                                            <div class="info-type">
                                                Thời lượng pin
                                            </div>
                                            <div class="info-value">
                                                '.$row['thoi_luong_pin'].'
                                            </div>
                                        </div>
                                        <div class="info-box">
                                            <div class="info-type">
                                                Chống nước
                                            </div>
                                            <div class="info-value">
                                                '.$row['chong_nuoc'].'
                                            </div>
                                        </div>
                                        <div class="info-box">
                                            <div class="info-type">
                                                Công nghệ âm thanh
                                            </div>
                                            <div class="info-value">
                                                '.$row['cong_nghe_am_thanh'].'
                                            </div>
                                        </div>
                                    </div>';
                                break;
                            case '4':
                                echo '<div class="row">
                                        <div class="info-box">
                                            <div class="info-type">
                                                Keycap
                                            </div>
                                            <div class="info-value">
                                                '.$row['keycap'].'
                                            </div>
                                        </div>
                                        <div class="info-box">
                                            <div class="info-type">
                                                Số phím
                                            </div>
                                            <div class="info-value">
                                                '.$row['so_phim'].'
                                            </div>
                                        </div>
                                        <div class="info-box">
                                            <div class="info-type">
                                                Cổng kết nối
                                            </div>
                                            <div class="info-value">
                                                '.$row['cong_ket_noi'].'
                                            </div>
                                        </div>
                                    </div>';
                                break;
                            case '5':
                                echo '<div class="row">
                                        <div class="info-box">
                                            <div class="info-type">
                                                Dung lượng pin
                                            </div>
                                            <div class="info-value">
                                                '.$row['dung_luong_pin'].'
                                            </div>
                                        </div>
                                        <div class="info-box">
                                            <div class="info-type">
                                                Công suất
                                            </div>
                                            <div class="info-value">
                                                '.$row['cong_suat'].'
                                            </div>
                                        </div>
                                        <div class="info-box">
                                            <div class="info-type">
                                                Cổng kết nối
                                            </div>
                                            <div class="info-value">
                                                '.$row['cong_ket_noi'].'
                                            </div>
                                        </div>
                                        <div class="info-box">
                                            <div class="info-type">
                                                Chất liệu
                                            </div>
                                            <div class="info-value">
                                                '.$row['chat_lieu'].'
                                            </div>
                                        </div>
                                    </div>';
                                break;
                            case '6':
                                echo '<div class="row">
                                        <div class="info-box">
                                            <div class="info-type">
                                                Chất liệu
                                            </div>
                                            <div class="info-value">
                                                '.$row['chat_lieu'].'
                                            </div>
                                        </div>
                                        <div class="info-box">
                                            <div class="info-type">
                                                Độ dày
                                            </div>
                                            <div class="info-value">
                                                '.$row['do_day'].'
                                            </div>
                                        </div>
                                    </div>';
                                break;
                        }
                        ?>
                    </div>
                </div>

                <div class="info-wrapper container">
                    <div class="fs-4 mb-2">Hình ảnh và Mô tả</div>

                    <div class="row">
                        <div class="info-box type-image">
                            <div class="info-type">
                                Hình ảnh sản phẩm
                            </div>
                            <div class="info-value">
                                <img src="<?php echo $row['hinh_anh']; ?>" alt="Product image">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="info-box">
                            <div class="info-type">
                                Mô tả sản phẩm
                            </div>
                            <div class="info-value p-2" style="text-align: justify; font-size: 1rem;">
                                <?php echo $row['mo_ta']; ?>
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
<script>
    $("#edit-btn").click(function () {
        let des = `./product_edit.php?product_id=<?php echo $product_id; ?>`;
        window.location.href = des;
    });
</script>
</html>
<?php
$conn->close();
?>