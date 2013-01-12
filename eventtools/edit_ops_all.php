<?php require_once('access.php'); require_once('secure.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Edit Operating Session Requests</title>
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
</style>
</head>
<body>
<h3>Edit Operating Session Requests</h3>
<a href="index.php">Back to main page</a>
<p>


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
$opts['tb'] = $event_tools_db_prefix . 'eventtools_opsession_req';

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
$opts['navigation'] = 'UDB';

// Display special page elements
$opts['display'] = array(
	'form'  => true,
	'query' => false,
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

//$opts['filters'] = "opsreq_person_email = '".$REMOTE_USER."' ";

// Options you wish to give the users
// A - add,  C - change, P - copy, V - view, D - delete,
// F - filter, I - initial sort suppressed
$opts['options'] = 'CPVDF';

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
  'name'     => 'Your email address',
  'select'   => 'T',
  'maxlen'   => 32,
  'sort'     => true
);
$opts['fdd']['opsreq_person_email'] = array(
  'name'     => 'Requestor',
  'select'   => 'T',
  'options'  => 'LAVCDR',
  'maxlen'   => 25,
  'sort'     => true,
  'default'  => 'None',
  'escape'   => false,
  'values'   => array('table' => $event_tools_db_prefix.'customers', 
                        'column' => 'customers_email_address',
                        'description' => array(
                                'columns' => array(
                                                'customers_firstname',
                                                'customers_lastname',
                                                'customers_email_address'
                                                ),
                                'divs' => array(
                                                ' ','<br/>',''
                                                )
                            )
                        )
);
$opts['fdd']['opsreq_pri1'] = array(
  'name'     => '1st priority request',
  'select'   => 'T',
  'maxlen'   => 5,
  'sort'     => true,
  'default'  => 'None',
  'values'   => array('table' => $event_tools_db_prefix.'eventtools_opsession_with_layouts', 
                        'column' => 'ops_id',
                        'orderby' => 'layout_owner_lastname',
                        'description' => array(
                                'columns' => array(
                                                'layout_owner_lastname',
                                                'layout_name',
                                                'start_date'
                                                ),
                                'divs' => array(
                                                ' ', 
                                                ' ',
                                                '  ',
                                                ''
                                                )
                            )
                        )
);
$opts['fdd']['opsreq_pri2'] = array(
  'name'     => '2nd priority request',
  'select'   => 'T',
  'maxlen'   => 5,
  'sort'     => true,
  'default'  => 'None',
  'values'   => array('table' => $event_tools_db_prefix.'eventtools_opsession_with_layouts', 
                        'column' => 'ops_id',
                        'orderby' => 'layout_owner_lastname',
                        'description' => array(
                                'columns' => array(
                                                'layout_owner_lastname',
                                                'layout_name',
                                                'start_date'
                                                ),
                                'divs' => array(
                                                ' ', 
                                                ' ',
                                                '  ',
                                                ''
                                                )
                            )
                        )
);
$opts['fdd']['opsreq_pri3'] = array(
  'name'     => '3rd priority request',
  'select'   => 'T',
  'maxlen'   => 5,
  'sort'     => true,
  'default'  => 'None',
  'values'   => array('table' => $event_tools_db_prefix.'eventtools_opsession_with_layouts', 
                        'column' => 'ops_id',
                        'orderby' => 'layout_owner_lastname',
                        'description' => array(
                                'columns' => array(
                                                'layout_owner_lastname',
                                                'layout_name',
                                                'start_date'
                                                ),
                                'divs' => array(
                                                ' ', 
                                                ' ',
                                                '  ',
                                                ''
                                                )
                            )
                        )
);
$opts['fdd']['opsreq_pri4'] = array(
  'name'     => '4th priority request',
  'select'   => 'T',
  'maxlen'   => 5,
  'sort'     => true,
  'default'  => 'None',
  'values'   => array('table' => $event_tools_db_prefix.'eventtools_opsession_with_layouts', 
                        'column' => 'ops_id',
                        'orderby' => 'layout_owner_lastname',
                        'description' => array(
                                'columns' => array(
                                                'layout_owner_lastname',
                                                'layout_name',
                                                'start_date'
                                                ),
                                'divs' => array(
                                                ' ', 
                                                ' ',
                                                '  ',
                                                ''
                                                )
                            )
                        )
);
$opts['fdd']['opsreq_pri5'] = array(
  'name'     => '5th priority request',
  'select'   => 'T',
  'maxlen'   => 5,
  'sort'     => true,
  'default'  => 'None',
  'values'   => array('table' => $event_tools_db_prefix.'eventtools_opsession_with_layouts', 
                        'column' => 'ops_id',
                        'orderby' => 'layout_owner_lastname',
                        'description' => array(
                                'columns' => array(
                                                'layout_owner_lastname',
                                                'layout_name',
                                                'start_date'
                                                ),
                                'divs' => array(
                                                ' ', 
                                                ' ',
                                                '  ',
                                                ''
                                                )
                            )
                        )
);
$opts['fdd']['opsreq_pri6'] = array(
  'name'     => '6th priority request',
  'select'   => 'T',
  'maxlen'   => 5,
  'sort'     => true,
  'default'  => 'None',
  'values'   => array('table' => $event_tools_db_prefix.'eventtools_opsession_with_layouts', 
                        'column' => 'ops_id',
                        'orderby' => 'layout_owner_lastname',
                        'description' => array(
                                'columns' => array(
                                                'layout_owner_lastname',
                                                'layout_name',
                                                'start_date'
                                                ),
                                'divs' => array(
                                                ' ', 
                                                ' ',
                                                '  ',
                                                ''
                                                )
                            )
                        )
);
$opts['fdd']['opsreq_pri7'] = array(
  'name'     => '7th priority request',
  'select'   => 'T',
  'maxlen'   => 5,
  'sort'     => true,
  'default'  => 'None',
  'values'   => array('table' => $event_tools_db_prefix.'eventtools_opsession_with_layouts', 
                        'column' => 'ops_id',
                        'orderby' => 'layout_owner_lastname',
                        'description' => array(
                                'columns' => array(
                                                'layout_owner_lastname',
                                                'layout_name',
                                                'start_date'
                                                ),
                                'divs' => array(
                                                ' ', 
                                                ' ',
                                                '  ',
                                                ''
                                                )
                            )
                        )
);
$opts['fdd']['opsreq_pri8'] = array(
  'name'     => '8th priority request',
  'select'   => 'T',
  'maxlen'   => 5,
  'sort'     => true,
  'default'  => 'None',
  'values'   => array('table' => $event_tools_db_prefix.'eventtools_opsession_with_layouts', 
                        'column' => 'ops_id',
                        'orderby' => 'layout_owner_lastname',
                        'description' => array(
                                'columns' => array(
                                                'layout_owner_lastname',
                                                'layout_name',
                                                'start_date'
                                                ),
                                'divs' => array(
                                                ' ',
                                                ' ',
                                                ''
                                                )
                            )
                        )
);
$opts['fdd']['opsreq_pri9'] = array(
  'name'     => '9th priority request',
  'select'   => 'T',
  'maxlen'   => 5,
  'sort'     => true,
  'default'  => 'None',
  'values'   => array('table' => $event_tools_db_prefix.'eventtools_opsession_with_layouts', 
                        'column' => 'ops_id',
                        'orderby' => 'layout_owner_lastname',
                        'description' => array(
                                'columns' => array(
                                                'layout_owner_lastname',
                                                'layout_name',
                                                'start_date'
                                                ),
                                'divs' => array(
                                                ' ',
                                                ' ',
                                                ''
                                                )
                            )
                        )
);
$opts['fdd']['opsreq_pri10'] = array(
  'name'     => '10th priority request',
  'select'   => 'T',
  'maxlen'   => 5,
  'sort'     => true,
  'default'  => 'None',
  'values'   => array('table' => $event_tools_db_prefix.'eventtools_opsession_with_layouts', 
                        'column' => 'ops_id',
                        'orderby' => 'layout_owner_lastname',
                        'description' => array(
                                'columns' => array(
                                                'layout_owner_lastname',
                                                'layout_name',
                                                'start_date'
                                                ),
                                'divs' => array(
                                                ' ',
                                                ' ',
                                                ''
                                                )
                            )
                        )
);
$opts['fdd']['opsreq_pri11'] = array(
  'name'     => '11th priority request',
  'select'   => 'T',
  'maxlen'   => 5,
  'sort'     => true,
  'default'  => 'None',
  'values'   => array('table' => $event_tools_db_prefix.'eventtools_opsession_with_layouts', 
                        'column' => 'ops_id',
                        'orderby' => 'layout_owner_lastname',
                        'description' => array(
                                'columns' => array(
                                                'layout_owner_lastname',
                                                'layout_name',
                                                'start_date'
                                                ),
                                'divs' => array(
                                                ' ',
                                                ' ',
                                                ''
                                                )
                            )
                        )
);
$opts['fdd']['opsreq_pri12'] = array(
  'name'     => '12th priority request',
  'select'   => 'T',
  'maxlen'   => 5,
  'sort'     => true,
  'default'  => 'None',
  'values'   => array('table' => $event_tools_db_prefix.'eventtools_opsession_with_layouts', 
                        'column' => 'ops_id',
                        'orderby' => 'layout_owner_lastname',
                        'description' => array(
                                'columns' => array(
                                                'layout_owner_lastname',
                                                'layout_name',
                                                'start_date'
                                                ),
                                'divs' => array(
                                                ' ',
                                                ' ',
                                                ''
                                                )
                            )
                        )
);
$opts['fdd']['opsreq_priority'] = array(
  'name'     => 'Attendee Category (0 last)',
  'select'   => 'T',
  'maxlen'   => 5,
  'sort'     => true
);
$opts['fdd']['opsreq_opt1'] = array(
  'name'     => $event_tools_op_session_opt1_name,
  'select'   => 'T',
  'maxlen'   => 1,
  'sort'     => true
);
$opts['fdd']['opsreq_opt2'] = array(
  'name'     => $event_tools_op_session_opt2_name,
  'select'   => 'T',
  'maxlen'   => 1,
  'sort'     => true
);
$opts['fdd']['opsreq_opt3'] = array(
  'name'     => $event_tools_op_session_opt3_name,
  'select'   => 'T',
  'maxlen'   => 1,
  'sort'     => true
);
$opts['fdd']['opsreq_opt4'] = array(
  'name'     => $event_tools_op_session_opt4_name,
  'select'   => 'T',
  'maxlen'   => 1,
  'sort'     => true
);
$opts['fdd']['opsreq_opt5'] = array(
  'name'     => $event_tools_op_session_opt5_name,
  'select'   => 'T',
  'maxlen'   => 1,
  'sort'     => true
);
$opts['fdd']['opsreq_opt6'] = array(
  'name'     => $event_tools_op_session_opt6_name,
  'select'   => 'T',
  'maxlen'   => 1,
  'sort'     => true
);
$opts['fdd']['opsreq_opt7'] = array(
  'name'     => $event_tools_op_session_opt7_name,
  'select'   => 'T',
  'maxlen'   => 1,
  'sort'     => true
);
$opts['fdd']['opsreq_opt8'] = array(
  'name'     => $event_tools_op_session_opt8_name,
  'select'   => 'T',
  'maxlen'   => 1,
  'sort'     => true
);
$opts['fdd']['opsreq_any'] = array(
  'name'     => "Any session OK, not just priorities?",
  'select'   => 'T',
  'maxlen'   => 5,
  'sort'     => true,
  'values'   => array('Y','N')
);
$opts['fdd']['opsreq_number'] = array(
  'name'     => "Number of sessions you'd like",
  'select'   => 'T',
  'maxlen'   => 5,
  'sort'     => true,
  'values'   => array('1','2','3','4','5','6','7','8','9','10','11','12')
);
$opts['fdd']['opsreq_comment'] = array(
  'name'     => 'Any comments?',
  'select'   => 'T',
  'maxlen'   => 200,
  'sort'     => true
);

// Now important call to phpMyEdit
require_once 'phpMyEdit.class.php';
new phpMyEdit($opts);
//require_once 'extensions/phpMyEdit-slide.class.php';
//new phpMyEdit_slide($opts);

?>


</body>
</html>
