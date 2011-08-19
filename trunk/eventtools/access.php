<?php

// provide database access constants for phpMyEdit
// this is locally replaced on the using system

$opts['hn'] = '127.0.0.1';
$opts['un'] = 'root';
$opts['pw'] = '';

$opts['db'] = 'databasename';
$event_tools_db_prefix = 'test_';

$opts['logtable'] = $event_tools_db_prefix.'eventtools_changelog';

// What's the minimum status to show the entry?
$event_tools_show_min_value = 0;

// Should "warn", "error" codes be highlighted, or just ignored?
$event_tools_replace_on_data_warn = FALSE;  // TRUE replace with text, FALSE leave as is
$event_tools_replace_on_data_error = FALSE;  // TRUE replace with text, FALSE leave as is

// What should be added to external links?
$event_tools_href_add_on = ' target="_blank" ';

// how to get the connected to optional Zen Cart
$eventtools_cartlink = "http://127.0.0.1/localcart";

// Require user name, authentification?
$eventtools_require_user = TRUE;
$eventtools_require_authenticate = FALSE;

?>
