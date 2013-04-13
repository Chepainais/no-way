SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- Table structure for table `translations`
--

CREATE TABLE IF NOT EXISTS `translations` (
  `translation_id` int(11) NOT NULL AUTO_INCREMENT,
  `msgid` varchar(255) NOT NULL,
  `msgstring` varchar(255) NOT NULL,
  `locale` enum('lv','no','fi','ru','en','gb','sv') NOT NULL,
  `module` varchar(15) DEFAULT NULL,
  `controller` varchar(20) DEFAULT NULL,
  `time_created` datetime DEFAULT NULL,
  `time_edited` datetime DEFAULT NULL,
  `edited_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`translation_id`),
  UNIQUE KEY `msgid` (`msgid`,`locale`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;
