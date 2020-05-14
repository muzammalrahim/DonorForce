CREATE TABLE IF NOT EXISTS `#__donorforce_orders` (
`id` int(11)  NOT NULL AUTO_INCREMENT,
  `prefix` varchar(23) NOT NULL,
  `user_info_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `cdate` datetime DEFAULT NULL,
  `mdate` datetime DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `payee_id` varchar(100) DEFAULT NULL,
  `original_amount` float(10,2) NOT NULL,
  `amount` float(10,2) NOT NULL,
  `coupon_code` varchar(100) NOT NULL,
  `order_tax` float(10,2) DEFAULT NULL,
  `order_tax_details` text NOT NULL,
  `order_shipping` float(10,2) DEFAULT NULL,
  `order_shipping_details` text,
  `fee` float(10,2) DEFAULT NULL,
  `customer_note` text,
  `status` varchar(100) DEFAULT NULL,
  `processor` varchar(100) DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `ticketscount` int(11) NOT NULL,
  `currency` varchar(16) NOT NULL,
  `extra` text,
  `donation_history_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Store Information';

-- ALTER TABLE `#__donorforce_orders`
-- ADD PRIMARY KEY (`id`);
 
-- ALTER TABLE `#__donorforce_orders`
-- MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=50;

-- ALTER TABLE `#__donorforce_history` ADD `Reference` varchar(255) DEFAULT NULL;



