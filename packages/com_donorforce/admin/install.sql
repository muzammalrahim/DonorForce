/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50612
Source Host           : localhost:3306
Source Database       : jmla3

Target Server Type    : MYSQL
Target Server Version : 50612
File Encoding         : 65001

Date: 2014-06-09 17:39:00
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for #__donorforce_bequest
-- ----------------------------

CREATE TABLE  IF NOT EXISTS `#__donorforce_bequest` (
  `bequest_id` int(20) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(40) DEFAULT NULL,
  `surname` varchar(40) DEFAULT NULL,
  `j_name` varchar(40) DEFAULT NULL,
  `follow` varchar(20) DEFAULT NULL,
  `notes` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`bequest_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



-- ----------------------------
-- Table structure for #__donorforce_donor
-- ----------------------------

CREATE TABLE IF NOT EXISTS `#__donorforce_donor` (
  `donor_id` int(11) NOT NULL AUTO_INCREMENT,
  `name_first` varchar(50) DEFAULT NULL,
  `name_last` varchar(50) DEFAULT NULL,
  `cms_user_id` int(11) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `org_type` varchar(255) DEFAULT NULL,
  `org_name` varchar(255) DEFAULT NULL,
  `org_latitude` varchar(255) DEFAULT NULL,
  `org_longitude` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `phy_address` varchar(255) DEFAULT NULL,
  `phy_address2` varchar(255) DEFAULT NULL,
  `phy_city` varchar(255) DEFAULT NULL,
  `phy_state` varchar(255) DEFAULT NULL,
  `phy_zip` varchar(255) DEFAULT NULL,
  `phy_country` varchar(255) DEFAULT NULL,
  `post_address` varchar(255) DEFAULT NULL,
  `post_address2` varchar(255) DEFAULT NULL,
  `post_city` varchar(255) DEFAULT NULL,
  `post_state` varchar(255) DEFAULT NULL,
  `post_zip` varchar(255) DEFAULT NULL,
  `post_country` varchar(255) DEFAULT NULL,
  `published` tinyint(4) DEFAULT NULL,
  `dateofbirth` date DEFAULT NULL,
  `note_title` text,
  `notes` text,
  PRIMARY KEY (`donor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for #__donorforce_donor_subscriptions
-- ----------------------------

CREATE TABLE IF NOT EXISTS `#__donorforce_donor_subscriptions` (
  `subscription_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `donor_id` int(11) DEFAULT NULL COMMENT 'cms_user_id',
  `project_id` int(11) DEFAULT NULL,
  `donation_type` varchar(255) DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL,
  `source` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`subscription_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for #__donorforce_history
-- ----------------------------

CREATE TABLE IF NOT EXISTS `#__donorforce_history` (
  `donor_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `donation_id` varchar(255) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `cms_user_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `amount` float(20,0) DEFAULT NULL,
  `status` char(255) DEFAULT NULL,
  PRIMARY KEY (`donor_history_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for #__donorforce_project
-- ----------------------------

CREATE TABLE IF NOT EXISTS `#__donorforce_project` (
  `project_id` int(11) NOT NULL AUTO_INCREMENT,
  `pcategory_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `image` varchar(255) NOT NULL,
  `date_start` datetime DEFAULT NULL,
  `date_end` datetime DEFAULT NULL,
  `fundraising_goal` float DEFAULT NULL,
  `total_raised` float DEFAULT NULL,
  `published` tinyint(4) DEFAULT '1',
  `ordering` int(11) DEFAULT NULL,
  PRIMARY KEY (`project_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for #__donorforce_rec_donation
-- ----------------------------

CREATE TABLE IF NOT EXISTS `#__donorforce_rec_donation` (
  `donation_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'banking_inst_no',
  `donor_id` int(11) NOT NULL COMMENT 'donor_cms_id',
  `debit_order_name` varchar(255) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `account_number` int(11) NOT NULL,
  `method_of_payment` varchar(25) NOT NULL,
  `account_name` varchar(255) NOT NULL,
  `account_type` varchar(50) NOT NULL,
  `branchcode` varchar(20) NOT NULL,
  `branch_number` varchar(20) NOT NULL,
  `comp_code` varchar(255) NOT NULL,
  `beneficiary_reference` varchar(255) NOT NULL,
  `credit_account_name` varchar(255) NOT NULL COMMENT 'Account Holder Name',
  `credit_card_type` varchar(20) NOT NULL,
  `credit_card_number` bigint(20) NOT NULL,
  `credit_expiry_date` datetime NOT NULL,
  `credit_card_cvv` char(3) NOT NULL,
  `donation_start_date` date NOT NULL,
  `donation_end_date` date NOT NULL,
  `deduction_day` smallint(6) NOT NULL,
  `frequency` varchar(10) NOT NULL,
  `debit_order_status` tinyint(4) NOT NULL,
  PRIMARY KEY (`donation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `#__donorforce_invoice_temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `head_logo` varchar(255) DEFAULT NULL,
  `head_addresses` text,
  `upper_body_sign` text,
  `bottom_body_txt` text,
  `footer_slogan` varchar(255) DEFAULT NULL,
  `footer_addresses` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- ALTER TABLE `#__donorforce_bequest` CHANGE `j_name` `cms_user_id` INT NULL DEFAULT NULL;

-- ALTER TABLE `#__donorforce_history`  ADD `donation_type` VARCHAR(25) NOT NULL AFTER `status`;


-- ALTER TABLE `#__donorforce_history` CHANGE `donation_id` `donor_id` INT NULL DEFAULT NULL;

-- ALTER TABLE `#__donorforce_rec_donation` CHANGE `branch_number` `branch_name` VARCHAR(255) CHARACTER SET utf8 COLLATE -- utf8_general_ci NOT NULL;


DROP TABLE IF EXISTS `#__donorforce_countries`;

CREATE TABLE IF NOT EXISTS `#__donorforce_countries` (
  `country_id` int(11) NOT NULL AUTO_INCREMENT,
  `country_code` varchar(100) DEFAULT NULL,
  `country_name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`country_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=243 ;

INSERT INTO `#__donorforce_countries` (`country_id`, `country_code`, `country_name`) VALUES
(1, 'US', 'United States'),
(2, 'CA', 'Canada'),
(3, 'AF', 'Afghanistan'),
(4, 'AL', 'Albania'),
(5, 'DZ', 'Algeria'),
(6, 'DS', 'American Samoa'),
(7, 'AD', 'Andorra'),
(8, 'AO', 'Angola'),
(9, 'AI', 'Anguilla'),
(10, 'AQ', 'Antarctica'),
(11, 'AG', 'Antigua and/or Barbuda'),
(12, 'AR', 'Argentina'),
(13, 'AM', 'Armenia'),
(14, 'AW', 'Aruba'),
(15, 'AU', 'Australia'),
(16, 'AT', 'Austria'),
(17, 'AZ', 'Azerbaijan'),
(18, 'BS', 'Bahamas'),
(19, 'BH', 'Bahrain'),
(20, 'BD', 'Bangladesh'),
(21, 'BB', 'Barbados'),
(22, 'BY', 'Belarus'),
(23, 'BE', 'Belgium'),
(24, 'BZ', 'Belize'),
(25, 'BJ', 'Benin'),
(26, 'BM', 'Bermuda'),
(27, 'BT', 'Bhutan'),
(28, 'BO', 'Bolivia'),
(29, 'BA', 'Bosnia and Herzegovina'),
(30, 'BW', 'Botswana'),
(31, 'BV', 'Bouvet Island'),
(32, 'BR', 'Brazil'),
(33, 'IO', 'British lndian Ocean Territory'),
(34, 'BN', 'Brunei Darussalam'),
(35, 'BG', 'Bulgaria'),
(36, 'BF', 'Burkina Faso'),
(37, 'BI', 'Burundi'),
(38, 'KH', 'Cambodia'),
(39, 'CM', 'Cameroon'),
(40, 'CV', 'Cape Verde'),
(41, 'KY', 'Cayman Islands'),
(42, 'CF', 'Central African Republic'),
(43, 'TD', 'Chad'),
(44, 'CL', 'Chile'),
(45, 'CN', 'China'),
(46, 'CX', 'Christmas Island'),
(47, 'CC', 'Cocos (Keeling) Islands'),
(48, 'CO', 'Colombia'),
(49, 'KM', 'Comoros'),
(50, 'CG', 'Congo'),
(51, 'CK', 'Cook Islands'),
(52, 'CR', 'Costa Rica'),
(53, 'HR', 'Croatia (Hrvatska)'),
(54, 'CU', 'Cuba'),
(55, 'CY', 'Cyprus'),
(56, 'CZ', 'Czech Republic'),
(57, 'DK', 'Denmark'),
(58, 'DJ', 'Djibouti'),
(59, 'DM', 'Dominica'),
(60, 'DO', 'Dominican Republic'),
(61, 'TP', 'East Timor'),
(62, 'EC', 'Ecuador'),
(63, 'EG', 'Egypt'),
(64, 'SV', 'El Salvador'),
(65, 'GQ', 'Equatorial Guinea'),
(66, 'ER', 'Eritrea'),
(67, 'EE', 'Estonia'),
(68, 'ET', 'Ethiopia'),
(69, 'FK', 'Falkland Islands (Malvinas)'),
(70, 'FO', 'Faroe Islands'),
(71, 'FJ', 'Fiji'),
(72, 'FI', 'Finland'),
(73, 'FR', 'France'),
(74, 'FX', 'France, Metropolitan'),
(75, 'GF', 'French Guiana'),
(76, 'PF', 'French Polynesia'),
(77, 'TF', 'French Southern Territories'),
(78, 'GA', 'Gabon'),
(79, 'GM', 'Gambia'),
(80, 'GE', 'Georgia'),
(81, 'DE', 'Germany'),
(82, 'GH', 'Ghana'),
(83, 'GI', 'Gibraltar'),
(84, 'GR', 'Greece'),
(85, 'GL', 'Greenland'),
(86, 'GD', 'Grenada'),
(87, 'GP', 'Guadeloupe'),
(88, 'GU', 'Guam'),
(89, 'GT', 'Guatemala'),
(90, 'GN', 'Guinea'),
(91, 'GW', 'Guinea-Bissau'),
(92, 'GY', 'Guyana'),
(93, 'HT', 'Haiti'),
(94, 'HM', 'Heard and Mc Donald Islands'),
(95, 'HN', 'Honduras'),
(96, 'HK', 'Hong Kong'),
(97, 'HU', 'Hungary'),
(98, 'IS', 'Iceland'),
(99, 'IN', 'India'),
(100, 'ID', 'Indonesia'),
(101, 'IR', 'Iran (Islamic Republic of)'),
(102, 'IQ', 'Iraq'),
(103, 'IE', 'Ireland'),
(104, 'IL', 'Israel'),
(105, 'IT', 'Italy'),
(106, 'CI', 'Ivory Coast'),
(107, 'JM', 'Jamaica'),
(108, 'JP', 'Japan'),
(109, 'JO', 'Jordan'),
(110, 'KZ', 'Kazakhstan'),
(111, 'KE', 'Kenya'),
(112, 'KI', 'Kiribati'),
(113, 'KP', 'Korea, Democratic People''s Republic of'),
(114, 'KR', 'Korea, Republic of'),
(115, 'XK', 'Kosovo'),
(116, 'KW', 'Kuwait'),
(117, 'KG', 'Kyrgyzstan'),
(118, 'LA', 'Lao People''s Democratic Republic'),
(119, 'LV', 'Latvia'),
(120, 'LB', 'Lebanon'),
(121, 'LS', 'Lesotho'),
(122, 'LR', 'Liberia'),
(123, 'LY', 'Libyan Arab Jamahiriya'),
(124, 'LI', 'Liechtenstein'),
(125, 'LT', 'Lithuania'),
(126, 'LU', 'Luxembourg'),
(127, 'MO', 'Macau'),
(128, 'MK', 'Macedonia'),
(129, 'MG', 'Madagascar'),
(130, 'MW', 'Malawi'),
(131, 'MY', 'Malaysia'),
(132, 'MV', 'Maldives'),
(133, 'ML', 'Mali'),
(134, 'MT', 'Malta'),
(135, 'MH', 'Marshall Islands'),
(136, 'MQ', 'Martinique'),
(137, 'MR', 'Mauritania'),
(138, 'MU', 'Mauritius'),
(139, 'TY', 'Mayotte'),
(140, 'MX', 'Mexico'),
(141, 'FM', 'Micronesia, Federated States of'),
(142, 'MD', 'Moldova, Republic of'),
(143, 'MC', 'Monaco'),
(144, 'MN', 'Mongolia'),
(145, 'ME', 'Montenegro'),
(146, 'MS', 'Montserrat'),
(147, 'MA', 'Morocco'),
(148, 'MZ', 'Mozambique'),
(149, 'MM', 'Myanmar'),
(150, 'NA', 'Namibia'),
(151, 'NR', 'Nauru'),
(152, 'NP', 'Nepal'),
(153, 'NL', 'Netherlands'),
(154, 'AN', 'Netherlands Antilles'),
(155, 'NC', 'New Caledonia'),
(156, 'NZ', 'New Zealand'),
(157, 'NI', 'Nicaragua'),
(158, 'NE', 'Niger'),
(159, 'NG', 'Nigeria'),
(160, 'NU', 'Niue'),
(161, 'NF', 'Norfork Island'),
(162, 'MP', 'Northern Mariana Islands'),
(163, 'NO', 'Norway'),
(164, 'OM', 'Oman'),
(165, 'PK', 'Pakistan'),
(166, 'PW', 'Palau'),
(167, 'PA', 'Panama'),
(168, 'PG', 'Papua New Guinea'),
(169, 'PY', 'Paraguay'),
(170, 'PE', 'Peru'),
(171, 'PH', 'Philippines'),
(172, 'PN', 'Pitcairn'),
(173, 'PL', 'Poland'),
(174, 'PT', 'Portugal'),
(175, 'PR', 'Puerto Rico'),
(176, 'QA', 'Qatar'),
(177, 'RE', 'Reunion'),
(178, 'RO', 'Romania'),
(179, 'RU', 'Russian Federation'),
(180, 'RW', 'Rwanda'),
(181, 'KN', 'Saint Kitts and Nevis'),
(182, 'LC', 'Saint Lucia'),
(183, 'VC', 'Saint Vincent and the Grenadines'),
(184, 'WS', 'Samoa'),
(185, 'SM', 'San Marino'),
(186, 'ST', 'Sao Tome and Principe'),
(187, 'SA', 'Saudi Arabia'),
(188, 'SN', 'Senegal'),
(189, 'RS', 'Serbia'),
(190, 'SC', 'Seychelles'),
(191, 'SL', 'Sierra Leone'),
(192, 'SG', 'Singapore'),
(193, 'SK', 'Slovakia'),
(194, 'SI', 'Slovenia'),
(195, 'SB', 'Solomon Islands'),
(196, 'SO', 'Somalia'),
(197, 'ZA', 'South Africa'),
(198, 'GS', 'South Georgia South Sandwich Islands'),
(199, 'ES', 'Spain'),
(200, 'LK', 'Sri Lanka'),
(201, 'SH', 'St. Helena'),
(202, 'PM', 'St. Pierre and Miquelon'),
(203, 'SD', 'Sudan'),
(204, 'SR', 'Suriname'),
(205, 'SJ', 'Svalbarn and Jan Mayen Islands'),
(206, 'SZ', 'Swaziland'),
(207, 'SE', 'Sweden'),
(208, 'CH', 'Switzerland'),
(209, 'SY', 'Syrian Arab Republic'),
(210, 'TW', 'Taiwan'),
(211, 'TJ', 'Tajikistan'),
(212, 'TZ', 'Tanzania, United Republic of'),
(213, 'TH', 'Thailand'),
(214, 'TG', 'Togo'),
(215, 'TK', 'Tokelau'),
(216, 'TO', 'Tonga'),
(217, 'TT', 'Trinidad and Tobago'),
(218, 'TN', 'Tunisia'),
(219, 'TR', 'Turkey'),
(220, 'TM', 'Turkmenistan'),
(221, 'TC', 'Turks and Caicos Islands'),
(222, 'TV', 'Tuvalu'),
(223, 'UG', 'Uganda'),
(224, 'UA', 'Ukraine'),
(225, 'AE', 'United Arab Emirates'),
(226, 'GB', 'United Kingdom'),
(227, 'UM', 'United States minor outlying islands'),
(228, 'UY', 'Uruguay'),
(229, 'UZ', 'Uzbekistan'),
(230, 'VU', 'Vanuatu'),
(231, 'VA', 'Vatican City State'),
(232, 'VE', 'Venezuela'),
(233, 'VN', 'Vietnam'),
(234, 'VG', 'Virigan Islands (British)'),
(235, 'VI', 'Virgin Islands (U.S.)'),
(236, 'WF', 'Wallis and Futuna Islands'),
(237, 'EH', 'Western Sahara'),
(238, 'YE', 'Yemen'),
(239, 'YU', 'Yugoslavia'),
(240, 'ZR', 'Zaire'),
(241, 'ZM', 'Zambia'),
(242, 'ZW', 'Zimbabwe');

DROP TABLE IF EXISTS `#__donorforce_currencies`;

CREATE TABLE IF NOT EXISTS `#__donorforce_currencies` (
  `currency_id` int(11) NOT NULL AUTO_INCREMENT,
  `currency_name` varchar(64) DEFAULT NULL,
  `currency_code` char(3) DEFAULT NULL,
  PRIMARY KEY (`currency_id`),
  KEY `idx_currency_name` (`currency_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=168 ;


INSERT INTO `#__donorforce_currencies` (`currency_id`, `currency_name`, `currency_code`) VALUES
(1, 'Andorran Peseta', 'ADP'),
(2, 'United Arab Emirates Dirham', 'AED'),
(3, 'Afghanistan Afghani', 'AFA'),
(4, 'Albanian Lek', 'ALL'),
(5, 'Netherlands Antillian Guilder', 'ANG'),
(6, 'Angolan Kwanza', 'AOK'),
(7, 'Argentine Peso', 'ARS'),
(9, 'Australian Dollar', 'AUD'),
(10, 'Aruban Florin', 'AWG'),
(11, 'Barbados Dollar', 'BBD'),
(12, 'Bangladeshi Taka', 'BDT'),
(14, 'Bulgarian Lev', 'BGN'),
(15, 'Bahraini Dinar', 'BHD'),
(16, 'Burundi Franc', 'BIF'),
(17, 'Bermudian Dollar', 'BMD'),
(18, 'Brunei Dollar', 'BND'),
(19, 'Bolivian Boliviano', 'BOB'),
(20, 'Brazilian Real', 'BRL'),
(21, 'Bahamian Dollar', 'BSD'),
(22, 'Bhutan Ngultrum', 'BTN'),
(23, 'Burma Kyat', 'BUK'),
(24, 'Botswanian Pula', 'BWP'),
(25, 'Belize Dollar', 'BZD'),
(26, 'Canadian Dollar', 'CAD'),
(27, 'Swiss Franc', 'CHF'),
(28, 'Chilean Unidades de Fomento', 'CLF'),
(29, 'Chilean Peso', 'CLP'),
(30, 'Yuan (Chinese) Renminbi', 'CNY'),
(31, 'Colombian Peso', 'COP'),
(32, 'Costa Rican Colon', 'CRC'),
(33, 'Czech Republic Koruna', 'CZK'),
(34, 'Cuban Peso', 'CUP'),
(35, 'Cape Verde Escudo', 'CVE'),
(36, 'Cyprus Pound', 'CYP'),
(40, 'Danish Krone', 'DKK'),
(41, 'Dominican Peso', 'DOP'),
(42, 'Algerian Dinar', 'DZD'),
(43, 'Ecuador Sucre', 'ECS'),
(44, 'Egyptian Pound', 'EGP'),
(45, 'Estonian Kroon (EEK)', 'EEK'),
(46, 'Ethiopian Birr', 'ETB'),
(47, 'Euro', 'EUR'),
(49, 'Fiji Dollar', 'FJD'),
(50, 'Falkland Islands Pound', 'FKP'),
(52, 'British Pound', 'GBP'),
(53, 'Ghanaian Cedi', 'GHC'),
(54, 'Gibraltar Pound', 'GIP'),
(55, 'Gambian Dalasi', 'GMD'),
(56, 'Guinea Franc', 'GNF'),
(58, 'Guatemalan Quetzal', 'GTQ'),
(59, 'Guinea-Bissau Peso', 'GWP'),
(60, 'Guyanan Dollar', 'GYD'),
(61, 'Hong Kong Dollar', 'HKD'),
(62, 'Honduran Lempira', 'HNL'),
(63, 'Haitian Gourde', 'HTG'),
(64, 'Hungarian Forint', 'HUF'),
(65, 'Indonesian Rupiah', 'IDR'),
(66, 'Irish Punt', 'IEP'),
(67, 'Israeli Shekel', 'ILS'),
(68, 'Indian Rupee', 'INR'),
(69, 'Iraqi Dinar', 'IQD'),
(70, 'Iranian Rial', 'IRR'),
(73, 'Jamaican Dollar', 'JMD'),
(74, 'Jordanian Dinar', 'JOD'),
(75, 'Japanese Yen', 'JPY'),
(76, 'Kenyan Schilling', 'KES'),
(77, 'Kampuchean (Cambodian) Riel', 'KHR'),
(78, 'Comoros Franc', 'KMF'),
(79, 'North Korean Won', 'KPW'),
(80, '(South) Korean Won', 'KRW'),
(81, 'Kuwaiti Dinar', 'KWD'),
(82, 'Cayman Islands Dollar', 'KYD'),
(83, 'Lao Kip', 'LAK'),
(84, 'Lebanese Pound', 'LBP'),
(85, 'Sri Lanka Rupee', 'LKR'),
(86, 'Liberian Dollar', 'LRD'),
(87, 'Lesotho Loti', 'LSL'),
(89, 'Libyan Dinar', 'LYD'),
(90, 'Moroccan Dirham', 'MAD'),
(91, 'Malagasy Franc', 'MGF'),
(92, 'Mongolian Tugrik', 'MNT'),
(93, 'Macau Pataca', 'MOP'),
(94, 'Mauritanian Ouguiya', 'MRO'),
(95, 'Maltese Lira', 'MTL'),
(96, 'Mauritius Rupee', 'MUR'),
(97, 'Maldive Rufiyaa', 'MVR'),
(98, 'Malawi Kwacha', 'MWK'),
(99, 'Mexican Peso', 'MXP'),
(100, 'Malaysian Ringgit', 'MYR'),
(101, 'Mozambique Metical', 'MZM'),
(102, 'Namibian Dollar', 'NAD'),
(103, 'Nigerian Naira', 'NGN'),
(104, 'Nicaraguan Cordoba', 'NIO'),
(105, 'Norwegian Kroner', 'NOK'),
(106, 'Nepalese Rupee', 'NPR'),
(107, 'New Zealand Dollar', 'NZD'),
(108, 'Omani Rial', 'OMR'),
(109, 'Panamanian Balboa', 'PAB'),
(110, 'Peruvian Nuevo Sol', 'PEN'),
(111, 'Papua New Guinea Kina', 'PGK'),
(112, 'Philippine Peso', 'PHP'),
(113, 'Pakistan Rupee', 'PKR'),
(114, 'Polish Zloty', 'PLN'),
(116, 'Paraguay Guarani', 'PYG'),
(117, 'Qatari Rial', 'QAR'),
(118, 'Romanian Leu', 'RON'),
(119, 'Rwanda Franc', 'RWF'),
(120, 'Saudi Arabian Riyal', 'SAR'),
(121, 'Solomon Islands Dollar', 'SBD'),
(122, 'Seychelles Rupee', 'SCR'),
(123, 'Sudanese Pound', 'SDP'),
(124, 'Swedish Krona', 'SEK'),
(125, 'Singapore Dollar', 'SGD'),
(126, 'St. Helena Pound', 'SHP'),
(127, 'Sierra Leone Leone', 'SLL'),
(128, 'Somali Schilling', 'SOS'),
(129, 'Suriname Guilder', 'SRG'),
(130, 'Sao Tome and Principe Dobra', 'STD'),
(131, 'Russian Ruble', 'RUB'),
(132, 'El Salvador Colon', 'SVC'),
(133, 'Syrian Potmd', 'SYP'),
(134, 'Swaziland Lilangeni', 'SZL'),
(135, 'Thai Baht', 'THB'),
(136, 'Tunisian Dinar', 'TND'),
(137, 'Tongan Paanga', 'TOP'),
(138, 'East Timor Escudo', 'TPE'),
(139, 'Turkish Lira', 'TRY'),
(140, 'Trinidad and Tobago Dollar', 'TTD'),
(141, 'Taiwan Dollar', 'TWD'),
(142, 'Tanzanian Schilling', 'TZS'),
(143, 'Uganda Shilling', 'UGX'),
(144, 'US Dollar', 'USD'),
(145, 'Uruguayan Peso', 'UYU'),
(146, 'Venezualan Bolivar', 'VEF'),
(147, 'Vietnamese Dong', 'VND'),
(148, 'Vanuatu Vatu', 'VUV'),
(149, 'Samoan Tala', 'WST'),
(150, 'Communauté Financière Africaine BEAC, Francs', 'XAF'),
(151, 'Silver, Ounces', 'XAG'),
(152, 'Gold, Ounces', 'XAU'),
(153, 'East Caribbean Dollar', 'XCD'),
(154, 'International Monetary Fund (IMF) Special Drawing Rights', 'XDR'),
(155, 'Communauté Financière Africaine BCEAO - Francs', 'XOF'),
(156, 'Palladium Ounces', 'XPD'),
(157, 'Comptoirs Français du Pacifique Francs', 'XPF'),
(158, 'Platinum, Ounces', 'XPT'),
(159, 'Democratic Yemeni Dinar', 'YDD'),
(160, 'Yemeni Rial', 'YER'),
(161, 'New Yugoslavia Dinar', 'YUD'),
(162, 'South African Rand', 'ZAR'),
(163, 'Zambian Kwacha', 'ZMK'),
(164, 'Zaire Zaire', 'ZRZ'),
(165, 'Zimbabwe Dollar', 'ZWD'),
(166, 'Slovak Koruna', 'SKK'),
(167, 'Armenian Dram', 'AMD');


CREATE TABLE IF NOT EXISTS `#__donorforce_pcategory` (
  `pcategory_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY (`pcategory_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;



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


CREATE TABLE IF NOT EXISTS `#__donorforce_gift` (
  `gift_id` int(11) NOT NULL AUTO_INCREMENT,
  `donor_id` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `reference` varchar(255) NOT NULL,
  `status` char(255) DEFAULT NULL,
  `desc` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`gift_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


--
-- Table structure for table `#__donorforce_rdo_history`
--

CREATE TABLE IF NOT EXISTS `#__donorforce_rdo_history` (
  `rdo_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `donor_history_id` int(11) DEFAULT NULL,
  `subscriptions_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`rdo_history_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


--
-- Table structure for table `#__donorforce_donornotes`
--

CREATE TABLE IF NOT EXISTS `#__donorforce_donornotes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `donor_id` int(11) DEFAULT NULL,
  `title` text,
  `notes` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


/* ALTER TABLE `#__donorforce_donor` 
  ADD `note_title` text AFTER `dateofbirth`; */