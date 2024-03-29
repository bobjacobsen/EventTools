
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `database`
-- Prefix:   `prefix_`
--

-- --------------------------------------------------------

-- 
-- Add/update necessary columns in tables.
-- 
-- To be used if an Git update changes any .sql files
--
-- After this, you MUST rerun the define_views.sql file
--
-- Should not lose existing date, but make
-- sure you have a good backup first.
--
-- --------------------------------------------------------

--
-- Table structure for table `prefix_eventtools_users`
--

--
-- To check for missing default address links:
-- SELECT * FROM prefix_customers LEFT JOIN prefix_address_book USING (customers_id);
--   (optionally with) WHERE customers_default_address_id = NULL;
--
-- To correct those:
-- UPDATE (prefix_customers LEFT JOIN prefix_address_book USING (customers_id)) SET customers_default_address_id = address_book_id;
--

--
--  Necessary table updates
--

ALTER TABLE `prefix_customers`                MODIFY customers_email_address varchar(96) NOT NULL default '';

ALTER TABLE `prefix_eventtools_layouts`       MODIFY layout_owner_email      varchar(96) default '';
ALTER TABLE `prefix_eventtools_layouts`       MODIFY layout_scale            varchar(64) default NULL;
ALTER TABLE `prefix_eventtools_layouts`       MODIFY layout_gauge            varchar(64) default NULL;
ALTER TABLE `prefix_eventtools_layouts`       MODIFY layout_owner_firstname  varchar(64) default '';
ALTER TABLE `prefix_eventtools_layouts`       MODIFY layout_owner_lastname   varchar(64) default '';
ALTER TABLE `prefix_eventtools_layouts`       MODIFY layout_fidelity         varchar(64) default '';
ALTER TABLE `prefix_eventtools_layouts`       MODIFY layout_rigor            varchar(64) default '';
ALTER TABLE `prefix_eventtools_layouts`       MODIFY layout_documentation    varchar(64) default '';
ALTER TABLE `prefix_eventtools_layouts`       MODIFY layout_session_pace     varchar(64) default '';
ALTER TABLE `prefix_eventtools_layouts`       MODIFY layout_car_forwarding   varchar(64) default '';
ALTER TABLE `prefix_eventtools_layouts`       MODIFY layout_tone             varchar(64) default '';
ALTER TABLE `prefix_eventtools_layouts`       MODIFY layout_dispatched_by1   varchar(64) default '';
ALTER TABLE `prefix_eventtools_layouts`       MODIFY layout_dispatched_by2   varchar(64) default '';
ALTER TABLE `prefix_eventtools_layouts`       MODIFY layout_communications   varchar(64) default '';

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

-- --------------------------------------------------------
-- Lengthen URL fields
-- --------------------------------------------------------
ALTER TABLE `prefix_eventtools_layouts`       MODIFY `layout_local_url`      varchar(128) default '';
ALTER TABLE `prefix_eventtools_layouts`       MODIFY `layout_photo_url`      varchar(128) default '';

-- --------------------------------------------------------
-- Add additional option names - May be duplicate
-- --------------------------------------------------------
ALTER TABLE `prefix_eventtools_customer_options` ADD `customer_option_session_report_name`       varchar(40) default '';

-- --------------------------------------------------------
-- After this, you MUST rerun the define_views.sql file
-- --------------------------------------------------------

