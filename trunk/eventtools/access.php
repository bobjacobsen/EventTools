<?php

// provide database access constants for phpMyEdit
// default values - only these should show in SVN
$opts['hn'] = '127.0.0.1';  // MySQL host name
$opts['un'] = 'root';       // MySQL user name
$opts['pw'] = '';           // MySQL password

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
// If not in same timezone as site host location, uncomment the following line and set your proper timezone
//date_default_timezone_set('America/Los_Angeles');

// Connected to Zen Cart?
$event_tools_option_zen_cart_used       = FALSE;

// how to generate a link to optional Zen Cart
$event_tools_cartlink = "http://127.0.0.1/localcart";

// Include support for doing op-session requests by category? 
$event_tools_ops_session_by_category     = TRUE;

// Do op-session requests By Layout (TRUE) or By Session (FALSE)
$event_tools_ops_session_assign_by_layout = TRUE;

// Names for optional fields 1-8 in op session requests
$event_tools_op_session_opt1_name        = 'Opt1';
$event_tools_op_session_opt1_long_name   = 'Option 1';
$event_tools_op_session_opt2_name        = 'Opt2';
$event_tools_op_session_opt2_long_name   = 'Option 2';
$event_tools_op_session_opt3_name        = 'Opt3';
$event_tools_op_session_opt3_long_name   = 'Option 3';
$event_tools_op_session_opt4_name        = 'Opt4';
$event_tools_op_session_opt4_long_name   = 'Option 4';
$event_tools_op_session_opt5_name        = 'Opt5';
$event_tools_op_session_opt5_long_name   = 'Option 5';
$event_tools_op_session_opt6_name        = 'Opt6';
$event_tools_op_session_opt6_long_name   = 'Option 6';
$event_tools_op_session_opt7_name        = 'Opt7';
$event_tools_op_session_opt7_long_name   = 'Option 7';
$event_tools_op_session_opt8_name        = 'Opt8';
$event_tools_op_session_opt8_long_name   = 'Option 8';

?>
