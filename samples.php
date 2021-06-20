<!DOCTYPE html>
<html>
<head>
	<title>EventTools Sample Queries</title>
</head>
<body>
<h1>EventTools Sample Queries</h1>

The following are small examples of forms for selecting various things.
<p>
There are also example selection forms in some of the "formatting" and "index" example pages,
see the <a href="index.php">main page</a> for access to those.
<p>
Note that the sample code includes PHP 'require' and 'require_once'
statements that reference files in the EventTools directory.  If you
move the sample file to another directory, you'll have to update the file 
paths in these statements.
<p>
This page includes samples for features that might not be enabled in your EventTools
installation. 
<hr>
<form method="get" action="format_all_clinics.php">
    <button type="submit">Table of clinics with tag:</button>
    <input name="tag" type="text" size="64" maxlength="64"/>
</form>

<hr>
<form method="get" action="format_all_clinics.php">
    <button type="submit">Table of clinics with tag:</button>
    <select name="tag">
    <?php  require_once('access.php'); require_once('utilities.php');
    foreach(get_clinic_tags() as $tag) { echo "<option>".$tag."</option>"; }
    ?>
    </select>
</form>

<hr>
<form method="get" action="format_all_clinics.php">
    <button type="submit">Table of clinics from presenter:</button>
    <select name="presenter">
    <?php  require_once('access.php'); require_once('utilities.php');
    foreach(get_clinic_presenters() as $tag) { echo "<option>".$tag."</option>"; }
    ?>
    </select>
</form>

<hr>
<form method="get" action="format_all_clinics.php">
    <button type="submit">Non-Rail Clinic Button</button>
    <input type="hidden" name="tag" value="Non-Rail" />
</form>

<hr>
<form method="get" action="format_all_clinics.php">
    <button type="submit">Table of clinics on day:</button>
    <select name="date">  <!-- manually enter days -->
    <option value="07-06">Tuesday 7/6</option><option value="07-07">Wednesday 7/7</option><option value="07-08">Thursday 7/8</option>
    <option value="07-09">Friday 7/9</option><option value="07-10">Saturday 7/10</option>
    </select>
</form>

<hr>
<form method="get" action="format_clinics_by_loc.php">
    <button type="submit">Calendar of clinics with tag:</button>
    <input name="tag" type="text" size="64" maxlength="64"/>
</form>

<hr>
<form method="get" action="format_one_clinic.php">
    <button type="submit">Clinic details for clinics with tag:</button>
    <input name="tag" type="text" size="64" maxlength="64"/>
</form>

<hr>
<form method="get" action="format_all_misc_events.php">
    <button type="submit">Table of misc events with tag:</button>
    <input name="tag" type="text" size="64" maxlength="64"/>
</form>

<hr>
<form method="get" action="format_all_misc_events.php">
    <button type="submit">Table of misc events with tag:</button>
    <select name="tag">
    <?php  require_once('access.php'); require_once('utilities.php');
    foreach(get_misc_event_tags() as $tag) { echo "<option>".$tag."</option>"; }
    ?>
    </select>
</form>

<hr>
<form method="get" action="format_all_misc_events.php">
    <button type="submit">Table of misc events on day:</button>
    <select name="date">  <!-- manually enter scales -->
    <option value="1">Friday 7/1</option><option value="2">Saturday 7/2</option><option value="3">Sunday 7/3</option>
    <option value="4">Monday 7/4</option><option value="5">Tuesday 7/5</option><option value="6">Wednesday 7/6</option>
    <option value="7">Thursday 7/7</option><option value="8">Friday 7/8</option><option value="9">Saturday 7/9</option>
    </select>
</form>

<hr>
<form method="get" action="format_misc_by_loc.php">
    <button type="submit">Calendar of misc events with tag:</button>
    <input name="tag" type="text" size="64" maxlength="64"/>
</form>

<hr>
<form method="get" action="format_one_misc_event.php">
    <button type="submit">Misc event details for events with tag:</button>
    <input name="tag" type="text" size="64" maxlength="64"/>
