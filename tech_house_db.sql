-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 06, 2024 at 02:21 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tech_house_db`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `Tao_don_hang_mot_sp` (IN `p_thanh_vien` VARCHAR(25), IN `p_ma_sp` INT, IN `p_so_luong` INT, IN `p_tong_gia` INT)   begin
    insert into Don_hang (thanh_vien, thoi_diem_dat_hang, tinh_trang, tong_gia) values (p_thanh_vien, now(), 0, p_tong_gia);
    set @ma_don_hang = (select ma_don_hang from Don_hang where thanh_vien = p_thanh_vien and thoi_diem_dat_hang = now());
    insert into Chi_tiet_don_hang values (@ma_don_hang, p_ma_sp, p_so_luong, p_tong_gia);
    update San_pham set sl_ton_kho = sl_ton_kho - p_so_luong where ma_sp = p_ma_sp;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Tao_don_hang_tu_gio_hang` (IN `p_thanh_vien` VARCHAR(25), IN `p_tong_gia` INT)   BEGIN
    DECLARE c_so_luong INT;
    DECLARE c_ma_sp INT;
    DECLARE c_gia_thanh INT;
    DECLARE c_sale_off FLOAT;
    DECLARE c_don_gia INT;
    DECLARE done INT DEFAULT FALSE;

    DECLARE gio_hang_cursor CURSOR FOR 
        SELECT ma_sp, so_luong 
        FROM Gio_hang 
        WHERE thanh_vien = p_thanh_vien;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    INSERT INTO Don_hang (thanh_vien, thoi_diem_dat_hang, tinh_trang, tong_gia) 
    VALUES (p_thanh_vien, NOW(), 0, p_tong_gia);
    
    SET @ma_don_hang = LAST_INSERT_ID();

    OPEN gio_hang_cursor;

    gio_hang_loop: LOOP
        FETCH gio_hang_cursor INTO c_ma_sp, c_so_luong;
        IF done THEN
            LEAVE gio_hang_loop;
        END IF;
        SET c_gia_thanh = (SELECT gia_thanh FROM San_pham WHERE ma_sp = c_ma_sp);
        SET c_sale_off = (SELECT sale_off FROM San_pham WHERE ma_sp = c_ma_sp);
        SET c_don_gia = c_gia_thanh * (1 - c_sale_off);
        INSERT INTO Chi_tiet_don_hang (ma_don_hang, ma_sp, so_luong, don_gia) 
        VALUES (@ma_don_hang, c_ma_sp, c_so_luong, c_don_gia);
    END LOOP;

    CLOSE gio_hang_cursor;

    DELETE FROM Gio_hang WHERE thanh_vien = p_thanh_vien;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Tao_nhan_vien` (IN `p_ten_dang_nhap` VARCHAR(25), IN `mat_khau` VARCHAR(255), IN `ho_va_ten` VARCHAR(100), IN `email` VARCHAR(100), IN `sdt` VARCHAR(10), IN `dia_chi` VARCHAR(255), IN `cccd` VARCHAR(12), IN `gioi_tinh` VARCHAR(3), IN `ngay_sinh` DATE)   begin
    insert into Tai_khoan values (p_ten_dang_nhap, mat_khau, ho_va_ten, email, sdt, dia_chi, 'nv', now(), null);
    insert into Nhan_vien values (p_ten_dang_nhap, cccd, gioi_tinh, ngay_sinh);

    select * from Tai_khoan where ten_dang_nhap = p_ten_dang_nhap;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Tao_thanh_vien` (IN `p_ten_dang_nhap` VARCHAR(25), IN `mat_khau` VARCHAR(255), IN `sdt` VARCHAR(10))   begin
    insert into Tai_khoan values (p_ten_dang_nhap, mat_khau, "New User", null, sdt, null, 'tv', now(), null);
    insert into Thanh_vien values (p_ten_dang_nhap, TRUE, null);

    select * from Tai_khoan where ten_dang_nhap = p_ten_dang_nhap;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Them_ban_phim` (IN `p_ten_sp` VARCHAR(500), IN `p_thuong_hieu` VARCHAR(20), IN `p_hinh_anh` VARCHAR(500), IN `p_sl_ton_kho` INT, IN `p_gia_thanh` INT, IN `p_sale_off` FLOAT, IN `p_mo_ta` VARCHAR(5000), IN `p_mau_sac` VARCHAR(20), IN `p_key_cap` VARCHAR(100), IN `p_so_phim` INT, IN `p_cong_ket_noi` VARCHAR(100))   begin
    insert into San_pham (ten_sp, thuong_hieu, phan_loai, hinh_anh, sl_ton_kho, gia_thanh, sale_off, mo_ta, mau_sac) values (p_ten_sp, p_thuong_hieu, 4, p_hinh_anh, p_sl_ton_kho, p_gia_thanh, p_sale_off, p_mo_ta, p_mau_sac);
    set @ma_sp = (select ma_sp from San_pham where ten_sp = p_ten_sp);
    insert into Ban_phim values (@ma_sp, p_key_cap, p_so_phim, p_cong_ket_noi);

    select * from San_pham where ma_sp = @ma_sp;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Them_laptop` (IN `p_ten_sp` VARCHAR(500), IN `p_thuong_hieu` VARCHAR(20), IN `p_hinh_anh` VARCHAR(500), IN `p_sl_ton_kho` INT, IN `p_gia_thanh` INT, IN `p_sale_off` FLOAT, IN `p_mo_ta` VARCHAR(5000), IN `p_mau_sac` VARCHAR(20), IN `p_bo_xu_ly` VARCHAR(100), IN `p_dung_luong_pin` VARCHAR(100), IN `p_kich_thuoc_man_hinh` VARCHAR(100), IN `p_cong_nghe_man_hinh` VARCHAR(100), IN `p_he_dieu_hanh` VARCHAR(50), IN `p_ram` VARCHAR(50), IN `p_bo_nho` VARCHAR(50))   begin
    insert into San_pham (ten_sp, thuong_hieu, phan_loai, hinh_anh, sl_ton_kho, gia_thanh, sale_off, mo_ta, mau_sac) values (p_ten_sp, p_thuong_hieu, 0, p_hinh_anh, p_sl_ton_kho, p_gia_thanh, p_sale_off, p_mo_ta, p_mau_sac);
    set @ma_sp = (select ma_sp from San_pham where ten_sp = p_ten_sp);
    insert into Laptop values (@ma_sp, p_bo_xu_ly, p_dung_luong_pin, p_kich_thuoc_man_hinh, p_cong_nghe_man_hinh, p_he_dieu_hanh, p_ram, p_bo_nho);

    select * from San_pham where ma_sp = @ma_sp;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Them_mobile` (IN `p_ten_sp` VARCHAR(500), IN `p_thuong_hieu` VARCHAR(20), IN `p_hinh_anh` VARCHAR(500), IN `p_sl_ton_kho` INT, IN `p_gia_thanh` INT, IN `p_sale_off` FLOAT, IN `p_mo_ta` VARCHAR(5000), IN `p_mau_sac` VARCHAR(20), IN `p_bo_xu_ly` VARCHAR(100), IN `p_dung_luong_pin` VARCHAR(100), IN `p_kich_thuoc_man_hinh` VARCHAR(100), IN `p_cong_nghe_man_hinh` VARCHAR(100), IN `p_he_dieu_hanh` VARCHAR(50), IN `p_bo_nho` VARCHAR(50))   begin
    insert into San_pham (ten_sp, thuong_hieu, phan_loai, hinh_anh, sl_ton_kho, gia_thanh, sale_off, mo_ta, mau_sac) values (p_ten_sp, p_thuong_hieu, 1, p_hinh_anh, p_sl_ton_kho, p_gia_thanh, p_sale_off, p_mo_ta, p_mau_sac);
    set @ma_sp = (select ma_sp from San_pham where ten_sp = p_ten_sp);
    insert into Mobile values (@ma_sp, p_bo_xu_ly, p_dung_luong_pin, p_kich_thuoc_man_hinh, p_cong_nghe_man_hinh, p_he_dieu_hanh, p_bo_nho);

    select * from San_pham where ma_sp = @ma_sp;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Them_op_lung` (IN `p_ten_sp` VARCHAR(500), IN `p_thuong_hieu` VARCHAR(20), IN `p_hinh_anh` VARCHAR(500), IN `p_sl_ton_kho` INT, IN `p_gia_thanh` INT, IN `p_sale_off` FLOAT, IN `p_mo_ta` VARCHAR(5000), IN `p_mau_sac` VARCHAR(20), IN `p_chat_lieu` VARCHAR(100), IN `p_do_day` VARCHAR(100))   begin
    insert into San_pham (ten_sp, thuong_hieu, phan_loai, hinh_anh, sl_ton_kho, gia_thanh, sale_off, mo_ta, mau_sac) values (p_ten_sp, p_thuong_hieu, 6, p_hinh_anh, p_sl_ton_kho, p_gia_thanh, p_sale_off, p_mo_ta, p_mau_sac);
    set @ma_sp = (select ma_sp from San_pham where ten_sp = p_ten_sp);
    insert into Op_lung values (@ma_sp, p_chat_lieu, p_do_day);

    select * from San_pham where ma_sp = @ma_sp;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Them_sac_du_phong` (IN `p_ten_sp` VARCHAR(500), IN `p_thuong_hieu` VARCHAR(20), IN `p_hinh_anh` VARCHAR(500), IN `p_sl_ton_kho` INT, IN `p_gia_thanh` INT, IN `p_sale_off` FLOAT, IN `p_mo_ta` VARCHAR(5000), IN `p_mau_sac` VARCHAR(20), IN `p_dung_luong_pin` VARCHAR(100), IN `p_cong_suat` VARCHAR(100), IN `p_cong_ket_noi` VARCHAR(100), IN `p_chat_lieu` VARCHAR(100))   begin
    insert into San_pham (ten_sp, thuong_hieu, phan_loai, hinh_anh, sl_ton_kho, gia_thanh, sale_off, mo_ta, mau_sac) values (p_ten_sp, p_thuong_hieu, 5, p_hinh_anh, p_sl_ton_kho, p_gia_thanh, p_sale_off, p_mo_ta, p_mau_sac);
    set @ma_sp = (select ma_sp from San_pham where ten_sp = p_ten_sp);
    insert into Sac_du_phong values (@ma_sp, p_dung_luong_pin, p_cong_suat, p_cong_ket_noi, p_chat_lieu);

    select * from San_pham where ma_sp = @ma_sp;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Them_tablet` (IN `p_ten_sp` VARCHAR(500), IN `p_thuong_hieu` VARCHAR(20), IN `p_hinh_anh` VARCHAR(500), IN `p_sl_ton_kho` INT, IN `p_gia_thanh` INT, IN `p_sale_off` FLOAT, IN `p_mo_ta` VARCHAR(5000), IN `p_mau_sac` VARCHAR(20), IN `p_bo_xu_ly` VARCHAR(100), IN `p_dung_luong_pin` VARCHAR(100), IN `p_kich_thuoc_man_hinh` VARCHAR(100), IN `p_cong_nghe_man_hinh` VARCHAR(100), IN `p_he_dieu_hanh` VARCHAR(50), IN `p_bo_nho` VARCHAR(50))   begin
    insert into San_pham (ten_sp, thuong_hieu, phan_loai, hinh_anh, sl_ton_kho, gia_thanh, sale_off, mo_ta, mau_sac) values (p_ten_sp, p_thuong_hieu, 2, p_hinh_anh, p_sl_ton_kho, p_gia_thanh, p_sale_off, p_mo_ta, p_mau_sac);
    set @ma_sp = (select ma_sp from San_pham where ten_sp = p_ten_sp);
    insert into Tablet values (@ma_sp, p_bo_xu_ly, p_dung_luong_pin, p_kich_thuoc_man_hinh, p_cong_nghe_man_hinh, p_he_dieu_hanh, p_bo_nho);

    select * from San_pham where ma_sp = @ma_sp;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Them_tai_nghe_blue_tooth` (IN `p_ten_sp` VARCHAR(500), IN `p_thuong_hieu` VARCHAR(20), IN `p_hinh_anh` VARCHAR(500), IN `p_sl_ton_kho` INT, IN `p_gia_thanh` INT, IN `p_sale_off` FLOAT, IN `p_mo_ta` VARCHAR(5000), IN `p_mau_sac` VARCHAR(20), IN `p_pham_vi_ket_noi` VARCHAR(100), IN `p_thoi_luong_pin` VARCHAR(100), IN `p_chong_nuoc` VARCHAR(100), IN `p_cong_nghe_am_thanh` VARCHAR(100))   begin
    insert into San_pham (ten_sp, thuong_hieu, phan_loai, hinh_anh, sl_ton_kho, gia_thanh, sale_off, mo_ta, mau_sac) values (p_ten_sp, p_thuong_hieu, 3, p_hinh_anh, p_sl_ton_kho, p_gia_thanh, p_sale_off, p_mo_ta, p_mau_sac);
    set @ma_sp = (select ma_sp from San_pham where ten_sp = p_ten_sp);
    insert into Tai_nghe_bluetooth values (@ma_sp, p_pham_vi_ket_noi, p_thoi_luong_pin, p_chong_nuoc, p_cong_nghe_am_thanh);

    select * from San_pham where ma_sp = @ma_sp;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Them_vao_gio_hang` (IN `p_thanh_vien` VARCHAR(25), IN `p_ma_sp` INT, IN `p_so_luong` INT)   begin
    if exists (select * from Gio_hang where thanh_vien = p_thanh_vien and ma_sp = p_ma_sp) then
        update Gio_hang set so_luong = so_luong + p_so_luong where thanh_vien = p_thanh_vien and ma_sp = p_ma_sp;
    else
        insert into Gio_hang values (p_thanh_vien, p_ma_sp, p_so_luong);
    end if;
