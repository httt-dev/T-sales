ALTER TABLE `t_sales` ADD `sanluong_soluong_hh` INT NOT NULL AFTER `sanluong_dongia`;
ALTER TABLE `t_sales` ADD `sanluong_dongia_hh` INT NOT NULL AFTER `sanluong_soluong_hh`;
ALTER TABLE `t_sales` CHANGE `sanluong_soluong` `sanluong_soluong_dd` int(11);
ALTER TABLE `t_sales` CHANGE `sanluong_dongia` `sanluong_dongia_dd` int(11)