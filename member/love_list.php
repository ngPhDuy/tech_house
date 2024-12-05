<?php
session_start();

if (!isset($_SESSION['ten_dang_nhap'])) {
  header('Location: ../public/login.php');
  exit();
}

$conn = new mysqli('localhost', 'root', '', 'tech_house_db');

$username = $_SESSION['ten_dang_nhap'];
$stmt = $conn->prepare('select * from Danh_sach_yeu_thich
join San_pham on Danh_sach_yeu_thich.ma_sp = San_pham.ma_sp
where thanh_vien = ?');
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

$love_items = [];
while ($row = $result->fetch_assoc()) {
  $love_items[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" >
    <meta name="viewport" content="width=device-width, initial-scale=1.0" >
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
      crossorigin="anonymous"
    >
    <link href="../styles/public/custom.css" rel="stylesheet" >
    <link href="../styles/member/cart.css" rel="stylesheet" >
    <title>Danh sách yêu thích</title>
    <style>
      th, td {
        padding: 0.5rem;
      }
    </style>
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

      <main class="pt-3 pb-0 d-flex bg-white justify-content-center min-vh-50 container pb-3">
          <div class="row p-2 w-100 mb-3">
            <div class="col-12 d-flex flex-column p-0">
              <h5 class="fs-3">Danh sách yêu thích</h5>
              <?php
              if (count($love_items) == 0) {
                echo '<p class="text-center m-0 mt-5 fs-5 fw-lighter">Không có sản phẩm nào trong danh sách yêu thích</p>';
              } else {
              ?>
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th class="w-40">Sản phẩm</th>
                    <th class="w-20">Thương hiệu</th>
                    <th class="w-20">Đơn giá (VND)</th>
                    <th class=""></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  foreach($love_items as $item) {
                    echo '
                    <tr class="product" data-id="'.$item['ma_sp'].'">
                      <td>
                        <a href="../public/product_detail.php?product_id='.$item['ma_sp'].'">
                        <img src="'.$item['hinh_anh'].'" alt="'.$item['ten_sp'].'" width="40" height="40">
                        <span>'.$item['ten_sp'].'</span>
                        </a>
                      </td>
                      <td>'.$item['thuong_hieu'].'</td>
                      <td>'.number_format($item['gia_thanh'] * (1 - $item['sale_off']), 0, '.', '.').'</td>
                      <td>
                        <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        width="20"
                        height="20"
                        fill="red"
                        stroke="red"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="heart-icon"
                        style="cursor: pointer;"
                        data-id="'.$item['ma_sp'].'"
                        >
                          <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                      </td>
                    </tr>';
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
              }
              ?>
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
      <div class="modal" id="delete-modal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Xóa khỏi giỏ hàng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="m-0">Bạn có chắc chắn xóa sản phẩm này khỏi giỏ hàng?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="button" class="btn btn-danger" id="confirm-delete-btn">Xác nhận</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
  
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"
  ></script>
  <script src="../node_modules/jquery/dist/jquery.min.js"></script>
  <script src="../scripts/public/search.js"></script>
  <script src="../scripts/public/pagination.js"></script>
  <script>
    let paginationLength = 5;
    let productsPerPage = 5;
    let paginationFunc = pagination(paginationLength, productsPerPage, $('.product'));

    paginationFunc(1);

    $('.heart-icon').each(function() {
      $(this).click(function() {
        const productId = $(this).attr('data-id');
        let add = $(this).attr('fill') == 'white';
        console.log(productId);
        $.ajax({
          url: '../member/toggle_favorite.php',
          type: 'POST',
          data: {
            product_id: productId,
            add: add
          },
          success: function(response) {
            let thisIcon = $(`svg[data-id="${productId}"]`);
            console.log(response);
            if (response == "Xóa sản phẩm khỏi yêu thích thành công") {
              thisIcon.attr('fill', 'white');
              thisIcon.attr('stroke', 'gray');
            } else if (response == "Thêm sản phẩm vào yêu thích thành công") {
              thisIcon.attr('fill', 'red');
              thisIcon.attr('stroke', 'red');
            }
          }
        });
      });
    });
  </script>
  </body>
</html>
<?php 
$conn->close();
?>