
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
-- This defines the MySQL views across the 
-- the tables defined by defined_db.sql
--
-- It should be executed after any changes to the 
-- table structure.
--

-- --------------------------------------------------------


--
-- View section, done last to ensure tables exist
--

-- Layout tours, plus any contained layouts
CREATE OR REPLACE VIEW prefix_eventtools_layout_tour_with_layouts
AS SELECT prefix_eventtools_layout_tours.*, prefix_eventtools_layouts.*, 
                prefix_eventtools_layout_tour_links.layout_tour_link_order, prefix_eventtools_event_status_values.*, 
                prefix_eventtools_accessibility_codes.*
        FROM ((
                (prefix_eventtools_layout_tours
                    LEFT JOIN prefix_eventtools_layout_tour_links
                    ON prefix_eventtools_layout_tour_links.tour_number = prefix_eventtools_layout_tours.id
                )  
                    LEFT JOIN prefix_eventtools_layouts 
                    ON prefix_eventtools_layouts.layout_id = prefix_eventtools_layout_tour_links.layout_id
                )
                    LEFT JOIN prefix_eventtools_event_status_values
                    ON prefix_eventtools_layout_tours.status_code = prefix_eventtools_event_status_values.event_status_code
                )
                    LEFT JOIN prefix_eventtools_accessibility_codes
                    ON layout_accessibility = accessibility_code;
                

-- Layouts, plus any tours they're on, and accessibility
CREATE OR REPLACE VIEW prefix_eventtools_layout_with_layout_tours
AS  SELECT  prefix_eventtools_layout_tours.*,
            prefix_eventtools_layouts.*, prefix_eventtools_accessibility_codes.*, prefix_eventtools_event_status_values.*
    FROM  ((
            (prefix_eventtools_layouts 
                LEFT JOIN prefix_eventtools_layout_tour_links
                ON prefix_eventtools_layouts.layout_id = prefix_eventtools_layout_tour_links.layout_id
            )  
                LEFT JOIN prefix_eventtools_layout_tours
                ON prefix_eventtools_layout_tour_links.tour_number = prefix_eventtools_layout_tours.id
            )
                LEFT JOIN prefix_eventtools_accessibility_codes
                ON layout_accessibility = accessibility_code
            )
                LEFT JOIN prefix_eventtools_event_status_values
                ON prefix_eventtools_layouts.layout_status_code = prefix_eventtools_event_status_values.event_status_code;
            
-- General tours, plus any contained status
CREATE OR REPLACE VIEW prefix_eventtools_general_tour_with_status
AS SELECT prefix_eventtools_general_tours.*, prefix_eventtools_event_status_values.*
        FROM prefix_eventtools_general_tours
            LEFT JOIN prefix_eventtools_event_status_values
            ON prefix_eventtools_general_tours.status_code = prefix_eventtools_event_status_values.event_status_code;


-- Clinics, plus any applied tags

CREATE OR REPLACE VIEW prefix_eventtools_clinics_with_tags
AS SELECT prefix_eventtools_clinics.*, prefix_eventtools_clinic_tags.tag_name, prefix_eventtools_clinic_locations.location_name
        FROM (
            prefix_eventtools_clinics
            LEFT JOIN prefix_eventtools_clinic_tags
            ON prefix_eventtools_clinics.id = clinic_tag_clinic_number
            )
            LEFT JOIN prefix_eventtools_clinic_locations
            ON clinic_location_code = location_code
            ;


-- Misc Events, plus any applied tags

CREATE OR REPLACE VIEW prefix_eventtools_misc_events_with_tags
AS SELECT prefix_eventtools_misc_events.*, prefix_eventtools_misc_event_tags.tag_name, prefix_eventtools_clinic_locations.location_name
        FROM (
            prefix_eventtools_misc_events
            LEFT JOIN prefix_eventtools_misc_event_tags
            ON prefix_eventtools_misc_events.id = misc_event_tag_misc_event_number
            )
            LEFT JOIN prefix_eventtools_clinic_locations
            ON misc_location_code = location_code
            ;


-- Person, plus any availability tags
CREATE OR REPLACE VIEW prefix_eventtools_person_with_availability
AS SELECT * FROM prefix_eventtools_people LEFT JOIN prefix_eventtools_availability
    ON prefix_eventtools_people.person_id = prefix_eventtools_availability.availability_person_id;

-- Op Session, plus layout info if present
CREATE OR REPLACE VIEW prefix_eventtools_opsession_with_layouts
AS SELECT * FROM prefix_eventtools_opsession LEFT JOIN prefix_eventtools_layouts
    ON prefix_eventtools_opsession.ops_layout_id = prefix_eventtools_layouts.layout_id;


