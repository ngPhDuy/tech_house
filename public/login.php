<?php
session_start();

if (isset($_SESSION['ten_dang_nhap']) && $_SESSION['phan_loai_tk'] == 'tv') {
    header("Location: ../index.php");
    return;

} else if (isset($_SESSION['ten_dang_nhap']) && $_SESSION['phan_loai_tk'] == 'nv') {
    header("Location: ../admin/homepage.php");
    return;
}

if (!isset($_SESSION['ten_dang_nhap']) && isset($_COOKIE['ten_dang_nhap'])) {
    $_SESSION['ten_dang_nhap'] = $_COOKIE['ten_dang_nhap'];
    $_SESSION['phan_loai_tk'] = $_COOKIE['phan_loai_tk'];
    $_SESSION['ho_ten'] = $_COOKIE['ho_ten'];

    if ($_SESSION['phan_loai_tk'] == 'tv') {
        header("Location: ../index.php");
        return;
    } else if ($_SESSION['phan_loai_tk'] == 'nv') {
        header("Location: ../admin/homepage.php");
        return;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $conn = new mysqli("localhost", "root", "", "tech_house_db");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember = $_POST['remember'];

    if (substr($username, 0, 1) == '$') {
        $stmt = $conn->prepare("SELECT * FROM tai_khoan 
        WHERE ten_dang_nhap = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $verify = password_verify($password, $row['mat_khau']);

            if (!$verify) {
                echo "Tên đăng nhập hoặc mật khẩu không đúng";
                $conn->close();
                exit();
            }

            $_SESSION['ten_dang_nhap'] = $row['ten_dang_nhap'];
            $tempArr = explode(" ", $row['ho_va_ten']);
            $_SESSION['ho_ten'] = $tempArr[count($tempArr) - 2] . " " . $tempArr[count($tempArr) - 1];
            $_SESSION['phan_loai_tk'] = 'nv';

            if ($remember) {
                setcookie("ten_dang_nhap", $_SESSION['ten_dang_nhap'], time() + 3600 * 24 * 30, "/");
                setcookie("phan_loai_tk", 'nv', time() + 3600 * 24 * 30, "/");
                setcookie("ho_ten", $_SESSION['ho_ten'], time() + 3600 * 24 * 30, "/");
            }

            echo "OK: ../admin/homepage.php";
        } else {
            echo "Tài khoản không tồn tại";
        }

    } else {
        $stmt = $conn->prepare("SELECT * FROM tai_khoan tk JOIN thanh_vien tv 
        ON tk.ten_dang_nhap = tv.ten_dang_nhap WHERE tk.ten_dang_nhap = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result(); 

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $verify = password_verify($password, $row['mat_khau']);

            if (!$verify) {
                echo "Tên đăng nhập hoặc mật khẩu không đúng";
                $conn->close();
                exit();
            }

            if ($row['active_status'] == false) {
                echo "Tài khoản đã bị khóa";
                $conn->close();
                exit();
            }

            $_SESSION['ten_dang_nhap'] = $row['ten_dang_nhap'];
            $tempArr = explode(" ", $row['ho_va_ten']);
            $_SESSION['ho_ten'] = $tempArr[count($tempArr) - 2] . " " . $tempArr[count($tempArr) - 1];
            $_SESSION['phan_loai_tk'] = 'tv';

            if ($remember) {
                setcookie("ten_dang_nhap", $_SESSION['ten_dang_nhap'], time() + 3600 * 24 * 30, "/");
                setcookie("phan_loai_tk", 'tv', time() + 3600 * 24 * 30, "/");
                setcookie("ho_ten", $_SESSION['ho_ten'], time() + 3600 * 24 * 30, "/");
            }

            echo "OK: ../index.php";
        } else {
            echo "Tài khoản không tồn tại";
        }
    }

    $conn->close();
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="../styles/public/custom.css" rel="stylesheet">
    <link href="../styles/public/login.css" rel="stylesheet">
    <title>Đăng nhập</title>
</head>
<body>
    <div class="page-wrapper">
        <header>
            <div class="row bg-primary align-items-center">
                <div class="logo col-lg-3 col-3 text-white d-flex justify-content-center align-items-center ps-3">
                    <a href="./product_list.php" class="text-white text-center">
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
                            '<a href="../member/user_info.php" class="fw-bold text-white">
                                <img src="../imgs/icons/user.png" alt="user" width="32" height="32">
                                <span>'.$_SESSION['ho_ten'].'</span></a>';
                            echo '
                            <div class="dropdown-content">
                                <div><a href="../member/user_info.php">Thông tin cá nhân</a></div>
                                <div><a href="../member/change_password.html">Đổi mật khẩu</a></div>
                                <div><a href="../member/order_history_dashboard.php">Lịch sử mua hàng</a></div>
                                <div><a href="./logout.php">Đăng xuất</a></div>
                            </div>';
                        } else {
                            echo 
                            '<a href="./login.php" class="fw-bold text-white">
                                <img src="../imgs/icons/user.png" alt="user" width="32" height="32">
                                <span>Đăng nhập</span>
                            </a>';
                        }
                        ?>
                    </div>
                    <div class="cart w-50 d-flex justify-content-center">
                        <a href="../member/love_list.php" class="fw-bold text-white">
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
                    <a href="./product_list.php?product_type=1">
                        <img src="../imgs/icons/phone_iphone.png" alt="phone" width="24" height="24">
                        Điện thoại
                    </a>
                </div>  
                <div class="tab col">
                    <a href="./product_list.php?product_type=0">
                        <img src="../imgs/icons/laptop_mac.png" alt="laptop" width="24" height="24">
                        Laptop
                    </a>
                </div>
                <div class="tab col">
                    <a href="./product_list.php?product_type=2">
                        <img src="../imgs/icons/tablet_android.png" alt="tablet" width="24" height="24">
                        Tablet
                    </a>
                </div>
                <div class="tab col">
                    <a href="./product_list.php?product_type=3">
                        <img src="../imgs/icons/gamepad.png" alt="other" width="24" height="24">
                        Phụ kiện
                        <img src="../imgs/icons/keyboard_arrow_down.png" alt="arrow-down" width="24" height="24">
                    </a>
                    <div class="dropdown-content">
                        <div><a href="./product_list.php?product_type=3">Tai nghe</a></div>
                        <div><a href="./product_list.php?product_type=4">Bàn phím</a></div>
                        <div><a href="./product_list.php?product_type=5">Sạc dự phòng</a></div>
                        <div><a href="./product_list.php?product_type=6">Ốp lưng</a></div>
                    </div>
                </div>
                <div class="col-4"></div>
            </div>
        </header>
        <main>
            <div class="form-wrapper container bg-white">
                <div class="row">
                            <div class="login-option-item col selected">
                                <a href="./login.php" class="login-option-item-link fw-normal fs-5">
                                    Đăng nhập
                                </a>
                            </div>
                            <div class="login-option-item col">
                                <a href="./register.php" class="login-option-item-link fw-normal fs-5">
                                    Đăng ký
                                </a>
                            </div>
                </div>
                <div class="row py-3 px-3">
                        <form class="d-flex flex-column justify-content-center">
                            <div class="mb-3">
                                <label for="username" class="form-label">Tên đăng nhập</label>
                                <input type="text" class="form-control" id="username" name="username">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                            <div class="mb-3 d-flex justify-content-between">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember">
                                    <label class="form-check-label" for="remember">
                                        Ghi nhớ đăng nhập
                                    </label>
                                </div>
                                <a href="#" class="forget-pwd text-decoration-none">Quên mật khẩu?</a>
                            </div>
                            <button type="button" class="btn-lg btn-primary w-75">Đăng nhập</button>
                        </form>
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
        <div class="modal" tabindex="-1" id="message-modal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <p class="my-3">Nội dung</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="../node_modules/jquery/dist/jquery.min.js"></script>
<script src="../scripts/public/search.js"></script>
<script>
    $("form button").click(function(e) {
        e.preventDefault();

        let username = $("input[name='username']").val();
        let password = $("input[name='password']").val();
        let remember = $("input[type='checkbox']").prop("checked");

        console.log(username);
        console.log(password);
        if (remember) {
            console.log("Remember");
        } else {
            console.log("Not remember");
        }
        if (username == "" || password == "") {
            $("#message-modal .modal-body p").text("Vui lòng nhập đầy đủ thông tin");
            $("#message-modal").css("color", "red");
            $("#message-modal").modal("show");
        } else {
            $.ajax({
                url: "./login.php",
                type: "POST",
                data: {
                    username: username,
                    password: password,
                    remember: remember
                },
                success: function(data) {
                    let tempArr = data.split(": ");
                    if (tempArr.length == 2 && tempArr[0] == "OK") {
                        window.location.href = tempArr[1];
                    } else {
                        $("#message-modal .modal-body p").text(data);
                        $("#message-modal").css("color", "red");
                        $("#message-modal").modal("show");
                    }
                }
            });
        }
    });
</script>
</body>
</html>