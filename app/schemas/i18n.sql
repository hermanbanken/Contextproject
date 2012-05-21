CREATE TABLE `%stranslation` (
 `table` varchar(15) NOT NULL,
 `pk` int(11) NOT NULL,
 `field` varchar(15) NOT NULL,
 `translation` text NOT NULL,
 `lang` varchar(5) NOT NULL,
 `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 KEY `idx_trans` (`table`,`pk`,`field`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8