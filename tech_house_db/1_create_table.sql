drop database if exists tech_house_db;

create database tech_house_db;
use tech_house_db;

create table Tai_khoan (
    ten_dang_nhap varchar(25) primary key,
    mat_khau varchar(255) not null,
    ho_va_ten varchar(100),
    email varchar(100),
    sdt varchar(10),
    dia_chi varchar(255),
    phan_loai_tk varchar(3) not null,
    /*
    nv: nhân viên
    tv: thành viên
    */
    thoi_diem_mo_tk date not null,
    avatar varchar(500)
);

create table Nhan_vien (
    ten_dang_nhap varchar(25) primary key,
    cccd varchar(12) not null,
    gioi_tinh varchar(3) not null,
    ngay_sinh date not null,
    
    foreign key (ten_dang_nhap) references Tai_khoan(ten_dang_nhap)
);

create table Thanh_vien (
    ten_dang_nhap varchar(25) primary key,
    active_status boolean not null,
    thoi_diem_huy_tk date,

    foreign key (ten_dang_nhap) references Tai_khoan(ten_dang_nhap)
);

create table San_pham (
    ma_sp int primary key auto_increment,
    ten_sp varchar(500) unique not null,
    thuong_hieu varchar(20) not null,
    phan_loai int not null,
    /*
    0: laptop
    1: mobile
    2: tablet
    3: tai nghe bluetooth
    4: bàn phím
    5: sạc dự phòng
    6: ốp lưng
    */
    hinh_anh varchar(500) not null,
    sl_ton_kho int not null,
    gia_thanh int not null,
    sale_off float not null,
    mo_ta varchar(5000) not null,
    mau_sac varchar(20)
);

create table Laptop (
    ma_sp int primary key,
    bo_xu_ly varchar(100) not null,
    dung_luong_pin varchar(100) not null,
    kich_thuoc_man_hinh varchar(100) not null,
    cong_nghe_man_hinh varchar(100) not null,
    he_dieu_hanh varchar(50) not null,
    ram varchar(50) not null,
    bo_nho varchar(50) not null,

    foreign key (ma_sp) references San_pham(ma_sp)
);

create table Mobile (
    ma_sp int primary key,
    bo_xu_ly varchar(100) not null,
    dung_luong_pin varchar(100) not null,
    kich_thuoc_man_hinh varchar(100) not null,
    cong_nghe_man_hinh varchar(100) not null,
    he_dieu_hanh varchar(50) not null,
    bo_nho varchar(50) not null,

    foreign key (ma_sp) references San_pham(ma_sp)
);

create table Tablet (
    ma_sp int primary key,
    bo_xu_ly varchar(100) not null,
    dung_luong_pin varchar(100) not null,
    kich_thuoc_man_hinh varchar(100) not null,
    cong_nghe_man_hinh varchar(100) not null,
    he_dieu_hanh varchar(50) not null,
    bo_nho varchar(50) not null,

    foreign key (ma_sp) references San_pham(ma_sp)
);

create table Tai_nghe_bluetooth (
    ma_sp int primary key,
    pham_vi_ket_noi varchar(100) not null,
    thoi_luong_pin varchar(100) not null,
    chong_nuoc varchar(100) not null,
    cong_nghe_am_thanh varchar(100) not null,

    foreign key (ma_sp) references San_pham(ma_sp)
);

create table Ban_phim (
    ma_sp int primary key,
    key_cap varchar(100) not null,
    so_phim int not null,
    cong_ket_noi varchar(100) not null,

    foreign key (ma_sp) references San_pham(ma_sp)
);

create table Sac_du_phong (
    ma_sp int primary key,
    dung_luong_pin varchar(100) not null,
    cong_suat varchar(100) not null,
    cong_ket_noi varchar(100) not null,
    chat_lieu varchar(100) not null,

    foreign key (ma_sp) references San_pham(ma_sp)
);

create table Op_lung (
    ma_sp int primary key,
    chat_lieu varchar(100) not null,
    do_day varchar(100) not null,

    foreign key (ma_sp) references San_pham(ma_sp)
);

create table Don_hang (
    ma_don_hang int primary key auto_increment,
    thanh_vien varchar(25) not null,
    thoi_diem_dat_hang datetime not null,
    thoi_diem_nhan_hang datetime,
    tinh_trang int not null,
    /*
    0: chờ xác nhận
    1: đã xác nhận
    2: đang giao hàng
    3: đã giao hàng
    4: đã hủy
    */
    tong_gia int not null,

    foreign key (thanh_vien) references Thanh_vien(ten_dang_nhap)
);

