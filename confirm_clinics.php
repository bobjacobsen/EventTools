<?php 
require_once('access.php'); 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Confirm <?php require_once('access.php'); echo $event_tools_event_name; ?> Clinic(s)</title>
    <link href="../css/clinicsPHP.css" rel="stylesheet" type="text/css" />    
</head>
<body>
<h3>Confirm <?php 
    include_once('mysql2i.class.php'); // migration step
    require_once('access.php'); echo $event_tools_event_name; ?> Clinic(s)</h3>
<p>

<?php
mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
@mysql_select_db($opts['db']) or die( "Unable to select database");

// confirm_clinics.php and verify_clinics.php work together.
// confirm_clinics present the web page with info for the clinician to confirm
// verify_clinics sends the emails that point to 

// Prod test URL: eventtools/confirm_clinics.php?key=2153
// Local test URL: eventtools/confirm_clinics.php?key=1565

// find the user name from the ID
parse_str($_SERVER["QUERY_STRING"], $args);

// check for submission
//foreach ($_POST as $a) echo $a."<br/>";
if ($_POST['submit']) {
    echo "Submission received, ";
    
    // send the email
    //$to = "falken@mac.com";
    //$to = "jacobsen@berkeley.edu";
    $body = "email: ".$_POST['email']."\n\n".
            "OK: ".$_POST['OK']."\n\n".
            "Comment: ".$_POST['clinic-comment']."\n\n".
            "Cell: ".$_POST['cell-number']."\n\n".
            "AV: ".$_POST['av']." ".$_POST['av-comment']."\n\n";
    $subject = "X2011 Clinic Confirmation Update";
    $headers = "from: x2011west@pacbell.net\nbcc: rgj1927@gmail.com";
    mail($to,$subject,$body,$headers);
    
    // do the insert
    $query = "UPDATE ".$event_tools_db_prefix."eventtools_clinics
                SET clinic_ok = '".$_POST['OK']."',
                    clinic_presenter_cell_number = '".$_POST['cell-number']."',
                    clinic_presenter_confirm_comment = '".$_POST['clinic-comment']."',
                    clinic_presenter_av_comment = '".$_POST['av-comment']."',
                    clinic_presenter_av_request = '".$_POST['av']."'
                WHERE id = '".$_POST['id']."'
                ;";
    //echo  $query;
    mysql_query($query);

    echo "thanks!";
    return;
}
// process 1st display

if (! $args["key"]) {
    echo "No key, therefore no information";
    return;
}
$id = ($args["key"]-1445)/12;
//echo '['.$id.']';

$query="
    SELECT *
    FROM ".$event_tools_db_prefix."eventtools_clinics_with_tags 
    WHERE ".$id." = `id`
    ";
//echo $query;
$result=mysql_query($query);
$num=mysql_numrows($result);

$email = mysql_result($result,0,"clinic_presenter_email");

if ($email == "") {
    echo "<b>Failure retrieving email for ID ".$id."</b>";
    return;
}

echo "Dear ".mysql_result($result,0,"clinic_presenter").":<p>";

echo "
In less than two months, the NMRA National Convention will kick 
off in Sacramento, California.
<p>
We're in the process of finalizing the clinics schedule 
for the convention this summer.  
You are receiving this email because you've volunteered to present 
a clinic and have previously been in contact with the show organizers to 
arrange this.
<p>
Below you will find a summary of your clinics, and the current scheduled time:
";

require_once('utilities.php');
require_once('formatting.php');

$where = " id = '".$id."' ";
//echo $where;

format_all_clinics_as_3table($where, "");

echo "
<FORM action=\"confirm_clinics.php\" method=\"post\">
<input type=\"hidden\" name=\"email\" value=\"".$email."\">
<input type=\"hidden\" name=\"id\" value=\"".$id."\">
<p>
Please look it over carefully.
If everything is OK, check the the box here and click
&quot;Submit&quot; below.<p>
<input type=\"checkbox\" name=\"OK\" value=\"Y\" /><b>OK</b>
<p>
If you have any corrections, 
additions or deletions, please describe there here and 
click &quot;Submit&quot; below.<br/>
<TEXTAREA name=\"clinic-comment\" cols=\"80\" wrap=\"virtual\" rows=\"5\">".mysql_result($result,0,"clinic_presenter_confirm_comment")."</TEXTAREA>

<p>";

echo "<b>Cell Number</b><p>
Please provide your cell phone number so that we 
can contact you during the Convention if needed.
This will not be released to the public.<p>
<TEXTAREA name=\"cell-number\" cols=\"20\" wrap=\"virtual\" rows=\"1\"/>".mysql_result($result,0,"clinic_presenter_cell_number")."</TEXTAREA>
<p>
";

$checkcw  = (mysql_result($result,0,"clinic_presenter_av_request") == "conventionwindows") ? " checked " : "" ;
$checkmw  = (mysql_result($result,0,"clinic_presenter_av_request") == "mywindows") ? " checked " : "" ;
$checkmm  = (mysql_result($result,0,"clinic_presenter_av_request") == "mymac") ? " checked " : "" ;
$checkml  = (mysql_result($result,0,"clinic_presenter_av_request") == "mylinunx") ? " checked " : "" ;
$checknn  = (mysql_result($result,0,"clinic_presenter_av_request") == "none") ? " checked " : "" ;

echo "<b>Presentation Equipment</b><p>
X2011West will be providing a VGA-based computer projection system for all 
clinic rooms, as well as audio equipment for larger clinic rooms.
<p>
We will have PC laptops available for use, 
but we will also allow presenters to attach their personal 
laptop to our projection equipment provided it is compatible. 
We will have a projector set up in the clinics office to verify 
proper operation. Please select which type of equipment you'd like to use:<p>
<input type=\"radio\" name=\"av\" value=\"conventionwindows\" ".$checkcw." />Convention Windows computer<br />
<input type=\"radio\" name=\"av\" value=\"mywindows\" ".$checkmw." />My Windows computer<br />
<input type=\"radio\" name=\"av\" value=\"mymac\" ".$checkmm." />My Mac computer<br />
<input type=\"radio\" name=\"av\" value=\"mylinunx\" ".$checkml." />My Linux computer<br />
<input type=\"radio\" name=\"av\" value=\"none\" ".$checknn." />None<br />
<p>";

echo "If you have any needs for additional presentation equipment, 
please use the form below to make requests.<p>

<TEXTAREA name=\"av-comment\" cols=\"80\" wrap=\"virtual\" rows=\"5\"/>".mysql_result($result,0,"clinic_presenter_av_request")."</TEXTAREA>

";
echo "<br/><b>Recording Policy</b><p>
X2011West will not be allowing any video or audio recording of 
clinic presentations at the convention.
<p>
<p>

Sincerely,<br/>
David Falkenburg and Anthony Thompson X2011West Clinics Coordinators
<p>
<INPUT type=\"submit\" name=\"submit\" value=\"Submit\">
</form>";


?>


</body>
</html>
