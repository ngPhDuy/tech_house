<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
      crossorigin="anonymous"
    />
    <link href="../styles/public/custom.css" rel="stylesheet" />
    <link href="../styles/public/empty_cart.css" rel="stylesheet" />
    <title>Empty Cart</title>
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
                    <img src="../imgs/icons/search.png" alt="search" width="24" height="24">
                    <input type="text" class="search-input bg-secondary border-0" 
                    placeholder="Tìm kiếm sản phẩm.." link-to="../public/product_list.php">
                </div>
                <div class="login-cart col-lg-3 col-4 d-flex align-items-center justify-content-evenly">
                    <div class="login w-50">
                        <?php
                        if (isset($_SESSION['ten_dang_nhap'])) {
                            echo 
                            '<a href="./user_info.php" class="fw-bold text-white">
                                <img src="../imgs/icons/user.png" alt="user" width="32" height="32">
                                '.$_SESSION['ho_ten'].'</a>';
                            echo '
                            <div class="dropdown-content">
                                <div><a href="./user_info.php">Thông tin cá nhân</a></div>
                                <div><a href="./change_password.html">Đổi mật khẩu</a></div>
                                <div><a href="./order_history_dashboard.php">Lịch sử mua hàng</a></div>
                                <div><a href="../public/logout.php">Đăng xuất</a></div>
                            </div>';
                        } else {
                            echo 
                            '<a href="../public/login.php" class="fw-bold text-white">
                                <img src="../imgs/icons/user.png" alt="user" width="32" height="32">
                                Đăng nhập
                            </a>';
                        }
                        ?>
                    </div>
                    <div class="cart w-50">
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
                            Yêu thích
                        </a>
                    </div>
                </div>
            </div>
            <div class="tabs row justify-content-between align-items-center bg-white p-3 ps-5">
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

      <main class="pt-0 pb-0">
        <div class="container bg-white pt-4">
          <div class="justify-content-center align-items-center d-flex">
            <img
              src="../imgs/icons/empty_cart.png"
              alt="Giỏ hàng trống"
              srcset=""
              width="495"
              height="351"
              style="max-width: 100%; height: auto"
            />
          </div>
          <div class="text-center d-flex flex-column gap-3 mt-3">
            <div class="justify-content-center align-items-center d-flex">
              <h1 class="fs-3 w-100">
                Chưa có sản phẩm nào trong giỏ hàng!
              </h1>
            </div>
            <div class="justify-content-center align-items-center d-flex">
              <h2 class="fs-6 w-100">
                Cùng mua sắm hàng ngàn sản phẩm với TechHouse nhé!
              </h2>
            </div>
          </div>
          <div
            class="justify-content-center align-items-center d-flex pb-4 pt-4 text-primary"
          >
            <a class="btn btn-primary p-2 text-uppercase fs-5" href="../index.php" style="width: fit-content">
                <svg
                xmlns="http://www.w3.org/2000/svg"
                width="1em"
                height="1em"
                viewBox="0 0 32 32"
                style="margin-right: 4px"
                >
                    <path
                      fill="currentColor"
                      d="M16.81 4.3a1.25 1.25 0 0 0-1.62 0l-9.75 8.288a1.25 1.25 0 0 0-.44.953V26.5a.5.5 0 0 0 .5.5h5a.5.5 0 0 0 .5-.5v-7a2.5 2.5 0 0 1 2.5-2.5h5.004a2.5 2.5 0 0 1 2.5 2.5v7a.5.5 0 0 0 .5.5H26.5a.5.5 0 0 0 .5-.5V13.54a1.25 1.25 0 0 0-.44-.952zm-2.915-1.523a3.25 3.25 0 0 1 4.21 0l9.75 8.287A3.25 3.25 0 0 1 29 13.54V26.5a2.5 2.5 0 0 1-2.5 2.5h-4.996a2.5 2.5 0 0 1-2.5-2.5v-7a.5.5 0 0 0-.5-.5H13.5a.5.5 0 0 0-.5.5v7a2.5 2.5 0 0 1-2.5 2.5h-5A2.5 2.5 0 0 1 3 26.5V13.54a3.25 3.25 0 0 1 1.145-2.476z"
                    />
                </svg>
                Về trang chủ
            </a>
          </div>
        </div>
      </main>

      <footer class="row bg-primary text-white p-3 justify-content-center">
        <div class="row justify-content-evenly">
          <div class="col-3 pt-4">
            <h5>Tổng đài hỗ trợ</h5>
            <div class="phone-wrapper">
              <img
                src="../imgs/icons/call_icon.png"
                alt="phone"
                width="24"
                height="24"
              />
              <span>Gọi mua:</span>
            </div>
            <p>1922-6067 (8:00 - 21:30)</p>
            <div class="phone-wrapper">
              <img
                src="../imgs/icons/call_icon.png"
                alt="phone"
                width="24"
                height="24"
              />
              <span>Bảo hành:</span>
            </div>
            <p>1922-6068 (8:00 - 21:30)</p>
            <div class="phone-wrapper">
              <img
                src="../imgs/icons/call_icon.png"
                alt="phone"
                width="24"
                height="24"
              />
              <span>Khiếu nại:</span>
            </div>
            <p>1922-6069 (8:00 - 21:30)</p>
          </div>
          <!-- <div class="col-1"></div> -->
          <div class="category col-4">
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
          <div class="other-info col-4">
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
    </div>
  </body>
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"
  ></script>
</html>