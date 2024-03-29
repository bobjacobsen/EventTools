<?php 
include_once('mysql2i.class.php'); // migration step
require('access_and_open.php'); 

    // required to get user name
    $user = $_SERVER['PHP_AUTH_USER'];
    $REMOTE_USER = $user;  // for phpMyEdit
    if ($user == '') {
        // fail
        header('WWW-Authenticate: Basic realm="'.$event_tools_event_name.' Layout Confirmation"');
        header('HTTP/1.0 401 Unauthorized');
    }
    
    // check if exists
    global $opts, $event_tools_db_prefix;

    $query = "SELECT layout_owner_email 
            FROM ".$event_tools_db_prefix ."eventtools_layouts
            WHERE layout_owner_email = '".$REMOTE_USER."';
    ";
    $result=mysql_query($query);
    $num=mysql_numrows($result);
    if ($num == 0) {
        // fail
        header('WWW-Authenticate: Basic realm="'.$event_tools_event_name.' Layout Confirmation"');
        header('HTTP/1.0 401 Unauthorized');
    }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Confirm Layout Information</title>
<style type="text/css">
	hr.pme-hr		     { border: 0px solid; padding: 0px; margin: 0px; border-top-width: 1px; height: 1px; }
	table.pme-main 	     { border: #004d9c 1px solid; border-collapse: collapse; border-spacing: 0px; width: 100%; }
	table.pme-navigation { border: #004d9c 0px solid; border-collapse: collapse; border-spacing: 0px; width: 100%; }
	td.pme-navigation-0, td.pme-navigation-1 { white-space: nowrap; }
	th.pme-header
	    { border: #004d9c 1px solid; padding: 4px; background: #add8e6; }
	td.pme-key-0, td.pme-value-0, td.pme-help-0, td.pme-navigation-0, td.pme-cell-0,
	td.pme-key-1, td.pme-value-1, td.pme-help-0, td.pme-navigation-1, td.pme-cell-1,
	td.pme-sortinfo, td.pme-filter { border: #004d9c 1px solid; padding: 3px; }
	td.pme-buttons { text-align: left;   }
	td.pme-message { text-align: center; }
	td.pme-stats   { text-align: right;  }
</style>
</head>
<body>
<h3>Confirm Layout Information</h3>

To change the information, click "Change" at the bottom left, make changes, 
then click "Save".

<p>
If the information is correct, you don't have to do anything.
<p>
Questions?  Contact us at x2011west@pacbell.net.
<p>
Thanks!
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

// email results
$opts['notify']['all'] = $event_tools_notify_email_address;
$opts['notify']['prefix'] = $event_tools_notify_email_prefix;

// MySQL host name, user name, password, database, and table
$opts['tb'] = $event_tools_db_prefix .'eventtools_layouts';

// Name of field which is the unique key
$opts['key'] = 'layout_id';

// Type of key field (int/real/string/date etc.)
$opts['key_type'] = 'int';

// Sorting field(s)
$opts['sort_field'] = array('layout_owner_lastname');

// Number of records to display on the screen
// Value of -1 lists all records in a table
$opts['inc'] = 15;

// Options you wish to give the users
// A - add,  C - change, P - copy, V - view, D - delete,
// F - filter, I - initial sort suppressed
$opts['options'] = 'CV';

// Number of lines to display on multiple selection filters
$opts['multiple'] = '4';

// Navigation style: B - buttons (default), T - text links, G - graphic links
// Buttons position: U - up, D - down (default)
$opts['navigation'] = 'DB';

// Display special page elements
$opts['display'] = array(
	'form'  => true,
	'query' => false,
	'sort'  => false,
	'time'  => true,
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

$opts['filters'] = "layout_owner_email = '".$REMOTE_USER."' ";

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

$opts['fdd']['layout_id'] = array(
  'name'     => 'ID',
  'select'   => 'T',
  'options'  => 'AVCPDR', // auto increment
  'maxlen'   => 11,
  'default'  => '0',
  'sort'     => true
);
$opts['fdd']['layout_owner_firstname'] = array(
  'name'     => 'Owner first name',
  'select'   => 'T',
  'maxlen'   => 64,
  'sort'     => true
);
$opts['fdd']['layout_owner_lastname'] = array(
  'name'     => 'Owner last name',
  'select'   => 'T',
  'maxlen'   => 64,
  'sort'     => true
);
$opts['fdd']['layout_name'] = array(
  'name'     => 'Layout Name',
  'select'   => 'T',
  'maxlen'   => 64,
  'sort'     => true
);
$opts['fdd']['layout_scale'] = array(
  'name'     => 'Scale',
  'select'   => 'T',
  'maxlen'   => 64,
  'help'     => 'N HO HOn30: Listing more than one OK',
  'sort'     => true
);
$opts['fdd']['layout_size'] = array(
  'name'     => 'Size',
  'select'   => 'T',
  'maxlen'   => 64,
  'help'     => 'Sq ft, or dimensions (12 x 12) OK',
  'sort'     => true
);
$opts['fdd']['layout_scenery'] = array(
  'name'     => 'Scenery',
  'select'   => 'T',
  'maxlen'   => 64,
  'help'     => 'Percent completed',
  'sort'     => true
);
$opts['fdd']['layout_control'] = array(
  'name'     => 'Control',
  'select'   => 'T',
  'maxlen'   => 64,
  'help'     => 'DC, DCC type, computer, ...',
  'sort'     => true
);
$opts['fdd']['layout_num_ops'] = array(
  'name'     => 'Num Operators',
  'select'   => 'T',
  'maxlen'   => 5,
  'sort'     => true
);
$opts['fdd']['layout_accessibility'] = array(
  'name'     => 'Accessibility',
  'select'   => 'D',
  'maxlen'   => 10,
  'sort'     => true,
  'values'   => array('table' => $event_tools_db_prefix.'eventtools_accessibility_codes', 
                        'column' => 'accessibility_code',
                        'description' => 'accessibility_name'),
  'trimlen|LF' => 6,
  'default'  => 0
);
$opts['fdd']['layout_owner_url'] = array(
  'name'     => 'Layout URL',
  'select'   => 'T',
  'maxlen'   => 128,
  'help'     => 'Enter a URL here if you want us to link to your web site',
  'sort'     => true
);
$opts['fdd']['layout_street_address'] = array(
  'name'     => 'Street address',
  'select'   => 'T',
  'maxlen'   => 64,
  'help'     => 'Kept private',
  'sort'     => true
);
$opts['fdd']['layout_city'] = array(
  'name'     => 'City',
  'select'   => 'T',
  'maxlen'   => 32,
  'help'     => 'Kept private',
  'sort'     => true
);
$opts['fdd']['layout_state'] = array(
  'name'     => 'State',
  'select'   => 'T',
  'maxlen'   => 2,
  'help'     => 'Kept private',
  'sort'     => true
);
$opts['fdd']['layout_postcode'] = array(
  'name'     => 'Zip code',
  'select'   => 'T',
  'maxlen'   => 10,
  'help'     => 'Kept private',
  'sort'     => true
);
$opts['fdd']['layout_owner_phone'] = array(
  'name'     => 'Owner phone number',
  'select'   => 'T',
  'maxlen'   => 16,
  'help'     => 'Kept private',
  'sort'     => true
);
$opts['fdd']['layout_owner_call_time'] = array(
  'name'     => 'Best time to call',
  'select'   => 'T',
  'maxlen'   => 32,
  'help'     => 'Kept private',
  'sort'     => true
);
$opts['fdd']['layout_short_description'] = array(
  'name'     => 'Short Description',
  'select'   => 'T',
  'maxlen'   => 64,
  'help'     => 'One line, for summary',
  'sort'     => true
);
$opts['fdd']['layout_long_description'] = array(
  'name|LF'  => '(Start of) Description, click view for rest',
  'name'     => 'Full Description',
  'select'   => 'T',
  'maxlen'   => 5000,
  'sort'     => true,
  'help'     => "Don't worry about formatting, we'll handle that",
  'textarea' => array('rows' => 20, 'cols' => 120),
  'trimlen|LF' => 64,
  'nowrap|LF'   => false
);



$opts['fdd']['layout_prototype'] = array(
  'name'     => 'Prototype',
  'select'   => 'T',
  'maxlen'   => 64,
  'sort'     => true
);
$opts['fdd']['layout_era'] = array(
  'name'     => 'Era/Location',
  'select'   => 'T',
  'maxlen'   => 64,
  'sort'     => true,
  'nowrap'   => true
);
$opts['fdd']['layout_mainline_length'] = array(
  'name'     => 'Mainline Length',
  'select'   => 'T',
  'maxlen'   => 64,
  'sort'     => true
);
$opts['fdd']['layout_plan_type'] = array(
  'name'     => 'Layout plan Type',
  'select'   => 'T',
  'maxlen'   => 64,
  'sort'     => true
);
$opts['fdd']['layout_ops_scheme'] = array(
  'name'     => 'Operations Scheme',
  'select'   => 'T',
  'maxlen'   => 64,
  'sort'     => true
);


$opts['fdd']['layout_wheelchair_access'] = array(
  'name'     => 'Wheelchair accessible',
  'select'   => 'O',
  'maxlen'   => 2,
  'sort'     => true,
  'values'   => array('N','Y') 
);
$opts['fdd']['layout_duckunder_entry'] = array(
  'name'     => 'Duck Entry',
  'select'   => 'O',
  'maxlen'   => 2,
  'sort'     => true,
  'values'   => array('N','Y') 
);


// Now important call to phpMyEdit
//require_once 'phpMyEdit.class.php';
//new phpMyEdit($opts);

// to do slide-show mode, for e.g. quick change
require_once 'extensions/phpMyEdit-slide.class.php';
new phpMyEdit_slide($opts);

?>


</body>
</html>
