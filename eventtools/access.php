<?php

// provide database access constants for phpMyEdit
// default values - only these should show in SVN
$opts['hn'] = 'localhost';
$opts['un'] = 'user';
$opts['pw'] = 'password';

$opts['db'] = 'database';
$event_tools_db_prefix = 'prefix_';



// local replacement on the specific system
if ($_SERVER['SERVER_NAME'] == 'www.x2011west.org') {
    $opts['hn'] = 'localhost';
    $opts['un'] = 'x2011west';
    $opts['pw'] = 's9etqWeZRMNrzZ48';
} else {
    $opts['hn'] = '127.0.0.1';
    $opts['un'] = 'root';
    $opts['pw'] = '';
}

$opts['db'] = 'x2011west';
$event_tools_db_prefix = 'gr2012_';


// Basic event name for e.g. page headers
$event_tools_event_name = "GR2012";

// Require user name, authentification?
$eventtools_require_user = TRUE;
$eventtools_require_authenticate = FALSE;

// to get entries logged & reported via email
$opts['logtable'] = $event_tools_db_prefix.'eventtools_changelog';
$event_tools_notify_email_address = 'x2011west@pacbell.net';
$event_tools_notify_email_prefix = 'GR2012 ';


// to constrain various info in entry/edit pages
$event_tools_constrain_scale  = TRUE;
$event_tools_constrain_gauge  = TRUE;
$event_tools_constrain_era    = TRUE;
$event_tools_constrain_class  = TRUE;
$event_tools_constrain_theme  = TRUE;
$event_tools_constrain_locale = TRUE;

$event_tools_constrain_scenery        = TRUE;
$event_tools_constrain_plan_type      = TRUE;
$event_tools_constrain_ops_scheme     = TRUE;
$event_tools_constrain_control        = TRUE;
$event_tools_constrain_fidelity       = TRUE;
$event_tools_constrain_rigor          = TRUE;
$event_tools_constrain_documentation  = TRUE;
$event_tools_constrain_session_pace   = TRUE;
$event_tools_constrain_car_forwarding = TRUE;
$event_tools_constrain_tone           = TRUE;
$event_tools_constrain_dispatched_by1 = TRUE;
$event_tools_constrain_dispatched_by2 = TRUE;
$event_tools_constrain_communications = TRUE;


// What's the minimum status to show the entry as OK in table, index?
$event_tools_show_min_value = 0;

// Should "warn", "error" codes be highlighted, or just ignored?
$event_tools_replace_on_data_warn = FALSE;  // TRUE replace with text, FALSE leave as is
$event_tools_replace_on_data_error = FALSE;  // TRUE replace with text, FALSE leave as is

// What should be added to external links?
$event_tools_href_add_on = ' target="_blank" ';


// how to get the connected to optional Zen Cart
$eventtools_cartlink = "http://127.0.0.1/localcart";


?>
