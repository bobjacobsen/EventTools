<!DOCTYPE html>
<?php
    require_once('access.php');

echo "<html>
<head>
	<title>".$event_tools_event_name." Event Tools</title>
</head>
<body>
<h1>".$event_tools_event_name." Event Tools</h1>

<h2 id=\"events\">Events</h2>
EventTools pages for tours, clinics and operating sessions.

"?>

<p>

<table border="1">
<?php
echo "
<tr><th></th>";

if ($event_tools_option_general_tours ) echo '<th>General Tours</th>';
if ($event_tools_option_layout_tours)   echo '<th>Layout Tours</th>';
if ($event_tools_option_other_events)   echo '<th>Misc Events</th>';
if ($event_tools_option_layouts)        echo '<th>Layouts</th>';
if ($event_tools_option_clinics)        echo '<th>Clinics</th>';
if ($event_tools_option_op_sessions)    echo '<th>Op Sessions</th>';

echo "
</tr>
<tr>
    <th>Enter/Change Content</th>
";

if ($event_tools_option_general_tours ) echo '<td><a href="edit_general_tour_all.php">Enter/Change</a></td>';
if ($event_tools_option_layout_tours)   echo '<td><a href="edit_layout_tour_all.php">Enter/Change</a></td>';
if ($event_tools_option_other_events)   echo '<td><a href="edit_misc.php">Enter/Change</a></td>';
if ($event_tools_option_layouts)        echo '<td><a href="edit_layouts_all.php">Enter/Change</a></td>';
if ($event_tools_option_clinics)        echo '<td><a href="edit_clinics.php">Enter/Change</a></td>';
if ($event_tools_option_op_sessions)    echo '<td><a href="edit_ops_add_layout.php">Enter/Change Sessions</a></td>';

echo '
</tr><tr>
    <th>Other Edits</th>
';

if ($event_tools_option_general_tours ) echo '<td></td>';
if ($event_tools_option_layout_tours)   echo '<td><a href="edit_layout_tour_add_layout.php">Add Layouts To Tour</a></td>';
if ($event_tools_option_other_events)   echo '<td><a href="edit_misc_event_tags.php">Add or remove tags</a></td>';
if ($event_tools_option_layouts)        echo '<td><a href="edit_layouts_entry.php">Quick Entry</a></td>';
if ($event_tools_option_clinics)        echo '<td><a href="edit_clinic_tags.php">Add or remove tags</a></td>';
if ($event_tools_option_op_sessions)    echo '<td><a href="edit_ops_all.php">Enter/Change Attendee Requests</a></td>';

echo '</tr><tr>
    <th>Review Content</th>';

if ($event_tools_option_general_tours ) echo '<td><a href="list_all_general_tours.php">List</a></td>';
if ($event_tools_option_layout_tours)   echo '<td><a href="list_all_layout_tours.php">List</a></td>';
if ($event_tools_option_other_events)   echo '<td><a href="list_all_misc_events.php">List</a></td>';
if ($event_tools_option_layouts)        echo '<td><a href="list_all_layouts.php">List</a></td>';
if ($event_tools_option_clinics)        echo '<td><a href="list_all_clinics.php">List</a></td>';
if ($event_tools_option_op_sessions)    echo '<td></td>';

echo '</tr><tr>
    <th>Index Example</th>';

if ($event_tools_option_general_tours ) echo '<td><a href="index_general_tours.php">Index</a></td>';
if ($event_tools_option_layout_tours)   echo '<td><a href="index_layout_tours.php">Index</a></td>';
if ($event_tools_option_other_events)   echo '<td><a href="index_misc_events.php">Index</a></td>';
if ($event_tools_option_layouts)        echo '<td><a href="index_layouts.php">Index</a></td></td>';
if ($event_tools_option_clinics)        echo '<td><a href="index_clinics.php">Index</a></td>';
if ($event_tools_option_op_sessions)    echo '<td><a href="index_ops.php">Session Index</a></td>';

echo '</tr><tr>
    <th>Format Example</th>';

if ($event_tools_option_general_tours ) echo '<td><a href="format_all_general_tours.php">Formatted View</a></td>';
if ($event_tools_option_layout_tours)   echo '<td><a href="format_all_layout_tours.php">Formatted View</a></td>';
if ($event_tools_option_other_events)   echo '<td><a href="format_all_misc_events.php">Formatted View</td>';
if ($event_tools_option_layouts)        echo '<td><a href="format_all_layouts.php">Formatted View</a></td>';
if ($event_tools_option_clinics)        echo '<td><a href="format_all_clinics.php">Formatted View</a></td>';
if ($event_tools_option_op_sessions)    echo '<td><a href="view_ops_by_day.php">Formatted Ops By Day</a></td>';


