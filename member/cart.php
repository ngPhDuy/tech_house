<?php
session_start();

if (!isset($_SESSION['ten_dang_nhap'])) {
  header('Location: ../public/login.php');
  exit();
}

$conn = new mysqli('localhost', 'root', '', 'tech_house_db');

$username = $_SESSION['ten_dang_nhap'];
$stmt = $conn->prepare('select * from gio_hang join san_pham
on gio_hang.ma_sp = san_pham.ma_sp where thanh_vien = ?');
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if ($result->num_rows == 0) {
  header('Location: ./empty_cart.php');
  $conn->close();
  exit();
}

$cart_items = array();
while ($row = $result->fetch_assoc()) {
  $cart_items[] = $row;
  $cart_items[count($cart_items) - 1]['gia_thuc_te'] = $row['gia_thanh'] * (1 - $row['sale_off']);
  $cart_items[count($cart_items) - 1]['tong_gia'] = $row['so_luong'] * $cart_items[count($cart_items) - 1]['gia_thuc_te'];
}
$conn->close();
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
    <link href="../styles/member/cart.css" rel="stylesheet" />
    <title>Giỏ hàng của bạn</title>
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
                    <input type="text" class="search-input bg-secondary border-0" placeholder="Tìm kiếm sản phẩm..">
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
                        <a href="./cart.php" class="fw-bold text-white">
                            <img src="../imgs/icons/cart.png" alt="user" width="32" height="32">
                            Giỏ hàng
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

      <main class="pt-3 pb-0 d-flex bg-white justify-content-center min-vh-100 container">
          <div class="row p-2">
            <div class="col-12 col-lg-9 d-flex flex-column p-0">
              <h5 class="fs-3">Giỏ hàng</h5>
              <table class="table table-striped">
                <thead>
                  <th class="w-10"></th>
                    <th clas="w-40">Sản phẩm</th>
                    <th class="">Đơn giá (VND)</th>
                    <th class="w-20">Số lượng</th>
                    <th class="">Tổng cộng (VND)</th>
                </thead>
                <tbody>
                  <?php
                  foreach ($cart_items as $item) {
                    echo '
                    <tr data-id="'.$item['ma_sp'].'">
                        <td>
                            <button type="button" class="p-0 border border-0 delete-btn"
                            data-id="'.$item['ma_sp'].'"><img src="../imgs/icons/XCircle.png" alt="delete" width="24" height="24"></button>
                        </td>
                        <td>
                            <div class="d-flex m-0 p-0">
                                <img src="'.$item['hinh_anh'].'" alt="'.$item['ten_sp'].'" height="32" width="32" style="margin-right: 4px">
                                <span class="product-name">'.$item['ten_sp'].'</span>
                            </div>
                        </td>
                        <td class="price" data-id="'.$item['ma_sp'].'">'.number_format($item['gia_thuc_te'], 0, '.', '.').'</td>
                        <td>
                            <div class="quantity-wrapper col d-flex 
                            align-items-center justify-content-between
                            mx-auto">
                                <button class="btn decrement-btn" data-id="'.$item['ma_sp'].'">-</button>
                                <span class="quantity-value" data-id="'.$item['ma_sp'].'">'.$item['so_luong'].'</span>
                                <button class="btn increment-btn" data-id="'.$item['ma_sp'].'">+</button>
                            </div>
                        </td>
                        <td class="total-price" data-id="'.$item['ma_sp'].'">'.number_format($item['tong_gia'], 0, '.', '.').'</td>
                    </tr>';
                  }
                  ?>
                </tbody>
              </table>
            </div>
            <div class="col-12 col-lg-3 d-flex flex-column">
              <div
                class="row border border-2 border-gray ms-2 mb-3"
              >
                <div class="mb-2 mt-2">
                  <h1 class="fs-4">Chi tiết</h1>
                </div>
                <div class="border-bottom border-2 border-gray d-flex flex-column gap-1">
                    <div
                      class="d-flex justify-content-between align-items-center"
                    >
                      <div>Giá sản phẩm</div>
                      <div class="price-value" id="order-price">
                        <?php
                        $total_price = 0;
                        foreach ($cart_items as $item) {
                          $total_price += $item['tong_gia'];
                        }
                        echo number_format($total_price, 0, '.', '.');
                        ?> VND
                      </div>
                    </div>
                    <div
                      class="d-flex justify-content-between align-items-center"
                    >
                      <div>Vận chuyển</div>
                      <div class="price-value">Miễn phí</div>
                    </div>
                    <div
                      class="d-flex justify-content-between align-items-center"
                    >
                      <div>Giảm giá</div>
                      <div class="price-value">
                        <?php
                        $discount = 0;
                        foreach ($cart_items as $item) {
                          $discount += $item['gia_thanh'] * $item['sale_off'] * $item['so_luong'];
                        }
                        echo number_format($discount, 0, '.', '.');
                        ?> VND
                      </div>
                    </div>
                    <div
                      class="d-flex justify-content-between align-items-center mb-2"
                    >
                      <div>Thuế</div>
                      <div class="price-value" id="tax">
                        <?php
                        $tax = 0.01 * $total_price;
                        echo number_format($tax, 0, '.', '.');
                        ?> VND
                      </div>
                    </div>
                    <div
                    class="d-flex justify-content-between align-items-center mt-2 mb-2"
                    >
                      <div>Tổng giá</div>
                      <div class="fw-bold text-primary" id="order-total-price">
                        <?php
                        $total_price += $tax;
                        echo number_format($total_price, 0, '.', '.');
                        ?> VND
                      </div>
                    </div>
                </div>
                <div
                  class="d-flex align-items-center justify-content-center m-2 p-2"
                >
                <a class="custom-btn btn" href="./checkout.php?from_cart=1">Thanh toán
                    <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    style="margin-left: 4px"
                    >
                      <path
                      fill="white"
                      d="M4 12h12.25L11 6.75l.66-.75l6.5 6.5l-6.5 6.5l-.66-.75L16.25 13H4z"
                      />
                    </svg>
                </a>
                </div>
              </div>
              <div class="row border border-2 border-gray ms-2">
                <div class="mb-2 mt-2">
                  <h1 class="fs-4">Mã giảm giá</h1>
                </div>
                <div class="d-grid gap-3">
                  <div
                    class="mt-3 d-flex align-items-center border border-2 border-gray"
                  >
                    <input
                      type="text"
                      placeholder="Email address"
                      class="w-100 border border-0"
                    />
                  </div>
                  <div>
                    <div class="d-flex align-items-center mb-3 justify-content-center">
                        <button class="custom-btn btn">Áp dụng</button>
                    </div>
                  </div>
                </div>
              </div>
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
  </body>
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"
  ></script>
  <script src="../node_modules/jquery/dist/jquery.min.js"></script>
  <script>
    const decrementBtns = document.querySelectorAll('.decrement-btn');
    const incrementBtns = document.querySelectorAll('.increment-btn');

    function callToUpdateQuantityApi(productId, newQuantity) {
      console.log("Product ID: " + productId + ", New quantity: " + newQuantity);
      $.ajax({
        url: './update_quantity.php',
        type: 'POST',
        data: {
          productId: productId,
          newQuantity: newQuantity
        },
        success: function(response) {
          if (response == "Cập nhật số lượng thất bại") {
            alert("Cập nhật số lượng thất bại");
          } else {
          let singlePrice = $('.price[data-id="' + productId + '"]').text().replace(/\./g, '');
          let totalPrice = newQuantity * +singlePrice;
          $('.total-price[data-id="' + productId + '"]').text(totalPrice.toLocaleString('vi-VN'));
          let orderPrice = 0;
          $('.total-price').each(function() {
            orderPrice += +$(this).text().replace(/\./g, '');
          });
          $('#order-price').text(orderPrice.toLocaleString('vi-VN') + ' VND');
          $('#tax').text((0.01 * orderPrice).toLocaleString('vi-VN') + ' VND');
          $('#order-total-price').text((orderPrice + 0.01 * orderPrice).toLocaleString('vi-VN') + ' VND');
          }
        }
      });
    }

    function updateQuantity(productId) {
      let timeout;
      return function(newQuantity) {
        clearTimeout(timeout);
        timeout = setTimeout(() => callToUpdateQuantityApi.call(this, productId, newQuantity), 700);
      }
    }

    const updateQuantityFuncs = [];

    for (let i = 0; i < decrementBtns.length; i++) {
      let thisProductId = decrementBtns[i].getAttribute('data-id');
      updateQuantityFuncs.push(updateQuantity(thisProductId));
      const btn = decrementBtns[i];
      btn.addEventListener('click', function() {
        const productId = this.getAttribute('data-id');
        const quantityValue = document.querySelector(`.quantity-value[data-id="${productId}"]`);
        let newQuantity = +quantityValue.textContent - 1;
        if (newQuantity < 1) {
          newQuantity = 1;
        }
        quantityValue.textContent = newQuantity;
        updateQuantityFuncs[i](newQuantity);
      });
    }

    for (let i =0; i < incrementBtns.length; i++) {
      updateQuantityFuncs.push(updateQuantity(incrementBtns[i].getAttribute('data-id')));
      const btn = incrementBtns[i];
      btn.addEventListener('click', function() {
        const productId = this.getAttribute('data-id');
        const quantityValue = document.querySelector(`.quantity-value[data-id="${productId}"]`);
        let newQuantity = +quantityValue.textContent + 1;
        quantityValue.textContent = newQuantity;
        updateQuantityFuncs[i](newQuantity);
      });
    }

    $('.delete-btn').each(function() {
      $(this).click(function() {
        const productId = $(this).attr('data-id');
        $('#delete-modal').modal('show');
        $('#delete-modal').attr('data-id', productId);
      })
    });

    $('#confirm-delete-btn').click(function() {
      const productId = $('#delete-modal').attr('data-id');
      $.ajax({
        url: './remove_item_from_cart.php',
        type: 'POST',
        data: {
          product_id: productId
        },
        success: function(response) {
          if (response == "Xóa sản phẩm khỏi giỏ hàng thành công") {
            $(`tr[data-id="${productId}"]`).remove();

            if ($('.total-price').length == 0) {
              window.location.href = './empty_cart.php';
            }

            let orderPrice = 0;

            $('.total-price').each(function() {
              orderPrice += +$(this).text().replace(/\./g, '');
            });
            $('#order-price').text(orderPrice.toLocaleString('vi-VN') + ' VND');
            $('#tax').text((0.01 * orderPrice).toLocaleString('vi-VN') + ' VND');
            $('#order-total-price').text((orderPrice + 0.01 * orderPrice).toLocaleString('vi-VN') + ' VND');

            $('#delete-modal').modal('hide');
          } else {
            alert("Xóa sản phẩm khỏi giỏ hàng thất bại");
          }
        }
      });
    });
  </script>
</html>