end$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `ban_phim`
--

CREATE TABLE `ban_phim` (
  `ma_sp` int(11) NOT NULL,
  `key_cap` varchar(100) NOT NULL,
  `so_phim` int(11) NOT NULL,
  `cong_ket_noi` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ban_phim`
--

INSERT INTO `ban_phim` (`ma_sp`, `key_cap`, `so_phim`, `cong_ket_noi`) VALUES
(58, 'Nhựa ABS', 82, 'Bluetooth'),
(59, 'Nhựa ABS', 104, 'Type-C'),
(60, 'Nhựa ABS', 104, 'Bluetooth');

-- --------------------------------------------------------

--
-- Table structure for table `chi_tiet_don_hang`
--

CREATE TABLE `chi_tiet_don_hang` (
  `ma_don_hang` int(11) NOT NULL,
  `ma_sp` int(11) NOT NULL,
  `so_luong` int(11) NOT NULL,
  `don_gia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chi_tiet_don_hang`
--

INSERT INTO `chi_tiet_don_hang` (`ma_don_hang`, `ma_sp`, `so_luong`, `don_gia`) VALUES
(1, 1, 1, 20000000),
(2, 2, 1, 41990000),
(3, 2, 1, 41990000),
(4, 3, 1, 20500000),
(5, 2, 1, 41990000),
(6, 3, 1, 19475000),
(7, 20, 2, 81491446),
(8, 4, 1, 7990000),
(8, 20, 1, 40342300),
(8, 27, 1, 12490000);

-- --------------------------------------------------------

--
-- Table structure for table `danh_gia`
--

CREATE TABLE `danh_gia` (
  `thoi_diem_danh_gia` datetime NOT NULL,
  `thanh_vien` varchar(25) NOT NULL,
  `ma_dh` int(11) NOT NULL,
  `ma_sp` int(11) NOT NULL,
  `diem_danh_gia` int(11) NOT NULL,
  `noi_dung` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `danh_gia`
--

INSERT INTO `danh_gia` (`thoi_diem_danh_gia`, `thanh_vien`, `ma_dh`, `ma_sp`, `diem_danh_gia`, `noi_dung`) VALUES
('2024-12-05 22:53:45', 'khachhang1', 6, 3, 5, 'Điện thoại xịn'),
('2024-12-05 23:46:26', 'khachhang1', 8, 20, 4, 'Giá mắc, nhưng có thương hiệu');

-- --------------------------------------------------------

--
-- Table structure for table `danh_sach_yeu_thich`
--

CREATE TABLE `danh_sach_yeu_thich` (
  `thanh_vien` varchar(25) NOT NULL,
  `ma_sp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `danh_sach_yeu_thich`
--

INSERT INTO `danh_sach_yeu_thich` (`thanh_vien`, `ma_sp`) VALUES
('khachhang1', 20);

-- --------------------------------------------------------

--
-- Table structure for table `don_hang`
--

CREATE TABLE `don_hang` (
  `ma_don_hang` int(11) NOT NULL,
  `thanh_vien` varchar(25) NOT NULL,
  `thoi_diem_dat_hang` datetime NOT NULL,
  `thoi_diem_nhan_hang` datetime DEFAULT NULL,
  `tinh_trang` int(11) NOT NULL,
  `tong_gia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `don_hang`
--

INSERT INTO `don_hang` (`ma_don_hang`, `thanh_vien`, `thoi_diem_dat_hang`, `thoi_diem_nhan_hang`, `tinh_trang`, `tong_gia`) VALUES
(1, 'khachhang1', '2024-11-06 10:08:00', '2024-12-06 07:29:41', 3, 20000000),
(2, 'khachhang1', '2024-11-05 10:08:00', NULL, 1, 41990000),
(3, 'khachhang1', '2024-11-03 10:08:00', NULL, 2, 41990000),
(4, 'khachhang1', '2024-11-02 10:08:00', NULL, 3, 20500000),
(5, 'khachhang1', '2024-11-01 10:08:00', '2024-11-01 12:08:00', 4, 41990000),
(6, 'khachhang1', '2024-12-05 22:51:47', '2024-12-05 22:53:07', 3, 19669750),
(7, 'khachhang1', '2024-12-05 23:33:42', NULL, 0, 81491446),
(8, 'khachhang1', '2024-12-05 23:45:21', '2024-12-05 23:45:45', 3, 61430523);

-- --------------------------------------------------------

--
-- Table structure for table `duyet_don_hang`
--

CREATE TABLE `duyet_don_hang` (
  `ma_don_hang` int(11) NOT NULL,
  `nhan_vien` varchar(25) NOT NULL,
  `thoi_diem_duyet` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `duyet_don_hang`
--

INSERT INTO `duyet_don_hang` (`ma_don_hang`, `nhan_vien`, `thoi_diem_duyet`) VALUES
(1, '$admin1', '2024-12-06 07:29:34'),
(2, '$admin1', '2024-11-05 21:00:00'),
(3, '$admin1', '2024-11-03 21:00:00'),
(4, '$admin1', '2024-11-02 21:00:00'),
(6, '$admin1', '2024-12-05 22:52:16'),
(8, '$admin1', '2024-12-05 23:45:44');

-- --------------------------------------------------------

--
-- Table structure for table `gio_hang`
--

CREATE TABLE `gio_hang` (
  `thanh_vien` varchar(25) NOT NULL,
  `ma_sp` int(11) NOT NULL,
  `so_luong` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gio_hang`
--

INSERT INTO `gio_hang` (`thanh_vien`, `ma_sp`, `so_luong`) VALUES
('khachhang1', 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `laptop`
--

CREATE TABLE `laptop` (
  `ma_sp` int(11) NOT NULL,
  `bo_xu_ly` varchar(100) NOT NULL,
  `dung_luong_pin` varchar(100) NOT NULL,
  `kich_thuoc_man_hinh` varchar(100) NOT NULL,
  `cong_nghe_man_hinh` varchar(100) NOT NULL,
  `he_dieu_hanh` varchar(50) NOT NULL,
  `ram` varchar(50) NOT NULL,
  `bo_nho` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `laptop`
--

INSERT INTO `laptop` (`ma_sp`, `bo_xu_ly`, `dung_luong_pin`, `kich_thuoc_man_hinh`, `cong_nghe_man_hinh`, `he_dieu_hanh`, `ram`, `bo_nho`) VALUES
(26, 'Intel Core i3', '3 Cell, 41 Wh', '15.6 inch', 'Intel Iris Xe Graphics', 'Windows 10', '8GB', '256GB'),
(27, 'Intel Core i5 thế hệ 12', '3 Cell, 41 Wh', '15.6 inch', 'Intel UHD Graphics', 'Windows 11', '8GB', '256GB'),
(28, 'Intel Core i5 thế hệ 11', '3 Cell, 41 Wh', '15.6 inch', 'Intel UHD Graphics', 'Windows 11', '8GB', '256GB'),
(29, 'Apple M2', '52,6 Wh', '13.6 inches', 'Liquid Retina Display', 'MacOS', '8GB', '256GB'),
(30, 'Intel Core i5 thế hệ 13', '3 Cell, 41 Wh', '15.6 inch', 'Intel Iris Xe Graphics', 'Windows 11', '16GB', '512GB'),
(31, 'AMD Ryzen 5 5600H 3.3GHz up to 4.2GHz 16MB', '3 Cell, 41 Wh', '15.6 inch', 'NVIDIA GeForce GTX 1650 4GB GDDR6', 'Windows 10 Home SL', '8GB', '512GB'),
(32, 'Intel Core i5 thế hệ 11', '3 Cell, 41 Wh', '15.6 inch', 'Intel UHD Graphics', 'Windows 11', '8GB', '512GB'),
(33, 'Intel Core i7 thế hệ 11', '3 Cell, 41 Wh', '15.6 inch', 'Intel UHD Graphics', 'Windows 11', '8GB', '512GB'),
(34, 'Intel Core i7 thế hệ 11', '3 Cell, 41 Wh', '15.6 inch', 'Intel UHD Graphics', 'Windows 11', '16GB', '512GB'),
(35, 'Intel Core i5 thế hệ 13', '3 Cell, 41 Wh', '15.6 inch', 'Intel Iris Xe Graphics', 'Windows 11', '8GB', '512GB'),
(36, 'Intel Core i5 thế hệ 12', '3 Cell, 41 Wh', '15.6 inch', 'Intel UHD Graphics', 'Windows 11', '16GB', '256GB'),
(37, 'Intel Core i7 thế hệ 13', '3 Cell, 41 Wh', '15.6 inch', 'Intel Iris Xe Graphics', 'Windows 11', '16GB', '512GB'),
(38, 'Intel Core i3', '3 Cell, 41 Wh', '15.6 inch', 'Intel UHD Graphics 620', 'Windows 10', '8GB', '256GB');

-- --------------------------------------------------------

--
-- Table structure for table `mobile`
--

CREATE TABLE `mobile` (
  `ma_sp` int(11) NOT NULL,
  `bo_xu_ly` varchar(100) NOT NULL,
  `dung_luong_pin` varchar(100) NOT NULL,
  `kich_thuoc_man_hinh` varchar(100) NOT NULL,
  `cong_nghe_man_hinh` varchar(100) NOT NULL,
  `he_dieu_hanh` varchar(50) NOT NULL,
  `bo_nho` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mobile`
--

INSERT INTO `mobile` (`ma_sp`, `bo_xu_ly`, `dung_luong_pin`, `kich_thuoc_man_hinh`, `cong_nghe_man_hinh`, `he_dieu_hanh`, `bo_nho`) VALUES
(1, 'Apple A15 Bionic 6 nhân', '4000mAh', '6.7 inch', 'OLED', 'iOS 17', '128GB'),
(2, 'Snapdragon 8 Gen 3 For Galaxy', '4400mAh', '7.6 inch', 'Dynamic AMOLED 2X', 'Android 14', '256GB'),
(3, 'Apple A15 Bionic 6 nhân', '4000mAh', '6.7 inch', 'OLED', 'iOS 17', '128GB'),
(4, 'Snapdragon 695 5G', '4500mAh', '6.5 inch', 'OLED', 'Android 12', '128GB'),
(5, 'Snapdragon 778G', '4500mAh', '6.5 inch', 'OLED', 'Android 12', '128GB'),
(6, 'Apple A15 Bionic 6 nhân', '4000mAh', '6.7 inch', 'OLED', 'iOS 17', '128GB'),
(7, 'Snapdragon 8 Gen 2', '4500mAh', '6.5 inch', 'OLED', 'Android 12', '256GB'),
(8, 'Apple A15 Bionic 6 nhân', '4000mAh', '6.7 inch', 'OLED', 'iOS 17', '256GB'),
(9, 'Apple A15 Bionic 6 nhân', '4000mAh', '6.7 inch', 'OLED', 'iOS 17', '256GB'),
(10, 'Apple A15 Bionic 6 nhân', '4000mAh', '6.7 inch', 'OLED', 'iOS 17', '256GB'),
(11, 'Apple A15 Bionic 6 nhân', '4000mAh', '6.7 inch', 'OLED', 'iOS 17', '512GB'),
(12, 'Apple A15 Bionic 6 nhân', '4000mAh', '6.7 inch', 'OLED', 'iOS 17', '512GB'),
(13, 'Apple A15 Bionic 6 nhân', '4000mAh', '6.7 inch', 'OLED', 'iOS 17', '512GB'),
(14, 'Apple A15 Bionic 6 nhân', '4000mAh', '6.1 inch', 'OLED', 'iOS 16', '256GB'),
(15, 'Apple A16 Bionic 6 nhân', '4000mAh', '6.7 inch', 'OLED', 'iOS 16', '128GB'),
(16, 'Apple A16 Bionic 6 nhân', '4000mAh', '6.7 inch', 'OLED', 'iOS 16', '256GB'),
(17, 'Apple A15 Bionic 6 nhân', '4000mAh', '6.1 inch', 'OLED', 'iOS 16', '128GB'),
(18, 'Apple A16 Bionic 6 nhân', '4000mAh', '6.7 inch', 'OLED', 'iOS 16', '512GB'),
(19, 'Snapdragon 8 Gen 3 For Galaxy', '5000mAh', '6.8 inch', 'Dynamic AMOLED 2X', 'Android 14', '256GB'),
(20, 'Apple A16 Bionic 6 nhân', '4000mAh', '6.7 inch', 'OLED', 'iOS 16', '1TB'),
(21, 'Snapdragon 8 Gen 3 For Galaxy', '4400mAh', '7.6 inch', 'Dynamic AMOLED 2X', 'Android 14', '512GB'),
(22, 'Apple A15 Bionic 6 nhân', '4000mAh', '6.1 inch', 'OLED', 'iOS 16', '512GB'),
(23, 'Snapdragon 8 Gen 3 For Galaxy', '4400mAh', '7.6 inch', 'Dynamic AMOLED 2X', 'Android 14', '1TB'),
(24, 'Snapdragon 778G', '4500mAh', '6.5 inch', 'OLED', 'Android 12', '128GB'),
(25, 'Snapdragon 778G', '4500mAh', '6.5 inch', 'OLED', 'Android 12', '128GB');

-- --------------------------------------------------------

--
-- Table structure for table `nhan_vien`
--

CREATE TABLE `nhan_vien` (
  `ten_dang_nhap` varchar(25) NOT NULL,
  `cccd` varchar(12) NOT NULL,
  `gioi_tinh` varchar(3) NOT NULL,
  `ngay_sinh` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nhan_vien`
--

INSERT INTO `nhan_vien` (`ten_dang_nhap`, `cccd`, `gioi_tinh`, `ngay_sinh`) VALUES
('$admin1', '123456789012', 'nam', '1999-01-01');

-- --------------------------------------------------------

--
-- Table structure for table `op_lung`
--

CREATE TABLE `op_lung` (
  `ma_sp` int(11) NOT NULL,
  `chat_lieu` varchar(100) NOT NULL,
  `do_day` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `op_lung`
--

INSERT INTO `op_lung` (`ma_sp`, `chat_lieu`, `do_day`) VALUES
(55, 'Silicone', '5mm'),
(56, 'Silicone', '5mm'),
(57, 'Silicone', '5mm'),
(62, 'nhua', '5mm');

-- --------------------------------------------------------

--
-- Table structure for table `sac_du_phong`
--

CREATE TABLE `sac_du_phong` (
  `ma_sp` int(11) NOT NULL,
  `dung_luong_pin` varchar(100) NOT NULL,
  `cong_suat` varchar(100) NOT NULL,
  `cong_ket_noi` varchar(100) NOT NULL,
  `chat_lieu` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sac_du_phong`
--

INSERT INTO `sac_du_phong` (`ma_sp`, `dung_luong_pin`, `cong_suat`, `cong_ket_noi`, `chat_lieu`) VALUES
(53, '20000mAh', '30W', '1 x Type-C, 1 x micro USB', 'Nhựa ABS'),
(54, '20000mAh', '18W', '1 x Type-C, 1 x micro USB', 'Nhựa ABS');

-- --------------------------------------------------------

--
-- Table structure for table `san_pham`
--

CREATE TABLE `san_pham` (
  `ma_sp` int(11) NOT NULL,
  `ten_sp` varchar(500) NOT NULL,
  `thuong_hieu` varchar(20) NOT NULL,
  `phan_loai` int(11) NOT NULL,
  `hinh_anh` varchar(500) NOT NULL,
  `sl_ton_kho` int(11) NOT NULL,
  `gia_thanh` int(11) NOT NULL,
  `sale_off` float NOT NULL,
  `mo_ta` varchar(5000) NOT NULL,
  `mau_sac` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `san_pham`
--

INSERT INTO `san_pham` (`ma_sp`, `ten_sp`, `thuong_hieu`, `phan_loai`, `hinh_anh`, `sl_ton_kho`, `gia_thanh`, `sale_off`, `mo_ta`, `mau_sac`) VALUES
(1, 'iPhone 15 - 128GB - Pink', 'Apple', 1, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:358:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/i/p/iphone-15-plus_1__1.png', 50, 20000000, 0.03, 'iPhone 15 128GB được trang bị màn hình Dynamic Island kích thước 6.1 inch với công nghệ hiển thị Super Retina XDR màn lại trải nghiệm hình ảnh vượt trội. Điện thoại với mặt lưng kính nhám chống bám mồ hôi cùng 5 phiên bản màu sắc lựa chọn: Hồng, Vàng, Xanh lá, Xanh dương và Đen. Camera trên iPhone 15 series cũng được nâng cấp lên cảm biến 48MP cùng tính năng chụp zoom quang học tới 2x. Cùng với thiết kế cổng sạc thay đổi từ lightning sang USB-C vô cùng ấn tượng.', 'Pink'),
(2, 'Samsung Galaxy Z Fold6 - 256GB - Pink', 'Samsung', 1, 'https://cdn2.cellphones.com.vn/358x/media/catalog/product/i/m/image_1171.png', 100, 41990000, 0.02, 'Samsung Z Fold 6 là siêu phẩm điện thoại gập được ra mắt ngày 10/7, hiệu năng dẫn đầu phân khúc với chip 8 nhân Snapdragon 8 Gen 3 for Galaxy, 12GB RAM cùng bộ nhớ trong từ 256GB đến 1TB. Thay đổi mạnh mẽ về hiệu năng và thiết kế, Galaxy Z Fold 6 hứa hẹn sẽ là chiếc smartphone AI đáng sở hữu nhất nửa cuối năm 2024. Cùng CellphoneS cập nhật tất tần tật thông tin về Galaxy Z Fold6 ngay đây nhé!', 'Pink'),
(3, 'iPhone 15 - 128GB - Gold', 'Apple', 1, 'https://cdn2.cellphones.com.vn/358x/media/catalog/product/i/p/iphone-15-128gb-vang.png', 100, 20500000, 0.05, 'iPhone 15 128GB được trang bị màn hình Dynamic Island kích thước 6.1 inch với công nghệ hiển thị Super Retina XDR màn lại trải nghiệm hình ảnh vượt trội. Điện thoại với mặt lưng kính nhám chống bám mồ hôi cùng 5 phiên bản màu sắc lựa chọn: Hồng, Vàng, Xanh lá, Xanh dương và Đen. Camera trên iPhone 15 series cũng được nâng cấp lên cảm biến 48MP cùng tính năng chụp zoom quang học tới 2x. Cùng với thiết kế cổng sạc thay đổi từ lightning sang USB-C vô cùng ấn tượng.', 'Gold'),
(4, 'Sony Xperia 10 V - 128GB - Purple', 'Sony', 1, 'https://cdn2.cellphones.com.vn/358x/media/catalog/product/s/o/son-xperia-10v-8gb-128gb-ti-99.png', 100, 7990000, 0, 'Sony Xperia 10 V được trang bị cấu hình mạnh mẽ với chip Snapdragon 695 5G, RAM 8GB ấn tượng. Bên cạnh đó chiếc điện thoại Sony này còn sở hữu giá thành phải chăng cùng nhiều tính năng vượt trội. Chắc chắn Xperia 10V sẽ mang đến lợi thế cạnh tranh mà nhiều thương hiệu khác cùng phân khúc phải dè chừng.', 'Purple'),
(5, 'Google Pixel 6 - 128GB - Yellow', 'Google', 1, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/g/g/gggg_1__1.jpg', 100, 13990000, 0, 'Google Pixel 6 là mẫu smartphone độc đáo và vô cùng chất lượng từ vẻ ngoài cho tới hiệu năng bên trong. Nhờ nâng cấp mạnh mẽ và ấn tượng đây được đánh giá là chiếc điện thoại chất lượng nổi bật trong phân khúc. Chắc chắn với những gì mang lại sẽ giúp chúng có được sức cạnh tranh mạnh mẽ trên thị trường.', 'Yellow'),
(6, 'iPhone 15 - 128GB - Black', 'Apple', 1, 'https://cdn2.cellphones.com.vn/358x/media/catalog/product/i/p/iphone-15-128-gbden.png', 100, 21000000, 0.05, 'iPhone 15 128GB được trang bị màn hình Dynamic Island kích thước 6.1 inch với công nghệ hiển thị Super Retina XDR màn lại trải nghiệm hình ảnh vượt trội. Điện thoại với mặt lưng kính nhám chống bám mồ hôi cùng 5 phiên bản màu sắc lựa chọn: Hồng, Vàng, Xanh lá, Xanh dương và Đen. Camera trên iPhone 15 series cũng được nâng cấp lên cảm biến 48MP cùng tính năng chụp zoom quang học tới 2x. Cùng với thiết kế cổng sạc thay đổi từ lightning sang USB-C vô cùng ấn tượng.', 'Black'),
(7, 'Sony Xperia 1 V - 256GB - Black', 'Sony', 1, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/s/o/sony-xperia-1-v.png', 100, 27990000, 0, 'Sony Xperia 1 V (hay Sony 1 Mark 5) sẽ là một siêu phẩm camera trong năm này với hệ thống 3 cảm biến có khả năng zoom quang học ấn tượng. Bên cạnh đó, sản phẩm còn sở hữu hiệu suất hoạt động vượt trội nhờ có bộ cấu hình tiên tiến với dung lượng RAM và bộ nhớ trong khổng lồ. Điểm nhấn ở smartphone còn nằm ở phần màn hình bắt mắt với màu sắc tươi sáng.', 'Black'),
(8, 'iPhone 15 - 256GB - Pink', 'Apple', 1, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:358:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/i/p/iphone-15-plus_1__1.png', 100, 20000000, 0.05, 'iPhone 15 256GB được trang bị màn hình Dynamic Island kích thước 6.1 inch với công nghệ hiển thị Super Retina XDR màn lại trải nghiệm hình ảnh vượt trội. Điện thoại với mặt lưng kính nhám chống bám mồ hôi cùng 5 phiên bản màu sắc lựa chọn: Hồng, Vàng, Xanh lá, Xanh dương và Đen. Camera trên iPhone 15 series cũng được nâng cấp lên cảm biến 48MP cùng tính năng chụp zoom quang học tới 2x. Cùng với thiết kế cổng sạc thay đổi từ lightning sang USB-C vô cùng ấn tượng.', 'Pink'),
(9, 'iPhone 15 - 256GB - Gold', 'Apple', 1, 'https://cdn2.cellphones.com.vn/358x/media/catalog/product/i/p/iphone-15-128gb-vang.png', 100, 20500000, 0.05, 'iPhone 15 256GB được trang bị màn hình Dynamic Island kích thước 6.1 inch với công nghệ hiển thị Super Retina XDR màn lại trải nghiệm hình ảnh vượt trội. Điện thoại với mặt lưng kính nhám chống bám mồ hôi cùng 5 phiên bản màu sắc lựa chọn: Hồng, Vàng, Xanh lá, Xanh dương và Đen. Camera trên iPhone 15 series cũng được nâng cấp lên cảm biến 48MP cùng tính năng chụp zoom quang học tới 2x. Cùng với thiết kế cổng sạc thay đổi từ lightning sang USB-C vô cùng ấn tượng.', 'Gold'),
(10, 'iPhone 15 - 256GB - Black', 'Apple', 1, 'https://cdn2.cellphones.com.vn/358x/media/catalog/product/i/p/iphone-15-128-gbden.png', 100, 21000000, 0.05, 'iPhone 15 256GB được trang bị màn hình Dynamic Island kích thước 6.1 inch với công nghệ hiển thị Super Retina XDR màn lại trải nghiệm hình ảnh vượt trội. Điện thoại với mặt lưng kính nhám chống bám mồ hôi cùng 5 phiên bản màu sắc lựa chọn: Hồng, Vàng, Xanh lá, Xanh dương và Đen. Camera trên iPhone 15 series cũng được nâng cấp lên cảm biến 48MP cùng tính năng chụp zoom quang học tới 2x. Cùng với thiết kế cổng sạc thay đổi từ lightning sang USB-C vô cùng ấn tượng.', 'Black'),
(11, 'iPhone 15 - 512GB - Pink', 'Apple', 1, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:358:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/i/p/iphone-15-plus_1__1.png', 100, 20000000, 0.05, 'iPhone 15 512GB được trang bị màn hình Dynamic Island kích thước 6.1 inch với công nghệ hiển thị Super Retina XDR màn lại trải nghiệm hình ảnh vượt trội. Điện thoại với mặt lưng kính nhám chống bám mồ hôi cùng 5 phiên bản màu sắc lựa chọn: Hồng, Vàng, Xanh lá, Xanh dương và Đen. Camera trên iPhone 15 series cũng được nâng cấp lên cảm biến 48MP cùng tính năng chụp zoom quang học tới 2x. Cùng với thiết kế cổng sạc thay đổi từ lightning sang USB-C vô cùng ấn tượng.', 'Pink'),
(12, 'iPhone 15 - 512GB - Gold', 'Apple', 1, 'https://cdn2.cellphones.com.vn/358x/media/catalog/product/i/p/iphone-15-128gb-vang.png', 100, 20500000, 0.05, 'iPhone 15 512GB được trang bị màn hình Dynamic Island kích thước 6.1 inch với công nghệ hiển thị Super Retina XDR màn lại trải nghiệm hình ảnh vượt trội. Điện thoại với mặt lưng kính nhám chống bám mồ hôi cùng 5 phiên bản màu sắc lựa chọn: Hồng, Vàng, Xanh lá, Xanh dương và Đen. Camera trên iPhone 15 series cũng được nâng cấp lên cảm biến 48MP cùng tính năng chụp zoom quang học tới 2x. Cùng với thiết kế cổng sạc thay đổi từ lightning sang USB-C vô cùng ấn tượng.', 'Gold'),
(13, 'iPhone 15 - 512GB - Black', 'Apple', 1, 'https://cdn2.cellphones.com.vn/358x/media/catalog/product/i/p/iphone-15-128-gbden.png', 100, 21000000, 0.05, 'iPhone 15 512GB được trang bị màn hình Dynamic Island kích thước 6.1 inch với công nghệ hiển thị Super Retina XDR màn lại trải nghiệm hình ảnh vượt trội. Điện thoại với mặt lưng kính nhám chống bám mồ hôi cùng 5 phiên bản màu sắc lựa chọn: Hồng, Vàng, Xanh lá, Xanh dương và Đen. Camera trên iPhone 15 series cũng được nâng cấp lên cảm biến 48MP cùng tính năng chụp zoom quang học tới 2x. Cùng với thiết kế cổng sạc thay đổi từ lightning sang USB-C vô cùng ấn tượng.', 'Black'),
(14, 'iPhone 13 - 256GB - White', 'Apple', 1, 'https://cdn2.cellphones.com.vn/358x/media/catalog/product/t/r/tr_ng_5.jpg', 100, 17500000, 0, 'Cuối năm 2020, bộ 4 iPhone 12 đã được ra mắt với nhiều cái tiến. Sau đó, mọi sự quan tâm lại đổ dồn vào sản phẩm tiếp theo - iPhone 13. Vậy iP 13 sẽ có những gì nổi bật, hãy tìm hiểu ngay sau đây nhé!\nVề kích thước, iPhone 13 sẽ có 4 phiên bản khác nhau và kích thước không đổi so với series iPhone 12 hiện tại. Nếu iPhone 12 có sự thay đổi trong thiết kế từ góc cạnh bo tròn (Thiết kế được duy trì từ thời iPhone 6 đến iPhone 11 Pro Max) sang thiết kế vuông vắn (đã từng có mặt trên iPhone 4 đến iPhone 5S, SE).\nĐiện thoại iPhone 13 vẫn được duy trì một thiết kế tương tự. Máy vẫn có phiên bản khung viền thép, một số phiên bản khung nhôm cùng mặt lưng kính. Tương tự năm ngoái, Apple cũng sẽ cho ra mắt 4 phiên bản là iPhone 13, 13 mini, 13 Pro và 13 Pro Max.', 'White'),
(15, 'iPhone 14 Promax - 128GB - Black', 'Apple', 1, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/x/_/x_m_24.png', 100, 25590000, 0.03, 'iPhone 14 Pro Max sở hữu thiết kế màn hình Dynamic Island ấn tượng cùng màn hình OLED 6,7 inch hỗ trợ always-on display và hiệu năng vượt trội với chip A16 Bionic. Bên cạnh đó máy còn sở hữu nhiều nâng cấp về camera với cụm camera sau 48MP, camera trước 12MP dùng bộ nhớ RAM 6GB đa nhiệm vượt trội. Cùng phân tích chi tiết thông số siêu phẩm này ngay sau đây.', 'Black'),
(16, 'iPhone 14 Promax - 256GB - Black', 'Apple', 1, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/x/_/x_m_24.png', 100, 27590000, 0.03, 'iPhone 14 Pro Max sở hữu thiết kế màn hình Dynamic Island ấn tượng cùng màn hình OLED 6,7 inch hỗ trợ always-on display và hiệu năng vượt trội với chip A16 Bionic. Bên cạnh đó máy còn sở hữu nhiều nâng cấp về camera với cụm camera sau 48MP, camera trước 12MP dùng bộ nhớ RAM 6GB đa nhiệm vượt trội. Cùng phân tích chi tiết thông số siêu phẩm này ngay sau đây.', 'Black'),
(17, 'iPhone 13 - 128GB - White', 'Apple', 1, 'https://cdn2.cellphones.com.vn/358x/media/catalog/product/t/r/tr_ng_5.jpg', 100, 13500000, 0, 'Cuối năm 2020, bộ 4 iPhone 12 đã được ra mắt với nhiều cái tiến. Sau đó, mọi sự quan tâm lại đổ dồn vào sản phẩm tiếp theo - iPhone 13. Vậy iP 13 sẽ có những gì nổi bật, hãy tìm hiểu ngay sau đây nhé!\nVề kích thước, iPhone 13 sẽ có 4 phiên bản khác nhau và kích thước không đổi so với series iPhone 12 hiện tại. Nếu iPhone 12 có sự thay đổi trong thiết kế từ góc cạnh bo tròn (Thiết kế được duy trì từ thời iPhone 6 đến iPhone 11 Pro Max) sang thiết kế vuông vắn (đã từng có mặt trên iPhone 4 đến iPhone 5S, SE).\nĐiện thoại iPhone 13 vẫn được duy trì một thiết kế tương tự. Máy vẫn có phiên bản khung viền thép, một số phiên bản khung nhôm cùng mặt lưng kính. Tương tự năm ngoái, Apple cũng sẽ cho ra mắt 4 phiên bản là iPhone 13, 13 mini, 13 Pro và 13 Pro Max.', 'White'),
(18, 'iPhone 14 Promax - 512GB - Black', 'Apple', 1, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/x/_/x_m_24.png', 100, 35590000, 0.03, 'iPhone 14 Pro Max sở hữu thiết kế màn hình Dynamic Island ấn tượng cùng màn hình OLED 6,7 inch hỗ trợ always-on display và hiệu năng vượt trội với chip A16 Bionic. Bên cạnh đó máy còn sở hữu nhiều nâng cấp về camera với cụm camera sau 48MP, camera trước 12MP dùng bộ nhớ RAM 6GB đa nhiệm vượt trội. Cùng phân tích chi tiết thông số siêu phẩm này ngay sau đây.', 'Black'),
(19, 'Samsung Galaxy S24 Ultra - 256GB - Black', 'Samsung', 1, 'https://cdn2.cellphones.com.vn/358x/media/catalog/product/g/a/galaxy-s24-ultra-den-1_1_3.png', 100, 27990000, 0.01, 'Samsung S24 Ultra là siêu phẩm smartphone đỉnh cao mở đầu năm 2024 đến từ nhà Samsung với chip Snapdragon 8 Gen 3 For Galaxy mạnh mẽ, công nghệ tương lai Galaxy AI cùng khung viền Titan đẳng cấp hứa hẹn sẽ mang tới nhiều sự thay đổi lớn về mặt thiết kế và cấu hình. SS Galaxy S24 bản Ultra sở hữu màn hình 6.8 inch Dynamic AMOLED 2X tần số quét 120Hz. Máy cũng sở hữu camera chính 200MP, camera zoom quang học 50MP, camera tele 10MP và camera góc siêu rộng 12MP.', 'Black'),
(20, 'iPhone 14 Promax - 1TB - Black', 'Apple', 1, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/x/_/x_m_24.png', 98, 41590000, 0.03, 'iPhone 14 Pro Max sở hữu thiết kế màn hình Dynamic Island ấn tượng cùng màn hình OLED 6,7 inch hỗ trợ always-on display và hiệu năng vượt trội với chip A16 Bionic. Bên cạnh đó máy còn sở hữu nhiều nâng cấp về camera với cụm camera sau 48MP, camera trước 12MP dùng bộ nhớ RAM 6GB đa nhiệm vượt trội. Cùng phân tích chi tiết thông số siêu phẩm này ngay sau đây.', 'Black'),
(21, 'Samsung Galaxy Z Fold6 - 512GB - Pink', 'Samsung', 1, 'https://cdn2.cellphones.com.vn/358x/media/catalog/product/i/m/image_1171.png', 100, 45990000, 0.01, 'Samsung Z Fold 6 là siêu phẩm điện thoại gập được ra mắt ngày 10/7, hiệu năng dẫn đầu phân khúc với chip 8 nhân Snapdragon 8 Gen 3 for Galaxy, 12GB RAM cùng bộ nhớ trong từ 256GB đến 1TB. Thay đổi mạnh mẽ về hiệu năng và thiết kế, Galaxy Z Fold 6 hứa hẹn sẽ là chiếc smartphone AI đáng sở hữu nhất nửa cuối năm 2024. Cùng CellphoneS cập nhật tất tần tật thông tin về Galaxy Z Fold6 ngay đây nhé!', 'Pink'),
(22, 'iPhone 13 - 512GB - White', 'Apple', 1, 'https://cdn2.cellphones.com.vn/358x/media/catalog/product/t/r/tr_ng_5.jpg', 100, 25500000, 0.05, 'Cuối năm 2020, bộ 4 iPhone 12 đã được ra mắt với nhiều cái tiến. Sau đó, mọi sự quan tâm lại đổ dồn vào sản phẩm tiếp theo - iPhone 13. Vậy iP 13 sẽ có những gì nổi bật, hãy tìm hiểu ngay sau đây nhé!\nVề kích thước, iPhone 13 sẽ có 4 phiên bản khác nhau và kích thước không đổi so với series iPhone 12 hiện tại. Nếu iPhone 12 có sự thay đổi trong thiết kế từ góc cạnh bo tròn (Thiết kế được duy trì từ thời iPhone 6 đến iPhone 11 Pro Max) sang thiết kế vuông vắn (đã từng có mặt trên iPhone 4 đến iPhone 5S, SE).\nĐiện thoại iPhone 13 vẫn được duy trì một thiết kế tương tự. Máy vẫn có phiên bản khung viền thép, một số phiên bản khung nhôm cùng mặt lưng kính. Tương tự năm ngoái, Apple cũng sẽ cho ra mắt 4 phiên bản là iPhone 13, 13 mini, 13 Pro và 13 Pro Max.', 'White'),
(23, 'Samsung Galaxy Z Fold6 - 1TB - Pink', 'Samsung', 1, 'https://cdn2.cellphones.com.vn/358x/media/catalog/product/i/m/image_1171.png', 100, 52990000, 0, 'Samsung Z Fold 6 là siêu phẩm điện thoại gập được ra mắt ngày 10/7, hiệu năng dẫn đầu phân khúc với chip 8 nhân Snapdragon 8 Gen 3 for Galaxy, 12GB RAM cùng bộ nhớ trong từ 256GB đến 1TB. Thay đổi mạnh mẽ về hiệu năng và thiết kế, Galaxy Z Fold 6 hứa hẹn sẽ là chiếc smartphone AI đáng sở hữu nhất nửa cuối năm 2024. Cùng CellphoneS cập nhật tất tần tật thông tin về Galaxy Z Fold6 ngay đây nhé!', 'Pink'),
(24, 'Google Pixel 7 Pro - 128GB - Black', 'Google', 1, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/_/v/_vc_1.jpg', 100, 15990000, 0, 'Google Pixel 7 Pro là mẫu smartphone độc đáo và vô cùng chất lượng từ vẻ ngoài cho tới hiệu năng bên trong. Nhờ nâng cấp mạnh mẽ và ấn tượng đây được đánh giá là chiếc điện thoại chất lượng nổi bật trong phân khúc. Chắc chắn với những gì mang lại sẽ giúp chúng có được sức cạnh tranh mạnh mẽ trên thị trường.', 'Black'),
(25, 'Google Pixel 8 Pro - 128GB - Black', 'Google', 1, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/g/o/google-pixel-8-pro_3_.png', 100, 17990000, 0, 'Google Pixel 8 Pro là mẫu smartphone độc đáo và vô cùng chất lượng từ vẻ ngoài cho tới hiệu năng bên trong. Nhờ nâng cấp mạnh mẽ và ấn tượng đây được đánh giá là chiếc điện thoại chất lượng nổi bật trong phân khúc. Chắc chắn với những gì mang lại sẽ giúp chúng có được sức cạnh tranh mạnh mẽ trên thị trường.', 'Black'),
(26, 'Laptop HP 15S - 256GB - i3 - 8GB', 'HP', 0, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/l/a/laptop-hp-15s-fq5231tu-8u241pa_1_.jpg', 100, 9490000, 0, 'Laptop HP 15S-FQ5231TU 8U241PA thuộc phân khúc laptop giá rẻ nhưng vẫn mang trong mình đầy đủ tính năng cần thiết để đáp ứng nhu cầu người dùng. Chiếc máy tính xách tay này rất thích hợp dành cho những đối tượng thường xuyên thực hiện tác vụ văn phòng, học tập hay giải trí cơ bản hàng ngày. Laptop HP 15S-FQ5231TU 8U241PA được trang bị bộ nhớ RAM dung lượng 8GB 3200MHz cho tốc độ truy xuất vượt trội. Với chuẩn RAM DDR4 cùng dung lượng 8GB, bạn có thể yên tâm chơi các tựa game nhẹ, lướt web hoặc làm việc liên tục mà không lo hiện tượng giật lag.', 'Black'),
(27, 'Laptop Dell Vostro 3520 - 256GB - i512 - 8GB', 'Dell', 0, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/t/e/text_ng_n_14__5_17.png', 100, 12490000, 0, 'Laptop Dell Vostro 3520 sở hữu cấu hình đáp ứng tốt mọi yêu cầu công việc với con chip Core i5 1235U, 8GB RAM và ổ cứng SSD 512GB. Màn hình 15.6 inch FHD của laptop còn tích hợp tần số quét nâng cao 120Hz để nâng cao trải nghiệm giải trí. Với thiết kế mỏng nhẹ cùng tổng thể mạnh mẽ, cứng cáp, mẫu laptop Dell Inspiron sẽ vừa có được sự nổi bật, vừa tinh tế để phù hợp với các môi trường mang tính chuyên nghiệp.', 'Black'),
(28, 'Laptop Dell Inspiron 15 3520 - 256GB - i511 - 8GB', 'Dell', 0, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/l/a/laptop-dell-inspiron-15-3520-w15kt_5__1.png', 100, 12290000, 0, 'Laptop Dell Inspiron 15 3520-5124BLK GJ8W7 sở hữu cấu hình đáp ứng tốt mọi yêu cầu công việc với con chip Core i5 1235U, 8GB RAM và ổ cứng SSD 512GB. Màn hình 15.6 inch FHD của laptop còn tích hợp tần số quét nâng cao 120Hz để nâng cao trải nghiệm giải trí. Với thiết kế mỏng nhẹ cùng tổng thể mạnh mẽ, cứng cáp, mẫu laptop Dell Inspiron sẽ vừa có được sự nổi bật, vừa tinh tế để phù hợp với các môi trường mang tính chuyên nghiệp.', 'Black'),
(29, 'Apple MacBook Air M2 2022 - 256GB - Apple M2 - 8GB', 'Apple', 0, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/m/a/macbook_air_m2_1_1.jpg', 100, 22990000, 0, 'Macbook Air M2 2022 với thiết kế sang trọng, vẻ ngoài siêu mỏng đầy lịch lãm. Mẫu Macbook Air mới với những nâng cấp về thiết kế và cấu hình cùng giá bán phải chăng, đây sẽ là một thiết bị lý tưởng cho công việc và giải trí.', 'Black'),
(30, 'Laptop LG Gram 2023 14Z90RS - 512GB - i513 - 16GB', 'LG', 0, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/t/e/text_ng_n_-_2023-04-13t133915.668.png', 100, 27990000, 0.01, 'Laptop LG Gram 2023 14Z90RS-G.AH54A5 là một dòng máy tính xách tay nhẹ và mạnh mẽ được phát hành vào năm 2023. Thông qua nhiều đặc điểm nổi bật, phiên bản laptop LG Gram 2023 này hứa hẹn sẽ mang đến những giây phút làm việc và giải trí tuyệt vời dành cho bạn. Laptop LG Gram 14Z90RS-G.AH54A5 được trang bị bộ nhớ RAM LPDDR5 có dung lượng 16GB đủ để xử lý các tác vụ thông thường như lướt web, xem phim, làm việc văn phòng. Qua ổ cứng SSD NVMe chứa dung lượng lên đến 256GB, máy tính có thể tối ưu thời gian khởi động, truyền dữ liệu nhanh hơn và tăng tốc độ đọc/ghi dữ liệu. Có thể thấy dung lượng này đủ để lưu trữ nhiều tài liệu, ảnh, video.', 'Black'),
(31, 'Laptop HP Gaming Victus 16 - 512GB - R5 5600H - 8GB', 'HP', 0, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/_/0/_0004_hp_victus_16-e0175ax__4r0u8pa__b_2_1_2_2_1.jpg', 100, 17490000, 0, 'HP Victus 16-e1107AX mang trên mình vẻ ngoài lịch lãm theo ngôn ngữ thiết kế đơn giản, thay vì hầm hố như những chiếc laptop chơi game khác. Màu đen xám mạnh mẽ, những đường nét vuông vắn cứng cáp và đặc biệt là sự tối giản tạo nên một sản phẩm đẳng cấp, toát lên vẻ lạnh lùng, sang trọng. Dù có màn hình lớn 16,1 inch nhưng HP Victus vẫn rất gọn gàng với trọng lượng chỉ 2,46kg và độ mỏng 2,35cm. Viền màn hình cực mỏng ở 3 cạnh cùng trọng lượng tương đối nhẹ giúp HP Victus có tính di động cao, dễ dàng để bạn mang đi bất cứ đâu.', 'Black'),
(32, 'Laptop Dell Inspiron 15 3520 - 512GB - i511 - 8GB', 'Dell', 0, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/l/a/laptop-dell-inspiron-15-3520-w15kt_5__1.png', 100, 15290000, 0, 'Laptop Dell Inspiron 15 3520-5124BLK GJ8W7 sở hữu cấu hình đáp ứng tốt mọi yêu cầu công việc với con chip Core i5 1235U, 8GB RAM và ổ cứng SSD 512GB. Màn hình 15.6 inch FHD của laptop còn tích hợp tần số quét nâng cao 120Hz để nâng cao trải nghiệm giải trí. Với thiết kế mỏng nhẹ cùng tổng thể mạnh mẽ, cứng cáp, mẫu laptop Dell Inspiron sẽ vừa có được sự nổi bật, vừa tinh tế để phù hợp với các môi trường mang tính chuyên nghiệp.', 'Black'),
(33, 'Laptop Dell Inspiron 15 3520 - 512GB - i711 - 8GB', 'Dell', 0, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/l/a/laptop-dell-inspiron-15-3520-w15kt_5__1.png', 100, 19290000, 0.02, 'Laptop Dell Inspiron 15 3520-5124BLK GJ8W7 sở hữu cấu hình đáp ứng tốt mọi yêu cầu công việc với con chip Core i5 1235U, 8GB RAM và ổ cứng SSD 512GB. Màn hình 15.6 inch FHD của laptop còn tích hợp tần số quét nâng cao 120Hz để nâng cao trải nghiệm giải trí. Với thiết kế mỏng nhẹ cùng tổng thể mạnh mẽ, cứng cáp, mẫu laptop Dell Inspiron sẽ vừa có được sự nổi bật, vừa tinh tế để phù hợp với các môi trường mang tính chuyên nghiệp.', 'Black'),
(34, 'Laptop Dell Inspiron 15 3520 - 512GB - i711 - 16GB', 'Dell', 0, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/l/a/laptop-dell-inspiron-15-3520-w15kt_5__1.png', 100, 23290000, 0.02, 'Laptop Dell Inspiron 15 3520-5124BLK GJ8W7 sở hữu cấu hình đáp ứng tốt mọi yêu cầu công việc với con chip Core i5 1235U, 8GB RAM và ổ cứng SSD 512GB. Màn hình 15.6 inch FHD của laptop còn tích hợp tần số quét nâng cao 120Hz để nâng cao trải nghiệm giải trí. Với thiết kế mỏng nhẹ cùng tổng thể mạnh mẽ, cứng cáp, mẫu laptop Dell Inspiron sẽ vừa có được sự nổi bật, vừa tinh tế để phù hợp với các môi trường mang tính chuyên nghiệp.', 'Black'),
(35, 'Laptop LG Gram 2023 14Z90RS - 512GB - i513 - 8GB', 'LG', 0, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/t/e/text_ng_n_-_2023-04-13t133915.668.png', 100, 25990000, 0.01, 'Laptop LG Gram 2023 14Z90RS-G.AH54A5 là một dòng máy tính xách tay nhẹ và mạnh mẽ được phát hành vào năm 2023. Thông qua nhiều đặc điểm nổi bật, phiên bản laptop LG Gram 2023 này hứa hẹn sẽ mang đến những giây phút làm việc và giải trí tuyệt vời dành cho bạn. Laptop LG Gram 14Z90RS-G.AH54A5 được trang bị bộ nhớ RAM LPDDR5 có dung lượng 16GB đủ để xử lý các tác vụ thông thường như lướt web, xem phim, làm việc văn phòng. Qua ổ cứng SSD NVMe chứa dung lượng lên đến 256GB, máy tính có thể tối ưu thời gian khởi động, truyền dữ liệu nhanh hơn và tăng tốc độ đọc/ghi dữ liệu. Có thể thấy dung lượng này đủ để lưu trữ nhiều tài liệu, ảnh, video.', 'Black'),
(36, 'Laptop Dell Vostro 3520 - 256GB - i512 - 16GB', 'Dell', 0, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/t/e/text_ng_n_14__5_17.png', 100, 14490000, 0, 'Laptop Dell Vostro 3520 sở hữu cấu hình đáp ứng tốt mọi yêu cầu công việc với con chip Core i5 1235U, 8GB RAM và ổ cứng SSD 512GB. Màn hình 15.6 inch FHD của laptop còn tích hợp tần số quét nâng cao 120Hz để nâng cao trải nghiệm giải trí. Với thiết kế mỏng nhẹ cùng tổng thể mạnh mẽ, cứng cáp, mẫu laptop Dell Inspiron sẽ vừa có được sự nổi bật, vừa tinh tế để phù hợp với các môi trường mang tính chuyên nghiệp.', 'Black'),
(37, 'Laptop LG Gram 2023 14Z90RS - 512GB - i713 - 16GB', 'LG', 0, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/t/e/text_ng_n_-_2023-04-13t133915.668.png', 100, 28990000, 0.01, 'Laptop LG Gram 2023 14Z90RS-G.AH54A5 là một dòng máy tính xách tay nhẹ và mạnh mẽ được phát hành vào năm 2023. Thông qua nhiều đặc điểm nổi bật, phiên bản laptop LG Gram 2023 này hứa hẹn sẽ mang đến những giây phút làm việc và giải trí tuyệt vời dành cho bạn. Laptop LG Gram 14Z90RS-G.AH54A5 được trang bị bộ nhớ RAM LPDDR5 có dung lượng 16GB đủ để xử lý các tác vụ thông thường như lướt web, xem phim, làm việc văn phòng. Qua ổ cứng SSD NVMe chứa dung lượng lên đến 256GB, máy tính có thể tối ưu thời gian khởi động, truyền dữ liệu nhanh hơn và tăng tốc độ đọc/ghi dữ liệu. Có thể thấy dung lượng này đủ để lưu trữ nhiều tài liệu, ảnh, video.', 'Black'),
(38, 'Laptop HP Pavilion 14 - 256GB - i3 - 8GB', 'HP', 0, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/7/3/733329293eb51bca07e91da8ed203e66.jpg', 100, 13490000, 0, 'HP Pavilion có lẽ là một trong những dòng máy tính thuộc phân khúc tầm trung được ưa chuộng nhất tại Việt Nam, với đối tượng sử dụng trải dài từ học sinh, sinh viên, người dùng phổ thông cho đến các doanh nhân. Và chiếc laptop HP Pavilion 14-ce1014TU-5JN05PA cũng không hề ngoại lệ. Đây cũng là chiếc laptop sở hữu nhiều nâng cấp đáng giá so với phiên bản tiền nhiệm của nó được ra mắt vào năm 2017.', 'Black'),
(39, 'Samsung Galaxy Tab A9 WIFI - 64GB - Silver', 'Samsung', 2, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/s/a/samsung-galaxy-tab-a9_10_.png', 100, 6490000, 0, 'Máy tính bảng Samsung Galaxy Tab A9 WIFI sử dụng con chip MediaTek Dimensity 9300+ kết hợp với GPU ARM Immortalis G720 cho hiệu năng vượt trội. Sản phẩm sử dụng RAM dung lượng 12GB kết hợp với bộ nhớ trong 256GB cho khả năng đa nhiệm và lưu trữ đáng tin cậy. Màn hình Dynamic AMOLED 2X 12.4 inch cung cấp chất lượng hiển thị chi tiết với màu sắc rực rỡ.', 'Silver'),
(40, 'iPad Gen 10 10.9 inch 2022 - 64GB - Pink', 'Apple', 2, 'https://cdn2.cellphones.com.vn/358x/media/catalog/product/h/_/h_ngff.png', 100, 11990000, 0, 'iPad gen 10 2022 (iPad 10.9 inch) là chiếc máy tính bảng mới nhất sở hữu sức mạnh vô song từ con chip A14 Bionic chạy trên hệ điều hành iPadOS 16. Với thiết kế tối giản đã cải thiện các đường nét để hình ảnh luôn hợp thời trang, chiếc iPad này sẽ cho bạn quãng thời gian trải nghiệm tuyệt vời nhất. Cùng xem ưu thế mạnh mẽ của iPad 10.9 inch 2022 này đến từ đâu nhé!', 'Pink'),
(41, 'Samsung Galaxy Tab A9 WIFI - 64GB - Gray', 'Samsung', 2, 'https://cdn2.cellphones.com.vn/358x/media/catalog/product/0/b/0bcf7447-02a2-4fb0-84cc-48fb575e1e44.png', 100, 6490000, 0, 'Máy tính bảng Samsung Galaxy Tab A9 WIFI sử dụng con chip MediaTek Dimensity 9300+ kết hợp với GPU ARM Immortalis G720 cho hiệu năng vượt trội. Sản phẩm sử dụng RAM dung lượng 12GB kết hợp với bộ nhớ trong 256GB cho khả năng đa nhiệm và lưu trữ đáng tin cậy. Màn hình Dynamic AMOLED 2X 12.4 inch cung cấp chất lượng hiển thị chi tiết với màu sắc rực rỡ.', 'Gray'),
(42, 'iPad Gen 10 10.9 inch 2022 - 64GB - Silver', 'Apple', 2, 'https://cdn2.cellphones.com.vn/358x/media/catalog/product/b/_/b_ccc.png', 100, 11990000, 0, 'iPad gen 10 2022 (iPad 10.9 inch) là chiếc máy tính bảng mới nhất sở hữu sức mạnh vô song từ con chip A14 Bionic chạy trên hệ điều hành iPadOS 16. Với thiết kế tối giản đã cải thiện các đường nét để hình ảnh luôn hợp thời trang, chiếc iPad này sẽ cho bạn quãng thời gian trải nghiệm tuyệt vời nhất. Cùng xem ưu thế mạnh mẽ của iPad 10.9 inch 2022 này đến từ đâu nhé!', 'Silver'),
(43, 'Samsung Galaxy Tab S10 Plus - 64GB - Silver', 'Samsung', 2, 'https://cdn2.cellphones.com.vn/358x/media/catalog/product/t/a/tab-s10-bac_1.jpg', 100, 12990000, 0, 'Máy tính bảng Samsung Galaxy Tab S10 Plus Wifi sử dụng con chip MediaTek Dimensity 9300+ kết hợp với GPU ARM Immortalis G720 cho hiệu năng vượt trội. Sản phẩm sử dụng RAM dung lượng 12GB kết hợp với bộ nhớ trong 256GB cho khả năng đa nhiệm và lưu trữ đáng tin cậy. Màn hình Dynamic AMOLED 2X 12.4 inch cung cấp chất lượng hiển thị chi tiết với màu sắc rực rỡ.', 'Silver'),
(44, 'Samsung Galaxy Tab S10 Plus - 64GB - Gray', 'Samsung', 2, 'https://cdn2.cellphones.com.vn/358x/media/catalog/product/t/a/tab-s10-xam_1.jpg', 100, 12990000, 0, 'Máy tính bảng Samsung Galaxy Tab S10 Plus Wifi sử dụng con chip MediaTek Dimensity 9300+ kết hợp với GPU ARM Immortalis G720 cho hiệu năng vượt trội. Sản phẩm sử dụng RAM dung lượng 12GB kết hợp với bộ nhớ trong 256GB cho khả năng đa nhiệm và lưu trữ đáng tin cậy. Màn hình Dynamic AMOLED 2X 12.4 inch cung cấp chất lượng hiển thị chi tiết với màu sắc rực rỡ.', 'Gray'),
(45, 'iPad Gen 10 10.9 inch 2022 - 64GB - Gold', 'Apple', 2, 'https://cdn2.cellphones.com.vn/358x/media/catalog/product/v/n/vnaggf.png', 100, 11990000, 0, 'iPad gen 10 2022 (iPad 10.9 inch) là chiếc máy tính bảng mới nhất sở hữu sức mạnh vô song từ con chip A14 Bionic chạy trên hệ điều hành iPadOS 16. Với thiết kế tối giản đã cải thiện các đường nét để hình ảnh luôn hợp thời trang, chiếc iPad này sẽ cho bạn quãng thời gian trải nghiệm tuyệt vời nhất. Cùng xem ưu thế mạnh mẽ của iPad 10.9 inch 2022 này đến từ đâu nhé!', 'Gold'),
(46, 'Tai nghe Bluetooth Apple AirPods 4 - White', 'Apple', 3, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/a/i/airpods-4-1.png', 100, 3990000, 0, 'Tai nghe Bluetooth Apple AirPods 4 với thiết kế nhỏ gọn, dễ dàng mang theo bên mình. Sản phẩm sở hữu chất âm tuyệt vời, kết nối nhanh chóng và ổn định, giúp bạn trải nghiệm âm nhạc một cách tuyệt vời.', 'White'),
(47, 'Tai nghe Bluetooth chụp tai Sony WH 1000XM5 - Silver', 'Sony', 3, 'https://cdn2.cellphones.com.vn/358x/media/catalog/product/t/a/tai-nghe-chup-tai-sony-wh-1000xm5-2-removebg-preview_1.png', 100, 7990000, 0, 'Tai nghe Bluetooth chụp tai Sony WH 1000XM5 với thiết kế sang trọng, chất âm tuyệt vời, chống ồn tốt, giúp bạn trải nghiệm âm nhạc một cách tuyệt vời.', 'Silver'),
(48, 'Tai nghe Bluetooth Apple AirPods 4 chống ồn chủ động - White', 'Apple', 3, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/a/i/airpods-4-1.png', 100, 4990000, 0, 'Tai nghe Bluetooth Apple AirPods 4 với thiết kế nhỏ gọn, dễ dàng mang theo bên mình. Sản phẩm sở hữu chất âm tuyệt vời, kết nối nhanh chóng và ổn định, giúp bạn trải nghiệm âm nhạc một cách tuyệt vời.', 'White'),
(49, 'Tai nghe Bluetooth chụp tai Sony WH 1000XM5 - Black', 'Sony', 3, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/t/a/tai-nghe-chup-tai-sony-wh-1000xm5-4.png', 100, 7990000, 0, 'Tai nghe Bluetooth chụp tai Sony WH 1000XM5 với thiết kế sang trọng, chất âm tuyệt vời, chống ồn tốt, giúp bạn trải nghiệm âm nhạc một cách tuyệt vời.', 'Black'),
(50, 'Tai nghe Bluetooth True Wireless HUAWEI FreeClip - Black', 'Huawei', 3, 'https://cdn2.cellphones.com.vn/358x/media/catalog/product/t/a/tai-nghe-khong-day-huawei-freeclip_7_.png', 100, 3690000, 0, 'Tai nghe Bluetooth True Wireless HUAWEI FreeClip với thiết kế nhỏ gọn, dễ dàng mang theo bên mình. Sản phẩm sở hữu chất âm tuyệt vời, kết nối nhanh chóng và ổn định, giúp bạn trải nghiệm âm nhạc một cách tuyệt vời.', 'White'),
(51, 'Tai nghe chụp tai chống ồn Apple AirPods Max 2024 - Gold', 'Apple', 3, 'https://cdn2.cellphones.com.vn/358x/media/catalog/product/a/p/apple-airpods-max_5__1.png', 100, 14990000, 0, 'Tai nghe chụp tai chống ồn Apple AirPods Max 2024 với thiết kế sang trọng, chất âm tuyệt vời, chống ồn tốt, giúp bạn trải nghiệm âm nhạc một cách tuyệt vời.', 'Gold'),
(53, 'Pin dự phòng Anker Zolo 20000mAh 30W 1A1C tích hợp cáp C, L A1689', 'Anker', 5, 'https://cdn2.cellphones.com.vn/358x/media/catalog/product/p/i/pin-sac-du-phong-anker-zolo-a1689-20000mah-30w_5__1.png', 100, 799000, 0, 'Pin dự phòng Anker Zolo 20000mAh 30W 1A1C tích hợp cáp C, L A1689 với dung lượng lớn, thiết kế nhỏ gọn, dễ dàng mang theo bên mình. Sản phẩm sở hữu chất lượng tốt, giúp bạn sạc nhanh chóng cho thiết bị của mình.', 'Black'),
(54, 'Pin sạc dự phòng Xiaomi Redmi 20000mAh sạc nhanh 18W', 'Xiaomi', 5, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/p/i/pin-sac-du-phong-xiaomi-redmi-20000mah-sac-nhanh-18w_2_.jpg', 100, 499000, 0, 'Pin sạc dự phòng Xiaomi Redmi 20000mAh sạc nhanh 18W với dung lượng lớn, thiết kế nhỏ gọn, dễ dàng mang theo bên mình. Sản phẩm sở hữu chất lượng tốt, giúp bạn sạc nhanh chóng cho thiết bị của mình.', 'Black'),
(55, 'Ốp lưng iPhone 16 Pro Max Apple Silicone With Magsafe - Black', 'Apple', 6, 'https://cdn2.cellphones.com.vn/358x/media/catalog/product/o/p/op-lung-iphone-16-pro-max-apple-silicone-magsafe_7__1.png', 100, 1490000, 0, 'Ốp lưng iPhone 16 Pro Max Apple Silicone With Magsafe với thiết kế sang trọng, chất liệu cao cấp, giúp bảo vệ chiếc điện thoại của bạn khỏi va đập, trầy xước.', 'Black'),
(56, 'Ốp lưng iPhone 16 Pro Max Apple Silicone With Magsafe - Pink', 'Apple', 6, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/o/p/op-lung-iphone-16-pro-max-apple-silicone-magsafe_4_.png', 100, 1490000, 0.01, 'Ốp lưng iPhone 16 Pro Max Apple Silicone With Magsafe với thiết kế sang trọng, chất liệu cao cấp, giúp bảo vệ chiếc điện thoại của bạn khỏi va đập, trầy xước.', 'Pink'),
(57, 'Ốp lưng iPhone 16 Pro Max Apple With Magsafe Clear', 'Apple', 6, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/o/p/op-lung-iphone-16-pro-max-apple-magsafe-clear.png', 100, 1490000, 0.01, 'Ốp lưng iPhone 16 Pro Max Apple With Magsafe Clear với thiết kế sang trọng, chất liệu cao cấp, giúp bảo vệ chiếc điện thoại của bạn khỏi va đập, trầy xước.', 'Clear'),
(58, 'Bàn phím Apple Magic Keyboard 2 Kèm Phím Số Trắng', 'Apple', 4, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/m/a/magic-keyboard-2-phim-so-1_1.jpg', 100, 3990000, 0.02, 'Bàn phím Apple Magic Keyboard 2 Kèm Phím Số Trắng với thiết kế sang trọng, chất liệu cao cấp, giúp bạn làm việc hiệu quả hơn.', 'White'),
(59, 'Bàn phím gaming Logitech G813 Lightsync Rgb Mechanical Clicky', 'Logitech', 4, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/b/a/ban-phim-gaming-logitech-g813-lightsync-rgb-mechanical-tactile_2__2_1.png', 100, 2990000, 0.02, 'Bàn phím gaming Logitech G813 Lightsync Rgb Mechanical Clicky với thiết kế sang trọng, chất liệu cao cấp, giúp bạn làm việc hiệu quả hơn.', 'Black'),
(60, 'Bàn phím không dây Logitech MX Keys S Fullsize Black', 'Logitech', 4, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/b/a/ban-phim-khong-day-fullsize-logitech-mx-keys-s.png', 100, 2990000, 0.02, 'Bàn phím không dây Logitech MX Keys S Fullsize Black với thiết kế sang trọng, chất liệu cao cấp, giúp bạn làm việc hiệu quả hơn.', 'Black'),
(62, 'Op lung moiw', 'apple', 6, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/2/2/22_10_3.jpg', 100, 250000, 0.0005, 'nhua', 'Black');

-- --------------------------------------------------------

--
-- Table structure for table `tablet`
--

CREATE TABLE `tablet` (
  `ma_sp` int(11) NOT NULL,
  `bo_xu_ly` varchar(100) NOT NULL,
  `dung_luong_pin` varchar(100) NOT NULL,
  `kich_thuoc_man_hinh` varchar(100) NOT NULL,
  `cong_nghe_man_hinh` varchar(100) NOT NULL,
  `he_dieu_hanh` varchar(50) NOT NULL,
  `bo_nho` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tablet`
--

INSERT INTO `tablet` (`ma_sp`, `bo_xu_ly`, `dung_luong_pin`, `kich_thuoc_man_hinh`, `cong_nghe_man_hinh`, `he_dieu_hanh`, `bo_nho`) VALUES
(39, 'Exynos 9611', '7040mAh', '10.1 inch', 'TFT', 'Android 12', '64GB'),
(40, 'Apple A14 Bionic', 'Li-Po 28.6 Wh', '10.9 inch', 'Liquid Retina Display', 'iPadOS 16', '64GB'),
(41, 'Exynos 9611', '7040mAh', '10.1 inch', 'TFT', 'Android 12', '64GB'),
(42, 'Apple A14 Bionic', 'Li-Po 28.6 Wh', '10.9 inch', 'Liquid Retina Display', 'iPadOS 16', '64GB'),
(43, 'Exynos 9611', '7040mAh', '10.1 inch', 'TFT', 'Android 12', '64GB'),
(44, 'Exynos 9611', '7040mAh', '10.1 inch', 'TFT', 'Android 12', '64GB'),
(45, 'Apple A14 Bionic', 'Li-Po 28.6 Wh', '10.9 inch', 'Liquid Retina Display', 'iPadOS 16', '64GB');

-- --------------------------------------------------------

--
-- Table structure for table `tai_khoan`
--

CREATE TABLE `tai_khoan` (
  `ten_dang_nhap` varchar(25) NOT NULL,
  `mat_khau` varchar(255) NOT NULL,
  `ho_va_ten` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `sdt` varchar(10) DEFAULT NULL,
  `dia_chi` varchar(255) DEFAULT NULL,
  `phan_loai_tk` varchar(3) NOT NULL,
  `thoi_diem_mo_tk` date NOT NULL,
  `avatar` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tai_khoan`
--

INSERT INTO `tai_khoan` (`ten_dang_nhap`, `mat_khau`, `ho_va_ten`, `email`, `sdt`, `dia_chi`, `phan_loai_tk`, `thoi_diem_mo_tk`, `avatar`) VALUES
('$admin1', '$2y$10$00k8qCMGfZNYmD2stQnl1.0QAQ5wmpfCZCi9HOEq5Ie2lCuleLLgi', 'Nguyễn Văn A', 'ngvanain@gmail.com', '0123456789', 'Hà Nội', 'nv', '2024-12-05', '$admin1.jpg'),
('khachhang1', '$2y$10$OQSuOVoM5rkKEDZcjtVlxO8eJPWUrYO4X3DPUsCA3Y0sSzhcSWmaq', 'Nguyễn Phương Duy', 'nguyen.duy@gmail.com', '0123456789', 'Châu Phong.', 'tv', '2024-12-05', 'khachhang1.JPG'),
('khachhang10', '$2y$10$yjetmA0DEJRNUwpdOiP57uqFaroCHwH6wd086Iszsi/1iLwFdYTCC', 'Nguyễn Thị Liên', NULL, '0123456789', NULL, 'tv', '2024-12-05', NULL),
('khachhang11', '$2y$10$lHuS3d7a19C9D.3d3jlMBeo85JNn.c9BhH6amK8pHt6D2HdhQUjS2', 'Lâm Văn Đạt', NULL, '0123456789', NULL, 'tv', '2024-12-05', NULL),
('khachhang2', '$2y$10$z.tG6grGSW5qv.OXj4amlufSZe4eOIry9ynQu9MKSG0u0EgxIX.Lq', 'Nguyễn Văn Lang', 'nguyen.lang@gmail.com', '0123456789', 'Hà Nội', 'tv', '2024-12-05', NULL),
('khachhang3', '$2y$10$4WO3p3WQqGe2wMI92OiNveqV7E1z8srdZOyrD5UiDKJpFzZ1Vk.U.', 'Liên Và Đạt', NULL, '0123456789', NULL, 'tv', '2024-12-05', NULL),
('khachhang4', '$2y$10$r8KyAFNtkCjATMdAQ/7OZO5nljNswyXfjGmddw55IGhfoQ7wwqi4m', 'Công Liêm', NULL, '0123456789', NULL, 'tv', '2024-12-05', NULL),
('khachhang5', '$2y$10$biDOeTm7U.Nyisao.vPuQ.0OOAxPOeZ3nFopz4ADbZ7Q5B0G7L0pG', 'Kim Long', NULL, '0123456789', NULL, 'tv', '2024-12-05', NULL),
('khachhang6', '$2y$10$zg1/ihYa51LGobsoz3KpHus.bhQtxp3Fqqc0Sh8Z0OZEHd0X5uHym', 'Quỳnh Trúc', NULL, '0123456789', NULL, 'tv', '2024-12-05', NULL),
('khachhang7', '$2y$10$I/Q9EKWdNj7yoaxM5jf1gOFDrI3izAdI9EPyigQdUUGRxvT3vWwPG', 'Mẫn Chi', NULL, '0123456789', NULL, 'tv', '2024-12-05', NULL),
('khachhang8', '$2y$10$8hR..JiRmlGN/.x/5WNu0ed/h7VpBIxSdxq6kvVCwd.pjGRZOLHWO', 'Sang Thu', NULL, '0123456789', NULL, 'tv', '2024-12-05', NULL),
('khachhang9', '$2y$10$j/fezzVKmFT0viXlqcuV0OHXvRgDQr2QgVmPwq74gN0anYGpkPINy', 'Vũ Hạ', NULL, '0123456789', NULL, 'tv', '2024-12-05', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tai_nghe_bluetooth`
--

CREATE TABLE `tai_nghe_bluetooth` (
  `ma_sp` int(11) NOT NULL,
  `pham_vi_ket_noi` varchar(100) NOT NULL,
  `thoi_luong_pin` varchar(100) NOT NULL,
  `chong_nuoc` varchar(100) NOT NULL,
  `cong_nghe_am_thanh` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tai_nghe_bluetooth`
--

INSERT INTO `tai_nghe_bluetooth` (`ma_sp`, `pham_vi_ket_noi`, `thoi_luong_pin`, `chong_nuoc`, `cong_nghe_am_thanh`) VALUES
(46, 'Bán kính 10m', '5h', 'IP54', 'Chế độ xuyên âm, nhận biết cuộc hội thoại, tách lời nói'),
(47, 'Bán kính 10m', '30h ANC hoặc 40h', 'IP54', 'Auto NC Optimizer (Tự động điều chỉnh chống ồn)'),
(48, 'Bán kính 10m', '5h', 'IP54', 'Chủ động khử tiếng ồn, chế độ xuyên âm, nhận biết cuộc hội thoại, tách lời nói'),
(49, 'Bán kính 10m', '30h ANC hoặc 40h', 'IP54', 'Auto NC Optimizer (Tự động điều chỉnh chống ồn)'),
(50, 'Bán kính 10m', '8h', 'IPX4', 'Chế độ xuyên âm, nhận biết cuộc hội thoại, tách lời nói'),
(51, 'Bán kính 10m', '20h', 'IP54', 'Chủ động khử tiếng ồn, chế độ xuyên âm, nhận biết cuộc hội thoại, tách lời nói, eq thích ứng');

-- --------------------------------------------------------

--
-- Table structure for table `thanh_vien`
--

CREATE TABLE `thanh_vien` (
  `ten_dang_nhap` varchar(25) NOT NULL,
  `active_status` tinyint(1) NOT NULL,
  `thoi_diem_huy_tk` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `thanh_vien`
--

INSERT INTO `thanh_vien` (`ten_dang_nhap`, `active_status`, `thoi_diem_huy_tk`) VALUES
('khachhang1', 1, NULL),
('khachhang10', 1, NULL),
('khachhang11', 1, NULL),
('khachhang2', 1, NULL),
('khachhang3', 1, NULL),
('khachhang4', 1, NULL),
('khachhang5', 1, NULL),
('khachhang6', 1, NULL),
('khachhang7', 1, NULL),
('khachhang8', 1, NULL),
('khachhang9', 1, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ban_phim`
--
ALTER TABLE `ban_phim`
  ADD PRIMARY KEY (`ma_sp`);

--
-- Indexes for table `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  ADD PRIMARY KEY (`ma_don_hang`,`ma_sp`),
  ADD KEY `ma_sp` (`ma_sp`);

--
-- Indexes for table `danh_gia`
--
ALTER TABLE `danh_gia`
  ADD PRIMARY KEY (`thanh_vien`,`ma_dh`,`ma_sp`),
  ADD KEY `ma_sp` (`ma_sp`),
  ADD KEY `ma_dh` (`ma_dh`);

--
-- Indexes for table `danh_sach_yeu_thich`
--
ALTER TABLE `danh_sach_yeu_thich`
  ADD PRIMARY KEY (`thanh_vien`,`ma_sp`),
  ADD KEY `ma_sp` (`ma_sp`);

--
-- Indexes for table `don_hang`
--
ALTER TABLE `don_hang`
  ADD PRIMARY KEY (`ma_don_hang`),
  ADD KEY `thanh_vien` (`thanh_vien`);

--
-- Indexes for table `duyet_don_hang`
--
ALTER TABLE `duyet_don_hang`
  ADD PRIMARY KEY (`ma_don_hang`),
  ADD KEY `nhan_vien` (`nhan_vien`);

--
-- Indexes for table `gio_hang`
--
ALTER TABLE `gio_hang`
  ADD PRIMARY KEY (`thanh_vien`,`ma_sp`),
  ADD KEY `ma_sp` (`ma_sp`);

--
-- Indexes for table `laptop`
--
ALTER TABLE `laptop`
  ADD PRIMARY KEY (`ma_sp`);

--
-- Indexes for table `mobile`
--
ALTER TABLE `mobile`
  ADD PRIMARY KEY (`ma_sp`);

--
-- Indexes for table `nhan_vien`
--
ALTER TABLE `nhan_vien`
  ADD PRIMARY KEY (`ten_dang_nhap`);

--
-- Indexes for table `op_lung`
--
ALTER TABLE `op_lung`
  ADD PRIMARY KEY (`ma_sp`);

--
-- Indexes for table `sac_du_phong`
--
ALTER TABLE `sac_du_phong`
  ADD PRIMARY KEY (`ma_sp`);

--
-- Indexes for table `san_pham`
--
ALTER TABLE `san_pham`
  ADD PRIMARY KEY (`ma_sp`),
  ADD UNIQUE KEY `ten_sp` (`ten_sp`);

--
-- Indexes for table `tablet`
--
ALTER TABLE `tablet`
  ADD PRIMARY KEY (`ma_sp`);

--
-- Indexes for table `tai_khoan`
--
ALTER TABLE `tai_khoan`
  ADD PRIMARY KEY (`ten_dang_nhap`);

--
-- Indexes for table `tai_nghe_bluetooth`
--
ALTER TABLE `tai_nghe_bluetooth`
  ADD PRIMARY KEY (`ma_sp`);

--
-- Indexes for table `thanh_vien`
--
ALTER TABLE `thanh_vien`
  ADD PRIMARY KEY (`ten_dang_nhap`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `don_hang`
--
ALTER TABLE `don_hang`
  MODIFY `ma_don_hang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `san_pham`
--
ALTER TABLE `san_pham`
  MODIFY `ma_sp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ban_phim`
--
ALTER TABLE `ban_phim`
  ADD CONSTRAINT `ban_phim_ibfk_1` FOREIGN KEY (`ma_sp`) REFERENCES `san_pham` (`ma_sp`);

--
-- Constraints for table `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  ADD CONSTRAINT `chi_tiet_don_hang_ibfk_1` FOREIGN KEY (`ma_don_hang`) REFERENCES `don_hang` (`ma_don_hang`),
  ADD CONSTRAINT `chi_tiet_don_hang_ibfk_2` FOREIGN KEY (`ma_sp`) REFERENCES `san_pham` (`ma_sp`);

--
-- Constraints for table `danh_gia`
--
ALTER TABLE `danh_gia`
  ADD CONSTRAINT `danh_gia_ibfk_1` FOREIGN KEY (`ma_sp`) REFERENCES `san_pham` (`ma_sp`),
  ADD CONSTRAINT `danh_gia_ibfk_2` FOREIGN KEY (`thanh_vien`) REFERENCES `thanh_vien` (`ten_dang_nhap`),
  ADD CONSTRAINT `danh_gia_ibfk_3` FOREIGN KEY (`ma_dh`) REFERENCES `don_hang` (`ma_don_hang`);

--
-- Constraints for table `danh_sach_yeu_thich`
--
ALTER TABLE `danh_sach_yeu_thich`
  ADD CONSTRAINT `danh_sach_yeu_thich_ibfk_1` FOREIGN KEY (`thanh_vien`) REFERENCES `thanh_vien` (`ten_dang_nhap`),
  ADD CONSTRAINT `danh_sach_yeu_thich_ibfk_2` FOREIGN KEY (`ma_sp`) REFERENCES `san_pham` (`ma_sp`);

--
-- Constraints for table `don_hang`
--
ALTER TABLE `don_hang`
  ADD CONSTRAINT `don_hang_ibfk_1` FOREIGN KEY (`thanh_vien`) REFERENCES `thanh_vien` (`ten_dang_nhap`);

--
-- Constraints for table `duyet_don_hang`
--
ALTER TABLE `duyet_don_hang`
  ADD CONSTRAINT `duyet_don_hang_ibfk_1` FOREIGN KEY (`ma_don_hang`) REFERENCES `don_hang` (`ma_don_hang`),
  ADD CONSTRAINT `duyet_don_hang_ibfk_2` FOREIGN KEY (`nhan_vien`) REFERENCES `nhan_vien` (`ten_dang_nhap`);

--
-- Constraints for table `gio_hang`
--
ALTER TABLE `gio_hang`
  ADD CONSTRAINT `gio_hang_ibfk_1` FOREIGN KEY (`thanh_vien`) REFERENCES `thanh_vien` (`ten_dang_nhap`),
  ADD CONSTRAINT `gio_hang_ibfk_2` FOREIGN KEY (`ma_sp`) REFERENCES `san_pham` (`ma_sp`);

--
-- Constraints for table `laptop`
--
ALTER TABLE `laptop`
  ADD CONSTRAINT `laptop_ibfk_1` FOREIGN KEY (`ma_sp`) REFERENCES `san_pham` (`ma_sp`);

--
-- Constraints for table `mobile`
--
ALTER TABLE `mobile`
  ADD CONSTRAINT `mobile_ibfk_1` FOREIGN KEY (`ma_sp`) REFERENCES `san_pham` (`ma_sp`);

--
-- Constraints for table `nhan_vien`
--
ALTER TABLE `nhan_vien`
  ADD CONSTRAINT `nhan_vien_ibfk_1` FOREIGN KEY (`ten_dang_nhap`) REFERENCES `tai_khoan` (`ten_dang_nhap`);

--
-- Constraints for table `op_lung`
--
ALTER TABLE `op_lung`
  ADD CONSTRAINT `op_lung_ibfk_1` FOREIGN KEY (`ma_sp`) REFERENCES `san_pham` (`ma_sp`);

--
-- Constraints for table `sac_du_phong`
--
ALTER TABLE `sac_du_phong`
  ADD CONSTRAINT `sac_du_phong_ibfk_1` FOREIGN KEY (`ma_sp`) REFERENCES `san_pham` (`ma_sp`);

--
-- Constraints for table `tablet`
--
ALTER TABLE `tablet`
  ADD CONSTRAINT `tablet_ibfk_1` FOREIGN KEY (`ma_sp`) REFERENCES `san_pham` (`ma_sp`);

--
-- Constraints for table `tai_nghe_bluetooth`
--
ALTER TABLE `tai_nghe_bluetooth`
  ADD CONSTRAINT `tai_nghe_bluetooth_ibfk_1` FOREIGN KEY (`ma_sp`) REFERENCES `san_pham` (`ma_sp`);

--
-- Constraints for table `thanh_vien`
--
ALTER TABLE `thanh_vien`
  ADD CONSTRAINT `thanh_vien_ibfk_1` FOREIGN KEY (`ten_dang_nhap`) REFERENCES `tai_khoan` (`ten_dang_nhap`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
