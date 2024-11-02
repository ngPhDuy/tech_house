delimiter $$

create procedure Tim_mau_sac_mobile (
    in p_ten_sp varchar(500)
) begin
    declare mau_ma_sp varchar(500);
    set mau_ma_sp = substring_index(p_ten_sp, ' - ', 1);

    select * from San_pham join Mobile on San_pham.ma_sp = Mobile.ma_sp 
    where ten_sp like concat(mau_ma_sp, '%')
    distinct San_pham.mau_sac;
end$$

create procedure Tim_bo_nho_mobile (
    in p_ten_sp varchar(500)
) begin
    declare mau_ma_sp varchar(500);
    set mau_ma_sp = substring_index(p_ten_sp, ' - ', 1);

    select * from San_pham join Mobile on San_pham.ma_sp = Mobile.ma_sp 
    where ten_sp like concat(mau_ma_sp, '%')
    distinct Mobile.bo_nho;
end $$

delimiter ;