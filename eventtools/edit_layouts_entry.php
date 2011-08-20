<?php require_once('access.php'); require_once('secure.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Edit Layouts (Short Form)</title>
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
<h3>Edit Layouts (Short Form)</h3>
<a href="index.php">Back to main page</a>
<p>
<?php

function displayConstrained($name, $key, $constrain_table) {
    $tempArray = 
    array(
      'name'     => $name,
      'select'   => 'T',
      'maxlen'   => 64,
      'sort'     => true,
      'nowrap'   => true
    );
    if ($key) {
        $tempArray['values'] = array('table' => $constrain_table, 
                            'column' => 'constrain_value');
    }
    return $tempArray;
}

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
$opts['options'] = 'ACPVDF';

// Number of lines to display on multiple selection filters
$opts['multiple'] = '4';

// Navigation style: B - buttons (default), T - text links, G - graphic links
// Buttons position: U - up, D - down (default)
$opts['navigation'] = 'DB';

// Display special page elements
$opts['display'] = array(
	'form'  => true,
	'query' => true,
	'sort'  => true,
	'time'  => true,
	'tabs'  => true
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
  'options'  => 'AVCPDRL', // auto increment
  'maxlen'   => 11,
  'default'  => '0',
  'sort'     => true
);
$opts['fdd']['layout_owner_firstname'] = array(
  'name'     => 'Owner first name',
  'select'   => 'T',
  'maxlen'   => 16,
  'sort'     => true
);
$opts['fdd']['layout_owner_lastname'] = array(
  'name'     => 'Owner last name',
  'select'   => 'T',
  'maxlen'   => 32,
  'sort'     => true
);
$opts['fdd']['layout_name'] = array(
  'name'     => 'Layout Name',
  'select'   => 'T',
  'maxlen'   => 64,
  'sort'     => true
);
$opts['fdd']['layout_status_code'] = array(
  'name'     => 'Status',
  'select'   => 'D',
  'maxlen'   => 36,
  'sort'     => true,
  'values'   => array('table' => $event_tools_db_prefix.'eventtools_event_status_values', 
                        'column' => 'event_status_code',
                        'orderby' => 'event_status_code',
                        'description' => 'event_status_name'),
  'trimlen|LF' => 36
);


$opts['fdd']['layout_scale'] = displayConstrained('Scale', $event_tools_constrain_scale, $event_tools_db_prefix.'eventtools_constrain_scale');
$opts['fdd']['layout_gauge'] = displayConstrained('Gauge', $event_tools_constrain_gauge, $event_tools_db_prefix.'eventtools_constrain_gauge');
$opts['fdd']['layout_era'] = displayConstrained('Era', $event_tools_constrain_era, $event_tools_db_prefix.'eventtools_constrain_era');
$opts['fdd']['layout_class'] = displayConstrained('Class', $event_tools_constrain_class, $event_tools_db_prefix.'eventtools_constrain_class');
$opts['fdd']['layout_theme'] = displayConstrained('Theme', $event_tools_constrain_theme, $event_tools_db_prefix.'eventtools_constrain_theme');
$opts['fdd']['layout_fidelity'] = displayConstrained('Fidelity to Prototype', $event_tools_constrain_fidelity, $event_tools_db_prefix.'eventtools_constrain_fidelity');
$opts['fdd']['layout_locale'] = displayConstrained('Locale', $event_tools_constrain_locale, $event_tools_db_prefix.'eventtools_constrain_locale');
$opts['fdd']['layout_plan_type'] = displayConstrained('Plan Type', $event_tools_constrain_plan_type, $event_tools_db_prefix.'eventtools_constrain_plan_type');
$opts['fdd']['layout_scenery'] = displayConstrained('Scenery', $event_tools_constrain_scenery, $event_tools_db_prefix.'eventtools_constrain_scenery');
$opts['fdd']['layout_communications'] = displayConstrained('Communications', $event_tools_constrain_communications, $event_tools_db_prefix.'eventtools_constrain_communications');
$opts['fdd']['layout_dispatched_by1'] = displayConstrained('Dispatched By (primary)', $event_tools_constrain_dispatched_by1, $event_tools_db_prefix.'eventtools_constrain_dispatched_by1');
$opts['fdd']['layout_control'] = displayConstrained('Control', $event_tools_constrain_control, $event_tools_db_prefix.'eventtools_constrain_control');


$opts['fdd']['layout_size'] = array(
  'name'     => 'Size',
  'select'   => 'T',
  'maxlen'   => 64,
  'sort'     => true
);

$opts['fdd']['layout_allow_photo'] = array(
  'name'     => 'Allow Photos',
  'select'   => 'O',
  'maxlen'   => 2,
  'sort'     => true,
  'values'   => array('N','Y') 
);


// Now important call to phpMyEdit
require_once 'phpMyEdit.class.php';
new phpMyEdit($opts);

// to do slide-show mode, for e.g. quick change
//require_once 'extensions/phpMyEdit-slide.class.php';
//new phpMyEdit_slide($opts);

?>


</body>
</html>