</form>


<hr>
<form method="get" action="format_all_layout_tours.php">
    <button type="submit">Table of layout tours with scale:</button>
    <select name="scale">  <!-- manually enter scales -->
    <option>G</option><option>F</option><option>O</option><option selected>HO</option><option>N</option><option>Nn3</option><option>Z</option>
    </select>
</form>

<hr>
<form method="get" action="format_all_layout_tours.php">
    <button type="submit">Table of layout tours on day:</button>
    <select name="date">  <!-- manually enter scales -->
    <option value="1">Friday 7/1</option><option value="2">Saturday 7/2</option><option value="3">Sunday 7/3</option>
    <option value="4">Monday 7/4</option><option value="5">Tuesday 7/5</option><option value="6">Wednedsay 7/6</option>
    <option value="7">Thursday 7/7</option><option value="8">Friday 7/8</option><option value="9">Saturday 7/9</option>
    </select>
</form>

<hr>
<form method="get" action="format_all_layout_tours.php">
    <button type="submit">Show specific layout tour</button>Number:
    <input name="number" type="text" size="6" maxlength="5" value="L201" />
</form>

<hr>
<form method="get" action="format_all_layout_tours.php">
    <button type="submit">Show specific layout tour</button>Number: 
    <select name="number">
    <?php  require_once('access.php'); require_once('utilities.php');
    foreach(get_layout_tour_numbers() as $tag) { $val=explode(' ',$tag,2);echo "<option value=".$val[0].">".$tag."</option>"; }
    ?>
    </select>
</form>

<hr>
<form method="get" action="format_all_layout_tours.php">
    <button type="submit">Table of layout tours containing word:</button>
    <input name="match" type="text" size="64" maxlength="64"/>
</form>

<hr>
<form method="get" action="format_all_layouts.php">
    <button type="submit">Table of layouts containing word:</button>
    <input name="match" type="text" size="64" maxlength="64"/>
</form>

<hr>
<form method="get" action="format_one_general_tour.php">
    <button type="submit">Show specific general tour</button>Number:
    <input name="number" type="text" size="6" maxlength="5" value="P401" />
</form>

<hr>
<!-- Sample of free-form table -->
<p>Sample of free-form layout table</p>
<table border="1">
    <?php  
        require_once('access.php'); require_once('utilities.php'); require_once('formatting.php');
        
        // define routine that makes HTML (formatted) content from query results, called for each item
        function simple_table_format_cell($index, $row, $name) {
            $val = errorOnEmpty(htmlspecialchars($row[$name]),$name);
            // special cases
            if ($name == 'layout_owner_lastname') {
                $val = errorOnEmpty(htmlspecialchars($row["layout_owner_firstname"]." ".$row["layout_owner_lastname"]),"name");
            } else if ($name == 'layout_owner_firstname') {
                return;
            } else if ($name == 'layout_photo_url') {
                $val = '<a href="'.$row[$name].'">Description & Photos</a>';
            } else if ($name == 'layout_dispatched_by1') {
                if (strlen($row['layout_dispatched_by2']) > 0) {
                    $val = errorOnEmpty(htmlspecialchars($row["layout_dispatched_by1"]."/".$row["layout_dispatched_by2"]),"dispatch");
                }
            } else if ($name == 'layout_dispatched_by2') {
                return;
            }
            echo "  <td>\n";
            echo "    <span class=\"et-".$name."\">\n";
            echo "      <a name=\"".$row[$name]."\"></a>\n";
            echo "     ".$val;
            echo "      </span> \n";
            echo "  </td>\n";
        }
        
        // query and construct the table
        simple_table('layouts', 
            array('layout_owner_lastname', 'layout_owner_firstname', 'layout_photo_url', 'layout_name', 'layout_scale', 'layout_distance', 'layout_num_ops', 'layout_control', 'layout_dispatched_by1', 'layout_dispatched_by2'), 
            "`layout_status_code` >= 50 ");
    ?>
</table>

<hr>


</body>
</html>