-- Op Session name for use in requests
CREATE OR REPLACE VIEW prefix_eventtools_opsession_name
AS SELECT ops_id, start_date, presenting_time, spaces, distance, travel_time, location, ops_layout_id, status_code, 
            IF((ops_layout_id2!=0),CONCAT(l1.layout_owner_lastname,' / ',l2.layout_owner_lastname),CONCAT(l1.layout_owner_lastname,' ',l1.layout_name)) AS show_name,
            l1.layout_owner_lastname AS layout_owner_lastname1, l2.layout_owner_lastname AS layout_owner_lastname2,
            l1.layout_owner_firstname AS layout_owner_firstname1, l2.layout_owner_firstname AS layout_owner_firstname2,
            l1.layout_local_url AS layout_local_url1, l2.layout_local_url AS layout_local_url2,
            l1.layout_photo_url AS layout_photo_url1, l2.layout_photo_url AS layout_photo_url2,
            l1.layout_name AS layout_name1, l2.layout_name AS layout_name2,
            l1.layout_id AS layout_id1, l2.layout_id AS layout_id2
    FROM (
        prefix_eventtools_opsession LEFT JOIN prefix_eventtools_layouts l1
        ON prefix_eventtools_opsession.ops_layout_id = l1.layout_id
        )
        LEFT JOIN prefix_eventtools_layouts l2
        ON prefix_eventtools_opsession.ops_layout_id2 = l2.layout_id;

CREATE OR REPLACE VIEW prefix_eventtools_ops_group_names
AS SELECT customers_firstname, customers_lastname, customers_create_date, customers_updated_date,
            opsreq_person_email, opsreq_priority, prefix_eventtools_opsreq_group.opsreq_group_id, 
            opsreq_group_cycle_name, opsreq_comment, prefix_eventtools_opsreq_group_req_link.opsreq_id,
            opsreq_group_req_link_id, entry_city, entry_state, opsreq_number, opsreq_any
        FROM (((
        prefix_eventtools_opsession_req LEFT JOIN prefix_customers
        ON prefix_eventtools_opsession_req.opsreq_person_email = prefix_customers.customers_email_address
        ) JOIN prefix_eventtools_opsreq_group_req_link
        ON prefix_eventtools_opsession_req.opsreq_id = prefix_eventtools_opsreq_group_req_link.opsreq_id
        ) JOIN prefix_eventtools_opsreq_group
        ON prefix_eventtools_opsreq_group_req_link.opsreq_group_id = prefix_eventtools_opsreq_group.opsreq_group_id
        ) LEFT JOIN prefix_address_book
        ON prefix_customers.customers_default_address_id = prefix_address_book.address_book_id
        ;

-- op session request assignment with session name info
CREATE OR REPLACE VIEW prefix_eventtools_ops_group_session_assignments
AS SELECT customers_firstname, customers_lastname, customers_create_date, customers_updated_date,
            opsreq_person_email, opsreq_priority, prefix_eventtools_ops_group_names.opsreq_group_id, 
            opsreq_group_cycle_name, opsreq_comment, prefix_eventtools_ops_group_names.opsreq_id, opsreq_req_status_id, status,
            prefix_eventtools_ops_group_names.opsreq_group_req_link_id, req_num, prefix_eventtools_opsreq_req_status.ops_id,
            start_date, spaces, show_name, entry_city, entry_state, opsreq_number, opsreq_any, ops_layout_id 
    FROM (prefix_eventtools_ops_group_names
        LEFT JOIN prefix_eventtools_opsreq_req_status
        ON prefix_eventtools_ops_group_names.opsreq_group_req_link_id = prefix_eventtools_opsreq_req_status.opsreq_group_req_link_id
        ) LEFT JOIN prefix_eventtools_opsession_name
        ON prefix_eventtools_opsreq_req_status.ops_id = prefix_eventtools_opsession_name.ops_id
    ;

CREATE OR REPLACE VIEW prefix_eventtools_opsession_req_with_user_info
AS SELECT prefix_eventtools_opsession_req.*,  prefix_customers.*, 
            prefix_address_book.entry_street_address, prefix_address_book.entry_city, prefix_address_book.entry_state, prefix_address_book.entry_postcode
        FROM (
        prefix_eventtools_opsession_req LEFT JOIN prefix_customers
        ON prefix_eventtools_opsession_req.opsreq_person_email = prefix_customers.customers_email_address
        ) LEFT JOIN prefix_address_book
        ON prefix_customers.customers_id = prefix_address_book.customers_id
        ;

-- customer name with option values
CREATE OR REPLACE VIEW prefix_eventtools_customer_cross_options_and_values
AS SELECT prefix_customers.*, prefix_eventtools_customer_options.*, 
            prefix_eventtools_customer_option_values.customer_option_value_value, prefix_eventtools_customer_option_values.customer_option_value_date
        FROM (
        prefix_customers LEFT JOIN prefix_eventtools_customer_option_values
        ON prefix_customers.customers_id = prefix_eventtools_customer_option_values.customers_id
        ) LEFT JOIN prefix_eventtools_customer_options
        ON prefix_eventtools_customer_option_values.customer_option_id = prefix_eventtools_customer_options.customer_option_id
        ;


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

