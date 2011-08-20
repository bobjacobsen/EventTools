<!DOCTYPE html>
<?php 
    require_once('access.php'); 

echo "<html>
<head>
	<title>".$event_tools_event_name." Event Tools</title>
</head>
<body>
<h1>".$event_tools_event_name." Event Tools</h1>

Provides access to Event Tools for tours and clinics.

"?>

<p>

<table border="1">
<tr><th></th><th>General Tours</th><th>Layout Tours</th><th>Other Events</th><th>Layouts</th><th>Clinics</th><th>Op Sessions</th></tr>
<tr>
    <th>Enter/Change Content</th>
    <td><a href="edit_general_tour_all.php">Enter/Change</a></td>
    <td><a href="edit_layout_tour_all.php">Enter/Change</a></td>
    <td><a href="edit_misc.php">Enter/Change</a></td>
    <td><a href="edit_layouts_all.php">Enter/Change</a></td>
    <td><a href="edit_clinics.php">Enter/Change</a></td>
    <td><a href="edit_ops_add_layout.php">Enter/Change</a></td>
</tr><tr>
    <th>Other Edits</th>
    <td></td>
    <td><a href="edit_layout_tour_add_layout.php">Add Layouts To Tour</a></td>
    <td><a href="edit_misc_event_tags.php">Add or remove tags</a></td>
    <td><a href="edit_layouts_entry.php">Quick Entry</a></td>
    <td><a href="edit_clinic_tags.php">Add or remove tags</a></td>
    <td><a href="edit_ops_all.php">Enter/Change User Requests</a></td>
</tr><tr>
    <th>Review Content</th>
    <td><a href="list_all_general_tours.php">List</a></td>
    <td><a href="list_all_layout_tours.php">List</a></td>
    <td><a href="list_all_misc_events.php">List</a></td>
    <td><a href="list_all_layouts.php">List</a></td>
    <td><a href="list_all_clinics.php">List</a></td>
    <td></td>
</tr><tr>
    <th>Index Example</th>
    <td><a href="index_general_tours.php">Index</a></td>
    <td><a href="index_layout_tours.php">Index</a></td>
    <td><a href="index_misc_events.php">Index</a></td>
    <td><a href="index_layouts.php">Index</a></td></td>
    <td><a href="index_clinics.php">Index</a></td>
    <td><a href="index_ops.php">Index</a></td>
</tr><tr>
    <th>Format Example</th>
    <td><a href="format_all_general_tours.php">Formatted View</a></td>
    <td><a href="format_all_layout_tours.php">Formatted View</a></td>
    <td><a href="format_all_misc_events.php">Formatted View</td>
    <td><a href="format_all_layouts.php">Formatted View</a></td>
    <td><a href="format_all_clinics.php">Formatted View</td>
    <td></td>
</tr><tr>
    <th>Test Pages</th>
    <td><a href="../generalTable.php">General Table</a><br/>
        <a href="../generalIndex.php">General Index</a>
    </td>
    <td><a href="../layoutToursTable.php">Layout Tour Table</a><br/>
        <a href="../layoutToursIndex.php">Layout Tour Index</a>
    </td>
    <td><a href="../miscEventsTable.php">Misc Event Table</a><br/>
        <a href="../miscEventsIndex.php">Misc Event Index</a>
    </td>
    <td><a href="../layoutsTable.php">Layouts Table</a><br/>
        <a href="../layoutsIndex.php">Layouts Index</a>
    </td>
    <td><a href="../clinicsTable.php">Clinics Table</a><br/>
        <a href="../clinicsIndex.php">Clinics Index</a>
    </td>
    <td><a href="ops_req.php">User session request</a></td>
