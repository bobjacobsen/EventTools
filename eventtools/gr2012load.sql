--
-- Loading data for table `gr2012_eventtools_constrain_scale`
--

INSERT INTO `gr2012_eventtools_constrain_scale` (`constrain_scale_value`) VALUES
('HO'),
('O'),
('N');

--
-- Loading data for table `gr2012_eventtools_accessibility_codes`
--

INSERT INTO `gr2012_eventtools_accessibility_codes` (`accessibility_code`, `accessibility_name`, `accessibility_display`, `acc_mark_changed`, `acc_last_mod_time`) VALUES
(1, '(1) Not handicapped accessible', 'Not handicapped accessible', '', NULL),
(2, '(2) Several steps and/or duck-under(s)', 'Several steps and/or duck-under(s)', '', NULL),
(3, '(3) Average house (1-2 steps)', 'Average house (1-2 steps)', '', NULL),
(4, '(4) No hazards', 'No hazards', '', NULL),
(5, '(5) Special adaptations for handicapped', 'Special adaptations for handicapped', '', NULL),
(6, '(6) Unknown/not entered', 'Accessibility unknown/not entered', '', NULL);

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

