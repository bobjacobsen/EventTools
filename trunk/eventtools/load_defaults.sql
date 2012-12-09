
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `database`
-- Prefix:   `prefix_`
--

-- --------------------------------------------------------

-- 
-- You can do a bulk replace of 'prefix_eventtools_'
-- to convert to 'prefix_eventtools_' and vice versa
--
-- Load some starting defaults for various
-- EventTools tables. 
--
-- For a sample database, see the example_load.db file
--
-- Running this is the last step in setting
-- up the EventTools databases.

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
(13, 6, '(6) Cancelled', 'Cancelled'),
(14, 40, '(40) Not available', 'Not Available'),
(15, 50, '(50) Offered as bonus session', 'Bonus'),
(16, 60, '(60) Offering Session(s)', 'Sessions');

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
(19, 12, 'TBA');

-- --------------------------------------------------------

--
-- Loading default data for constraint tables
--

INSERT INTO `prefix_eventtools_constrain_scale` (`constrain_value`) VALUES
('Z'),
('S'),
('HO'),
('G/F'),
('O'),
('N');

INSERT INTO `prefix_eventtools_constrain_gauge` (`constrain_value`) VALUES
('Std.'),
('N2'),
('N30'),
('N3'),
('Dual');

INSERT INTO `prefix_eventtools_constrain_era` (`constrain_value`) VALUES
('Early'),
('Golden Age'),
('Transition'),
('First Gen.'),
('Mining'),
('Other');

INSERT INTO `prefix_eventtools_constrain_class` (`constrain_value`) VALUES
('Mainline'),
('Bridge Line'),
('Short Line'),
('Branch Line'),
('Industrial'),
('Other');

INSERT INTO `prefix_eventtools_constrain_theme` (`constrain_value`) VALUES
('Gen. Freight'),
('Coal'),
('Logging'),
('Passenger'),
('Mining'),
('Other');

INSERT INTO `prefix_eventtools_constrain_locale` (`constrain_value`) VALUES
('East'),
('West'),
('Midwest'),
('North'),
('South'),
('Pac. NW'),
('Other');

INSERT INTO `prefix_eventtools_constrain_scenery` (`constrain_value`) VALUES
('0%'),
('20%'),
('40%'),
('60%'),
('80%'),
('100%');

INSERT INTO `prefix_eventtools_constrain_plan_type` (`constrain_value`) VALUES
('Single Level'),
('Multi-level');

INSERT INTO `prefix_eventtools_constrain_ops_scheme` (`constrain_value`) VALUES
('Mainline Running'),
('Various Types'),
('Switching');

INSERT INTO `prefix_eventtools_constrain_control` (`constrain_value`) VALUES
('Lenz'),
('NCE'),
('Digitrax'),
('DCC (unknown type)'),
('Analog');

INSERT INTO `prefix_eventtools_constrain_fidelity` (`constrain_value`) VALUES
('Unknown'), 
('Mix of Eras'), 
('Evoke Era'),
('Few Comp.'), 
('Full');

INSERT INTO `prefix_eventtools_constrain_rigor` (`constrain_value`) VALUES
('Unknown'), 
('Attempt'), 
('Purposeful'),
('Adheres'),
('Tight');

INSERT INTO `prefix_eventtools_constrain_documentation` (`constrain_value`) VALUES
('Unknown'),
('Moderate'), 
('High');

INSERT INTO `prefix_eventtools_constrain_session_pace` (`constrain_value`) VALUES
('Unknown'), 
('Sequence'), 
('Fast Clock'), 
('Real Time');

INSERT INTO `prefix_eventtools_constrain_car_forwarding` (`constrain_value`) VALUES
('Unknown'),
('CC & WB'),
('Switchlist'),
('Computer'),
('Tab on Car'),
('Car-for-Car') ;

INSERT INTO `prefix_eventtools_constrain_tone` (`constrain_value`) VALUES
('Unknown'), 
('Casual'), 
('Disciplined');

INSERT INTO `prefix_eventtools_constrain_dispatched_by1` (`constrain_value`) VALUES
('Unknown'), 
('TT&TO'), 
('TWC'), 
('DTC'),
('Voice'), 
('CTC'), 
('Yard Limits'), 
('251'), 
('N/A');

INSERT INTO `prefix_eventtools_constrain_dispatched_by2` (`constrain_value`) VALUES
('Unknown'), 
('TT&TO'), 
('TWC'), 
('DTC'),
('Voice'), 
('CTC'), 
('Yard Limits'), 
('251'), 
('N/A');

INSERT INTO `prefix_eventtools_constrain_communications` (`constrain_value`) VALUES
('Unknown'), 
('Voice'), 
('Telephone'), 
('5ch Radio'), 
('Radio'), 
('FRS'), 
('Signals'), 
('N/A'), 
('TBD');

--
-- Loading data for table `prefix_eventtools_users`
--

INSERT INTO `prefix_eventtools_users` (`user_id`, `user_name`, `user_pwd`, `user_key`, `user_email_log_skip`) VALUES
(8, 'jacobsen@berkeley.edu', '', '*', '1');

