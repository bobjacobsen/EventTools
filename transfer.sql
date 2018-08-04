--
-- Copy the production tables to the test ones
--
-- Used to capture permanent data changes
-- for testing
--
-- Not all tables copied, see end
--

DELETE FROM test_eventtools_event_status_values; 
INSERT INTO test_eventtools_event_status_values 
    SELECT * FROM prod_eventtools_event_status_values;

DELETE FROM test_eventtools_accessibility_codes; 
INSERT INTO test_eventtools_accessibility_codes 
    SELECT * FROM prod_eventtools_accessibility_codes;

DELETE FROM test_eventtools_clinic_locations; 
INSERT INTO test_eventtools_clinic_locations 
    SELECT * FROM prod_eventtools_clinic_locations;

DELETE FROM test_eventtools_layouts; 
INSERT INTO test_eventtools_layouts 
    SELECT * FROM prod_eventtools_layouts;

DELETE FROM test_eventtools_layout_tours; 
INSERT INTO test_eventtools_layout_tours 
    SELECT * FROM prod_eventtools_layout_tours;

DELETE FROM test_eventtools_layout_tour_links; 
INSERT INTO test_eventtools_layout_tour_links 
    SELECT * FROM prod_eventtools_layout_tour_links;

DELETE FROM test_eventtools_clinics; 
INSERT INTO test_eventtools_clinics 
    SELECT * FROM prod_eventtools_clinics;

DELETE FROM test_eventtools_clinic_tags; 
INSERT INTO test_eventtools_clinic_tags 
    SELECT * FROM prod_eventtools_clinic_tags;

DELETE FROM test_eventtools_misc_events; 
INSERT INTO test_eventtools_misc_events 
    SELECT * FROM prod_eventtools_misc_events;

DELETE FROM test_eventtools_misc_event_tags; 
INSERT INTO test_eventtools_misc_event_tags 
    SELECT * FROM prod_eventtools_misc_event_tags;

DELETE FROM test_eventtools_general_tours; 
INSERT INTO test_eventtools_general_tours 
    SELECT * FROM prod_eventtools_general_tours;

DELETE FROM test_eventtools_opsession; 
INSERT INTO test_eventtools_opsession 
    SELECT * FROM prod_eventtools_opsession;

DELETE FROM test_eventtools_opsession_req; 
INSERT INTO test_eventtools_opsession_req 
    SELECT * FROM prod_eventtools_opsession_req;

-- 
-- tables not directly copied start here
--

-- don't copy log, just clear it
DELETE FROM test_eventtools_changelog; 

-- Don't change the user access controls
-- DELETE FROM test_eventtools_users; 
-- INSERT INTO test_eventtools_users 
--     SELECT * FROM prod_eventtools_users;