echo '</tr><tr>
    <th>Test Pages</th>';

if ($event_tools_option_general_tours ) echo '<td>
    </td>';
if ($event_tools_option_layout_tours)   echo '<td>
    </td>';
if ($event_tools_option_other_events)   echo '<td>
    </td>';
if ($event_tools_option_layouts)        echo '<td>
    </td>';
if ($event_tools_option_clinics)        echo '<td>
    </td>';
if ($event_tools_option_op_sessions)    echo '<td>
    </td>';

echo '</tr><tr>
    <th>Other Displays</th>';

if ($event_tools_option_general_tours ) echo '<td>
        <a href="calendar/show_general_tour_cal.php">Interactive General/Proto Tour Calendar<a/>
    </td>';
if ($event_tools_option_layout_tours)   echo '<td>
        <a href="map_layout_tour.php?tour=L501">Tour Maps (put tour number in URL)<a/><p>
        <a href="calendar/show_layout_tour_cal.php">Interactive Layout Tour Calendar<a/>
    </td>';
if ($event_tools_option_other_events)   echo '<td><a href="format_misc_by_loc.php">Misc Events Grouped by Location<a/><p>
        <a href="calendar/show_misc_events_cal.php">Interactive Misc Events Calendar<a/>
    </td>';
if ($event_tools_option_layouts)        echo '<td>
    <a href="map_layouts.php?minstatus=50">Map of all layouts</a><br/>(status in URL)<p>
    <a href="map_one_layout.php?minstatus=50">Layout directions</a><br/>(name in URL)<p>
    </td>';
if ($event_tools_option_clinics)        echo '<td><a href="format_clinics_by_loc.php">Clinics Grouped by Location<a/><p>
        <a href="calendar/show_clinics_cal.php">Interactive Clinics Calendar<a/>
    </td>';
if ($event_tools_option_op_sessions)    echo '<td><a href="ops_req_summary.php">Requests Summary<a/><p>
    <a href="format_all_ops.php">Operating Layout Table</a><br/>
    <a href="format_all_ops_address.php">Operating Layout Addresses</a><p>
    <a href="format_ops_by_day.php?where=status_code%3E50">Operating Sessions vs Day</a><p>
    <hr>
    <a href="ops_assign_group.php">Start Assignments with Grouping</a>  <p>
    The following display a given assignment cycle.<p>
    <a href="ops_print_by_layout.php">Session Rosters</a>    <br>
    <a href="ops_print_summary.php">Printable Session Rosters</a>    <p>
    <a href="ops_print_by_attendee.php">Assigned Sessions by Attendee</a><p>
    <a href="ops_assign_email_operator.php">Email Roster to Assigned Operators</a>    <br>
    <a href="ops_assign_email_owner.php">Email Roster to Layout Owners</a>

    </td>';

echo '</td>

</table>
';

if (!$event_tools_option_general_tours ||
    !$event_tools_option_layout_tours ||
    !$event_tools_option_other_events ||
    !$event_tools_option_layouts ||
    !$event_tools_option_clinics ||
    !$event_tools_option_op_sessions ) {

    if (!$event_tools_option_general_tours) echo "General Tours feature is disabled.<br/>";
    if (!$event_tools_option_layout_tours) echo "Layout Tours feature is disabled.<br/>";
    if (!$event_tools_option_other_events) echo "Other Events feature is disabled.<br/>";
    if (!$event_tools_option_layouts) echo "Layouts feature is disabled.<br/>";
    if (!$event_tools_option_clinics) echo "Clinics feature is disabled.<br/>";
    if (!$event_tools_option_op_sessions) echo "Op Session feature is disabled.<br/>";
}

echo '<h2 id="people">People</h2>
';

if ($event_tools_option_zen_cart_used) {
    echo '<p>Contact and attendee lists are being maintained in Zen Cart.</p>';
} else {
    echo '<p>';
    echo '<table border="1">';
    echo '<tr><th>Contacts<br/></th><td>';
    echo '<a href="index_attendees.php">Contacts Summary Table</a><p>';
    echo '<a href="edit_zen_repl_customer.php">View/Edit contact info</a><br/>';
    echo '<a href="edit_zen_repl_address.php">View/Edit addresses</a><p>';
    echo '</td><td>Contacts are people who have pre-registered<br/>or are otherwise known to the committee.</td></tr>';
    echo '<tr><th>Attendees</th><td>';
    echo '<a href="ops_reg_attendee_summary.php">Attendee Summary Table<a/><p>';
    if ($event_tools_ops_session_by_category) echo '<a href="edit_user_req_group.php">View/Edit Attendee Category Numbers</a><p>';
    echo '<a href="ops_req_single.php">Single Attendee Summary (enter email)<a/><p>';
    // the above used to be ops_req.php but that one is broken
    echo '<a href="edit_ops_one.php">Form for attendees to change their own request (requires ?email= arg)</a><p>';
    echo '<a href="ops_print_by_attendee.php">Assigned Sessions by Attendee (enter cycle)</a><p>';
    echo '<a href="ops_list_group.php">Email list for attendees (enter cycle)</a>    <p>';
    echo '</td><td>Attendees have registered and/or<br/>requested operating session<br/>assignments<p>They also appear in the contacts pages,<br/>where you can edit their contact info.</td></tr></table>';
}

