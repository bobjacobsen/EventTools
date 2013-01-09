
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `database`
-- Prefix:   `prefix_`
--

-- --------------------------------------------------------

-- 
-- Add/update necessary columns in tables.
-- 
-- To be used if an SVN update changes any .sql files
--
-- Should not lose existing date, but make
-- sure you have a good backup first.
--
-- $Revision$
--
-- --------------------------------------------------------

--
-- Table structure for table `prefix_eventtools_users`
--


-- ALTER TABLE `prefix_customers` ADD `customers_nmra_num` varchar(8) default NULL;

ALTER TABLE `prefix_customers`                MODIFY customers_email_address varchar(96) NOT NULL default '';
ALTER TABLE `prefix_eventtools_layouts`       MODIFY layout_owner_email      varchar(96) default '';
ALTER TABLE `prefix_eventtools_clinics`       MODIFY clinic_presenter_email  varchar(96) default '';
ALTER TABLE `prefix_eventtools_people`        MODIFY person_email            varchar(96) NOT NULL default '';
ALTER TABLE `prefix_eventtools_opsession_req` MODIFY opsreq_person_email     varchar(96);

-- --------------------------------------------------------
-- Depending on the version of your database, the following
-- may throw "Duplicate column" errors.  These are normal
-- and can be ignored.
-- --------------------------------------------------------

ALTER TABLE `prefix_eventtools_opsession_req` ADD   `opsreq_priority`        int(5) default '0';

ALTER TABLE `prefix_eventtools_layouts`       ADD   `layout_num_ops`         varchar(5) default NULL;
ALTER TABLE `prefix_eventtools_layouts`       ADD   `layout_distance`        varchar(25) default '';

ALTER TABLE `prefix_eventtools_opsession_req` ADD   `opsreq_opt5`            char(1);
ALTER TABLE `prefix_eventtools_opsession_req` ADD   `opsreq_opt6`            char(1);
ALTER TABLE `prefix_eventtools_opsession_req` ADD   `opsreq_opt7`            char(1);
ALTER TABLE `prefix_eventtools_opsession_req` ADD   `opsreq_opt8`            char(1);

