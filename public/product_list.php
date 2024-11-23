<?php
session_start();

$conn = new mysqli("localhost", "root", "", "tech_house_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['product_type'])) {
    $product_type = $_GET['product_type'];
    $sql = 'select p.*, count(d.ma_sp) as so_luong_danh_gia, avg(d.diem_danh_gia) as diem_trung_binh
    from san_pham p left join danh_gia d on p.ma_sp = d.ma_sp
    where p.phan_loai = '.$product_type.'
    group by p.ma_sp';
} else if (isset($_GET['search_key'])) {
    $search = $_GET['search_key'];
    $search = str_replace('+', ' ', $search);
    $product_type = -1;
    $sql = 'select p.*, count(d.ma_sp) as so_luong_danh_gia, avg(d.diem_danh_gia) as diem_trung_binh
    from san_pham p left join danh_gia d on p.ma_sp = d.ma_sp
    where lower(p.ten_sp) like "%'.$search.'%" or lower(p.thuong_hieu) like "%'.$search.'%" or lower(p.phan_loai) like "%'.$search.'%"
    group by p.ma_sp';
} else {
    $product_type = -1;
    $sql = 'select p.*, count(d.ma_sp) as so_luong_danh_gia, avg(d.diem_danh_gia) as diem_trung_binh
    from san_pham p left join danh_gia d on p.ma_sp = d.ma_sp
    group by p.ma_sp';
}

