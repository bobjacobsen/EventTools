
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `database`
-- Prefix:   `prefix_`
--

-- --------------------------------------------------------

-- 
-- Add necessary columns to Zen Cart customers table
--

-- --------------------------------------------------------

--
-- Table structure for table `prefix_eventtools_users`
--


ALTER TABLE `prefix_customers` ADD `customers_nmra_num` varchar(8) default NULL;
ALTER TABLE `prefix_customers` ADD `customers_nmra_expires` date default NULL;
ALTER TABLE `prefix_customers` ADD `customers_emerg_contact_name` varchar(60) default NULL;
ALTER TABLE `prefix_customers` ADD `customers_emerg_contact_phone` varchar(32) default NULL;
ALTER TABLE `prefix_customers` ADD `customers_first_name_badge` varchar(32) default NULL;

