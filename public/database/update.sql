CREATE TABLE `t_logs` (
 `id` int(150) NOT NULL AUTO_INCREMENT,
 `user_id` int(150) NOT NULL,
 `action` varchar(50) NOT NULL,
 `module` varchar(50) NULL,
 `type` text NULL,
 `order_id` int NULL,
 `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8