create table Chi_tiet_don_hang (
    ma_don_hang int,
    ma_sp int,
    so_luong int not null,
    don_gia int not null,

    primary key (ma_don_hang, ma_sp),
    foreign key (ma_don_hang) references Don_hang(ma_don_hang),
    foreign key (ma_sp) references San_pham(ma_sp)
);

create table Duyet_don_hang (
    ma_don_hang int primary key,
    nhan_vien varchar(25) not null,
    thoi_diem_duyet datetime not null,

    foreign key (ma_don_hang) references Don_hang(ma_don_hang),
    foreign key (nhan_vien) references Nhan_vien(ten_dang_nhap)
);

create table Gio_hang (
    thanh_vien varchar(25),
    ma_sp int not null,
    so_luong int not null,

    primary key (thanh_vien, ma_sp),
    foreign key (thanh_vien) references Thanh_vien(ten_dang_nhap),
    foreign key (ma_sp) references San_pham(ma_sp)
);

create table Danh_gia (
    thoi_diem_danh_gia datetime not null,
    thanh_vien varchar(25),
    ma_dh int not null,
    ma_sp int,
    diem_danh_gia int not null,
    noi_dung varchar(1000) not null,
    
    primary key (thanh_vien, ma_dh, ma_sp),
    foreign key (ma_sp) references San_pham(ma_sp),
    foreign key (thanh_vien) references Thanh_vien(ten_dang_nhap),
    foreign key (ma_dh) references Don_hang(ma_don_hang)
);
/*Procedure create accounts and products*/
delimiter //
create procedure Tao_nhan_vien (
    in p_ten_dang_nhap varchar(25),
    in mat_khau varchar(255),
    in ho_va_ten varchar(100),
    in email varchar(100),
    in sdt varchar(10),
    in dia_chi varchar(255),
    in cccd varchar(12),
    in gioi_tinh varchar(3),
    in ngay_sinh date
) begin
    insert into Tai_khoan values (p_ten_dang_nhap, mat_khau, ho_va_ten, email, sdt, dia_chi, 'nv', now(), null);
    insert into Nhan_vien values (p_ten_dang_nhap, cccd, gioi_tinh, ngay_sinh);

    select * from Tai_khoan where ten_dang_nhap = p_ten_dang_nhap;
end//

create procedure Tao_thanh_vien (
    in p_ten_dang_nhap varchar(25),
    in mat_khau varchar(255),
    in sdt varchar(10)
) begin
    insert into Tai_khoan values (p_ten_dang_nhap, mat_khau, "New User", null, sdt, null, 'tv', now(), null);
    insert into Thanh_vien values (p_ten_dang_nhap, TRUE, null);

    select * from Tai_khoan where ten_dang_nhap = p_ten_dang_nhap;
end//

create procedure Them_laptop (
    in p_ten_sp varchar(500),
    in p_thuong_hieu varchar(20),
    in p_hinh_anh varchar(500),
    in p_sl_ton_kho int,
    in p_gia_thanh int,
    in p_sale_off float,
    in p_mo_ta varchar(5000),
    in p_mau_sac varchar(20),
    in p_bo_xu_ly varchar(100),
    in p_dung_luong_pin varchar(100),
    in p_kich_thuoc_man_hinh varchar(100),
    in p_cong_nghe_man_hinh varchar(100),
    in p_he_dieu_hanh varchar(50),
    in p_ram varchar(50),
    in p_bo_nho varchar(50)
) begin
    insert into San_pham (ten_sp, thuong_hieu, phan_loai, hinh_anh, sl_ton_kho, gia_thanh, sale_off, mo_ta, mau_sac) values (p_ten_sp, p_thuong_hieu, 0, p_hinh_anh, p_sl_ton_kho, p_gia_thanh, p_sale_off, p_mo_ta, p_mau_sac);
    set @ma_sp = (select ma_sp from San_pham where ten_sp = p_ten_sp);
    insert into Laptop values (@ma_sp, p_bo_xu_ly, p_dung_luong_pin, p_kich_thuoc_man_hinh, p_cong_nghe_man_hinh, p_he_dieu_hanh, p_ram, p_bo_nho);

    select * from San_pham where ma_sp = @ma_sp;
end//

