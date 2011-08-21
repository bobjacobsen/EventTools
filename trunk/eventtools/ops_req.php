<?php 
    require_once('access.php'); 
    // required to get user name
    $user = $_SERVER['PHP_AUTH_USER'];
    $REMOTE_USER = $user;  // for phpMyEdit
    if ($user == '') {
        // fail
        header('WWW-Authenticate: Basic realm="X2011west Op Session Request (enter your email address for name)"');
        header('HTTP/1.0 401 Unauthorized');
    }
    
    if (! (strpos($user, "@") && strpos($user, ".")) ) {
        header('WWW-Authenticate: Basic realm="X2011west Op Session Request (be sure to enter your email address for name)"');
        header('HTTP/1.0 401 Unauthorized');
        echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		    "http://www.w3.org/TR/html4/loose.dtd">
            <html>
            <head>
            <title>Operating Session Request - Error</title>
            </head>
            <body>
            <h2>Operating Session Request - Error</h2>
            You must provide a valid email address
            as part of entering your request.
            Please hit the back button on your browser and 
            try again.
            </body>
            ';
        return;
    }
    
    global $opts, $event_tools_db_prefix;
    mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
    @mysql_select_db($opts['db']) or die( "Unable to select database");


    // OK, now check if account in cart
    $query = "SELECT * 
            FROM ".$event_tools_db_prefix ."customers
            WHERE customers_email_address = '".$REMOTE_USER."';
    ";

    $result=mysql_query($query);
    $num=mysql_numrows($result);

    if ( $num == 0) {
        header('WWW-Authenticate: Basic realm="X2011west Op Session Request (be sure to enter your email address for name)"');
        header('HTTP/1.0 401 Unauthorized');
        echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		    "http://www.w3.org/TR/html4/loose.dtd">
            <html>
            <head>
            <title>Operating Session Request - Error</title>
            </head>
            <body>
            <h2>Operating Session Request - Error</h2>
            You must provide the email address you
            used for your account on the X2011 West online store.
            </body>
            ';
        return;
    }

    // OK, now check if ops req record exists
    $query = "SELECT * 
            FROM ".$event_tools_db_prefix ."eventtools_opsession_req
            WHERE opsreq_person_email = '".$REMOTE_USER."';
    ";

    $result=mysql_query($query);
    $num=mysql_numrows($result);

    if ($num == 0) {
        // doesn't exist, insert
        $query = "
            INSERT INTO ".$event_tools_db_prefix ."eventtools_opsession_req
            ( opsreq_person_email )
            VALUE
            ( '".$REMOTE_USER."' );
        ";
        $result=mysql_query($query);
    }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Operating Session Request</title>
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
	.pme-more { visibility:hidden; } // suppress Apply button
</style>
</head>
<body>
<div id="x2011-logo"><a href="../index.html"><img alt="Extra 2011 West logo" src="../images/X2011_Logo.jpg" width="205" height="68" align="right"></a></div>
<h2>Operating Session Request</h2>

On this page, you can view the requests you are making for OPSIG operating sessions at the X2011 West convention.
<p>
You do not need to be an OPSIG member to request and take part in OPSIG operations sessions, but you
do need to be registered for the convention.
<p>
To make a new request if you have not made one yet, or to change requests you have already made, 
click the "Change" button at the bottom of this page.  
This will take you to a selection page. 
<p>
Then to add a new request, click on the down arrow in the first priority row 
and select your first priority for an operating session.  
Continue in this manner until you have selected all the sessions you want to attend ranked by priority, 
and then click "Save". To change an existing request, click on the drop down arrow next to that request, 
and select your new choice.  When you are finished, click "Save".  Clicking "Cancel" will leave your existing requests unchanged.
<p>
You will be able to make changes to your requests up until approximately April 15th.
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
$opts['navigation'] = 'DB';

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

$opts['filters'] = "opsreq_person_email = '".$REMOTE_USER."' ";

// Options you wish to give the users
// A - add,  C - change, P - copy, V - view, D - delete,
// F - filter, I - initial sort suppressed
$opts['options'] = 'CV';

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
  'options'  => 'LCVR',
  'maxlen'   => 32,
  'sort'     => true
);
$opts['fdd']['opsreq_pri1'] = array(
  'name'     => '1st priority',
  'help'     => "If blank, you haven't selected any preferred operating sessions",
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
$opts['fdd']['opsreq_any'] = array(
  'name'     => "Any session OK, not just priorities?",
  'select'   => 'T',
  'maxlen'   => 5,
  'sort'     => true,
  'values'   => array('Y','N')
);
$opts['fdd']['opsreq_number'] = array(
  'name'     => "Number of sessions you'd like",
  'help'     => "We'll try to schedule as many sessions as possible for you, up to this limit",
  'select'   => 'T',
  'maxlen'   => 5,
  'sort'     => true,
  'values'   => array('1','2','3','4','5','6','7','8')
);
$opts['fdd']['opsreq_comment'] = array(
  'name'     => 'Any comments?',
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

// start the bottom matter
echo '<p>';

global $opts, $event_tools_db_prefix;
mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
@mysql_select_db($opts['db']) or die( "Unable to select database");
$query = "SELECT * 
        FROM ".$event_tools_db_prefix ."eventtools_opsession_req
        WHERE opsreq_person_email = '".$REMOTE_USER."';
";

$result=mysql_query($query);
$num=mysql_numrows($result);

global $eventtools_warn_loud;
$eventtools_warn_loud = FALSE;

function checkForFull($id, $result) {
    global $eventtools_warn_loud;
    if (         
        mysql_result($result,0,"opsreq_pri1") == $id ||
        mysql_result($result,0,"opsreq_pri2") == $id ||
        mysql_result($result,0,"opsreq_pri3") == $id ||
        mysql_result($result,0,"opsreq_pri4") == $id ||
        mysql_result($result,0,"opsreq_pri5") == $id ||
        mysql_result($result,0,"opsreq_pri6") == $id ||
        mysql_result($result,0,"opsreq_pri7") == $id ||
        mysql_result($result,0,"opsreq_pri8") == $id ) $eventtools_warn_loud = TRUE;
}

checkForFull(55, $result);  // Clemens July 4
checkForFull(69, $result);  // Clemens July 4
checkForFull(70, $result);  // Clemens July 7PM
checkForFull(12, $result);  // Dias/Burgess
checkForFull(14, $result);  // Gulley July 5
checkForFull(13, $result);  // Gulley July 7
checkForFull(22, $result);  // Houston
checkForFull(23, $result);  // Kaufman 


if ($eventtools_warn_loud) {
    echo '<b><font color="red" size="4">';
}
echo "The following operating sessions are already oversubscribed.  If you request one of these,
    you may still get it, but your odds are better if you request other sessions.
    <ul>
    <li>Clemens, both sessions on July 4 and the July 7 afternoon session
    <li>Dias/Burgess 
    <li>Gulley, both sessions
    <li>Houston, July 5th session
    <li>Kaufman on Thursday July 7th
    </ul>"
    ;
    
if ($eventtools_warn_loud) {
    echo '</font></b>';
}

echo '<p>

    For more information on operating sessions at X2011 West, please
    see the
    <a href="http://x2011west.org/opsig.html">X2011 West OPSIG page</a>.
    If you have any trouble, please 
    <a href="mailto:x2011west@pacbell.net">email the X2011 West web organizers</a>.
    <p>';

?>


</body>
</html>