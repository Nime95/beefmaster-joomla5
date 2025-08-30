DROP TABLE IF EXISTS `#__securitycheck_db`;
CREATE TABLE `#__securitycheck_db` (
`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`Product` VARCHAR(35) NOT NULL,
`Type` VARCHAR(35),
`Vulnerableversion` VARCHAR(10) DEFAULT '---',
`modvulnversion` VARCHAR(2) DEFAULT '==',
`Joomlaversion` VARCHAR(10) DEFAULT 'Notdefined',
`modvulnjoomla` VARCHAR(2) DEFAULT '==',
PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
INSERT INTO `#__securitycheck_db` (`product`,`type`,`vulnerableversion`,`modvulnversion`,`Joomlaversion`,`modvulnjoomla`) VALUES 
('Joomla!','core','4.0.1','<=','4','>='),
('Joomla!','core','4.1.2','<=','4','>='),
('Joomla!','core','4.2.0','==','4','>='),
('Joomla!','core','4.2.3','<=','4','>='),
('com_eshop','component','3.6.0','==','4','>='),
('com_edocman','component','1.23.3','==','4','>='),
('com_joomrecipe','component','4.2.2','==','4','>='),
('com_opencart','component','3.0.3.19','==','4','>='),
('com_vikappointments','component','1.7.3','==','4','>='),
('com_vikrentcar','component','1.14','==','4','>='),
('com_vikbooking','component','1.15.0','==','4','>='),
('com_career','component','3.3.0','==','4','>='),
('com_solidres','component','2.12.9','==','4','>='),
('com_rentalotplus','component','19.05','==','4','>='),
('com_oscommerce','component','3.4','==','4','>='),
('com_easyshop','component','1.4.1','==','4','>='),
('com_jsjobs','component','1.3.6','==','4','>='),
('Joomla!','core','4.2.4','<=','4','>='),
('com_kunena','component','6.0.4','<=','4','>='),
('Joomla!','core','4.2.6','<=','4','>='),
('Joomla!','core','4.2.7','<=','4','>='),
('Joomla!','core','4.3.1','<=','4','>='),
('com_jbusinessdirectory','component','5.7.7','<=','4','>='),
('com_hikashop','component','4.7.2','<=','4','>='),
('com_jchoptimize','component','8.0.4','<=','4','>=');

CREATE TABLE IF NOT EXISTS `#__securitycheckpro_update_database` (
`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`version` VARCHAR(10),
`last_check` DATETIME,
`message` VARCHAR(300),
PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
INSERT INTO `#__securitycheckpro_update_database` (`version`) VALUES ('1.3.20');