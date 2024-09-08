
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `database`
-- Prefix:   `prefix_`
--

-- --------------------------------------------------------

--
-- This defines tables needed if you are not using Zen Cart
--

-- --------------------------------------------------------

--
-- Table structure for table `prefix_eventtools_users`
--


DROP TABLE IF EXISTS `prefix_customers`;
CREATE TABLE `prefix_customers` (
  `customers_id` int(11) NOT NULL AUTO_INCREMENT,
  `customers_gender` char(1) NOT NULL DEFAULT '',
  `customers_firstname` varchar(32) NOT NULL DEFAULT '',
  `customers_lastname` varchar(32) NOT NULL DEFAULT '',
  `customers_dob` datetime NOT NULL DEFAULT '0001-01-01 00:00:00',
  `customers_email_address` varchar(96) NOT NULL DEFAULT '',
  `customers_nick` varchar(96) NOT NULL DEFAULT '',
  `customers_default_address_id` int(11) NOT NULL DEFAULT '0',
  `customers_telephone` varchar(32) NOT NULL DEFAULT '',
  `customers_cellphone` varchar(32) NOT NULL DEFAULT '',
  `customers_fax` varchar(32) DEFAULT NULL,
  `customers_x2011_nmra_num` varchar(8) DEFAULT NULL,
  `customers_x2011_nmra_expires` date DEFAULT NULL,
  `customers_x2011_reg_num` varchar(8) DEFAULT NULL,
  `customers_x2011_ldsig_num` varchar(32) DEFAULT NULL,
  `customers_x2011_opsig_num` varchar(32) DEFAULT NULL,
  `customers_x2011_nasg_num` varchar(32) DEFAULT NULL,
  `customers_x2011_other_aff_groups` varchar(60) DEFAULT NULL,
  `customers_x2011_emerg_contact_name` varchar(60) DEFAULT NULL,
  `customers_x2011_emerg_contact_phone` varchar(32) DEFAULT NULL,
  `customers_x2011_MMRp` char(1) DEFAULT NULL,
  `customers_x2011_HLMp` char(1) DEFAULT NULL,
  `customers_x2011_first_name_badge` varchar(32) DEFAULT NULL,
  `customers_x2011_spouse_name` varchar(32) DEFAULT NULL,
  `customers_x2011_cell_number` varchar(32) DEFAULT NULL,
  `customers_x2011_nmra_region` varchar(8) DEFAULT NULL,
  `customers_x2011_scale` varchar(32) DEFAULT NULL,
  `customers_password` varchar(40) NOT NULL DEFAULT '',
  `customers_newsletter` char(1) DEFAULT NULL,
  `customers_group_pricing` int(11) NOT NULL DEFAULT '0',
  `customers_email_format` varchar(4) NOT NULL DEFAULT 'TEXT',
  `customers_authorization` int(1) NOT NULL DEFAULT '0',
  `customers_referral` varchar(32) NOT NULL DEFAULT '',
  `customers_paypal_payerid` varchar(20) NOT NULL DEFAULT '',
  `customers_paypal_ec` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `customers_x2011_associated_num` varchar(60) DEFAULT NULL,
  `customers_create_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `customers_updated_date` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`customers_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

ALTER TABLE `prefix_customers`
  ADD UNIQUE KEY `idx_email_address_zen` (`customers_email_address`),
  ADD KEY `idx_referral_zen` (`customers_referral`(10)),
  ADD KEY `idx_grp_pricing_zen` (`customers_group_pricing`),
  ADD KEY `idx_nick_zen` (`customers_nick`),
  ADD KEY `idx_newsletter_zen` (`customers_newsletter`);


DROP TABLE IF EXISTS `prefix_address_book`;

CREATE TABLE IF NOT EXISTS `prefix_address_book` (
  `address_book_id` int(11) NOT NULL auto_increment,
  `customers_id` int(11) NOT NULL default '0',
  `entry_gender` char(1) NOT NULL default '',
  `entry_company` varchar(64) default NULL,
  `entry_firstname` varchar(32) NOT NULL default '',
  `entry_lastname` varchar(32) NOT NULL default '',
  `entry_street_address` varchar(64) NOT NULL default '',
  `entry_suburb` varchar(32) default NULL,
  `entry_postcode` varchar(10) NOT NULL default '',
  `entry_city` varchar(32) NOT NULL default '',
  `entry_state` varchar(32) default NULL,
  `entry_country_id` int(11) NOT NULL default '0',
  `entry_zone_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`address_book_id`),
  KEY `idx_address_book_customers_id_zen` (`customers_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=816 ;
