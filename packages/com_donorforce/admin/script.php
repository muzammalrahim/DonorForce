<?php
/**
 * @package Component Donor Force for Joomla! 3
 * @version $Id: com_donorforce.php 600 2015-04-20 23:26:33Z brent $
 * @author Brent Bartlet
 * @copyright (C) 2015- Netwise Multimedia
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
/**
 * Script file of DonorForce component
 */
class com_donorforceInstallerScript
{
        /**
         * method to install the component
         *
         * @return void
         */
        function install($parent) 
        {
			
			//$installer = new JInstaller;
		
        	/*if($installer->install(dirname(__FILE__).'/admin/plugins/plg_donorforceprojects')){
           	 echo 'Plugin install success', '<br />';
        	} else{
          		echo 'Plugin install failed', '<br />';
        	}
			*/
				// Install the packages
				//$parent->install($parent->getParent()->getPath('source').'/admin/plugins/plg_donorforceprojects/');
			    // $parent is the class calling this method
               // $parent->getParent()->setRedirectURL('index.php?option=com_helloworld');
        }
 
        /**
         * method to uninstall the component
         *
         * @return void
         */
        function uninstall($parent) 
        {
                // $parent is the class calling this method
                echo '<p>' . JText::_('DonorForce Uninstall Successfull') . '</p>';
        }
 
 
		 function preflight($type, $parent) 
			{
				// $parent is the class calling this method
				// $type is the type of change (install, update or discover_install)
				//echo '<p>' . JText::_('COM_HELLOWORLD_PREFLIGHT_' . $type . '_TEXT') . '</p>';
				
			}
 
 
 
 		
	function postflight($type, $parent) 
	{
		$db = JFactory::getDBO();
		$columns = $db->getTableColumns('#__donorforce_donor');
		$columns1 = $db->getTableColumns('#__donorforce_bequest');
		$columns2 = $db->getTableColumns('#__donorforce_history');
		$columns3 = $db->getTableColumns('#__donorforce_rec_donation');
		$columns4 = $db->getTableColumns('#__donorforce_donor_subscriptions');
		$columns5 = $db->getTableColumns('#__donorforce_project');
		$columns6 = $db->getTableColumns('#__donorforce_orders');		
		$columns7 = $db->getTableColumns('#__donorforce_invoice_temp');
		$columns8 = $db->getTableColumns('#__donorforce_donornotes');
		
		
		if(!isset($columns['dateofbirth'])){
   			$alterQuery1 = "ALTER TABLE `#__donorforce_donor` ADD `dateofbirth` date DEFAULT NULL"; 
			$db->setQuery($alterQuery1);
			$db->execute();
			
		}
		if(!isset($columns['name_title'])){ 
   			$alterQuery2 = "ALTER TABLE `#__donorforce_donor` ADD `name_title` varchar(50) NOT NULL DEFAULT 'Mr'"; 
			$db->setQuery($alterQuery2);
			$db->execute();
			
		}
		if(!isset($columns['notes'])){ 
   			$alterQuery3 = "ALTER TABLE `#__donorforce_donor` ADD `notes` text NOT NULL"; 
			$db->setQuery($alterQuery3);
			$db->execute();
			
		}
		
		if(!isset($columns['note_title'])){ 
			$alterQuery3 = "ALTER TABLE `#__donorforce_donor` ADD `note_title` text NOT NULL"; 
			$db->setQuery($alterQuery3);
			$db->execute();
			
		}
	 
		if(!isset($columns['level'])){ 
   			$alterQuery3 = "ALTER TABLE `#__donorforce_donor` ADD `level` INT NOT NULL"; 
			$db->setQuery($alterQuery3);
			$db->execute();			
		}
		
		if(!isset($columns['membership'])){ 
   			$alterQuery3 = "ALTER TABLE `#__donorforce_donor` ADD `membership` INT NOT NULL"; 			
			$db->setQuery($alterQuery3);
			$db->execute();			
		}
		
		if(!isset($columns1['cms_user_id'])){ 
			if(isset($columns1['j_name'])){
				$alterQuery4 = "ALTER TABLE `#__donorforce_bequest` CHANGE `j_name` `cms_user_id` INT NULL DEFAULT NULL"; 
				$db->setQuery($alterQuery4);
				$db->execute();
			}
			
		}
		
		if(!isset($columns2['donation_type'])){ 
   			$alterQuery5 = "ALTER TABLE `#__donorforce_history`  ADD `donation_type` VARCHAR(25) NOT NULL AFTER `status`";
			$db->setQuery($alterQuery5);
			$db->execute();
			
		}
		
		if(!isset($columns2['donor_id'])){ 
			if(isset($columns2['donation_id'])){ 			
				$alterQuery6 = "ALTER TABLE `#__donorforce_history` CHANGE `donation_id` `donor_id` INT NULL DEFAULT NULL";
				$db->setQuery($alterQuery6);
				$db->execute();
			}
			
		}
		
		if(!isset($columns2['Reference'])){ 
   			$alterQuery6 = "ALTER TABLE `#__donorforce_history` ADD `Reference` varchar(255) DEFAULT NULL";
			$db->setQuery($alterQuery6);
			$db->execute();
			
		}
		
		if(isset($columns2['amount'])){ 
   			$alterQuery8 = "ALTER TABLE `#__donorforce_history` CHANGE `amount` `amount` FLOAT(20,2) NULL DEFAULT NULL";
			$db->setQuery($alterQuery8);
			$db->execute();
			
		}
		
		if(!isset($columns3['branch_name'])){ 
   			$alterQuery7 = "ALTER TABLE `#__donorforce_rec_donation` CHANGE `branch_number` `branch_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";
			$db->setQuery($alterQuery7);
			$db->execute();
			
		}
		
	
	
		if(!isset($columns4['donation_start_date'])){ 
   		$alterQuery8 = "ALTER TABLE `#__donorforce_donor_subscriptions` ADD `donation_start_date` date NOT NULL";
			$db->setQuery($alterQuery8);
			$db->execute();
			
		}
		if(!isset($columns4['donation_end_date'])){ 
   		$alterQuery9 = "ALTER TABLE `#__donorforce_donor_subscriptions` ADD `donation_end_date` date NOT NULL";
			$db->setQuery($alterQuery9);
			$db->execute();
			
		}
	
		
		if(!isset($columns4['deduction_day'])){ 
   		$alterQuery10 = "ALTER TABLE `#__donorforce_donor_subscriptions` ADD `deduction_day` smallint(6) NOT NULL";
			$db->setQuery($alterQuery10);
			$db->execute();
			
		}
		if(!isset($columns4['frequency'])){ 
   		$alterQuery11 = "ALTER TABLE `#__donorforce_donor_subscriptions` ADD `frequency`  varchar(10) NOT NULL";
			$db->setQuery($alterQuery11);
			$db->execute();
			
		}
		
		
		
		if(!isset($columns5['snapscan_image'])){ 
   		$alterQuery12 = "ALTER TABLE `#__donorforce_project` ADD `snapscan_image` varchar(255) CHARACTER SET utf8 NOT NULL";
			$db->setQuery($alterQuery12);
			$db->execute();
			
		}
		
		
		
		if(!isset($columns4['transaction_id'])){ 
   		$alterQuery13 = "ALTER TABLE `#__donorforce_donor_subscriptions` ADD `transaction_id` varchar(255) DEFAULT NULL";
			$db->setQuery($alterQuery13);
			$db->execute(); 			
		}
		
		
		if(!isset($columns6['rec_donation_subscription_id'])){ 
   		$alterQuery14 = "ALTER TABLE `#__donorforce_orders` ADD `rec_donation_subscription_id` int(11) DEFAULT NULL";
			$db->setQuery($alterQuery14);
			$db->execute();  			
		}
		
		
		if(isset($columns6['donation_history_id'])){ 
   		$alterQuery15 = "ALTER TABLE `#__donorforce_orders` CHANGE `donation_history_id` `donation_history_id` int(11) DEFAULT NULL";
			$db->setQuery($alterQuery15);
			$db->execute();			
		}
		
		
		if(!isset($columns6['rec_donation_debitorder_id'])){ 
   		$alterQuery16 = "ALTER TABLE `#__donorforce_orders` ADD `rec_donation_debitorder_id` int(11) DEFAULT NULL";
			$db->setQuery($alterQuery16);
			$db->execute();  			
		}
		
		/*-- Tax pdf filed --*/
		
		if(!isset($columns7['custom_style'])){ 
   		$alterQuery15X = "ALTER TABLE `#__donorforce_invoice_temp` ADD `custom_style` text NOT NULL";
			$db->setQuery($alterQuery15X);
			$db->execute();  			
		}
		
		if(!isset($columns7['head_logo2'])){ 
   		$alterQuery16 = "ALTER TABLE `#__donorforce_invoice_temp` ADD `head_logo2` varchar(255) NOT NULL";
			$db->setQuery($alterQuery16);
			$db->execute();  			
		}
		
		if(!isset($columns7['postal_address'])){ 
   		$alterQuery17 = "ALTER TABLE `#__donorforce_invoice_temp` ADD `postal_address` varchar(255) NOT NULL";
			$db->setQuery($alterQuery17);
			$db->execute();  			
		}
		
		if(!isset($columns7['physical_address'])){ 
   		$alterQuery18 = "ALTER TABLE `#__donorforce_invoice_temp` ADD `physical_address` varchar(255) NOT NULL";
			$db->setQuery($alterQuery18);
			$db->execute();  			
		}
		
		if(!isset($columns7['receipt_text'])){ 
   			$alterQuery19 = "ALTER TABLE `#__donorforce_invoice_temp` ADD `receipt_text` text NOT NULL";
			$db->setQuery($alterQuery19 );
			$db->execute();  			
		}
		
		if(!isset($columns7['receipt_body'])){ 
   			$alterQuery20 = "ALTER TABLE `#__donorforce_invoice_temp` ADD `receipt_body` text NOT NULL";
			$db->setQuery($alterQuery20 );
			$db->execute();  			
		}
		
		if(!isset($columns7['statement_intent'])){ 
   		$alterQuery21 = "ALTER TABLE `#__donorforce_invoice_temp` ADD `statement_intent` text NOT NULL";
			$db->setQuery($alterQuery21 );
			$db->execute();  			
		}
		if(!isset($columns7['chairman_title'])){ 
   			$alterQuery36 = "ALTER TABLE `#__donorforce_invoice_temp` ADD `chairman_title` TEXT NOT NULL";
			$db->setQuery($alterQuery36 );
			$db->execute();  			
		}
		
		if(!isset($columns7['chairman_image'])){ 
   			$alterQuery22 = "ALTER TABLE `#__donorforce_invoice_temp` ADD `chairman_image` varchar(255) NOT NULL";
			$db->setQuery($alterQuery22 );
			$db->execute();  			
		}
		
		if(!isset($columns7['footer1'])){ 
   		$alterQuery23 = "ALTER TABLE `#__donorforce_invoice_temp` ADD `footer1` varchar(255) NOT NULL";
			$db->setQuery($alterQuery23 );
			$db->execute();  			
		}
		
		if(!isset($columns7['footer2'])){ 
   		$alterQuery24 = "ALTER TABLE `#__donorforce_invoice_temp` ADD `footer2` varchar(255) NOT NULL";
			$db->setQuery($alterQuery24 );
			$db->execute();  			
		}
		
		if(!isset($columns7['footer3'])){ 
   		$alterQuery25 = "ALTER TABLE `#__donorforce_invoice_temp` ADD `footer3` varchar(255) NOT NULL";
			$db->setQuery($alterQuery25 );
			$db->execute();  			
		}
		
		if(!isset($columns7['footer4'])){ 
   			$alterQuery26 = "ALTER TABLE `#__donorforce_invoice_temp` ADD `footer4` varchar(255) NOT NULL";
			$db->setQuery($alterQuery26 );
			$db->execute();  			
		}

		if(!isset($columns7['footer5'])){ 
   		$alterQuery27 = "ALTER TABLE `#__donorforce_invoice_temp` ADD `footer5` varchar(255) NOT NULL";
			$db->setQuery($alterQuery27 );
			$db->execute();  			
		}
		
		if(!isset($columns7['footer6'])){ 
   		$alterQuery28 = "ALTER TABLE `#__donorforce_invoice_temp` ADD `footer6` varchar(255) NOT NULL";
			$db->setQuery($alterQuery28 );
			$db->execute();  			
		}
		
		if(!isset($columns7['custom_style2'])){ 
   		$alterQuery29 = "ALTER TABLE `#__donorforce_invoice_temp` ADD `custom_style2` text NOT NULL";
			$db->setQuery($alterQuery29 );
			$db->execute();  			
		}
		if(!isset($columns7['thankyou_body'])){ 
   		$alterQuery30 = "ALTER TABLE `#__donorforce_invoice_temp` ADD `thankyou_body` text NOT NULL";
			$db->setQuery($alterQuery30 );
			$db->execute();  			
		}
		if(!isset($columns7['chairman_name'])){ 
   			$alterQuery31 = "ALTER TABLE `#__donorforce_invoice_temp` ADD `chairman_name` varchar(255) NOT NULL";
			$db->setQuery($alterQuery31 );
			$db->execute();  			
		}
		if(isset($columns3['account_number'])){ 
   			$alterQuery32 = "ALTER TABLE `#__donorforce_rec_donation` CHANGE `account_number` `account_number` varchar(255) DEFAULT NULL";
			$db->setQuery($alterQuery32);
			$db->execute();			
		}
		if(!isset($columns['user_created'])){
   			$alterQuery33 = "ALTER TABLE `#__donorforce_donor` ADD `user_created` date DEFAULT NULL AFTER `org_type`"; 
			$db->setQuery($alterQuery33);
			$db->execute();
			
		}
		if(!isset($columns['entries'])){
   			$alterQuery34 = "ALTER TABLE `#__donorforce_donor` ADD `entries` varchar(500) DEFAULT NULL AFTER `membership`"; 
			$db->setQuery($alterQuery34);
			$db->execute();
			
		}
		if(!isset($columns['mail_only'])){
   			$alterQuery35 = "ALTER TABLE `#__donorforce_donor` ADD `mail_only` varchar(300) DEFAULT NULL AFTER `entries`"; 
			$db->setQuery($alterQuery35);
			$db->execute();
			
		}
		
		if(!isset($columns8['date'])){ 
			$alterQuery36 = "ALTER TABLE `#__donorforce_donornotes` ADD `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `notes`;"; 
			$db->setQuery($alterQuery36);
			$db->execute();
			
		}
	 
		if(!isset($columns8['date_modified'])){ 
			$alterQuery37 = "ALTER TABLE `#__donorforce_donornotes` ADD `date_modified` text AFTER `date`;"; 
			$db->setQuery($alterQuery37);
			$db->execute();
			
		}
		
		if(!isset($columns['vat_number'])){ 
			$alterQuery38 = "ALTER TABLE `#__donorforce_donor` ADD `vat_number` text NOT NULL"; 
			$db->setQuery($alterQuery38);
			$db->execute();
	
		}
	 
		if(!isset($columns['menbership_type'])){ 
			$alterQuery39 = "ALTER TABLE `#__donorforce_donor` ADD `menbership_type` varchar(100)"; 
			$db->setQuery($alterQuery39);
			$db->execute();
	
		}
	 
		/*-- Tax pdf filed --*/
		
	
		
		
		
		
				$db = JFactory::getDBO();		
				$drop1 = "DROP TABLE IF EXISTS `#__donorforce_currencies`"; 
				$db->setQuery($drop1);
				$db->execute();		
				
				$drop2 = "DROP TABLE IF EXISTS `#__donorforce_countries`"; 	
				$db->setQuery($drop2);
				$db->execute();		
				
	
				$create = " CREATE TABLE IF NOT EXISTS `#__donorforce_countries` (
							  `country_id` int(11) NOT NULL AUTO_INCREMENT,
							  `country_code` varchar(100) DEFAULT NULL,
							  `country_name` varchar(100) DEFAULT NULL,
							  PRIMARY KEY (`country_id`)
							) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=243 ;
							"; 
							$db->setQuery($create);
							$db->execute();	
							
				$insertquey = "								
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
							(242, 'ZW', 'Zimbabwe'); "; 
						
						$db->setQuery($insertquey);
						$db->execute();		
							
					   $create2 = "		
							CREATE TABLE IF NOT EXISTS `#__donorforce_currencies` (
							  `currency_id` int(11) NOT NULL AUTO_INCREMENT,
							  `currency_name` varchar(64) DEFAULT NULL,
							  `currency_code` char(3) DEFAULT NULL,
							  PRIMARY KEY (`currency_id`),
							  KEY `idx_currency_name` (`currency_name`)
							) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=168 ;
						"; 
						
						$db->setQuery($create2);
						$db->execute();	
						
						
						$insertquey2 = "		
							
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
							(167, 'Armenian Dram', 'AMD'); "; 
							
						$db->setQuery($insertquey2);
						$db->execute();	
						
					$create3 = "		
						CREATE TABLE IF NOT EXISTS `#__donorforce_donornotes` (
							`id` int(11) NOT NULL AUTO_INCREMENT,
							`donor_id` int(11) DEFAULT NULL,
							`title` text,
							`notes` text,
							PRIMARY KEY (`id`)
						) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
					"; 
					
					$db->setQuery($create3);
					$db->execute();	
	}
 
 
}