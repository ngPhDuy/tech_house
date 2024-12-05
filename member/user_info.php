<?php
session_start();

if (!isset($_SESSION['ten_dang_nhap'])) {
    header("Location: ../public/login.php");
    return;
}

if ($_SESSION['phan_loai_tk'] == 'nv') {
    header("Location: ../admin/homepage.php");
    return;
}

$conn = new mysqli('localhost', 'root', '', 'tech_house_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("select * from tai_khoan where ten_dang_nhap = ?");
$stmt->bind_param("s", $_SESSION['ten_dang_nhap']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="../styles/public/custom.css" rel="stylesheet">
    <link href="../styles/member/user_info.css" rel="stylesheet">
    <title>Người dùng</title>
</head>
<body>
    <div class="page-wrapper">
        <header>
            <div class="row bg-primary align-items-center">
                <div class="logo col-lg-3 col-3 text-white d-flex justify-content-center align-items-center ps-3">
                    <a href="../public/product_list.php" class="text-white text-center">
                        <h1 class="fw-bold">Tech House</h1>
                    </a>
                </div>
                <div class="search-bar col d-flex align-items-center bg-secondary">
                    <input type="text" id="search-input" class="search-input bg-secondary border-0" 
                    placeholder="Tìm kiếm sản phẩm..">
                    <button type="button" class="search-btn border border-0 p-0 m-0"
                    id="search-btn">
                        <img src="../imgs/icons/search.png" alt="search" width="24" height="24">
                    </button>
                </div>
                <div class="login-cart col-lg-3 col-4 d-flex align-items-center justify-content-evenly">
                    <div class="login w-50 d-flex justify-content-center">
                        <?php
                        if (isset($_SESSION['ten_dang_nhap'])) {
                            echo 
                            '<a href="./user_info.php" class="fw-bold text-white">
                                <img src="../imgs/icons/user.png" alt="user" width="32" height="32">
                                <span>'.$_SESSION['ho_ten'].'</span></a>';
                            echo '
                            <div class="dropdown-content">
                                <div><a href="./user_info.php">Thông tin cá nhân</a></div>
                                <div><a href="./order_history_dashboard.php">Lịch sử mua hàng</a></div>
                                <div><a href="./cart.php">Giỏ hàng</a></div>
                                <div><a href="../public/logout.php">Đăng xuất</a></div>
                            </div>';
                        } else {
                            echo 
                            '<a href="../public/login.php" class="fw-bold text-white">
                                <img src="../imgs/icons/user.png" alt="user" width="32" height="32">
                                <span>Đăng nhập</span>
                            </a>';
                        }
                        ?>
                    </div>
                    <div class="cart w-50 d-flex justify-content-center">
                        <a href="./love_list.php" class="fw-bold text-white">
                          <svg
                          xmlns="http://www.w3.org/2000/svg"
                          viewBox="0 0 24 24"
                          width="30"
                          height="30"
                          stroke="white"
                          fill="none"
                          stroke-width="1"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          class="heart-icon me-1"
                          style="cursor: pointer;"
                          >
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                          </svg>
                          <span>Yêu thích</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="tabs row justify-content-between align-items-center bg-white p-3 ps-5 gap-3">
                <div class="tab col">
                    <a href="../index.php">
                        <img src="../imgs/icons/house.png" alt="home" width="24" height="24">
                        Trang chủ
                    </a>
                </div>
                <div class="tab col">
                    <a href="../public/product_list.php?product_type=1">
                        <img src="../imgs/icons/phone_iphone.png" alt="phone" width="24" height="24">
                        Điện thoại
                    </a>
                </div>  
                <div class="tab col">
                    <a href="../public/product_list.php?product_type=0">
                        <img src="../imgs/icons/laptop_mac.png" alt="laptop" width="24" height="24">
                        Laptop
                    </a>
                </div>
                <div class="tab col">
                    <a href="../public/product_list.php?product_type=2">
                        <img src="../imgs/icons/tablet_android.png" alt="tablet" width="24" height="24">
                        Tablet
                    </a>
                </div>
                <div class="tab col">
                    <a href="../public/product_list.php?product_type=3">
                        <img src="../imgs/icons/gamepad.png" alt="other" width="24" height="24">
                        Phụ kiện
                        <img src="../imgs/icons/keyboard_arrow_down.png" alt="arrow-down" width="24" height="24">
                    </a>
                    <div class="dropdown-content">
                        <div><a href="../public/product_list.php?product_type=3">Tai nghe</a></div>
                        <div><a href="../public/product_list.php?product_type=4">Bàn phím</a></div>
                        <div><a href="../public/product_list.php?product_type=5">Sạc dự phòng</a></div>
                        <div><a href="../public/product_list.php?product_type=6">Ốp lưng</a></div>
                    </div>
                </div>
                <div class="col-4"></div>
            </div>
        </header>
        <main>
            <div class="hello ps-3 fw-bold">
                <h3>
                    Xin chào, <?php echo $_SESSION['ho_ten']; ?>
                </h3>
            </div>
            <div class="container-fluid mt-3">
                <div class="d-flex justify-content-center justify-content-md-start">
                    <nav class="col sidebar d-none d-md-block">
                        <div class="sidebar-sticky">
                            <ul class="nav border rounded-3 flex-column">
                                <li class="nav-item active">
                                    <a href="user_info.php">
                                        <img src="../imgs/icons/setting_white.png" alt="setting" width="22" height="22">
                                        <span>Thông tin cá nhân</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./order_history_dashboard.php">
                                        <span class="order-history-icon"></span>
                                        <!-- <img src="../imgs/icons/order_history.png" alt="order_history" width="22" height="22"> -->
                                        <span>Lịch sử mua hàng</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./cart.php">
                                        <!-- <img src="../imgs/icons/shopping_cart.png" alt="shopping_cart" width="22" height="22"> -->
                                        <span class="cart-icon"></span>
                                        <span>Giỏ hàng</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./logout.php">
                                        <!-- <img src="../imgs/icons/log-out.png" alt="log-out" width="22" height="22"> -->
                                        <span class="log-out-icon"></span>
                                        <span>Đăng xuất</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                    <div class="col-md-9 ml-sm-auto col-lg-10 px-4">
                        <div class="container mt-3 mb-5">
                            <div class="card p-3 border border-secondary rounded-3 account-info-card">
                                <div class="row border-bottom pb-2 mb-3">
                                    <div class="col-12">THÔNG TIN CÁ NHÂN</div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-12 mb-lg-0 mb-3 position-relative text-center 
                                    d-flex justify-content-center align0items-center">
                                        <?php
                                        if ($user['avatar']) {
                                            echo '<img src="../imgs/avatars/'.$user['avatar'].'" alt="avatar" class="profile-img">';
                                        } else {
                                            echo '<img src="../imgs/avatars/default.png" alt="avatar" class="profile-img">';
                                        }
                                        ?>
                                    </div>
                                    <div class="col">
                                        <div class="row mb-3 text-center">
                                            <div class="col-md-6 d-flex flex-column align-items-start mb-3 mb-md-0">
                                                <div><p class="m-0">Họ và tên<p></div>
                                                <p class="m-0 border border-1 p-2 w-100 text-start"><?php echo $user['ho_va_ten']; ?></p>
                                            </div>
                                            <div class="col-md-6 d-flex flex-column align-items-start">
                                                <div><p class="m-0">Tên đăng nhập<p></div>
                                                <p class="m-0 border border-1 p-2 w-100 text-start"><?php echo $user['ten_dang_nhap']; ?></p>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6 mb-3 mb-md-0">
                                                <div class="d-flex flex-column align-items-start">
                                                    <div><p class="m-0">Số điện thoại<p></div>
                                                    <p class="m-0 border border-1 p-2 w-100 text-start"><?php echo $user['sdt']; ?></p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="d-flex flex-column align-items-start">
                                                    <div><p class="m-0">Email<p></div>
                                                    <p class="m-0 border border-1 p-2 w-100 text-start"><?php echo $user['email'] ?? "Chưa cập nhật";?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <div class="d-flex flex-column align-items-start">
                                                    <div><p class="m-0">Địa chỉ<p></div>
                                                    <p class="m-0 border border-1 p-2 w-100 text-start"><?php echo $user['dia_chi'] ?? "Chưa cập nhật";?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end buttons">
                                            <button type="button" class="btn change-pwd-btn">Đổi mật khẩu</button>
                                            <button type="button" class="btn btn-primary edit-btn">Cập nhật</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <footer class="row bg-primary text-white p-3 justify-content-center">
            <div class="row justify-content-evenly infomations">
                <div class="contact col-sm-3 col-7 pt-sm-4">
                    <h5>Tổng đài hỗ trợ</h5>
                    <div class="phone-wrapper">
                        <img src="../imgs/icons/call_icon.png" alt="phone" width="24" height="24">
                        <span>Gọi mua:</span>
                    </div>
                    <p>1922-6067 (8:00 - 21:30)</p>
                    <div class="phone-wrapper">
                        <img src="../imgs/icons/call_icon.png" alt="phone" width="24" height="24">
                        <span>Bảo hành:</span>
                    </div>
                    <p>1922-6068 (8:00 - 21:30)</p>
                    <div class="phone-wrapper">
                        <img src="../imgs/icons/call_icon.png" alt="phone" width="24" height="24">
                        <span>Khiếu nại:</span>
                    </div>
                    <p>1922-6069 (8:00 - 21:30)</p>
                </div>
                <!-- <div class="col-1"></div> -->
                <div class="category col-sm-4 col-5">
                    <h5>Danh mục sản phẩm</h5>
                    <ul class="d-flex flex-column gap-1">
                        <li><a href="#">Điện thoại</a></li>
                        <li><a href="#">Laptop</a></li>
                        <li><a href="#">Tablet</a></li>
                        <li><a href="#">Tai nghe</a></li>
                        <li><a href="#">Bàn phím</a></li>
                        <li><a href="#">Sạc dự phòng</a></li>
                        <li><a href="#">Bao da, ốp lưng</a></li>
                    </ul>
                </div>
                <div class="other-info col-sm-4 col-5">
                    <h5>Các thông tin khác</h5>
                    <ul class="d-flex flex-column gap-1">
                        <li><a href="#">Giới thiệu công ty</a></li>
                        <li><a href="#">Chính sách bảo hành</a></li>
                        <li><a href="#">Góp ý, khiếu nại</a></li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <p class="text-center m-0">© 2024 Tech House. All rights reserved.</p>
            </div>
        </footer>
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Chỉnh sửa thông tin cá nhân</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="d-flex flex-column justify-content-center align-items-center"
                        enctype="multipart/form-data" id="edit_form">
                            <div class="mb-2">
                                <label for="ho_va_ten" class="form-label">Họ và tên</label>
                                <?php echo '<input type="text" class="form-control" id="ho_va_ten"
                                data-default-value="'.$user['ho_va_ten'].'" value="'.$user['ho_va_ten'].'" name="ho_va_ten">';?>
                            </div>
                            <div class="mb-2">
                                <label for="sdt" class="form-label">Số điện thoại</label>
                                <?php echo '<input type="text" class="form-control" id="sdt" 
                                data-default-value="'.$user['sdt'].'" value="'.$user['sdt'].'" name="sdt">';?>
                            </div>
                            <div class="mb-2">
                                <label for="email" class="form-label">Email</label>
                                <?php echo '<input type="email" class="form-control" id="email" 
                                data-default-value="'.$user['email'].'" value="'.$user['email'].'" name="email">';?>
                            </div>
                            <div class="mb-2">
                                <label for="dia_chi" class="form-label">Địa chỉ</label>
                                <?php echo '<input type="text" class="form-control" id="dia_chi" 
                                data-default-value="'.$user['dia_chi'].'" value="'.$user['dia_chi'].'" name="dia_chi">';?>
                            </div>
                            <div class="mb-2">
                                <label for="avatar" class="form-label">Ảnh đại diện</label>
                                <input type="file" class="form-control" id="avatar" name="avatar">
                            </div>
                            <button class="btn btn-primary" id="submit-edit-btn">Xác nhận</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="changePwdModal" tabindex="-1" aria-labelledby="changePwdModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="changePwdModalLabel">Đổi mật khẩu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body d-flex flex-column justify-content-center align-items-center">
                        <form class="d-flex flex-column justify-content-center align-items-center w-100" id="change_pwd_form">
                            <div class="mb-2">
                                <label for="old_pwd" class="form-label">Mật khẩu cũ</label>
                                <input type="password" class="form-control" id="old_pwd" name="old_pwd">
                            </div>
                            <div class="mb-2">
                                <label for="new_pwd" class="form-label">Mật khẩu mới</label>
                                <input type="password" class="form-control" id="new_pwd" name="new_pwd">
                            </div>
                            <div class="mb-2">
                                <label for="confirm_pwd" class="form-label">Xác nhận mật khẩu</label>
                                <input type="password" class="form-control" id="confirm_pwd" name="confirm_pwd">
                            </div>
                            <button class="btn btn-primary mt-3" id="submit-change-pwd-btn">Xác nhận</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" tabindex="-1" id="message-modal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <p class="m-0 fs-5 text-center p-3"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="../node_modules/jquery/dist/jquery.min.js"></script>
<script src="../scripts/public/search.js"></script>
<script>
    $(".edit-btn").click(function() {
        $("#editModal").modal("show");
    });

    $("#submit-edit-btn").click(function(e) {
        e.preventDefault();

        let ho_va_ten = $("#ho_va_ten").val();
        let sdt = $("#sdt").val();
        let email = $("#email").val();
        let dia_chi = $("#dia_chi").val();

        if (!ho_va_ten) {
            $("#ho_va_ten").val($("#ho_va_ten").attr("data-default-value"));
        }

        if (!sdt) {
            $("#sdt").val($("#sdt").attr("data-default-value"));
        }

        if (!email) {
            $("#email").val($("#email").attr("data-default-value"));
        }

        if (!dia_chi) {
            $("#dia_chi").val($("#dia_chi").attr("data-default-value"));
        }

        let editForm = $("#edit_form")[0];
        let formData = new FormData(editForm);

        formData.append('ten_dang_nhap', '<?php echo $user['ten_dang_nhap']; ?>');
        $.ajax({
            url: "./update_user_info.php",
            type: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                if (data === "Cập nhật thành công") {
                    $("#message-modal .modal-body p").text(data);
                    $("#message-modal .modal-body p").css("color", "green");
                    $("#message-modal").modal("show");
                    $("#editModal").modal("hide");
                    location.reload();
                } else {
                    $("#message-modal .modal-body p").text(data);
                    $("#message-modal .modal-body p").css("color", "red");
                    $("#message-modal").modal("show");
                    $("#editModal").modal("hide");
                }
            }
        })
    });

    $(".change-pwd-btn").click(function() {
        $("#changePwdModal").modal("show");
    });

    $("#submit-change-pwd-btn").click(function(e) {
        e.preventDefault();

        let old_pwd = $("#old_pwd").val();
        let new_pwd = $("#new_pwd").val();
        let confirm_pwd = $("#confirm_pwd").val();

        if (!old_pwd || !new_pwd || !confirm_pwd) {
            $("#message-modal .modal-body p").text("Vui lòng điền đầy đủ thông tin");
            $("#message-modal .modal-body p").css("color", "red");
            $("#message-modal").modal("show");
            $("#changePwdModal").modal("hide");
            return;
        }

        if (new_pwd !== confirm_pwd) {
            $("#message-modal .modal-body p").text("Mật khẩu xác nhận không khớp");
            $("#message-modal .modal-body p").css("color", "red");
            $("#message-modal").modal("show");
            $("#changePwdModal").modal("hide");
            return;
        }

        $.ajax({
            url: "../public/change_password.php",
            type: "POST",
            data: {
                old_pwd: old_pwd,
                new_pwd: new_pwd
            },
            success: function(data) {
                if (data === "Đổi mật khẩu thành công") {
                    $("#message-modal .modal-body p").text(data);
                    $("#message-modal .modal-body p").css("color", "green");
                    $("#message-modal").modal("show");
                    $("#changePwdModal").modal("hide");
                } else {
                    $("#message-modal .modal-body p").text(data);
                    $("#message-modal .modal-body p").css("color", "red");
                    $("#message-modal").modal("show");
                    $("#changePwdModal").modal("hide");
                }
            }
        });
    });
</script>
</body>
</html>
<?php
$conn->close();
?>