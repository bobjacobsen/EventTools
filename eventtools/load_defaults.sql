
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `x2011west`
--

-- --------------------------------------------------------

-- 
-- You can do a bulk replace of 'prefix_eventtools_'
-- to convert to 'prefix_eventtools_' and vice versa
--

-- --------------------------------------------------------

--
-- Loading data for table `prefix_eventtools_event_status_values`
--

DELETE FROM `prefix_eventtools_event_status_values`; 
INSERT INTO `prefix_eventtools_event_status_values` (`event_status_id`, `event_status_code`, `event_status_name`, `event_status_display`) VALUES
(7, 0, '(0) Unknown', '(unknown)'),
(8, 1, '(1) Some data entered', '(incomplete)'),
(9, 2, '(2) Waiting approval', '(wait approval)'),
(10, 3, '(3) Approved, no cart link', ''),
(11, 4, '(4) Visible, cart link shown', '(cart link)'),
(12, 5, '(5) Sold Out', 'Sold Out'),
(13, 6, '(6) Cancelled', 'Cancelled');

-- --------------------------------------------------------

--
-- Loading data for table `prefix_eventtools_handicapped_values`
--

DELETE FROM `prefix_eventtools_accessibility_codes`;
INSERT INTO `prefix_eventtools_accessibility_codes` (`accessibility_code`, `accessibility_name`, `accessibility_display`) VALUES
(1, '(1) Handicapped hostile', 'Handicapped hostile'),
(2, '(2) Several steps and/or duck-under(s)', 'Several steps and/or duck-under(s)'),
(3, '(3) Average house (1-2 steps)', 'Average house (1-2 steps)'),
(4, '(4) No hazards', 'No hazards'),
(5, '(5) Special adaptations for handicapped', 'Special adaptations for handicapped'),
(6, '(6) Unknown/not entered', 'Accessibility unknown/not entered');

-- --------------------------------------------------------

--
-- Loading data for table `prefix_eventtools_clinic_locations`
--

DELETE FROM `prefix_eventtools_clinic_locations`;
INSERT INTO `prefix_eventtools_clinic_locations` (`id`, `location_code`, `location_name`) VALUES
(7, 0, '(0) Unknown'),
(8, 1, 'Room 1'),
(9, 2, 'Room 2'),
(10, 3, 'Room 3'),
(11, 4, 'Room 4'),
(12, 5, 'Room 5'),
(13, 6, 'Room 6'),
(14, 7, 'Room 7'),
(15, 8, 'Room 8'),
(16, 9, 'Non-Rail'),
(17, 10, 'Auction Room'),
(18, 11, 'Convention Center'),
(19, 12, 'TBA');

-- --------------------------------------------------------
-- --------------------------------------------------------