create procedure Them_mobile (
    in p_ten_sp varchar(500),
    in p_thuong_hieu varchar(20),
    in p_hinh_anh varchar(500),
    in p_sl_ton_kho int,
    in p_gia_thanh int,
    in p_sale_off float,
    in p_mo_ta varchar(5000),
    in p_mau_sac varchar(20),
    in p_bo_xu_ly varchar(100),
    in p_dung_luong_pin varchar(100),
    in p_kich_thuoc_man_hinh varchar(100),
    in p_cong_nghe_man_hinh varchar(100),
    in p_he_dieu_hanh varchar(50),
    in p_bo_nho varchar(50)
) begin
    insert into San_pham (ten_sp, thuong_hieu, phan_loai, hinh_anh, sl_ton_kho, gia_thanh, sale_off, mo_ta, mau_sac) values (p_ten_sp, p_thuong_hieu, 1, p_hinh_anh, p_sl_ton_kho, p_gia_thanh, p_sale_off, p_mo_ta, p_mau_sac);
    set @ma_sp = (select ma_sp from San_pham where ten_sp = p_ten_sp);
    insert into Mobile values (@ma_sp, p_bo_xu_ly, p_dung_luong_pin, p_kich_thuoc_man_hinh, p_cong_nghe_man_hinh, p_he_dieu_hanh, p_bo_nho);

    select * from San_pham where ma_sp = @ma_sp;
end//

create procedure Them_tablet (
    in p_ten_sp varchar(500),
    in p_thuong_hieu varchar(20),
    in p_hinh_anh varchar(500),
    in p_sl_ton_kho int,
    in p_gia_thanh int,
    in p_sale_off float,
    in p_mo_ta varchar(5000),
    in p_mau_sac varchar(20),
    in p_bo_xu_ly varchar(100),
    in p_dung_luong_pin varchar(100),
    in p_kich_thuoc_man_hinh varchar(100),
    in p_cong_nghe_man_hinh varchar(100),
    in p_he_dieu_hanh varchar(50),
    in p_bo_nho varchar(50)
) begin
    insert into San_pham (ten_sp, thuong_hieu, phan_loai, hinh_anh, sl_ton_kho, gia_thanh, sale_off, mo_ta, mau_sac) values (p_ten_sp, p_thuong_hieu, 2, p_hinh_anh, p_sl_ton_kho, p_gia_thanh, p_sale_off, p_mo_ta, p_mau_sac);
    set @ma_sp = (select ma_sp from San_pham where ten_sp = p_ten_sp);
    insert into Tablet values (@ma_sp, p_bo_xu_ly, p_dung_luong_pin, p_kich_thuoc_man_hinh, p_cong_nghe_man_hinh, p_he_dieu_hanh, p_bo_nho);

    select * from San_pham where ma_sp = @ma_sp;
end//

create procedure Them_tai_nghe_blue_tooth (
    in p_ten_sp varchar(500),
    in p_thuong_hieu varchar(20),
    in p_hinh_anh varchar(500),
    in p_sl_ton_kho int,
    in p_gia_thanh int,
    in p_sale_off float,
    in p_mo_ta varchar(5000),
    in p_mau_sac varchar(20),
    in p_pham_vi_ket_noi varchar(100),
    in p_thoi_luong_pin varchar(100),
    in p_chong_nuoc varchar(100),
    in p_cong_nghe_am_thanh varchar(100)
) begin
    insert into San_pham (ten_sp, thuong_hieu, phan_loai, hinh_anh, sl_ton_kho, gia_thanh, sale_off, mo_ta, mau_sac) values (p_ten_sp, p_thuong_hieu, 3, p_hinh_anh, p_sl_ton_kho, p_gia_thanh, p_sale_off, p_mo_ta, p_mau_sac);
    set @ma_sp = (select ma_sp from San_pham where ten_sp = p_ten_sp);
    insert into Tai_nghe_bluetooth values (@ma_sp, p_pham_vi_ket_noi, p_thoi_luong_pin, p_chong_nuoc, p_cong_nghe_am_thanh);

    select * from San_pham where ma_sp = @ma_sp;
end//

