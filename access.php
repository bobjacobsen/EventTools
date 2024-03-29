<?php

// provide database access constants for phpMyEdit
// default values - only these should show in SVN
$opts['hn'] = '127.0.0.1';  // MySQL host name
$opts['un'] = 'user_name';       // MySQL user name
$opts['pw'] = 'user_password';           // MySQL password

$opts['db'] = 'database';               // database name to reference
$event_tools_db_prefix = 'prefix_';     // prefix on all table names

// Basic event name for e.g. page headers
$event_tools_event_name = "(Uncustomised)";

// Require EventTools user ID match, password authentification?
$event_tools_require_user_id             = TRUE;
$event_tools_require_user_authenticate   = FALSE;

// Require customer ID match, password authentification?
$event_tools_require_customer_id         = TRUE;        // TRUE checks account exists
$event_tools_require_customer_authenticate = FALSE;

// to get new and changed entries logged & reported via email, uncomment these lines
$opts['logtable'] = $event_tools_db_prefix.'eventtools_changelog';
$event_tools_notify_email_address = 'rgj1927@pacbell.net';
$event_tools_notify_email_prefix = $event_tools_event_name;

// Registrar email for notification from (sample) pre-registration and registration forms
$event_tools_registrar_email_address = 'rgj1927@pacbell.net';
$event_tools_registrar_email_prefix = $event_tools_event_name;

// Event timing: These provide defaults for data entry and display structure.
$event_tools_default_event_start_date   = '2021-07-06 00:00:00';  // default for entry, should be close but not exactly in schedule; c.f. utiltiies.php
$event_tools_default_event_end_date     = '2021-07-06 01:00:00';  // default for entry, should be close but not exactly in schedule

$event_tools_dates                      = array( // default days to display
                                            "2021-07-06","2021-07-07","2021-07-08","2021-07-09", "2021-07-10");

$event_tools_clinics_start_times        = array(  // c.f. locale.php
                                            "08:00","09:00","10:00","11:00","12:00",
                                            "13:00","14:00","15:00","16:00","17:00",
                                            "19:00","20:00","21:00","22:00");

$event_tools_misc_event_start_times     = $event_tools_clinics_start_times; // can also be specified separately

$event_tools_layout_tour_start_times    = array( // c.f. format_layout_tours_by_time.php
                                            "08:00:00", "09:30:00", "11:00:00", "13:00:00", "14:30:00",
                                            "16:00:00", "17:00:00", "19:00:00", "20:30:00", "22:00:00");

$event_tools_layout_tour_dates          = array( // c.f. format_layout_tours_by_time.php; might includes extras before/after main dates
                                            "2011-07-01", "2011-07-02", "2011-07-03",
                                            "2011-07-04", "2011-07-05", "2011-07-06",
                                            "2011-07-07", "2011-07-08", "2011-07-09");


// optional components - TRUE means present (default)

$event_tools_option_general_tours       = FALSE;
$event_tools_option_layout_tours        = FALSE;
$event_tools_option_other_events        = FALSE;
$event_tools_option_layouts             = TRUE;
$event_tools_option_clinics             = FALSE;
$event_tools_option_op_sessions         = TRUE;


// To constrain various info in layout entry/edit pages
// to only the values in database tables, set the following to TRUE

$event_tools_constrain_scale            = FALSE;
$event_tools_constrain_gauge            = FALSE;
$event_tools_constrain_era              = FALSE;
$event_tools_constrain_class            = FALSE;
$event_tools_constrain_theme            = FALSE;
$event_tools_constrain_locale           = FALSE;

$event_tools_constrain_scenery          = FALSE;
$event_tools_constrain_plan_type        = FALSE;
$event_tools_constrain_ops_scheme       = FALSE;
$event_tools_constrain_control          = FALSE;
$event_tools_constrain_fidelity         = FALSE;
$event_tools_constrain_rigor            = FALSE;
$event_tools_constrain_documentation    = FALSE;
$event_tools_constrain_session_pace     = FALSE;
$event_tools_constrain_car_forwarding   = FALSE;
$event_tools_constrain_tone             = FALSE;
$event_tools_constrain_dispatched_by1   = FALSE;
$event_tools_constrain_dispatched_by2   = FALSE;
$event_tools_constrain_communications   = FALSE;

// What's the minimum status to show the entry as OK in table, index?
$event_tools_show_min_value = 0;

// Should "warn", "error" codes be highlighted, or just ignored?
$event_tools_replace_on_data_warn = FALSE;  // TRUE replace with text, FALSE leave as is
$event_tools_replace_on_data_error = FALSE;  // TRUE replace with text, FALSE leave as is

// What should be added to external links?
$event_tools_href_add_on = ' target="_blank" ';

// Convention central point (e.g. hotel) for mapping, distances; Google must understand
$event_tools_central_addr = "1230+J+Street";
$event_tools_central_city = "Sacramento";
$event_tools_central_state = "CA";
$event_tools_central_postcode = "95814";
// If server is not in same timezone as event location, uncomment the following line and set event timezone
//date_default_timezone_set('America/Los_Angeles');

// include attendee emergency contact info?
$event_tools_emergency_contact_info     = TRUE;

// Connected to Zen Cart?
$event_tools_option_zen_cart_used       = FALSE;

// how to generate a link to optional Zen Cart
$event_tools_cartlink = "http://127.0.0.1/localcart";

// Include support for doing op-session requests by category?
$event_tools_ops_session_by_category     = TRUE;

// Do op-session requests By Layout (TRUE) or By Session (FALSE)
$event_tools_ops_session_assign_by_layout = TRUE;

?>
