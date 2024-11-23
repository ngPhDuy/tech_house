<?php
session_start();

if (!isset($_SESSION['ten_dang_nhap'])) {
    header("Location: ../public/login.php");
    exit();
}

$username = $_SESSION['ten_dang_nhap'];

$conn = new mysqli('localhost', 'root', '', 'tech_house_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['from_cart']) && $_GET['from_cart'] == 1) {
    $stmt = $conn->prepare('select * from gio_hang join san_pham
on gio_hang.ma_sp = san_pham.ma_sp where thanh_vien = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    $total_price = 0;
    if ($result->num_rows > 0) {
        $cart_items = array();
        while ($row = $result->fetch_assoc()) {
            $cart_items[] = $row;
            $cart_items[count($cart_items) - 1]['gia_thuc_te'] = $row['gia_thanh'] * (1 - $row['sale_off']);
            $cart_items[count($cart_items) - 1]['tong_gia'] = $row['so_luong'] * $cart_items[count($cart_items) - 1]['gia_thuc_te'];      
            $total_price += $cart_items[count($cart_items) - 1]['tong_gia'];    
        }
    } else {
        header("Location: ./empty_cart.php");
        exit();
    }
} else if (isset($_GET['from_product']) && isset($_GET['product_id']) && isset($_GET['quantity'])) {
    $stmt = $conn->prepare('select * from san_pham where ma_sp = ?');
    $stmt->bind_param('s', $_GET['product_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    
    if ($result->num_rows > 0) {
        $cart_items = array();
        $cart_items[0] = $result->fetch_assoc();
        $cart_items[0]['so_luong'] = (int)$_GET['quantity'];
        $cart_items[0]['gia_thuc_te'] = $cart_items[0]['gia_thanh'] * (1 - $cart_items[0]['sale_off']);
        $cart_items[0]['tong_gia'] = $cart_items[0]['gia_thuc_te'] * $cart_items[0]['so_luong'];
        $total_price = $cart_items[0]['tong_gia'];
    } else {
        header("Location: ../public/404.php");
        exit();
    }
} else {
    header("Location: ../public/404.php");
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
    <link href="../styles/member/checkout.css" rel="stylesheet">
    <title>Tra cứu đơn hàng</title>
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
                    placeholder="Tìm kiếm sản phẩm.." link-to="../public/product_list.php">
                    <button type="button" class="search-btn border border-0 p-0 m-0"
                    id="search-btn">
                        <img src="../imgs/icons/search.png" alt="search" width="24" height="24">
                    </button>
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
        <main class="pb-3">
            <div class="container mt-4">
                <div class="row">
                    <div class="col-md-8">
                        <h4>Thông tin thanh toán</h4>
                        <form>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="firstName">Họ</label>
                                    <input type="text" class="form-control" placeholder="First name" name="firstName">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="lastName">Tên</label>
                                    <input type="text" class="form-control" placeholder="Last name" name="lastName">
                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="address">Địa chỉ nhận hàng</label>
                                    <input type="text" class="form-control" placeholder="Address" name="address">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="email">Email</label>
                                    <input type="email" class="form-control" name="email">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="phone">Số điện thoại</label>
                                    <input type="text" class="form-control" name="phone">
                                </div>
                            </div>
                            <div class="container p-0">
                                <div class="card p-3 border">
                                    <div class="form-section ">
                                        <h5>Hình thức thanh toán</h5>
                                        <div class="row payment-options">
                                            <div class="col-2 payment-method d-flex flex-column align-items-center flex-fill">
                                                <label class="text-center" for="cashOnDelivery">
                                                    <img src="../imgs/icons/cod.png" alt="Cash on Delivery" class="payment-icon">
                                                    <span>Trả tiền mặt</span>
                                                </label>
                                                <input class="form-check-input" type="radio" name="paymentOption" id="cashOnDelivery">
                                            </div>
                                        
                                            <div class="col-2 payment-method d-flex flex-column align-items-center flex-fill">
                                                <label class="text-center" for="venmo">
                                                    <img src="../imgs/icons/venmo.png" alt="Venmo" class="payment-icon">
                                                    <span>Venmo</span>
                                                </label>
                                                <input class="form-check-input" type="radio" name="paymentOption" id="venmo">
                                            </div>
                                        
                                            <div class="col-2 payment-method d-flex flex-column align-items-center flex-fill">
                                                <label class="text-center" for="paypal">
                                                    <img src="../imgs/icons/paypal.png" alt="Paypal" class="payment-icon">
                                                    <span>Paypal</span>
                                                </label>
                                                <input class="form-check-input" type="radio" name="paymentOption" id="paypal">
                                            </div>
                                        
                                            <div class="col-2 payment-method d-flex flex-column align-items-center flex-fill">
                                                <label class="text-center" for="amazonPay">
                                                    <img src="../imgs/icons/amazone_pay.png" alt="Amazon Pay" class="payment-icon">
                                                    <span>Amazon Pay</span>
                                                </label>
                                                <input class="form-check-input" type="radio" name="paymentOption" id="amazonPay">
                                            </div>
                                        
                                            <div class="col-2 payment-method d-flex flex-column align-items-center flex-fill">
                                                <label class="text-center" for="creditCard">
                                                    <img src="../imgs/icons/debit_credit.png" alt="Debit/Credit Card" class="payment-icon">
                                                    <span>Credit Card</span>
                                                </label>
                                                <input class="form-check-input" type="radio" name="paymentOption" id="creditCard">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3" id="card-form">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label" for="card-owner">Tên chủ thẻ</label>
                                                <input type="text" class="form-control" name="card-owner">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label" for="card-number">Số thẻ</label>
                                                <input type="text" class="form-control" name="card-number">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label" for="expiration-date">Ngày hết hạn</label>
                                                <input type="text" class="form-control" name="expiration-date">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label" for="cvv">CVV</label>
                                                <input type="text" class="form-control" name="cvv">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-section mt-4 mb-4">
                                <h5>Ghi chú thêm</h5>
                                <textarea class="form-control" rows="3" placeholder="Order Notes (Optional)" 
                                name="order-note" id="order-note"></textarea>
                            </div>
                        </form>
                    </div>
            
                    <div class="col-md-4">
                        <div class="order-summary">
                            <h5>Tóm tắt đơn hàng</h5>
                            <?php
                            foreach ($cart_items as $item) {
                            ?>
                                <div class="d-flex gap-2">
                                    <img src="<?php echo $item['hinh_anh']; ?>" class="product-image me-3" alt="Product Image" width="80" height="80">
                                    <div>
                                        <p class="mb-1"><?php echo $item['ten_sp']; ?></p>
                                        <p><?php echo $item['so_luong']; ?> x <?php echo number_format($item['gia_thuc_te'], 0, '.', '.'); ?></p>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <p>Tổng giá sản phẩm</p>
                                <p>
                                    <?php
                                    echo number_format($total_price, 0, '.', '.');
                                    ?>  VND
                                </p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p>Vận chuyển</p>
                                <p>Free</p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p>Giảm giá</p>
                                <p>
                                    <?php
                                    $discount = 0;
                                    foreach($cart_items as $item) {
                                        $discount += $item['gia_thanh'] * $item['sale_off'] * $item['so_luong'];
                                    }
                                    echo number_format($discount, 0, '.', '.')
                                    ?>  VND
                                </p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p>Thuế</p>
                                <p>
                                    <?php
                                    $tax = 0.01 * $total_price;
                                    echo number_format($tax, 0, '.', '.');
                                    ?> VND
                                </p>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fw-bold">
                                <p>Thành tiền</p>
                                <p>
                                    <?php
                                    $total_price += $tax;
                                    echo number_format($total_price, 0, '.', '.');
                                    ?>  VND
                                </p>
                            </div>
                            <button id="order-btn" class="btn place-order-btn w-100 mt-3">ĐẶT HÀNG</button>
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
        <div class="modal fade" id="resultModal" tabindex="-1" aria-labelledby="resultModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="container d-flex justify-content-center align-items-center p-0">
                        <div class="card text-center order-success p-3 border-0">
                            <div class="card-body">
                                <div class="icon mb-3">
                                    <div class="success-label">
                                        <span class="checkmark">&#10003;</span>
                                    </div>
                                </div>
                                <h5 class="card-title fw-bold">Đơn hàng đã được đặt thành công</h5>
                                <p class="card-text text-muted">
                                    Cảm ơn bạn đã mua hàng tại Tech House. Đơn hàng của bạn đã được đặt thành công.
                                </p>
                                <div class="button-group d-flex justify-content-center gap-2">
                                    <a href="../index.php" class="btn btn-outline-primary fw-bold text-uppercase">Về trang chủ</a>
                                    <a href="./order_history_dashboard.php" class="btn btn-primary fw-bold text-uppercase">
                                        Xem chi tiết đơn hàng
                                        <span class="arrow-right">&rarr;</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="../node_modules/jquery/dist/jquery.min.js"></script>
<script src="../scripts/search.js"></script>
<script>
    $("document").ready(function() {
        $("#card-form").hide();
        $("#order-btn").prop("disabled", true);
    });

    $(".payment-method").click(function() {
        $(".payment-method").removeClass("selected");
        $(this).addClass("selected");
        if ($(this).find("input").attr("id") == "cashOnDelivery") {
            $("#card-form").hide();
        } else {
            $("#card-form").show();
        }
    });

    <?php
    if (isset($_GET['from_cart']) && $_GET['from_cart'] == 1) {
    ?>
    $("#order-btn").click(function() {
        $.ajax({
            url: "./create_order.php",
            type: "POST",
            data: {
                total_price: <?php echo $total_price; ?>,
                username: "<?php echo $username; ?>",
            },
            success: function(data) {
                if (data == "Tạo đơn hàng thành công") {
                    $("#resultModal").modal("show");
                    $("#resultModal").on("hidden.bs.modal", function() {
                        window.location.href = "./order_history_dashboard.php";
                    });
                } else {
                    alert(data);
                }
            }
        })
    });
    <?php
    } else if (isset($_GET['from_product']) && isset($_GET['product_id']) && isset($_GET['quantity'])) {
    ?>
    $("#order-btn").click(function() {
        $.ajax({
            url: "./create_order.php",
            type: "POST",
            data: {
                total_price: <?php echo $total_price; ?>,
                username: "<?php echo $username; ?>",
                product_id: "<?php echo $_GET['product_id']; ?>",
                quantity: <?php echo $_GET['quantity']; ?>
            },
            success: function(data) {
                if (data == "Tạo đơn hàng thành công") {
                    $("#resultModal").modal("show");
                    $("#resultModal").on("hidden.bs.modal", function() {
                        window.location.href = "./order_history_dashboard.php";
                    });
                } else {
                    alert(data);
                }
            }
        })
    });
    <?php
    }
    ?>

    $("form input").each(function(index, input) {
        input.addEventListener("input", function() {
            let firstName = $("input[name='firstName']").val();
            let lastName = $("input[name='lastName']").val();
            let address = $("input[name='address']").val();
            let email = $("input[name='email']").val();
            let phone = $("input[name='phone']").val();
            let paymentOption = $("input[name='paymentOption']:checked").attr("id");
            let cardOwner = $("input[name='card-owner']").val();
            let cardNumber = $("input[name='card-number']").val();
            let expirationDate = $("input[name='expiration-date']").val();
            let cvv = $("input[name='cvv']").val();

            if (firstName == "" || lastName == "" || address == "" || email == "" || phone == "" || paymentOption == undefined) {
                $("#order-btn").prop("disabled", true);
            } else if (paymentOption != "cashOnDelivery" && 
            (cardOwner == "" || cardNumber == "" || expirationDate == "" || cvv == "")
            ) {
                $("#order-btn").prop("disabled", true);
            } else {
                $("#order-btn").prop("disabled", false);
            }
        });
    })

</script>
</html>
<?php
$conn->close();
?>