create procedure Them_ban_phim (
    in p_ten_sp varchar(500),
    in p_thuong_hieu varchar(20),
    in p_hinh_anh varchar(500),
    in p_sl_ton_kho int,
    in p_gia_thanh int,
    in p_sale_off float,
    in p_mo_ta varchar(5000),
    in p_mau_sac varchar(20),
    in p_key_cap varchar(100),
    in p_so_phim int,
    in p_cong_ket_noi varchar(100)
) begin
    insert into San_pham (ten_sp, thuong_hieu, phan_loai, hinh_anh, sl_ton_kho, gia_thanh, sale_off, mo_ta, mau_sac) values (p_ten_sp, p_thuong_hieu, 4, p_hinh_anh, p_sl_ton_kho, p_gia_thanh, p_sale_off, p_mo_ta, p_mau_sac);
    set @ma_sp = (select ma_sp from San_pham where ten_sp = p_ten_sp);
    insert into Ban_phim values (@ma_sp, p_key_cap, p_so_phim, p_cong_ket_noi);

    select * from San_pham where ma_sp = @ma_sp;
end//

create procedure Them_sac_du_phong (
    in p_ten_sp varchar(500),
    in p_thuong_hieu varchar(20),
    in p_hinh_anh varchar(500),
    in p_sl_ton_kho int,
    in p_gia_thanh int,
    in p_sale_off float,
    in p_mo_ta varchar(5000),
    in p_mau_sac varchar(20),
    in p_dung_luong_pin varchar(100),
    in p_cong_suat varchar(100),
    in p_cong_ket_noi varchar(100),
    in p_chat_lieu varchar(100)
) begin
    insert into San_pham (ten_sp, thuong_hieu, phan_loai, hinh_anh, sl_ton_kho, gia_thanh, sale_off, mo_ta, mau_sac) values (p_ten_sp, p_thuong_hieu, 5, p_hinh_anh, p_sl_ton_kho, p_gia_thanh, p_sale_off, p_mo_ta, p_mau_sac);
    set @ma_sp = (select ma_sp from San_pham where ten_sp = p_ten_sp);
    insert into Sac_du_phong values (@ma_sp, p_dung_luong_pin, p_cong_suat, p_cong_ket_noi, p_chat_lieu);

    select * from San_pham where ma_sp = @ma_sp;
end//

create procedure Them_op_lung (
    in p_ten_sp varchar(500),
    in p_thuong_hieu varchar(20),
    in p_hinh_anh varchar(500),
    in p_sl_ton_kho int,
    in p_gia_thanh int,
    in p_sale_off float,
    in p_mo_ta varchar(5000),
    in p_mau_sac varchar(20),
    in p_chat_lieu varchar(100),
    in p_do_day varchar(100)
) begin
    insert into San_pham (ten_sp, thuong_hieu, phan_loai, hinh_anh, sl_ton_kho, gia_thanh, sale_off, mo_ta, mau_sac) values (p_ten_sp, p_thuong_hieu, 6, p_hinh_anh, p_sl_ton_kho, p_gia_thanh, p_sale_off, p_mo_ta, p_mau_sac);
    set @ma_sp = (select ma_sp from San_pham where ten_sp = p_ten_sp);
    insert into Op_lung values (@ma_sp, p_chat_lieu, p_do_day);

    select * from San_pham where ma_sp = @ma_sp;
end//

create procedure Them_vao_gio_hang (
    in p_thanh_vien varchar(25),
    in p_ma_sp int,
    in p_so_luong int
) begin
    if exists (select * from Gio_hang where thanh_vien = p_thanh_vien and ma_sp = p_ma_sp) then
        update Gio_hang set so_luong = so_luong + p_so_luong where thanh_vien = p_thanh_vien and ma_sp = p_ma_sp;
    else
        insert into Gio_hang values (p_thanh_vien, p_ma_sp, p_so_luong);
    end if;
end//

CREATE PROCEDURE Tao_don_hang_tu_gio_hang (
    IN p_thanh_vien VARCHAR(25),
    IN p_tong_gia INT
)
BEGIN
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
END //

create procedure Tao_don_hang_mot_sp(
    in p_thanh_vien varchar(25),
    in p_ma_sp int,
    in p_so_luong int,
    in p_tong_gia int
) begin
    insert into Don_hang (thanh_vien, thoi_diem_dat_hang, tinh_trang, tong_gia) values (p_thanh_vien, now(), 0, p_tong_gia);
    set @ma_don_hang = (select ma_don_hang from Don_hang where thanh_vien = p_thanh_vien and thoi_diem_dat_hang = now());
    insert into Chi_tiet_don_hang values (@ma_don_hang, p_ma_sp, p_so_luong, p_tong_gia);
end//

delimiter ;