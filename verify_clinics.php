<?php

include_once('mysql2i.class.php'); // migration step
require_once('access.php');
require_once('parsers.php');
require_once('utilities.php');
require_once('mail_utilities.php');

// confirm_clinics.php and verify_clinics.php work together.
// confirm_clinics present the web page with info for the clinician to confirm
// verify_clinics sends the emails that point to

// Over-ride globals for on visibility here
$event_tools_replace_on_data_warn = TRUE;  // TRUE replace with text, FALSE leave as is
$event_tools_replace_on_data_error = TRUE;  // TRUE replace with text, FALSE leave as is

$where = parse_clinic_query();
$order = parse_order();

mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
@mysql_select_db($opts['db']) or die( "Unable to select database");

$query="
    SELECT *
    FROM ".$event_tools_db_prefix."eventtools_clinics_with_tags
    WHERE clinic_presenter_email != ''
    ";

//    WHERE clinic_presenter_email = 'jacobsen@berkeley.edu'
//    WHERE clinic_presenter_email != ''
//    GROUP BY clinic_presenter_email

if ($where != NULL)
    $query = $query.' WHERE '.$where.' ';

$query = $query."
    ;
";

$result=mysql_query($query);

$num=mysql_numrows($result);

$i=0;
$lastmajorkey= "";

$part1 = "
X2011 West, the 2011 NMRA National Convention is less than 2 months away.  According to our records, we have you down as presenting one or more clinics in Sacramento.

Please take a moment to review the following web page to confirm your participation:
";

$part2 = "
Check the page to confirm your schedule, and make any special requests.  Be sure to click the submit button at the bottom of the page to ensure your information is sent along.

If you received this in error, or have ANY questions, feel fee to email us at clinics@x2011west.org

Sincerely,

Dave Falkenburg and Anthony Thompson
X2011West Clinics
";

while ($i < $num) {
    if ($lastmajorkey != mysql_result($result,$i,"id")) {
        $lastmajorkey = mysql_result($result,$i,"id");

        echo "<br>sending to ".mysql_result($result,$i,"clinic_presenter_email").' id '.mysql_result($result,$i,"id").' key '.(1445+12*mysql_result($result,$i,"id"));

        $to = mysql_result($result,$i,"clinic_presenter_email");
        $subject = "X2011 Clinic Confirmation";

        $url = "http://x2011west.org/eventtools/confirm_clinics.php?key=".(1445+12*mysql_result($result,$i,"id"));

        $body = $part1.$url.$part2;

        sendNotificationEmail($to, $subject, $body);
    }

    $i++;
}

mysql_close();

echo "</clinics>\n";
?>

