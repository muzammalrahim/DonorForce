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