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
    <select name="date">  <!-- manually enter scales -->
    <option value="1">Friday 7/1</option><option value="2">Saturday 7/2</option><option value="3">Sunday 7/3</option>
    <option value="4">Monday 7/4</option><option value="5">Tuesday 7/5</option><option value="6">Wednedsay 7/6</option>
    <option value="7">Thursday 7/7</option><option value="8">Friday 7/8</option><option value="9">Saturday 7/9</option>
    </select>
</form>

<hr>
<form method="get" action="format_clinics_by_loc.php">
    <button type="submit">Calendar of clinics with tag:</button>
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


</body>
</html>