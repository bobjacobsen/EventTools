
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `database`
-- Prefix:   `prefix_`
--

-- --------------------------------------------------------

-- 
-- Update size of layout fields.
-- 
-- This is also present in the update_tables.sql file, so 
-- you don't need to run both
--
-- --------------------------------------------------------

ALTER TABLE `prefix_eventtools_layouts`    MODIFY layout_scale           varchar(64) default NULL;
ALTER TABLE `prefix_eventtools_layouts`    MODIFY layout_gauge           varchar(64) default NULL;

ALTER TABLE `prefix_eventtools_layouts`    MODIFY layout_owner_firstname varchar(64) default '';
ALTER TABLE `prefix_eventtools_layouts`    MODIFY layout_owner_lastname  varchar(64) default '';

ALTER TABLE `prefix_eventtools_layouts`    MODIFY layout_fidelity        varchar(64) default '';
ALTER TABLE `prefix_eventtools_layouts`    MODIFY layout_rigor           varchar(64) default '';
ALTER TABLE `prefix_eventtools_layouts`    MODIFY layout_documentation   varchar(64) default '';
ALTER TABLE `prefix_eventtools_layouts`    MODIFY layout_session_pace    varchar(64) default '';
ALTER TABLE `prefix_eventtools_layouts`    MODIFY layout_car_forwarding  varchar(64) default '';
ALTER TABLE `prefix_eventtools_layouts`    MODIFY layout_tone            varchar(64) default '';
ALTER TABLE `prefix_eventtools_layouts`    MODIFY layout_dispatched_by1  varchar(64) default '';
ALTER TABLE `prefix_eventtools_layouts`    MODIFY layout_dispatched_by2  varchar(64) default '';
ALTER TABLE `prefix_eventtools_layouts`    MODIFY layout_communications  varchar(64) default '';


-- --------------------------------------------------------
-- After this, you MUST rerun the define_views.sql file
-- --------------------------------------------------------

