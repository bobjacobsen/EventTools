--
-- Loading data for constraint tables
--

INSERT INTO `gr2012_eventtools_constrain_scale` (`constrain_value`) VALUES
('Z'),
('S'),
('HO'),
('G/F'),
('O'),
('N');

INSERT INTO `gr2012_eventtools_constrain_gauge` (`constrain_value`) VALUES
('Std.'),
('N2'),
('N30'),
('N3'),
('Dual');

INSERT INTO `gr2012_eventtools_constrain_era` (`constrain_value`) VALUES
('Early'),
('Golden Age'),
('Transition'),
('First Gen.'),
('Mining'),
('Other');

INSERT INTO `gr2012_eventtools_constrain_class` (`constrain_value`) VALUES
('Mainline'),
('Bridge Line'),
('Short Line'),
('Branch Line'),
('Industrial'),
('Other');

INSERT INTO `gr2012_eventtools_constrain_theme` (`constrain_value`) VALUES
('Gen. Freight'),
('Coal'),
('Logging'),
('Passenger'),
('Mining'),
('Other');

INSERT INTO `gr2012_eventtools_constrain_locale` (`constrain_value`) VALUES
('East'),
('West'),
('Midwest'),
('North'),
('South'),
('Pac. NW'),
('Other');

INSERT INTO `gr2012_eventtools_constrain_scenery` (`constrain_value`) VALUES
('0%'),
('20%'),
('40%'),
('60%'),
('80%'),
('100%');

INSERT INTO `gr2012_eventtools_constrain_plan_type` (`constrain_value`) VALUES
('Single Level'),
('Multi-level');

INSERT INTO `gr2012_eventtools_constrain_ops_scheme` (`constrain_value`) VALUES
('Mainline Running'),
('Various Types'),
('Switching');

INSERT INTO `gr2012_eventtools_constrain_control` (`constrain_value`) VALUES
('Lenz'),
('NCE'),
('Digitrax'),
('DCC (unknown type)'),
('Analog');

INSERT INTO `gr2012_eventtools_constrain_fidelity` (`constrain_value`) VALUES
('Unknown'), 
('Mix of Eras'), 
('Evoke Era'),
('Few Comp.'), 
('Full');

INSERT INTO `gr2012_eventtools_constrain_rigor` (`constrain_value`) VALUES
('Unknown'), 
('Attempt'), 
('Purposeful'),
('Adheres'),
('Tight');

INSERT INTO `gr2012_eventtools_constrain_documentation` (`constrain_value`) VALUES
('Unknown'),
('Moderate'), 
('High');

INSERT INTO `gr2012_eventtools_constrain_session_pace` (`constrain_value`) VALUES
('Unknown'), 
('Sequence'), 
('Fast Clock'), 
('Real Time');

INSERT INTO `gr2012_eventtools_constrain_car_forwarding` (`constrain_value`) VALUES
('Unknown'),
('CC & WB'),
('Switchlist'),
('Computer'),
('Tab on Car'),
('Car-for-Car') ;

INSERT INTO `gr2012_eventtools_constrain_tone` (`constrain_value`) VALUES
('Unknown'), 
('Casual'), 
('Disciplined');

INSERT INTO `gr2012_eventtools_constrain_dispatched_by1` (`constrain_value`) VALUES
('Unknown'), 
('TT&TO'), 
('TWC'), 
('DTC'),
('Voice'), 
('CTC'), 
('Yard Limits'), 
('251'), 
('N/A');

INSERT INTO `gr2012_eventtools_constrain_dispatched_by2` (`constrain_value`) VALUES
('Unknown'), 
('TT&TO'), 
('TWC'), 
('DTC'),
('Voice'), 
('CTC'), 
('Yard Limits'), 
('251'), 
('N/A');

INSERT INTO `gr2012_eventtools_constrain_communications` (`constrain_value`) VALUES
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
-- Loading data for table `gr2012_eventtools_accessibility_codes`
--

INSERT INTO `gr2012_eventtools_accessibility_codes` (`accessibility_code`, `accessibility_name`, `accessibility_display`, `acc_mark_changed`, `acc_last_mod_time`) VALUES
(1, '(1) Not handicapped accessible', 'Not handicapped accessible', '', NULL),
(2, '(2) Several steps and/or duck-under(s)', 'Several steps and/or duck-under(s)', '', NULL),
(3, '(3) Average house (1-2 steps)', 'Average house (1-2 steps)', '', NULL),
(4, '(4) No hazards', 'No hazards', '', NULL),
(5, '(5) Special adaptations for handicapped', 'Special adaptations for handicapped', '', NULL),
(0, '(0) Unknown/not entered', 'Accessibility unknown/not entered', '', NULL);

--
-- Loading data for table `prod_eventtools_event_status_values`
--

INSERT INTO `gr2012_eventtools_event_status_values` (`event_status_id`, `event_status_code`, `event_status_name`, `event_status_display`, `status_mark_changed`, `status_last_mod_time`) VALUES
(7, 0, 'Unknown', 'Proposed', '', NULL),
(13, 20, 'Incomplete, Some Data Entered', 'Proposed', '', NULL),
(14, 30, 'Waiting Approval', 'Under Construction', '', NULL),
(16, 40, 'Approved: Show Under Construction', 'Under Construction', '', NULL),
(17, 60, 'Approved: (show sales link)', 'Click to Order', '', NULL),
(20, 62, 'Approved: (no sales link)', '', '', NULL),
(18, 70, 'Sold Out', 'Sold Out', '', NULL),
(19, 80, 'Cancelled', 'Cancelled', '', NULL);

--
-- Loading data for table `gr2012_eventtools_users`
--

INSERT INTO `gr2012_eventtools_users` (`user_id`, `user_name`, `user_pwd`, `user_key`, `user_email_log_skip`) VALUES
(8, 'jacobsen@berkeley.edu', '', '*', '1'),
(23, 'ramsler@charter.net', '', '', '0');

