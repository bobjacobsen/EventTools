
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `database`
-- Prefix:   `prefix_`
--

-- --------------------------------------------------------

-- 
-- You can do a bulk replace of 'prefix_'
-- to convert to your local prefix.
--
-- Either the Zen Cart tables must exist, or
-- you should run the define_zen_repl.sql file
-- before this one.
--
-- After this, run define_views.sql to 
-- create views across the tables
--
-- --------------------------------------------------------

--
-- Table structure for value constraint tables
--

DROP TABLE IF EXISTS `prefix_eventtools_constrain_scale`;
CREATE TABLE IF NOT EXISTS `prefix_eventtools_constrain_scale` (
  `constrain_id` int(5) NOT NULL auto_increment,
  `constrain_value` varchar(25) NOT NULL,
  PRIMARY KEY  (`constrain_id`),
  KEY `idx_eventtools_constrain_scale_value` (`constrain_value`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

DROP TABLE IF EXISTS `prefix_eventtools_constrain_gauge`;
CREATE TABLE IF NOT EXISTS `prefix_eventtools_constrain_gauge` (
  `constrain_id` int(5) NOT NULL auto_increment,
  `constrain_value` varchar(25) NOT NULL,
  PRIMARY KEY  (`constrain_id`),
  KEY `idx_eventtools_constrain_value` (`constrain_value`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

DROP TABLE IF EXISTS `prefix_eventtools_constrain_era`;
CREATE TABLE IF NOT EXISTS `prefix_eventtools_constrain_era` (
  `constrain_id` int(5) NOT NULL auto_increment,
  `constrain_value` varchar(25) NOT NULL,
  PRIMARY KEY  (`constrain_id`),
  KEY `idx_eventtools_constrain_value` (`constrain_value`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

DROP TABLE IF EXISTS `prefix_eventtools_constrain_class`;
CREATE TABLE IF NOT EXISTS `prefix_eventtools_constrain_class` (
  `constrain_id` int(5) NOT NULL auto_increment,
  `constrain_value` varchar(25) NOT NULL,
  PRIMARY KEY  (`constrain_id`),
  KEY `idx_eventtools_constrain_value` (`constrain_value`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

DROP TABLE IF EXISTS `prefix_eventtools_constrain_theme`;
CREATE TABLE IF NOT EXISTS `prefix_eventtools_constrain_theme` (
  `constrain_id` int(5) NOT NULL auto_increment,
  `constrain_value` varchar(25) NOT NULL,
  PRIMARY KEY  (`constrain_id`),
  KEY `idx_eventtools_constrain_value` (`constrain_value`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

DROP TABLE IF EXISTS `prefix_eventtools_constrain_locale`;
CREATE TABLE IF NOT EXISTS `prefix_eventtools_constrain_locale` (
  `constrain_id` int(5) NOT NULL auto_increment,
  `constrain_value` varchar(25) NOT NULL,
  PRIMARY KEY  (`constrain_id`),
  KEY `idx_eventtools_constrain_value` (`constrain_value`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

DROP TABLE IF EXISTS `prefix_eventtools_constrain_scenery`;
CREATE TABLE IF NOT EXISTS `prefix_eventtools_constrain_scenery` (
  `constrain_id` int(5) NOT NULL auto_increment,
  `constrain_value` varchar(25) NOT NULL,
  PRIMARY KEY  (`constrain_id`),
  KEY `idx_eventtools_constrain_value` (`constrain_value`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

DROP TABLE IF EXISTS `prefix_eventtools_constrain_plan_type`;
CREATE TABLE IF NOT EXISTS `prefix_eventtools_constrain_plan_type` (
  `constrain_id` int(5) NOT NULL auto_increment,
  `constrain_value` varchar(25) NOT NULL,
  PRIMARY KEY  (`constrain_id`),
  KEY `idx_eventtools_constrain_value` (`constrain_value`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

DROP TABLE IF EXISTS `prefix_eventtools_constrain_ops_scheme`;
CREATE TABLE IF NOT EXISTS `prefix_eventtools_constrain_ops_scheme` (
  `constrain_id` int(5) NOT NULL auto_increment,
  `constrain_value` varchar(25) NOT NULL,
  PRIMARY KEY  (`constrain_id`),
  KEY `idx_eventtools_constrain_value` (`constrain_value`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

DROP TABLE IF EXISTS `prefix_eventtools_constrain_control`;
CREATE TABLE IF NOT EXISTS `prefix_eventtools_constrain_control` (
  `constrain_id` int(5) NOT NULL auto_increment,
  `constrain_value` varchar(25) NOT NULL,
  PRIMARY KEY  (`constrain_id`),
  KEY `idx_eventtools_constrain_value` (`constrain_value`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

DROP TABLE IF EXISTS `prefix_eventtools_constrain_fidelity`;
CREATE TABLE IF NOT EXISTS `prefix_eventtools_constrain_fidelity` (
  `constrain_id` int(5) NOT NULL auto_increment,
  `constrain_value` varchar(25) NOT NULL,
  PRIMARY KEY  (`constrain_id`),
  KEY `idx_eventtools_constrain_value` (`constrain_value`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

DROP TABLE IF EXISTS `prefix_eventtools_constrain_rigor`;
CREATE TABLE IF NOT EXISTS `prefix_eventtools_constrain_rigor` (
  `constrain_id` int(5) NOT NULL auto_increment,
  `constrain_value` varchar(25) NOT NULL,
  PRIMARY KEY  (`constrain_id`),
  KEY `idx_eventtools_constrain_value` (`constrain_value`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

DROP TABLE IF EXISTS `prefix_eventtools_constrain_documentation`;
CREATE TABLE IF NOT EXISTS `prefix_eventtools_constrain_documentation` (
  `constrain_id` int(5) NOT NULL auto_increment,
  `constrain_value` varchar(25) NOT NULL,
  PRIMARY KEY  (`constrain_id`),
  KEY `idx_eventtools_constrain_value` (`constrain_value`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

DROP TABLE IF EXISTS `prefix_eventtools_constrain_session_pace`;
CREATE TABLE IF NOT EXISTS `prefix_eventtools_constrain_session_pace` (
  `constrain_id` int(5) NOT NULL auto_increment,
  `constrain_value` varchar(25) NOT NULL,
  PRIMARY KEY  (`constrain_id`),
  KEY `idx_eventtools_constrain_value` (`constrain_value`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

DROP TABLE IF EXISTS `prefix_eventtools_constrain_car_forwarding`;
CREATE TABLE IF NOT EXISTS `prefix_eventtools_constrain_car_forwarding` (
  `constrain_id` int(5) NOT NULL auto_increment,
  `constrain_value` varchar(25) NOT NULL,
  PRIMARY KEY  (`constrain_id`),
  KEY `idx_eventtools_constrain_value` (`constrain_value`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

DROP TABLE IF EXISTS `prefix_eventtools_constrain_tone`;
CREATE TABLE IF NOT EXISTS `prefix_eventtools_constrain_tone` (
  `constrain_id` int(5) NOT NULL auto_increment,
  `constrain_value` varchar(25) NOT NULL,
  PRIMARY KEY  (`constrain_id`),
  KEY `idx_eventtools_constrain_value` (`constrain_value`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

DROP TABLE IF EXISTS `prefix_eventtools_constrain_dispatched_by1`;
CREATE TABLE IF NOT EXISTS `prefix_eventtools_constrain_dispatched_by1` (
  `constrain_id` int(5) NOT NULL auto_increment,
  `constrain_value` varchar(25) NOT NULL,
  PRIMARY KEY  (`constrain_id`),
  KEY `idx_eventtools_constrain_value` (`constrain_value`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

DROP TABLE IF EXISTS `prefix_eventtools_constrain_dispatched_by2`;
CREATE TABLE IF NOT EXISTS `prefix_eventtools_constrain_dispatched_by2` (
  `constrain_id` int(5) NOT NULL auto_increment,
  `constrain_value` varchar(25) NOT NULL,
  PRIMARY KEY  (`constrain_id`),
  KEY `idx_eventtools_constrain_value` (`constrain_value`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

DROP TABLE IF EXISTS `prefix_eventtools_constrain_communications`;
CREATE TABLE IF NOT EXISTS `prefix_eventtools_constrain_communications` (
  `constrain_id` int(5) NOT NULL auto_increment,
  `constrain_value` varchar(25) NOT NULL,
  PRIMARY KEY  (`constrain_id`),
  KEY `idx_eventtools_constrain_value` (`constrain_value`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Table structure for table `prefix_eventtools_users`
-- Defines user accounts that can access the tools
--

DROP TABLE IF EXISTS `prefix_eventtools_users`;

CREATE TABLE IF NOT EXISTS `prefix_eventtools_users` (
  `user_id` int(5) NOT NULL auto_increment,
  `user_name` varchar(25) NOT NULL,
  `user_pwd` varchar(25) NOT NULL,
  `user_key` varchar(25) NOT NULL,
  PRIMARY KEY  (`user_id`),
  KEY `idx_eventtools_users_user_name` (`user_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Table structure for table `prefix_eventtools_layouts`
--

DROP TABLE IF EXISTS `prefix_eventtools_layouts`;

CREATE TABLE IF NOT EXISTS `prefix_eventtools_layouts` (
  `layout_id` int(5) NOT NULL auto_increment,

  `layout_name` varchar(64) default '',
  `layout_short_description` varchar(64) default '',
  `layout_long_description` varchar(5000) default '',

  `layout_scale` varchar(20) default NULL,
  `layout_gauge` varchar(20) default NULL,
  `layout_era` varchar(64) default NULL,
  `layout_class` varchar(64) default NULL,
  `layout_theme` varchar(64) default NULL,
  `layout_locale` varchar(64) default NULL,
  
  `layout_prototype` varchar(64) default NULL,
  `layout_scenery` varchar(64) default NULL,
  `layout_size` varchar(64) default NULL,
  `layout_mainline_length` varchar(64) default NULL,
  `layout_plan_type` varchar(64) default NULL,
  `layout_ops_scheme` varchar(64) default NULL,
  `layout_control` varchar(64) default NULL,
  `layout_num_ops` varchar(5) default NULL,
  `layout_allow_photo` char(1) default NULL,
  
  `layout_accessibility` int(3) default NULL,
  `layout_wheelchair_access` char(1) default NULL,
  `layout_duckunder_entry` char(1) default NULL,

  `layout_owner_url` varchar(128) default '',
  
  `layout_owner_firstname` varchar(16) default '',
  `layout_owner_lastname` varchar(32) default '',
  `layout_owner_phone` varchar(16) default '',
  `layout_owner_call_time` varchar(32) default '',
  `layout_owner_email` varchar(32) default '',
  
  `layout_street_address` varchar(64) default '',
  `layout_city` varchar(32) default '',
  `layout_state` char(2) default '',
  `layout_postcode` varchar(14) default '',
  `layout_distance` varchar(25) default '',

  `layout_local_url` varchar(50) default '',
  
  `layout_status_code` int(3)  default 0,

  `layout_fidelity` varchar(12) default '',
  `layout_rigor` varchar(12) default '',
  `layout_documentation` varchar(12) default '',
  `layout_session_pace` varchar(12) default '',
  `layout_car_forwarding` varchar(12) default '',
  `layout_tone` varchar(12) default '',
  `layout_dispatched_by1` varchar(12) default '',
  `layout_dispatched_by2` varchar(12) default '',
  `layout_communications` varchar(12) default '',
  
  `layout_organizer_comment` varchar(250) default '',
  `layout_photo_url` varchar(50) default '',
  
  `layout_mark_changed` varchar(1) default '',
  `layout_last_mod_time` timestamp ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY  (`layout_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;


-- --------------------------------------------------------

--
-- Table structure for table `prefix_eventtools_layout_tours`
--

DROP TABLE IF EXISTS `prefix_eventtools_layout_tours`;

CREATE TABLE IF NOT EXISTS `prefix_eventtools_layout_tours` (
  `id` int(5) NOT NULL auto_increment,
  `number` varchar(5) NOT NULL,
  `name` varchar(64) default '<none>',
  `start_date` datetime NOT NULL default '2011-07-01 00:00:00',
  `end_date` datetime NOT NULL default '2011-07-01 00:00:00',
  `description` varchar(5000) default '<none>',
  `status_code` int(3)  default 0,
  `cart_item` varchar(5) default '',
  
  `tour_price` decimal(8,2)  default '-1',
  `tour_seats` int(5)  default '0',
  `tour_bus_type` varchar(16)  default '',
  `tour_buses` int(5)  default '0',
  `tour_mileage` int(5)  default '0',
  `tour_self_guide` char(1) default NULL,

  `mark_changed` varchar(1) default '',
  `last_mod_time` timestamp ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY  (`id`),
  KEY `idx_eventtools_layout_tours_number` (`number`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `prefix_eventtools_layout_tour_links`
--

DROP TABLE IF EXISTS `prefix_eventtools_layout_tour_links`;

CREATE TABLE IF NOT EXISTS `prefix_eventtools_layout_tour_links` (
  `layout_tour_link_num` int(11) NOT NULL auto_increment,
  `tour_number` varchar(5) NOT NULL,
  `layout_id` int(5) NOT NULL,
  `layout_tour_link_order` int(2) NOT NULL,

  `link_mark_changed` varchar(1) default '',
  `link_last_mod_time` timestamp ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY  (`layout_tour_link_num`),
  KEY `idx_eventtools_layout_tour_links_layout` (`layout_id`),
  KEY `idx_eventtools_layout_tour_links_tour` (`tour_number`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `prefix_eventtools_clinics`
--

DROP TABLE IF EXISTS `prefix_eventtools_clinics`;

CREATE TABLE IF NOT EXISTS `prefix_eventtools_clinics` (
  `id` int(5) NOT NULL auto_increment,
  `number` varchar(5) NOT NULL,
  `name` varchar(64) default '',
  `start_date` datetime NOT NULL default '2011-07-01 00:00:00',
  `end_date` datetime NOT NULL default '2011-07-01 00:00:00',
  `description` varchar(5000) default '',
  `status_code` int(3)  default '0',
  `cart_item` varchar(5) default '',
  
  `clinic_presenter` varchar(48) default '',
  `clinic_location_code` int(5) default 0,
  `clinic_url` varchar(64) default '',
  `clinic_presenter_email` varchar(64) default '',
  `comment` varchar(500) default '',
  `clinic_ok` varchar(2) default '',
  `clinic_presenter_cell_number` varchar(15) default '',
  `clinic_presenter_confirm_comment` varchar(500) default '',
  `clinic_presenter_av_comment` varchar(500) default '',
  `clinic_presenter_av_request` varchar(10) default '',

  `mark_changed` varchar(1) default '',
  `last_mod_time` timestamp ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY  (`id`),
  KEY `idx_eventtools_clinics_number` (`number`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `prefix_eventtools_misc_events`
--

DROP TABLE IF EXISTS `prefix_eventtools_misc_events`;

CREATE TABLE IF NOT EXISTS `prefix_eventtools_misc_events` (
  `id` int(5) NOT NULL auto_increment,
  `number` varchar(5) NOT NULL,
  `name` varchar(64) default '',
  `start_date` datetime NOT NULL default '2011-07-01 00:00:00',
  `end_date` datetime NOT NULL default '2011-07-01 00:00:00',
  `description` varchar(5000) default '',
  `status_code` int(3)  default '0',
  `cart_item` varchar(5) default '',
  
  `misc_location_code` int(5) default 0,
  `misc_url` varchar(64) default '',

  `mark_changed` varchar(1) default '',
  `last_mod_time` timestamp ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY  (`id`),
  KEY `idx_eventtools_misc_number` (`number`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `prefix_eventtools_handicapped_values`
--

DROP TABLE IF EXISTS `prefix_eventtools_accessibility_codes`;

CREATE TABLE IF NOT EXISTS `prefix_eventtools_accessibility_codes` (
  `accessibility_code` int(3) NOT NULL auto_increment,
  `accessibility_name` varchar(40) NOT NULL,
  `accessibility_display` varchar(40) NOT NULL,

  `acc_mark_changed` varchar(1) default '',
  `acc_last_mod_time` timestamp ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY  (`accessibility_code`),
  KEY `idx_eventtools_accessibility_name` (`accessibility_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `prefix_eventtools_tour_status_values`
--

DROP TABLE IF EXISTS `prefix_eventtools_event_status_values`;

CREATE TABLE IF NOT EXISTS `prefix_eventtools_event_status_values` (
  `event_status_id` int(5) NOT NULL auto_increment,
  `event_status_code` int(3) NOT NULL,
  `event_status_name` varchar(40) NOT NULL,
  `event_status_display` varchar(40) default NULL,

  `status_mark_changed` varchar(1) default '',
  `status_last_mod_time` timestamp ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY  (`event_status_id`),
  KEY `idx_eventtools_event_status_code` (`event_status_code`),
  KEY `idx_eventtools_event_status_name` (`event_status_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Table structure for table `prefix_eventtools_clinic_locations`
--

DROP TABLE IF EXISTS `prefix_eventtools_clinic_locations`;

CREATE TABLE IF NOT EXISTS `prefix_eventtools_clinic_locations` (
  `id` int(5) NOT NULL auto_increment,
  `location_code` int(3) NOT NULL,
  `location_name` varchar(40) NOT NULL,

  `locations_mark_changed` varchar(1) default '',
  `locations_last_mod_time` timestamp ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY  (`id`),
  KEY `idx_eventtools_clinic_location_code` (`location_code`),
  KEY `idx_eventtools_clinic_location_name` (`location_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `prefix_eventtools_clinic_tags`
--

DROP TABLE IF EXISTS `prefix_eventtools_clinic_tags`;

CREATE TABLE IF NOT EXISTS `prefix_eventtools_clinic_tags` (
  `clinic_tag_num` int(11) NOT NULL auto_increment,
  `clinic_tag_clinic_number` varchar(5) NOT NULL,
  `tag_name` varchar(64) default '',

  `mark_changed` varchar(1) default '',
  `last_mod_time` timestamp ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY  (`clinic_tag_num`),
  CONSTRAINT up UNIQUE NONCLUSTERED(clinic_tag_clinic_number, tag_name),
  KEY `idx_eventtools_clinic_tag_name` (`tag_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `prefix_eventtools_misc_event_tags`
--

DROP TABLE IF EXISTS `prefix_eventtools_misc_event_tags`;

CREATE TABLE IF NOT EXISTS `prefix_eventtools_misc_event_tags` (
  `misc_event_tag_num` int(11) NOT NULL auto_increment,
  `misc_event_tag_misc_event_number` varchar(5) NOT NULL,
  `tag_name` varchar(64) default '',

  `mark_changed` varchar(1) default '',
  `last_mod_time` timestamp ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY  (`misc_event_tag_num`),
  CONSTRAINT up UNIQUE NONCLUSTERED(misc_event_tag_misc_event_number, tag_name),
  KEY `idx_eventtools_misc_event_tag_name` (`tag_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `prefix_eventtools_general_tours`
--

DROP TABLE IF EXISTS `prefix_eventtools_general_tours`;

CREATE TABLE IF NOT EXISTS `prefix_eventtools_general_tours` (
  `id` int(5) NOT NULL auto_increment,
  `number` varchar(5) NOT NULL,
  `name` varchar(64) default '<none>',
  `start_date` datetime NOT NULL default '2011-07-01 00:00:00',
  `end_date` datetime NOT NULL default '2011-07-01 00:00:00',
  `description` varchar(5000) default '<none>',
  `status_code` int(3)  default 0,
  `cart_item` varchar(5) default '',
  
  `tour_price` decimal(8,2)  default '-1',
  `tour_seats` int(5)  default '0',
  `tour_bus_type` varchar(16)  default '',
  `tour_buses` int(5)  default '0',
  `tour_mileage` int(5)  default '0',
  
  `mark_changed` varchar(1) default '',
  `last_mod_time` timestamp ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY  (`id`),
  KEY `idx_eventtools_general_tours_number` (`number`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `prefix_eventtools_users`
--

DROP TABLE IF EXISTS `prefix_eventtools_users`;

CREATE TABLE IF NOT EXISTS `prefix_eventtools_users` (
  `user_id` int(5) NOT NULL auto_increment,
  `user_name` varchar(25) NOT NULL,
  `user_pwd` varchar(25) NOT NULL,
  `user_key` varchar(25) NOT NULL,
  `user_email_log_skip` binary(1) DEFAULT '0',

  `mark_changed` varchar(1) default '',
  `last_mod_time` timestamp ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY  (`user_id`),
  KEY `idx_eventtools_users_user_name` (`user_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `prefix_eventtools_people`
--

DROP TABLE IF EXISTS `prefix_eventtools_people`;

CREATE TABLE IF NOT EXISTS `prefix_eventtools_people` (
  `person_id` int(5) NOT NULL auto_increment,
  `person_firstname` varchar(16) default '',
  `person_lastname` varchar(32) default '',
  `person_phone` varchar(16) default '',
  `person_call_time` varchar(32) default '',
  `person_email` varchar(32) default '',

  `mark_changed` varchar(1) default '',
  `last_mod_time` timestamp ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY  (`person_id`),
  KEY `idx_eventtools_person_email` (`person_email`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `prefix_eventtools_opsession`
--

DROP TABLE IF EXISTS `prefix_eventtools_opsession`;

CREATE TABLE IF NOT EXISTS `prefix_eventtools_opsession` (
  `ops_id` int(5) NOT NULL auto_increment,
  `ops_layout_id` int(5),
  `start_date` datetime,
  `end_date` datetime,
  `location` varchar(25),
  `distance` varchar(25),
  `travel_time` varchar(25),
  `status_code` int(3)  default 0,
  `spaces` int(3) default 0,
  `presenting_time` varchar(50),
  `note` varchar(250),
  `ops_layout_id2` int(5),
  `ops_layout_id3` int(5),
  `ops_layout_id4` int(5),
  
  `mark_changed` varchar(1) default '',
  `last_mod_time` timestamp ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY  (`ops_id`),
  KEY `idx_eventtools_ops_layout_id` (`ops_layout_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `prefix_eventtools_opsession_req`
--

DROP TABLE IF EXISTS `prefix_eventtools_opsession_req`;

CREATE TABLE IF NOT EXISTS `prefix_eventtools_opsession_req` (
  `opsreq_id` int(5) NOT NULL auto_increment,
  `opsreq_person_email` varchar(32),
  `opsreq_pri1` int(5),
  `opsreq_pri2` int(5),
  `opsreq_pri3` int(5),
  `opsreq_pri4` int(5),
  `opsreq_pri5` int(5),
  `opsreq_pri6` int(5),
  `opsreq_pri7` int(5),
  `opsreq_pri8` int(5),
  `opsreq_pri9` int(5),
  `opsreq_pri10` int(5),
  `opsreq_pri11` int(5),
  `opsreq_pri12` int(5),
  `opsreq_opt1` char(1),
  `opsreq_opt2` char(1),
  `opsreq_opt3` char(1),
  `opsreq_opt4` char(1),
  `opsreq_opt5` char(1),
  `opsreq_opt6` char(1),
  `opsreq_opt7` char(1),
  `opsreq_opt8` char(1),
  `opsreq_any` char(1),
  `opsreq_number` int(5),
  `opsreq_comment` varchar(200),

  `mark_changed` varchar(1) default '',
  `last_mod_time` timestamp ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY  (`opsreq_id`),
  UNIQUE KEY `idx_eventtools_opsreq_person_email` (`opsreq_person_email`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------
--
-- Table structure for table `prefix_eventtools_opsreq_group`
--
-- Denotes an assignable group of attendees
--

DROP TABLE IF EXISTS `prefix_eventtools_opsreq_group`;

CREATE TABLE IF NOT EXISTS `prefix_eventtools_opsreq_group` (
  `opsreq_group_id` int(5) NOT NULL auto_increment,
  `opsreq_group_cycle_name` varchar(32),

  `mark_changed` varchar(1) default '',
  `last_mod_time` timestamp ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY  (`opsreq_group_id`),
  KEY `idx_eventtools_opsreq_group_cycle_name` (`opsreq_group_cycle_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------
--
-- Table structure for table `prefix_eventtools_opsreq_group_req_link`
--
-- Links an assignable group of attendees to a single session request
--

DROP TABLE IF EXISTS `prefix_eventtools_opsreq_group_req_link`;

CREATE TABLE IF NOT EXISTS `prefix_eventtools_opsreq_group_req_link` (
  `opsreq_group_req_link_id` int(5) NOT NULL auto_increment,
  `opsreq_group_id` int(5),
  `opsreq_id` int(5),

  `mark_changed` varchar(1) default '',
  `last_mod_time` timestamp ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY  (`opsreq_group_req_link_id`),
  KEY `idx_eventtools_opsreq_group_id` (`opsreq_group_id`),
  KEY `idx_eventtools_opsreq_id` (`opsreq_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------
--
-- Table structure for table `prefix_eventtools_opsreq_req_status`
--
-- A specific layout request and status
--    opsreq_group_req_link_id     points to a specific reg&group link, hence request
--    req_num                      1-8 request number
--    ops_id                       operating session link
--    status                       0 none, 1 assigned, 2 locked out
--

DROP TABLE IF EXISTS `prefix_eventtools_opsreq_req_status`;

CREATE TABLE IF NOT EXISTS `prefix_eventtools_opsreq_req_status` (
  `opsreq_req_status_id` int(5) NOT NULL auto_increment,
  `opsreq_group_req_link_id` int(5),
  `req_num` int(2),
  `ops_id` int(5),
  `status` int(2) DEFAULT 0,
  `forced` int(1) DEFAULT 0,

  `mark_changed` varchar(1) default '',
  `last_mod_time` timestamp ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY  (`opsreq_req_status_id`),
  KEY `idx_eventtools_opsreq_group_req_link_id` (`opsreq_group_req_link_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `prefix_eventtools_availability`
--

DROP TABLE IF EXISTS `prefix_eventtools_availability`;

CREATE TABLE IF NOT EXISTS `prefix_eventtools_availability` (
  `availability_id` int(5) NOT NULL auto_increment,
  `availability_person_id` int(5) NOT NULL,
  `availability_comment` varchar(32) default '',
  `availability_1_m` BOOLEAN default '1',
  `availability_1_a` BOOLEAN default '1',
  `availability_1_e` BOOLEAN default '1',
  `availability_2_m` BOOLEAN default '1',
  `availability_2_a` BOOLEAN default '1',
  `availability_2_e` BOOLEAN default '1',
  `availability_3_m` BOOLEAN default '1',
  `availability_3_a` BOOLEAN default '1',
  `availability_3_e` BOOLEAN default '1',
  `availability_4_m` BOOLEAN default '1',
  `availability_4_a` BOOLEAN default '1',
  `availability_4_e` BOOLEAN default '1',
  `availability_5_m` BOOLEAN default '1',
  `availability_5_a` BOOLEAN default '1',
  `availability_5_e` BOOLEAN default '1',
  `availability_6_m` BOOLEAN default '1',
  `availability_6_a` BOOLEAN default '1',
  `availability_6_e` BOOLEAN default '1',
  `availability_7_m` BOOLEAN default '1',
  `availability_7_a` BOOLEAN default '1',
  `availability_7_e` BOOLEAN default '1',
  `availability_8_m` BOOLEAN default '1',
  `availability_8_a` BOOLEAN default '1',
  `availability_8_e` BOOLEAN default '1',
  `availability_9_m` BOOLEAN default '1',
  `availability_9_a` BOOLEAN default '1',
  `availability_9_e` BOOLEAN default '1',

  PRIMARY KEY  (`availability_id`),
  KEY `idx_eventtools_availability_person_id` (`availability_person_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `prefix_eventtools_changelog`
--
DROP TABLE IF EXISTS `prefix_eventtools_changelog`;

CREATE TABLE prefix_eventtools_changelog (
	updated    TIMESTAMP 	  DEFAULT CURRENT_TIMESTAMP,
	user       varchar(255)   default NULL,
	host       varchar(255)   default NULL,
	operation  varchar(255)   default NULL,
	tab        varchar(255)   default NULL,
	rowkey     varchar(255)   default NULL,
	col        varchar(255)   default NULL,
	oldval     blob           default NULL,
	newval     blob           default NULL
);

-- --------------------------------------------------------


--
-- Trigger ensures availability is always available for view
--
-- DROP TRIGGER IF EXISTS prefix_eventtools_trigger_person_gets_availability;
-- delimiter |
-- CREATE TRIGGER prefix_eventtools_trigger_person_gets_availability
-- AFTER INSERT ON prefix_eventtools_people
-- FOR EACH ROW
-- BEGIN
--     INSERT INTO prefix_eventtools_availability SET availability_person_id = NEW.person_id;
-- END;
-- |