</tr><tr>
    <th>Other Displays</th>
    <td>
        <a href="calendar/show_general_tour_cal.php">Interactive General/Proto Tour Calendar<a/>
    </td>
    <td>
        <a href="map_layout_tour.php?tour=L501">Tour Maps (put tour number in URL)<a/><p>
        <a href="calendar/show_layout_tour_cal.php">Interactive Layout Tour Calendar<a/>
    </td>
    <td><a href="format_misc_by_loc.php">Misc Events Grouped by Location<a/><p>
        <a href="calendar/show_misc_events_cal.php">Interactive Misc Events Calendar<a/>
    </td>
    <td>
    </td>
    <td><a href="format_clinics_by_loc.php">Clinics Grouped by Location<a/><p>
        <a href="calendar/show_clinics_cal.php">Interactive Clinics Calendar<a/>
    </td>
    <td><a href="ops_req_summary.php">Request Summary<a/><p>
    <a href="format_all_ops.php">Operating Layout Table</a><p>
    <a href="format_ops_by_day.php">Operating Sessions vs Day</a><p> 
    <a href="ops_assign_group.php">Start Assignments with Grouping</a>  <p>  
    <a href="ops_list_group.php">Email list for cycle</a>    <p>    
    <a href="ops_print_summary.php">Printable Session Rosters</a>    <p>
    </td>
</td>

</table>
<p>
<table border="1">
<tr><th>General Edits</th>
<td>
<a href="edit_location_keys.php ">Edit Location Keys<a/><br/>
</td>
</tr></table>

<p>
EventTools can optionally require that only certain
values be entered in tables, for example for scale or gauge.
If you're using that feature, enter the acceptable values using
the following links.
<table border="1">
<tr><th>Character</th><th>Construction</th><th>Operation</th></tr>
<?php

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
if ($event_tools_constrain_fidelity)  echo '<a href="edit_constrain_fidelity.php" >Fidelity</a>';
echo '</td></tr>';

echo '<tr><td>';
if ($event_tools_constrain_era)    echo '<a href="edit_constrain_era.php"   >Eras</a>';
echo '</td><td>';
if ($event_tools_constrain_control)  echo '<a href="edit_constrain_control.php" >Control Systems</a>';
echo '</td><td>';
if ($event_tools_constrain_rigor)  echo '<a href="edit_constrain_rigor.php" >Rigor</a>';
echo '</td></tr>';

echo '<tr><td>';
if ($event_tools_constrain_class)  echo '<a href="edit_constrain_class.php" >Railroad Classes</a>';
echo '</td><td>';
echo '</td><td>';
if ($event_tools_constrain_documentation)  echo '<a href="edit_constrain_documentation.php" >Documentation</a>';
echo '</td></tr>';

echo '<tr><td>';
if ($event_tools_constrain_theme)  echo '<a href="edit_constrain_theme.php" >Themes</a>';
echo '</td><td>';
echo '</td><td>';
if ($event_tools_constrain_session_pace)  echo '<a href="edit_constrain_session_pace.php" >Session Pace</a>';
echo '</td></tr>';

echo '<tr><td>';
if ($event_tools_constrain_locale) echo '<a href="edit_constrain_locale.php">Locales</a>';
echo '</td><td>';
echo '</td><td>';
if ($event_tools_constrain_car_forwarding)  echo '<a href="edit_constrain_car_forwarding.php" >Car Forwarding</a>';
echo '</td></tr>';

echo '<tr><td>';
echo '</td><td>';
echo '</td><td>';
if ($event_tools_constrain_tone)  echo '<a href="edit_constrain_tone.php" >Tone</a>';
echo '</td></tr>';

echo '<tr><td>';
echo '</td><td>';
echo '</td><td>';
if ($event_tools_constrain_dispatched_by1)  echo '<a href="edit_constrain_dispatched_by2.php" >Dispatching (Primary)</a>';
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
echo '</td></tr>';
?>
</table>

<p>
<table border="1">
<tr><th>General Displays</th>
<td>
<a href="index_advanced_section.php">Index Advanced Section Tours<a/><br/>
<a href="format_advanced_section.php">All Advanced Section Tours<a/><p/>
<a href="calendar_form.php">Download Events Calendar<a/><p/>
<a href="calendar/show_all_cal.php">Full Interactive Calendar<a/>
</td>
</tr></table>

<p>
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
<a href="calendar_guidebook_form.php">Download for Guidebook install<a/>

<p>
For more information on using EventTools, please see the
most recent draft of the 
<a href="EventTools.pdf">EventTools User Guide</a>.

<p>
For EventTools background information, please see the 
most recent draft of the 
<a href="EventToolsAdmin.odt">EventTools Administration Guide</a>.

<p>
For example query code, see the <a href="samples.php">samples page</a>.

<p>
EventTools was written and is maintained by Bob Jacobsen, 
please contact him directly if you want more information on using EventTools.

</body>
</html>