?>


<h2 id="configuration">Configuration</h2>
You can define global optional quantities that can be attached to each
individual attendee. This can be used to capture a Y/N answer to "I'd like to attend the dinner",
for example.
<table border="1">
<tr><th>Attendee Options<br/>(Registration Extra Questions)</th>
<td>
<a href="edit_customer_options.php">Edit Attendee Options</a>
</td>
</tr></table>
<p>
The next section lets you edit general information used by EventTools.
<p>
<ul>
<li>Keys for status codes
<li>Keys for handicapped access values.
<li>Keys for locations (e.g. "Main Hall" or "Offsite")
</ul>
Changing the text associated with a key will change all existing entries
from the old text to the new text. Add another key instead of changing
an existing one if you want to leave existing events unchanged.
<table border="1">
<tr><th>General Edits</th>
<td>
<a href="edit_status_values.php">Edit Status Keys<a/><br/>
<a href="edit_handicap_access_keys.php">Edit Handicap Access Keys<a/><br/>
<a href="edit_location_keys.php">Edit Location Keys<a/><br/>
</td>
</tr></table>


<?php

if (
        $event_tools_constrain_scale ||
        $event_tools_constrain_gauge ||
        $event_tools_constrain_era   ||
        $event_tools_constrain_class ||
        $event_tools_constrain_theme ||
        $event_tools_constrain_locale ||

        $event_tools_constrain_scenery ||
        $event_tools_constrain_plan_type ||
        $event_tools_constrain_ops_scheme ||
        $event_tools_constrain_control ||
        $event_tools_constrain_fidelity ||
        $event_tools_constrain_rigor ||
        $event_tools_constrain_documentation ||
        $event_tools_constrain_session_pace ||
        $event_tools_constrain_car_forwarding ||
        $event_tools_constrain_tone ||
        $event_tools_constrain_dispatched_by1 ||
        $event_tools_constrain_dispatched_by2 ||
        $event_tools_constrain_communications ) {
    echo "
        <p>
        EventTools can optionally require that only certain
        values be entered in tables, for example for scale or gauge.
        If you're using that feature, enter the acceptable values using
        the following links.
        <table border=\"1\">
        <tr><th>Character</th><th>Construction</th><th>Operation</th></tr>
    ";

    echo '<tr><td>';
    if ($event_tools_constrain_scale)  echo '<a href="edit_constrain_scale.php" >Scales</a>';
    echo '</td><td>';
    if ($event_tools_constrain_scenery)  echo '<a href="edit_constrain_scenery.php" >Scenery Completion</a>';
    echo '</td><td>';
    if ($event_tools_constrain_ops_scheme)  echo '<a href="edit_constrain_ops_scheme.php" >Ops Scheme</a>';
    echo '</td></tr>';

    echo '<tr><td>';
    if ($event_tools_constrain_gauge)  echo '<a href="edit_constrain_gauge.php" >Gauges</a>';
    echo '</td><td>';
    if ($event_tools_constrain_plan_type)  echo '<a href="edit_constrain_plan_type.php" >Plan Types</a>';
    echo '</td><td>';
    if ($event_tools_constrain_rigor)  echo '<a href="edit_constrain_rigor.php" >Rigor</a>';
    echo '</td></tr>';

    echo '<tr><td>';
    if ($event_tools_constrain_era)    echo '<a href="edit_constrain_era.php"   >Eras</a>';
    echo '</td><td>';
    if ($event_tools_constrain_control)  echo '<a href="edit_constrain_control.php" >Control Systems</a>';
    echo '</td><td>';
    if ($event_tools_constrain_documentation)  echo '<a href="edit_constrain_documentation.php" >Documentation</a>';
    echo '</td></tr>';

    echo '<tr><td>';
    if ($event_tools_constrain_class)  echo '<a href="edit_constrain_class.php" >Railroad Classes</a>';
    echo '</td><td>';
    if ($event_tools_constrain_fidelity)  echo '<a href="edit_constrain_fidelity.php" >Fidelity</a>';
    echo '</td><td>';
    if ($event_tools_constrain_session_pace)  echo '<a href="edit_constrain_session_pace.php" >Session Pace</a>';
    echo '</td></tr>';

    echo '<tr><td>';
    if ($event_tools_constrain_theme)  echo '<a href="edit_constrain_theme.php" >Themes</a>';
    echo '</td><td>';
    echo '</td><td>';
    if ($event_tools_constrain_car_forwarding)  echo '<a href="edit_constrain_car_forwarding.php" >Car Forwarding</a>';
    echo '</td></tr>';

    echo '<tr><td>';
    if ($event_tools_constrain_locale) echo '<a href="edit_constrain_locale.php">Locales</a>';
    echo '</td><td>';
    echo '</td><td>';
    if ($event_tools_constrain_tone)  echo '<a href="edit_constrain_tone.php" >Tone</a>';
    echo '</td></tr>';

    echo '<tr><td>';
    echo '</td><td>';
    echo '</td><td>';
    if ($event_tools_constrain_dispatched_by1)  echo '<a href="edit_constrain_dispatched_by1.php" >Dispatching (Primary)</a>';
    echo '</td></tr>';

    echo '<tr><td>';
    echo '</td><td>';
    echo '</td><td>';
    if ($event_tools_constrain_dispatched_by2)  echo '<a href="edit_constrain_dispatched_by2.php" >Dispatching (secondary)</a>';
    echo '</td></tr>';

    echo '<tr><td>';
    echo '</td><td>';
    echo '</td><td>';
    if ($event_tools_constrain_communications)  echo '<a href="edit_constrain_communications.php" >Communications</a>';
    echo '</td></tr></table>';
}

