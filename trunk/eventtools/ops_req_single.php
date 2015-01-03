<?php 
    require_once('access.php'); 
    global $opts, $event_tools_db_prefix;
    mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
    @mysql_select_db($opts['db']) or die( "Unable to select database");

    // check for email address
    parse_str($_SERVER["QUERY_STRING"], $args);
    if ($args["email"] == NONE || $args["email"] == "") {
        echo "<html><body>You must enter an email address to see the corresponding request<p>";
        echo '<form action="" method="get">';
        echo '<input type=text size=25 name=email>';
        echo '<input type=submit value="Display">';
        echo '</form>';
        exit;
    }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Single Attendee Record</title>
<style type="text/css">
	hr.pme-hr		     { border: 0px solid; padding: 0px; margin: 0px; border-top-width: 1px; height: 1px; }
	table.pme-main 	     { border: #004d9c 1px solid; border-collapse: collapse; border-spacing: 0px; width: 100%; }
	table.pme-navigation { border: #004d9c 0px solid; border-collapse: collapse; border-spacing: 0px; width: 100%; }
	td.pme-navigation-0, td.pme-navigation-1 { white-space: nowrap; }
	th.pme-header	     { border: #004d9c 1px solid; padding: 4px; background: #add8e6; }
	td.pme-key-0, td.pme-value-0, td.pme-help-0, td.pme-navigation-0, td.pme-cell-0,
	td.pme-key-1, td.pme-value-1, td.pme-help-0, td.pme-navigation-1, td.pme-cell-1,
	td.pme-sortinfo, td.pme-filter { border: #004d9c 1px solid; padding: 3px; }
	td.pme-buttons { text-align: left;   }
	td.pme-message { text-align: center; }
	td.pme-stats   { text-align: right;  }

	.pme-navigation, .pme-hr,
	.pme-more {  display:none; } // suppress inter-table rule, nav table, Apply button

    
	hr.pme2-hr		     { border: 0px solid; padding: 0px; margin: 0px; border-top-width: 1px; height: 1px; }
	table.pme2-main 	     { border: #004d9c 1px solid; border-collapse: collapse; border-spacing: 0px; width: 100%; }
	table.pme2-navigation { border: #004d9c 0px solid; border-collapse: collapse; border-spacing: 0px; width: 100%; }
	td.pme2-navigation-0, td.pme2-navigation-1 { white-space: nowrap; }
	th.pme2-header	     { border: #004d9c 1px solid; padding: 4px; background: #add8e6; }
	td.pme2-key-0, td.pme2-value-0, td.pme2-help-0, td.pme2-navigation-0, td.pme2-cell-0,
	td.pme2-key-1, td.pme2-value-1, td.pme2-help-0, td.pme2-navigation-1, td.pme2-cell-1,
	td.pme2-sortinfo, td.pme2-filter { border: #004d9c 1px solid; padding: 3px; }
	td.pme2-buttons { text-align: left;   }
	td.pme2-message { text-align: center; }
	td.pme2-stats   { text-align: right;  }
	td.pme2-value-0, td.pme2-value-1 { width: 5%; } 
	
	.pme2-navigation, .pme2-hr,
	.pme2-more { display:none; } // suppress inter-table rule, nav table, Apply button

</style>
</head>
<body>
<h2>Single Attendee Record</h2>

<a href="index.php">Back to main page</a><p>

<!-- -------------------------------------------------------- -->
<!-- Note there are two separate PhpMyEdit calls on this page -->
<!-- -------------------------------------------------------- -->

<?php

/*
 * IMPORTANT NOTE: This generated file contains only a subset of huge amount
 * of options that can be used with phpMyEdit. To get information about all
 * features offered by phpMyEdit, check official documentation. It is available
 * online and also for download on phpMyEdit project management page:
 *
 * http://platon.sk/projects/main_page.php?project_id=5
 *
 * This file was generated by:
 *
 *                    phpMyEdit version: 5.7.1
 *       phpMyEdit.class.php core class: 1.204
 *            phpMyEditSetup.php script: 1.50
 *              generating setup script: 1.50
 */

if (!$event_tools_user_email_log_skip) {
    // email results
    $opts['notify']['all'] = $event_tools_notify_email_address;
    $opts['notify']['prefix'] = $event_tools_notify_email_prefix;
}

// MySQL host name, user name, password, database, and table
$opts['tb'] = $event_tools_db_prefix . 'eventtools_opsession_req_with_user_info';

// Name of field which is the unique key
$opts['key'] = 'opsreq_id';

// Type of key field (int/real/string/date etc.)
$opts['key_type'] = 'int';

// Sorting field(s)
$opts['sort_field'] = array('opsreq_id');

// Number of records to display on the screen
// Value of -1 lists all records in a table
$opts['inc'] = 15;

// Number of lines to display on multiple selection filters
$opts['multiple'] = '4';

// Navigation style: B - buttons (default), T - text links, G - graphic links
// Buttons position: U - up, D - down (default)
$opts['navigation'] = 'DB';

// Display special page elements
$opts['display'] = array(
	'form'  => true,
	'query' => true,
	'sort'  => false,
	'time'  => false,
	'tabs'  => false
);

// Set default prefixes for variables
$opts['js']['prefix']               = 'PME_js_';
$opts['dhtml']['prefix']            = 'PME_dhtml_';
$opts['cgi']['prefix']['operation'] = 'PME_op_';
$opts['cgi']['prefix']['sys']       = 'PME_sys_';
$opts['cgi']['prefix']['data']      = 'PME_data_';

/* Get the user's default language and use it if possible or you can
   specify particular one you want to use. Refer to official documentation
   for list of available languages. */
$opts['language'] = $_SERVER['HTTP_ACCEPT_LANGUAGE'] . '-UTF8';

/* Table-level filter capability. If set, it is included in the WHERE clause
   of any generated SELECT statement in SQL query. This gives you ability to
   work only with subset of data from table.

$opts['filters'] = "column1 like '%11%' AND column2<17";
$opts['filters'] = "section_id = 9";
$opts['filters'] = "PMEtable0.sessions_count > 200";
*/

parse_str($_SERVER["QUERY_STRING"], $args);
$opts['filters'] = "opsreq_person_email = '".$args["email"]."' ";

// Options you wish to give the users
// A - add,  C - change, P - copy, V - view, D - delete,
// F - filter, I - initial sort suppressed
$opts['options'] = 'V';

/* Field definitions
   
Fields will be displayed left to right on the screen in the order in which they
appear in generated list. Here are some most used field options documented.

['name'] is the title used for column headings, etc.;
['maxlen'] maximum length to display add/edit/search input boxes
['trimlen'] maximum length of string content to display in row listing
['width'] is an optional display width specification for the column
          e.g.  ['width'] = '100px';
['mask'] a string that is used by sprintf() to format field output
['sort'] true or false; means the users may sort the display on this column
['strip_tags'] true or false; whether to strip tags from content
['nowrap'] true or false; whether this field should get a NOWRAP
['select'] T - text, N - numeric, D - drop-down, M - multiple selection
['options'] optional parameter to control whether a field is displayed
  L - list, F - filter, A - add, C - change, P - copy, D - delete, V - view
            Another flags are:
            R - indicates that a field is read only
            W - indicates that a field is a password field
            H - indicates that a field is to be hidden and marked as hidden
['URL'] is used to make a field 'clickable' in the display
        e.g.: 'mailto:$value', 'http://$value' or '$page?stuff';
['URLtarget']  HTML target link specification (for example: _blank)
['textarea']['rows'] and/or ['textarea']['cols']
  specifies a textarea is to be used to give multi-line input
  e.g. ['textarea']['rows'] = 5; ['textarea']['cols'] = 10
['values'] restricts user input to the specified constants,
           e.g. ['values'] = array('A','B','C') or ['values'] = range(1,99)
['values']['table'] and ['values']['column'] restricts user input
  to the values found in the specified column of another table
['values']['description'] = 'desc_column'
  The optional ['values']['description'] field allows the value(s) displayed
  to the user to be different to those in the ['values']['column'] field.
  This is useful for giving more meaning to column values. Multiple
  descriptions fields are also possible. Check documentation for this.
*/

$opts['fdd']['opsreq_id'] = array(
  'name'     => 'ID',
  'select'   => 'T',
  'options'  => 'APDR', // auto increment
  'maxlen'   => 5,
  'default'  => '0',
  'sort'     => true
);
$opts['fdd']['opsreq_person_email'] = array(
  'name'     => 'Email address',
  'select'   => 'T',
  'options'  => 'LVR',
  'maxlen'   => 32,
  'sort'     => true
);
$opts['fdd']['customers_firstname'] = array(
  'name'     => 'First name',
  'select'   => 'T',
  'options'  => 'LVR',
  'maxlen'   => 32,
  'sort'     => true
);
$opts['fdd']['customers_lastname'] = array(
  'name'     => 'Last name',
  'select'   => 'T',
  'options'  => 'LVR',
  'maxlen'   => 32,
  'sort'     => true
);
$opts['fdd']['customers_telephone'] = array(
  'name'     => 'Telephone',
  'select'   => 'T',
  'options'  => 'LVR',
  'maxlen'   => 32,
  'sort'     => true
);
$opts['fdd']['customers_cellphone'] = array(
  'name'     => 'Cell Phone',
  'select'   => 'T',
  'options'  => 'LVR',
  'maxlen'   => 32,
  'sort'     => true
);
if ($event_tools_emergency_contact_info) {
    $opts['fdd']['customers_x2011_emerg_contact_name'] = array(
      'name'     => 'Emergency Contact',
      'select'   => 'T',
      'options'  => 'LVR',
      'maxlen'   => 60,
      'sort'     => true
    );
    $opts['fdd']['customers_x2011_emerg_contact_phone'] = array(
      'name'     => 'Emergency Contact Phone',
      'select'   => 'T',
      'options'  => 'LVR',
      'maxlen'   => 32,
      'sort'     => true
    );
}
$opts['fdd']['customers_id'] = array(
  'name'     => 'Address',
  'select'   => 'T',
  'options'  => 'LAVCDR',
  'maxlen'   => 25,
  'sort'     => true,
  'default'  => 'None',
  'values'   => array('table' => $event_tools_db_prefix.'address_book', 
                        'column' => 'customers_id',
                        'description' => array(
                                'columns' => array(
                                                'entry_street_address',
                                                'entry_city',
                                                'entry_state',
                                                'entry_postcode'
                                                ),
                                'divs' => array(
                                                ', ',', ',' '
                                                )
                            )
                        )
);
$opts['fdd']['opsreq_pri1'] = array(
  'name'     => '1st priority',
  'select'   => '',
  'maxlen'   => 5,
  'sort'     => true,
  'default'  => 'None',
  'values'   => array('table' => $event_tools_db_prefix.'eventtools_opsession_name', 
                        'column' => 'ops_id',
                        'orderby' => 'show_name',
                        'description' => array(
                                'columns' => array(
                                                'show_name' ,
                                                'presenting_time'
                                                ),
                                'divs' => array(
                                                ' ',
                                                ''
                                                )
                            )
                        )
);
$opts['fdd']['opsreq_pri2'] = array(
  'name'     => '2nd priority',
  'select'   => '',
  'maxlen'   => 5,
  'sort'     => true,
  'default'  => 'None',
  'values'   => array('table' => $event_tools_db_prefix.'eventtools_opsession_name', 
                        'column' => 'ops_id',
                        'orderby' => 'show_name',
                        'description' => array(
                                'columns' => array(
                                                'show_name' ,
                                                'presenting_time'
                                                ),
                                'divs' => array(
                                                ' ',
                                                ''
                                                )
                            )
                        )
);
$opts['fdd']['opsreq_pri3'] = array(
  'name'     => '3rd priority',
  'select'   => '',
  'maxlen'   => 5,
  'sort'     => true,
  'default'  => 'None',
  'values'   => array('table' => $event_tools_db_prefix.'eventtools_opsession_name', 
                        'column' => 'ops_id',
                        'orderby' => 'show_name',
                        'description' => array(
                                'columns' => array(
                                                'show_name' ,
                                                'presenting_time'
                                                ),
                                'divs' => array(
                                                ' ',
                                                ''
                                                )
                            )
                        )
);
$opts['fdd']['opsreq_pri4'] = array(
  'name'     => '4th priority',
  'select'   => '',
  'maxlen'   => 5,
  'sort'     => true,
  'default'  => 'None',
  'values'   => array('table' => $event_tools_db_prefix.'eventtools_opsession_name', 
                        'column' => 'ops_id',
                        'orderby' => 'show_name',
                        'description' => array(
                                'columns' => array(
                                                'show_name' ,
                                                'presenting_time'
                                                ),
                                'divs' => array(
                                                ' ',
                                                ''
                                                )
                            )
                        )
);
$opts['fdd']['opsreq_pri5'] = array(
  'name'     => '5th priority',
  'select'   => '',
  'maxlen'   => 5,
  'sort'     => true,
  'default'  => 'None',
  'values'   => array('table' => $event_tools_db_prefix.'eventtools_opsession_name', 
                        'column' => 'ops_id',
                        'orderby' => 'show_name',
                        'description' => array(
                                'columns' => array(
                                                'show_name' ,
                                                'presenting_time'
                                                ),
                                'divs' => array(
                                                ' ',
                                                ''
                                                )
                            )
                        )
);
$opts['fdd']['opsreq_pri6'] = array(
  'name'     => '6th priority',
  'select'   => '',
  'maxlen'   => 5,
  'sort'     => true,
  'default'  => 'None',
  'values'   => array('table' => $event_tools_db_prefix.'eventtools_opsession_name', 
                        'column' => 'ops_id',
                        'orderby' => 'show_name',
                        'description' => array(
                                'columns' => array(
                                                'show_name' ,
                                                'presenting_time'
                                                ),
                                'divs' => array(
                                                ' ',
                                                ''
                                                )
                            )
                        )
);
$opts['fdd']['opsreq_pri7'] = array(
  'name'     => '7th priority',
  'select'   => '',
  'maxlen'   => 5,
  'sort'     => true,
  'default'  => 'None',
  'values'   => array('table' => $event_tools_db_prefix.'eventtools_opsession_name', 
                        'column' => 'ops_id',
                        'orderby' => 'show_name',
                        'description' => array(
                                'columns' => array(
                                                'show_name' ,
                                                'presenting_time'
                                                ),
                                'divs' => array(
                                                ' ',
                                                ''
                                                )
                            )
                        )
);
$opts['fdd']['opsreq_pri8'] = array(
  'name'     => '8th priority',
  'select'   => '',
  'maxlen'   => 5,
  'sort'     => true,
  'default'  => 'None',
  'values'   => array('table' => $event_tools_db_prefix.'eventtools_opsession_name', 
                        'column' => 'ops_id',
                        'orderby' => 'show_name',
                        'description' => array(
                                'columns' => array(
                                                'show_name' ,
                                                'presenting_time'
                                                ),
                                'divs' => array(
                                                ' ',
                                                ''
                                                )
                            )
                        )
);
$opts['fdd']['opsreq_pri9'] = array(
  'name'     => '9th priority',
  'select'   => '',
  'maxlen'   => 5,
  'sort'     => true,
  'default'  => 'None',
  'values'   => array('table' => $event_tools_db_prefix.'eventtools_opsession_name', 
                        'column' => 'ops_id',
                        'orderby' => 'show_name',
                        'description' => array(
                                'columns' => array(
                                                'show_name' ,
                                                'presenting_time'
                                                ),
                                'divs' => array(
                                                ' ',
                                                ''
                                                )
                            )
                        )
);
$opts['fdd']['opsreq_pri10'] = array(
  'name'     => '10th priority',
  'select'   => '',
  'maxlen'   => 5,
  'sort'     => true,
  'default'  => 'None',
  'values'   => array('table' => $event_tools_db_prefix.'eventtools_opsession_name', 
                        'column' => 'ops_id',
                        'orderby' => 'show_name',
                        'description' => array(
                                'columns' => array(
                                                'show_name' ,
                                                'presenting_time'
                                                ),
                                'divs' => array(
                                                ' ',
                                                ''
                                                )
                            )
                        )
);
$opts['fdd']['opsreq_pri11'] = array(
  'name'     => '11th priority',
  'select'   => '',
  'maxlen'   => 5,
  'sort'     => true,
  'default'  => 'None',
  'values'   => array('table' => $event_tools_db_prefix.'eventtools_opsession_name', 
                        'column' => 'ops_id',
                        'orderby' => 'show_name',
                        'description' => array(
                                'columns' => array(
                                                'show_name' ,
                                                'presenting_time'
                                                ),
                                'divs' => array(
                                                ' ',
                                                ''
                                                )
                            )
                        )
);
$opts['fdd']['opsreq_pri12'] = array(
  'name'     => '12th priority',
  'select'   => '',
  'maxlen'   => 5,
  'sort'     => true,
  'default'  => 'None',
  'values'   => array('table' => $event_tools_db_prefix.'eventtools_opsession_name', 
                        'column' => 'ops_id',
                        'orderby' => 'show_name',
                        'description' => array(
                                'columns' => array(
                                                'show_name' ,
                                                'presenting_time'
                                                ),
                                'divs' => array(
                                                ' ',
                                                ''
                                                )
                            )
                        )
);


$opts['fdd']['opsreq_any'] = array(
  'name'     => "Any session OK, not just priorities?",
  'select'   => 'T',
  'maxlen'   => 5,
  'sort'     => true,
  'values'   => array('Y','N')
);
$opts['fdd']['opsreq_number'] = array(
  'name'     => "Number of sessions requested",
  'select'   => 'T',
  'maxlen'   => 5,
  'sort'     => true,
  'values'   => array('1','2','3','4','5','6','7','8')
);
$opts['fdd']['opsreq_comment'] = array(
  'name'     => 'Comment',
  'textarea' => array('rows' => 4, 'cols' => 50),
  'select'   => 'T',
  'maxlen'   => 200,
  'sort'     => true
);

// Now important call to phpMyEdit
//require_once 'phpMyEdit.class.php';
//new phpMyEdit($opts);
require_once 'extensions/phpMyEdit-slide-single-input.class.php';
new phpMyEdit_slide($opts);

?>



<!-- --------------------------------------- -->


<?php

    global $opts, $event_tools_db_prefix;
    mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
    @mysql_select_db($opts['db']) or die( "Unable to select database");

    // check for email address
    parse_str($_SERVER["QUERY_STRING"], $args);

// Reset the output from above
$opts['fdd'] = array();


$query="
    SELECT  *
    FROM ".$event_tools_db_prefix."eventtools_customer_cross_options_and_values
    WHERE customers_email_address = '".$args["email"]."'
    ORDER BY customer_option_order
    ;
";
//echo $query;
$options=mysql_query($query);

$i=0;
$num=mysql_numrows($options);
while ($i < $num) {
    if (mysql_result($options,$i,"customer_option_long_name") == "") break;
    if (mysql_result($options,$i,"customer_option_long_name") == NONE) break;
/*
 * IMPORTANT NOTE: This generated file contains only a subset of huge amount
 * of options that can be used with phpMyEdit. To get information about all
 * features offered by phpMyEdit, check official documentation. It is available
 * online and also for download on phpMyEdit project management page:
 *
 * http://platon.sk/projects/main_page.php?project_id=5
 *
 * This file was generated by:
 *
 *                    phpMyEdit version: 5.7.1
 *       phpMyEdit.class.php core class: 1.204
 *            phpMyEditSetup.php script: 1.50
 *              generating setup script: 1.50
 */

if (!$event_tools_user_email_log_skip) {
    // email results
    $opts['notify']['all'] = $event_tools_notify_email_address;
    $opts['notify']['prefix'] = $event_tools_notify_email_prefix;
}

// MySQL host name, user name, password, database, and table
$opts['tb'] = $event_tools_db_prefix . 'eventtools_customer_cross_options_and_values';

// Name of field which is the unique key
$opts['key'] = 'customer_option_value_id';

// Type of key field (int/real/string/date etc.)
$opts['key_type'] = 'int';

// Sorting field(s)
$opts['sort_field'] = array('customer_option_value_id');

// Number of records to display on the screen
// Value of -1 lists all records in a table
$opts['inc'] = 15;

// Number of lines to display on multiple selection filters
$opts['multiple'] = '4';

// Navigation style: B - buttons (default), T - text links, G - graphic links
// Buttons position: U - up, D - down (default)
$opts['navigation'] = 'DB';

// Display special page elements
$opts['display'] = array(
	'form'  => true,
	'query' => true,
	'sort'  => false,
	'time'  => false,
	'tabs'  => false
);

// Set default prefixes for CSS
$opts['css']['prefix'] = 'pme2';

// Set default prefixes for variables
$opts['js']['prefix']               = 'PME_js_';
$opts['dhtml']['prefix']            = 'PME_dhtml_';
$opts['cgi']['prefix']['operation'] = 'PME_op_';
$opts['cgi']['prefix']['sys']       = 'PME_sys_';
$opts['cgi']['prefix']['data']      = 'PME_data_';

/* Get the user's default language and use it if possible or you can
   specify particular one you want to use. Refer to official documentation
   for list of available languages. */
$opts['language'] = $_SERVER['HTTP_ACCEPT_LANGUAGE'] . '-UTF8';

/* Table-level filter capability. If set, it is included in the WHERE clause
   of any generated SELECT statement in SQL query. This gives you ability to
   work only with subset of data from table.

$opts['filters'] = "column1 like '%11%' AND column2<17";
$opts['filters'] = "section_id = 9";
$opts['filters'] = "PMEtable0.sessions_count > 200";
*/

parse_str($_SERVER["QUERY_STRING"], $args);
$opts['filters'] = " customer_option_value_id = '".mysql_result($options,$i,"customer_option_value_id")."' ";

// Options you wish to give the users
// A - add,  C - change, P - copy, V - view, D - delete,
// F - filter, I - initial sort suppressed
$opts['options'] = 'V';

/* Field definitions
   
Fields will be displayed left to right on the screen in the order in which they
appear in generated list. Here are some most used field options documented.

['name'] is the title used for column headings, etc.;
['maxlen'] maximum length to display add/edit/search input boxes
['trimlen'] maximum length of string content to display in row listing
['width'] is an optional display width specification for the column
          e.g.  ['width'] = '100px';
['mask'] a string that is used by sprintf() to format field output
['sort'] true or false; means the users may sort the display on this column
['strip_tags'] true or false; whether to strip tags from content
['nowrap'] true or false; whether this field should get a NOWRAP
['select'] T - text, N - numeric, D - drop-down, M - multiple selection
['options'] optional parameter to control whether a field is displayed
  L - list, F - filter, A - add, C - change, P - copy, D - delete, V - view
            Another flags are:
            R - indicates that a field is read only
            W - indicates that a field is a password field
            H - indicates that a field is to be hidden and marked as hidden
['URL'] is used to make a field 'clickable' in the display
        e.g.: 'mailto:$value', 'http://$value' or '$page?stuff';
['URLtarget']  HTML target link specification (for example: _blank)
['textarea']['rows'] and/or ['textarea']['cols']
  specifies a textarea is to be used to give multi-line input
  e.g. ['textarea']['rows'] = 5; ['textarea']['cols'] = 10
['values'] restricts user input to the specified constants,
           e.g. ['values'] = array('A','B','C') or ['values'] = range(1,99)
['values']['table'] and ['values']['column'] restricts user input
  to the values found in the specified column of another table
['values']['description'] = 'desc_column'
  The optional ['values']['description'] field allows the value(s) displayed
  to the user to be different to those in the ['values']['column'] field.
  This is useful for giving more meaning to column values. Multiple
  descriptions fields are also possible. Check documentation for this.
*/

$opts['fdd']['customer_option_value_id'] = array(
  'name'     => 'ID',
  'select'   => 'T',
  'options'  => 'APDR', // auto increment
  'maxlen'   => 5,
  'default'  => '0',
  'sort'     => true
);
$opts['fdd']['customer_option_value_value'] = array(
  'name'     => mysql_result($options,$i,"customer_option_long_name"),
  'select'   => 'T',
  'options'  => 'LVR',
  'maxlen'   => 32,
  'sort'     => true
);



// Now important call to phpMyEdit
//require_once 'phpMyEdit.class.php';
//new phpMyEdit($opts);
require_once 'extensions/phpMyEdit-slide-single-input.class.php';
new phpMyEdit_slide($opts);

// end of loop over items
$i++;
}

?>

</body>
</html>
