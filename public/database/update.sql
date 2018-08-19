CREATE TABLE `t_logs` (
 `id` int(150) NOT NULL AUTO_INCREMENT,
 `user_id` int(150) NOT NULL,
 `action` varchar(50) NOT NULL,
 `content` text,
 `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8