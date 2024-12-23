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

$sql = "SELECT * FROM san_pham";
$result = $conn->query($sql);

$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

$stmt = $conn->prepare('select * from tai_khoan join nhan_vien on tai_khoan.ten_dang_nhap = nhan_vien.ten_dang_nhap where tai_khoan.ten_dang_nhap = ?');
$stmt->bind_param('s', $_SESSION['ten_dang_nhap']);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$stmt->close();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech house</title>

    <link rel="stylesheet" href="../styles/admin/products.css">
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
            <div class="d-flex flex-column gap-3">
                <div id="utilities" class="d-flex justify-content-between align-items-center">
                    <div class="filter-button btn border border-secondary d-flex gap-2" id="filter-button">
                        <svg width="24" height="24" viewBox="0 0 27 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M2.06216 4.48145H24.8793C25.0854 4.48149 25.2871 4.5262 25.4597 4.61012C25.6323 4.69404 25.7684 4.81356 25.8514 4.9541C25.9345 5.09464 25.9609 5.25014 25.9274 5.40166C25.8939 5.55319 25.8021 5.69419 25.6629 5.80749L16.9372 12.9622C16.7556 13.107 16.6557 13.2984 16.6583 13.4966V19.0976C16.6599 19.2292 16.6169 19.3589 16.5333 19.4748C16.4498 19.5907 16.3283 19.689 16.1801 19.7606L11.9301 21.8684C11.7707 21.9467 11.5859 21.9915 11.3953 21.9981C11.2046 22.0047 11.015 21.973 10.8465 21.9061C10.678 21.8393 10.5367 21.7399 10.4376 21.6183C10.3385 21.4967 10.2852 21.3575 10.2833 21.2153V13.4966C10.2858 13.2984 10.186 13.107 10.0044 12.9622L1.27857 5.80749C1.13946 5.69419 1.04757 5.55319 1.0141 5.40166C0.980636 5.25014 1.00704 5.09464 1.09009 4.9541C1.17313 4.81356 1.30925 4.69404 1.48184 4.61012C1.65444 4.5262 1.85607 4.48149 2.06216 4.48145V4.48145Z"
                                stroke="#191C1F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div>Lọc</div>
                    </div>
                    
                    <div class="w-75 d-flex justify-content-between align-items-center gap-3">
                        <div class="searchbar" id="searchbar">
                            <input type="text" placeholder="Nhập tên sản phẩm..." name="search">
                            <button type="button" id="search-button"><i class="fa fa-search"></i></button>
                        </div>
    
                        <a href="./product_add.php" role="button"
                            class="add-product-button btn bg-warning text-light d-flex gap-2">
                            <i class='far fa-plus-square' style='font-size:24px'></i>
                        </a>
                    </div>
                </div>

                <table id="table-list-product" class="table table-hover align-middle">
                    <thead class="align-middle">
                        <tr>
                            <th></th>
                            <th class="w-20">Tên sản phẩm</th>
                            <th class="w-15">Phân loại</th>
                            <th class="w-15">Thương hiệu</th>
                            <th class="w-20">Đơn giá</th>
                            <th class="w-20">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="product-list">
                        <?php
                        foreach ($products as $product) {
                            echo '<tr class="product page-element" data-product-id="' . $product['ma_sp'].'" 
                            data-category="'.$product['phan_loai'].'" data-name="'.$product['ten_sp'].'"
                            data-brand="'.strtolower($product['thuong_hieu']).'" data-price="'.$product['gia_thanh'].'" >';
                            echo "<td>";
                            echo "<img width='60' src='" . $product['hinh_anh'] . "' alt='product'>";
                            echo "</td>";
                            echo "<td>";
                            echo $product['ten_sp'];
                            echo "</td>";
                            echo "<td>";
                            switch ($product['phan_loai']) {
                                case 0:
                                    echo "Laptop";
                                    break;
                                case 1:
                                    echo "Mobile";
                                    break;
                                case 2:
                                    echo "Tablet";
                                    break;
                                case 3:
                                    echo "Tai nghe";
                                    break;
                                case 4:
                                    echo "Bàn phím";
                                    break;
                                case 5:
                                    echo "Sạc dự phòng";
                                    break;
                                case 6:
                                    echo "Ốp lưng";
                                    break;
                            }
                            echo "</td>";
                            echo "<td>";
                            echo $product['thuong_hieu'];
                            echo "</td>";
                            echo "<td>";
                            echo number_format($product['gia_thanh'], 0, '.', '.') . "VND";
                            echo "</td>";
                            echo "<td>";
                            echo "<div class='d-flex'>";
                            echo "<a href='./product_detail.php?product_id=".$product['ma_sp']."&category=".$product['phan_loai']."'>";
                            echo "<div>";
                            echo "<svg width='48' height='33' viewBox='0 0 48 33' fill='none' xmlns='http://www.w3.org/2000/svg'>";
                            echo "<rect x='0.3' y='1.3' width='47.4' height='31.4' rx='7.7' fill='#FAFBFD' stroke='#D5D5D5' stroke-width='0.6' />";
                            echo "<g opacity='0.6'>";
                            echo "<path fill-rule='evenodd' clip-rule='evenodd' 
                            d='M24.6973 18.4237L22.2227 18.7777L22.576 16.3024L28.94 9.93837C29.5258 9.35258 30.4755 9.35258 31.0613 9.93837C31.6471 10.5242 31.6471 11.4739 31.0613 12.0597L24.6973 18.4237Z' 
                            stroke='black' stroke-width='1.2' stroke-linecap='round' stroke-linejoin='round' />";
                            echo "<path d='M28.2324 10.6455L30.3538 12.7668' 
                            stroke='black' stroke-width='1.2' stroke-linecap='round' stroke-linejoin='round' />";
                            echo "<path d='M28.5 18.5V23.5C28.5 24.0523 28.0523 24.5 27.5 24.5H17.5C16.9477 24.5 16.5 24.0523 16.5 23.5V13.5C16.5 12.9477 16.9477 12.5 17.5 12.5H22.5' 
                            stroke='black' stroke-width='1.2' stroke-linecap='round' stroke-linejoin='round' />";
                            echo "</g>";
                            echo "</svg>";
                            echo "</div>";
                            echo "</a>";
                            echo "<div data-id='".$product['ma_sp']."' class='delete-product-button'
                            style='cursor: pointer;'>";
                            echo "<div>";
                            echo "<svg width='48' height='33' viewBox='0 0 48 33' fill='none' xmlns='http://www.w3.org/2000/svg'>";
                            echo "<rect x='0.3' y='1.3' width='47.4' height='31.4' rx='7.7' fill='#FAFBFD' stroke='#D5D5D5' stroke-width='0.6' />";
                            echo "<g transform='translate(-50, 0)'>";
                            echo "<path fill-rule='evenodd' clip-rule='evenodd' 
                            d='M76.1996 24.4004H67.7996C67.1369 24.4004 66.5996 23.8631 66.5996 23.2004V12.4004H77.3996V23.2004C77.3996 23.8631 76.8624 24.4004 76.1996 24.4004Z' 
                            stroke='#EF3826' stroke-width='1.2' stroke-linecap='round' stroke-linejoin='round' />";
                            echo "<path d='M70.2008 20.8V16' stroke='#EF3826' stroke-width='1.2' stroke-linecap='round' stroke-linejoin='round' />";
                            echo "<path d='M73.8004 20.8V16' stroke='#EF3826' stroke-width='1.2' stroke-linecap='round' stroke-linejoin='round' />";
                            echo "<path d='M64.1992 12.4H79.7992' stroke='#EF3826' stroke-width='1.2' stroke-linecap='round' stroke-linejoin='round' />";
                            echo "<path fill-rule='evenodd' clip-rule='evenodd' 
                            d='M73.8 10H70.2C69.5373 10 69 10.5373 69 11.2V12.4H75V11.2C75 10.5373 74.4627 10 73.8 10Z' 
                            stroke='#EF3826' stroke-width='1.2' stroke-linecap='round' stroke-linejoin='round' />";
                            echo "</g>";
                            echo "</svg>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="pagination mt-3 d-flex justify-content-center d-none">
                <div class="page-numbers d-flex justify-content-center gap-2">
                    <a href="#" class="page-number">01</a>
                    <a href="#" class="page-number">02</a>
                    <a href="#" class="page-number">03</a>
                    <a href="#" class="page-number">04</a>
                    <a href="#" class="page-number">05</a>
                </div>
            </div>

        </div>


        <!-- Dont have footer! -->
        <div id="footer"></div>
    </div>

    <div class="modal" id="delete-modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Xóa khỏi hệ thống</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="m-0">Bạn có chắc chắn xóa sản phẩm?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-danger" id="confirm-delete-btn">Xác nhận</button>
                </div>
            </div>
        </div>
    </div>

    <div class="filter-modal-wrapper d-none">
        <div class="filter-modal">
            <div class="filter-modal-content d-flex gap-3 flex-wrap justify-content-start">
                <div class="brand-filter col-5">
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
                <div class="price-filter col-5">
                    <p class="m-0 fw-bold text-uppercase mb-3">Mức giá</p>
                    <div class="prices">
                        <div class="price">
                            <input type="radio" name="price" id="all-price">
                            <label for="all-price">Tất cả</label>
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
                <div class="category-filter col-5">
                    <p class="m-0 fw-bold text-uppercase mb-3">Phân loại</p>
                    <div class="categories">
                        <div class="category">
                            <input class="d-block" type="checkbox" name="category" id="0">
                            <label for="0">Laptop</label>
                        </div>
                        <div class="category">
                            <input type="checkbox" name="category" id="1">
                            <label for="1">Mobile</label>
                        </div>
                        <div class="category">
                            <input type="checkbox" name="category" id="2">
                            <label for="2">Tablet</label>
                        </div>
                        <div class="category">
                            <input type="checkbox" name="category" id="3">
                            <label for="3">Tai nghe</label>
                        </div>
                        <div class="category">
                            <input type="checkbox" name="category" id="4">
                            <label for="4">Bàn phím</label>
                        </div> 
                        <div class="category">
                            <input type="checkbox" name="category" id="5">
                            <label for="5">Sạc dự phòng</label>
                        </div>
                        <div class="category">
                            <input type="checkbox" name="category" id="6">
                            <label for="6">Ốp lưng</label>
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
    const productsPerPage = 10;
    let products = Array.from($('.product'));
    let oldProducts = products;
    let currentPage = 1;
    let brandsFilter = [];
    let priceFilter;
    let categoriesFilter = [];
    let paginationFunc = pagination(paginationLength, productsPerPage, $(products));

    paginationFunc(currentPage);

    // function displayProducts() {
    //     console.log(currentPage);
    //     products.forEach((product, index) => {
    //         const start = (currentPage - 1) * productsPerPage;
    //         const end = currentPage * productsPerPage;
    //         if (index >= start && index < end) {
    //             product.classList.remove('d-none');
    //         } else {
    //             product.classList.add('d-none');
    //         }
    //     });
    // }
    // function updatePagination() {
    //     const totalPages = Math.ceil(products.length / productsPerPage);

    //     if (totalPages == 1) {
    //         pagination.classList.add('d-none');
    //         return;
    //     }

    //     pageNumbers.innerHTML = '';

    //     const halfWindow = Math.floor(paginationLength / 2);
    //     let startPage = Math.max(1, currentPage - halfWindow);
    //     let endPage = Math.min(totalPages, currentPage + halfWindow);

    //     if (currentPage - halfWindow < 1) {
    //         endPage = Math.min(totalPages, endPage + (halfWindow - (currentPage - 1)));
    //     }
    
    //     if (currentPage + halfWindow > totalPages) {
    //         startPage = Math.max(1, startPage - (currentPage + halfWindow - totalPages));
    //     }

    //     for (let i = startPage; i <= endPage; i++) {
    //         const pageNumber = document.createElement('div');
    //         pageNumber.classList.add('page-number');
    //         pageNumber.textContent = i;
    //         if (i === currentPage) {
    //             pageNumber.classList.add('active');
    //         }

    //         pageNumber.addEventListener('click', (e) => {
    //             e.preventDefault();
    //             currentPage = i;
    //             displayProducts();
    //             updatePagination();
    //         });

    //         pageNumbers.appendChild(pageNumber);
    //     }

    //     pagination.classList.remove('d-none');
    // }

    // displayProducts();
    // updatePagination();

    $(".delete-product-button").click(function () {
        let productId = $(this).attr('data-id');
        $("#delete-modal").modal('show');
        $("#confirm-delete-btn").attr('data-id', productId);
    });

    $("#confirm-delete-btn").click(function () {
        let productId = $(this).attr('data-id');
        console.log(productId + ' - ' + $(`[data-product-id=${productId}]`).attr('data-category'));
        $.ajax({
            url: './delete_product.php',
            method: 'POST',
            data: {
                product_id: productId,
                category : $(`[data-product-id=${productId}]`).attr('data-category')
            },
            success: function (response) {
                if (response == 'Xoá sản phẩm thành công') {
                    $(`[data-product-id=${productId}]`).remove();
                    $("#delete-modal").modal('hide');
                } else {
                    console.log(response);
                    alert(response);
                }
            }
        });
    });

    $('#filter-button').click((e) => {
        console.log('click');
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

        categoriesFilter = [];
        $('.category input:checked').each((index, category) => {
            categoriesFilter.push($(category).attr('id'));
        });

        console.log(brandsFilter, priceFilter, categoriesFilter);

        products = Array.from(oldProducts);
        if (brandsFilter.length > 0) {
            products = products.filter(product => {
                return brandsFilter.includes($(product).attr('data-brand'));
            });
        } 

        if (priceFilter) {
            products = products.filter(product => {
                const price = +$(product).attr('data-price');
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

        if (categoriesFilter.length > 0) {
            products = products.filter(product => {
                return categoriesFilter.includes($(product).attr('data-category'));
            });
        }

        $('#product-list').empty();

        products.forEach(product => {
            $('#product-list').append(product);
        });

        let paginationFunc = pagination(paginationLength, productsPerPage, $(products));
        paginationFunc(1);
    });

    $('.filter-modal-wrapper').click((e) => {
        let filterModalWrapper =$('.filter-modal-wrapper');
        if (e.target === filterModalWrapper[0]) {
            filterModalWrapper.addClass('d-none');
        }
        // let filterModalWrapper = document.querySelector('.filter-modal-wrapper');
        // if (e.target === filterModalWrapper) {
        //     filterModalWrapper.classList.add('d-none');
        // }
    });

    $('#search-button').click((e) => {
        e.preventDefault();
        let searchValue = $('#searchbar input').val().toLowerCase().trim();

        console.log(searchValue);

        products = Array.from(oldProducts);

        products = products.filter(product => {
            return $(product).attr('data-name').toLowerCase().includes(searchValue) ||
                $(product).attr('data-brand').toLowerCase().includes(searchValue);
        });

        $('#product-list').empty();

        products.forEach(product => {
            $('#product-list').append(product);
        });

        let paginationFunc = pagination(paginationLength, productsPerPage, $(products));
        paginationFunc(1);
    });
</script>
</body>
</html>
<?php
$conn->close();
?>