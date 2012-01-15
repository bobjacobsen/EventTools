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
$event_tools_notify_email_address = 'x2011west@pacbell.net';
$event_tools_notify_email_prefix = $event_tools_event_name;


// optional components - TRUE means present (default)

$event_tools_option_general_tours       = FALSE;
$event_tools_option_layout_tours        = FALSE;
$event_tools_option_other_events        = FALSE;
$event_tools_option_layouts             = TRUE;
$event_tools_option_clinics             = FALSE;
$event_tools_option_op_sessions         = TRUE;


// To constrain various info in entry/edit pages
// to only the values in database tables, set the following to TRUE

$event_tools_constrain_scale            = TRUE;
$event_tools_constrain_gauge            = TRUE;
$event_tools_constrain_era              = TRUE;
$event_tools_constrain_class            = TRUE;
$event_tools_constrain_theme            = TRUE;
$event_tools_constrain_locale           = TRUE;

$event_tools_constrain_scenery          = TRUE;
$event_tools_constrain_plan_type        = TRUE;
$event_tools_constrain_ops_scheme       = TRUE;
$event_tools_constrain_control          = TRUE;
$event_tools_constrain_fidelity         = TRUE;
$event_tools_constrain_rigor            = TRUE;
$event_tools_constrain_documentation    = TRUE;
$event_tools_constrain_session_pace     = TRUE;
$event_tools_constrain_car_forwarding   = TRUE;
$event_tools_constrain_tone             = TRUE;
$event_tools_constrain_dispatched_by1   = TRUE;
$event_tools_constrain_dispatched_by2   = TRUE;
$event_tools_constrain_communications   = TRUE;

// What's the minimum status to show the entry as OK in table, index?
$event_tools_show_min_value = 0;

// Should "warn", "error" codes be highlighted, or just ignored?
$event_tools_replace_on_data_warn = FALSE;  // TRUE replace with text, FALSE leave as is
$event_tools_replace_on_data_error = FALSE;  // TRUE replace with text, FALSE leave as is

// What should be added to external links?
$event_tools_href_add_on = ' target="_blank" ';


// Connected to Zen Cart?
$event_tools_option_zen_cart_used       = FALSE;

// how to generate a link to optional Zen Cart
$event_tools_cartlink = "http://127.0.0.1/localcart";


?>