if ( $event_tools_option_general_tours ||
       $event_tools_option_layout_tours ||
       $event_tools_option_other_events ||
       $event_tools_option_clinics ) {

    echo '
        <h2 id="program">Program and Calendar</h2>
        The following links provide formatted information for inclusion in the
        printed program.
        <table border="1">
        <tr><th>Program Printing</th>
        <td>
        <a href="ip_layout_tours.php">Layout Tours<a/><br/>
        <a href="ip_general_tours.php?type=P">Prototype Tours<a/><br/>
        <a href="ip_general_tours.php?type=G">General Tours<a/><br/>
        <a href="ip_clinics.php">Clinics by time<a/><br/>
        <a href="ip_clinics.php?order=presenter">Clinics by presenter field<a/><br/>
        <a href="index_clinics.php?tag=NASG">Clinics by tag (fill in name in URL)<a/><br/>
        </td>
        </tr></table>

        <p>
        The following links are to pages that provide information for specific purposes.
        <table border="1">
        <tr><th>Special Displays</th>
        <td>
        <a href="index_advanced_section.php">Index Advanced Section Tours<a/><br/>
        <a href="format_advanced_section.php">All Advanced Section Tours<a/><p/>
        <a href="calendar_form.php">Download Events Calendar<a/><p/>
        <a href="calendar/show_all_cal.php">Full Interactive Calendar<a/><p/>
        <a href="calendar_guidebook_form.php">Download for Guidebook install<a/>
        </td>
        </tr></table>
    ';
}
?>

<h2 id="information">Information</h2>
For more information on using EventTools, please see the
most recent draft of the
<a href="EventTools.pdf">EventTools User Guide</a>.
<br>
For information on using EventTools at an operating meet, please see the
most recent draft of the
<a href="EventToolsOps.pdf">EventTools Op Sessions Guide</a>.
The
<a href="EventToolsOpsQuickStart.pdf">Quick Start guide</a>
will walk you through entering the initial data.
<br>
For EventTools installation and configuration information, please see the
most recent draft of the
<a href="EventToolsAdmin.pdf">EventTools Administration Guide</a>.
<br>
For information on using Cascading Style Sheets (CSS) to
configure how EventTools information is displayed on your web pages,
please see the most recent draft of the
<a href="EventToolsCSS.pdf">EventTools CSS Guide</a>.
<br>
For example query code, see the <a href="samples.php">samples page</a>.

<p>
There are sample pages for
<a href="sample_ops_prereg_form.php">preregistration</a>
and
<a href="sample_ops_reg_form.php">registration and requesting op sessions</a>.
<p>
EventTools was written and is maintained by Bob Jacobsen,
please contact him directly if you want more information on using EventTools.
This is the
<a href="<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/eventtools/index.php';  ?>">
<?php echo $event_tools_event_name;  ?> EventTools index page</a>
running on PHP <?php echo phpversion()  ?>

</body>
</html>
