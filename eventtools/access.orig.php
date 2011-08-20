<?php

// provide database access constants for phpMyEdit
// default values - only these should show in SVN
$opts['hn'] = 'localhost';
$opts['un'] = 'user';
$opts['pw'] = 'password';

$opts['db'] = 'eventtools';
$event_tools_db_prefix = 'prefix_';



// local replacement on the specific system





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