$result = $conn->query($sql);
$products = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="../styles/public/custom.css" rel="stylesheet">
    <link href="../styles/public/product_list.css" rel="stylesheet">
    <title>
        <?php
        switch ($product_type) {
            case 0:
                echo "Danh sách laptop";
                break;
            case 1:
                echo "Danh sách điện thoại";
                break;
            case 2:
                echo "Danh sách tablet";
                break;
            case 3:
                echo "Danh sách tai nghe";
                break;
            case 4:
                echo "Danh sách bàn phím";
                break;
            case 5:
                echo "Danh sách sạc dự phòng";
                break;
            case 6:
                echo "Danh sách bao da, ốp lưng";
                break;
            default:
                echo "Danh sách sản phẩm";
                break;
        }
        ?>
    </title>
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
                    placeholder="Tìm kiếm sản phẩm.." link-to="./product_list.php">
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
                            '<a href="../member/user_info.php" class="fw-bold text-white">
                                <img src="../imgs/icons/user.png" alt="user" width="32" height="32">
                                '.$_SESSION['ho_ten'].'</a>';
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
                                Đăng nhập
                            </a>';
                        }
                        ?>
                    </div>
                    <div class="cart w-50">
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
                <?php
                if ($product_type == 1) {
                    echo '<div class="tab tab-selected col">';
                } else {
                    echo '<div class="tab col">';
                }
                ?>
                    <a href="./product_list.php?product_type=1">
                        <?php
                        if ($product_type == 1) {
                            echo '<img src="../imgs/icons/phone_iphone_white.png" alt="phone" width="24" height="24">';
                        } else {
                            echo '<img src="../imgs/icons/phone_iphone.png" alt="phone" width="24" height="24">';
                        }
                        ?>
                        Điện thoại
                    </a>
                </div>  
                <?php
                if ($product_type == 0) {
                    echo '<div class="tab tab-selected col">';
                } else {
                    echo '<div class="tab col">';
                }
                ?>
                    <a href="./product_list.php?product_type=0">
                        <?php
                        if ($product_type == 0) {
                            echo '<img src="../imgs/icons/laptop_mac_white.png" alt="laptop" width="24" height="24">';
                        } else {
                            echo '<img src="../imgs/icons/laptop_mac.png" alt="laptop" width="24" height="24">';
                        }
                        ?>
                        Laptop
                    </a>
                </div>
                <?php
                if ($product_type == 2) {
                    echo '<div class="tab tab-selected col">';
                } else {
                    echo '<div class="tab col">';
                }
                ?>
                    <a href="./product_list.php?product_type=2">
                        <?php
                        if ($product_type == 2) {
                            echo '<img src="../imgs/icons/tablet_android_white.png" alt="tablet" width="24" height="24">';
                        } else {
                            echo '<img src="../imgs/icons/tablet_android.png" alt="tablet" width="24" height="24">';
                        }
                        ?>
                        Tablet
                    </a>
                </div>
                <?php
                if ($product_type > 2) {
                    echo '<div class="tab tab-selected col">';
                } else {
                    echo '<div class="tab col">';
                }
                ?>
                    <a href="./product_list.php?product_type=3">
                        <!-- <img src="../imgs/icons/gamepad.png" alt="other" width="24" height="24"> -->
                        <?php
                        if ($product_type > 2) {
                            echo '<img src="../imgs/icons/gamepad_white.png" alt="other" width="24" height="24">';
                        } else {
                            echo '<img src="../imgs/icons/gamepad.png" alt="other" width="24" height="24">';
                        }
                        ?>
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
        <main class="px-3">
            <div class="page-wrapper d-flex flex-column gap-3 px-5 pt-3 pb-5">
                <div class="header d-flex justify-content-between">
                    <div class="filter-wrapper d-flex gap-1 align-items-center">
                        <span class="funnel-icon"></span>
                        <p class="m-0">Bộ lọc</p>
                    </div>
                    <div class="sort-wrapper d-flex align-items-center gap-2">
                        <p class="m-0 align-center"
                        style="white-space: nowrap">Sắp xếp theo:</p>
                        <select class="form-select p-2" name="sort" id="sort">
                            <option value="default">Mặc định</option>
                            <option value="price-asc">Giá: Thấp đến cao</option>
                            <option value="price-desc">Giá: Cao đến thấp</option>
                            <option value="name-asc">Tên: A-Z</option>
                            <option value="name-desc">Tên: Z-A</option>
                        </select>
                    </div>
                </div>
                <div class="result-count d-flex justify-content-end">
                    <p class="m-0">Đã tìm thấy <span class="fw-bold">
                        <?php echo count($products); ?>
                    </span> kết quả</p>
                </div>
                <?php
                if (count($products) == 0) {
                    echo '<div class="no-result d-flex justify-content-center align-items-center">
                        <p class="m-0 fs-5 fw-lighter">Không tìm thấy sản phẩm nào</p>
                    </div>';
                } else {
                ?>
                <div class="product-list">
                    <?php
                    foreach ($products as $product) {
                        echo 
                        '<a class="product bg-white py-2" 
                        href="./product_detail.php?product_id='.$product['ma_sp'].'"
                        price="'.($product['gia_thanh'] * (1 - $product['sale_off'])).'" 
                        name="'.$product['ten_sp'].'" brand="'.strtolower($product['thuong_hieu']).'">
                            <img class="product-img d-block mx-auto" 
                            src="'.$product['hinh_anh'].'" alt="'.$product['ten_sp'].'" 
                            width="80%" height="50%">
                            <div class="product-info d-flex flex-column gap-2 ps-2 pe-1 mt-2">
                                <p class="m-0">'.$product['ten_sp'].'</p>
                                <p class="m-0">'.number_format($product['gia_thanh'] * (1 - $product['sale_off']), 0, '.', '.').'đ</p>';
                        if ($product['so_luong_danh_gia'] > 0) {
                            echo '<p class="m-0"><span class="star-icon"></span>'.round($product['diem_trung_binh'], 1).'</p>';
                        } else {
                            echo '<p class="m-0 no-rate">Chưa có đánh giá</p>';
                        }
                        echo'</div>
                        </a>';
                    }
                }
                    ?>
                </div>
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
    <div class="filter-modal-wrapper d-none">
        <div class="filter-modal">
            <div class="filter-modal-content d-flex gap-3">
                <div class="brand-filter col-6">
                    <p class="m-0 fw-bold text-uppercase mb-3">Hãng sản xuất</p>
                    <div class="brands">
                        <div class="brand">
                            <input class="d-block" type="checkbox" name="brand" id="apple">
                            <label class="d-block" for="apple">Apple</label>
                        </div>
                        <div class="brand">
                            <input type="checkbox" name="brand" id="samsung">
                            <label for="samsung">Samsung</label>
                        </div>
                        <div class="brand">
                            <input type="checkbox" name="brand" id="google">
                            <label for="google">Google</label>
                        </div>
                        <div class="brand">
                            <input type="checkbox" name="brand" id="hp">
                            <label for="hp">HP</label>
                        </div>
                        <div class="brand">
                            <input type="checkbox" name="brand" id="dell">
                            <label for="dell">Dell</label>
                        </div> 
                        <div class="brand">
                            <input type="checkbox" name="brand" id="lg">
                            <label for="lg">LG</label>
                        </div>
                        <div class="brand">
                            <input type="checkbox" name="brand" id="sony">
                            <label for="sony">Sony</label>
                        </div>
                        <div class="brand">
                            <input type="checkbox" name="brand" id="huawei">
                            <label for="huawei">Huawei</label>
                        </div>
                    </div>
                </div>
                <div class="price-filter col-6">
                    <p class="m-0 fw-bold text-uppercase mb-3">Mức giá</p>
                    <div class="prices">
                        <div class="price">
                            <input type="radio" name="price" id="all price">
                            <label for="all price">Tất cả</label>
                        </div>
                        <div class="price">
                            <input type="radio" name="price" id="0-10">
                            <label for="0-10">0 - 10tr</label>
                        </div>
                        <div class="price">
                            <input type="radio" name="price" id="10-20">
                            <label for="10-20">10- 20tr</label>
                        </div>
                        <div class="price">
                            <input type="radio" name="price" id="20-30">
                            <label for="20-30">20 - 30tr</label>
                        </div>
                        <div class="price">
                            <input type="radio" name="price" id="30-40">
                            <label for="30-40">30 - 40tr</label>
                        </div>
                        <div class="price">
                            <input type="radio" name="price" id="40-50">
                            <label for="40-50">40 - 50tr</label>
                        </div>
                        <div class="price">
                            <input type="radio" name="price" id="50+">
                            <label for="50+">Trên 50tr</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="filter-modal-footer d-flex justify-content-center mx-auto mt-3">
                <button class="btn btn-primary">Áp dụng</button>
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="../node_modules/jquery/dist/jquery.min.js"></script>
<script src="../scripts/search.js"></script>
<script>
    let brandsFilter = [];
    let priceFilter;
    const paginationLength = 3; 
    const productsPerPage = 15;
    let products = Array.from($('.product'));
    let oldProducts = products;
    const pagination = $('.pagination');
    const pageNumbers = $('.page-numbers');
    let currentPage = 1;

    $('.filter-wrapper').click((e) => {
        e.preventDefault();
        $('.filter-modal-wrapper').removeClass('d-none');
    });

    $('.filter-modal-footer button').click((e) => {
        e.preventDefault();
        $('.filter-modal-wrapper').addClass('d-none');

        brandsFilter = [];
        $('.brand input:checked').each((index, brand) => {
            brandsFilter.push($(brand).attr('id'));
        });

        priceFilter = $('.price input:checked').attr('id');

        console.log(brandsFilter, priceFilter);

        products = Array.from(oldProducts);
        if (brandsFilter.length > 0) {
            products = products.filter(product => {
                return brandsFilter.includes($(product).attr('brand'));
            });
        } 

        if (priceFilter) {
            products = products.filter(product => {
                const price = +$(product).attr('price');
                switch (priceFilter) {
                    case '0-10':
                        return price >= 0 && price <= 10000000;
                    case '10-20':
                        return price >= 10000000 && price <= 20000000;
                    case '20-30':
                        return price >= 20000000 && price <= 30000000;
                    case '30-40':
                        return price >= 30000000 && price <= 40000000;
                    case '40-50':
                        return price >= 40000000 && price <= 50000000;
                    case '50+':
                        return price >= 50000000;
                    default:
                        return true;
                }
            });
        }

        $('.product-list').empty();

        products.forEach(product => {
            $('.product-list').append(product);
        });

        $('.result-count span').text(products.length);

        currentPage = 1;
        displayProducts();
        updatePagination();
    });

    $('.filter-modal-wrapper').click((e) => {;
        if (e.target === $('.filter-modal-wrapper')[0]) {
            $('.filter-modal-wrapper').addClass('d-none');
        }
    });

    //Pagination

    function displayProducts() {
        console.log(products);
        products.forEach((product, index) => {
            const start = (currentPage - 1) * productsPerPage;
            const end = currentPage * productsPerPage;
            if (index >= start && index < end) {
                $(product).removeClass('d-none');
            } else {
                $(product).addClass('d-none');
            }
        });
    }

    function updatePagination() {
        const totalPages = Math.ceil(products.length / productsPerPage);

        if (totalPages == 1) {
            pagination.addClass('d-none');
            return;
        }

        pageNumbers.empty();

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
            const pageNumber = $('<button type="button"></button>').text(i).addClass('page-number');

            if (i === currentPage) {
                pageNumber.addClass('active');
            }

            pageNumber.on('click', (e) => {
                e.preventDefault();
                currentPage = i;
                displayProducts();
                updatePagination();
            });

            pageNumbers.append(pageNumber);
        }

        pagination.removeClass('d-none');
    }

    $('#sort').change(() => {
        const sortType = $('#sort').val();
        products = Array.from(products);
        switch (sortType) {
            case 'price-asc':
                products.sort((a, b) => {
                    return +$(a).attr('price') - +$(b).attr('price');
                });
                break;
            case 'price-desc':
                products.sort((a, b) => {
                    return +$(b).attr('price') - +$(a).attr('price');
                });
                break;
            case 'name-asc':
                products.sort((a, b) => {
                    return $(a).attr('name').localeCompare($(b).attr('name'));
                });
                break;
            case 'name-desc':
                products.sort((a, b) => {
                    return $(b).attr('name').localeCompare($(a).attr('name'));
                });
                break;
            default:
                break;
        }
        $('.product-list').empty();
        products.forEach(product => {
            $('.product-list').append(product);
        });
        currentPage = 1;
        displayProducts();
        updatePagination();
    });

    displayProducts();
    updatePagination();
</script>
</html>
<?php
$conn->close();
?>