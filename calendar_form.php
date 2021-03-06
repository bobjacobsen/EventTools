<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?php require_once('access.php'); echo $event_tools_event_name; ?> Calendar Download</title>
    <link href="clinics.css" rel="stylesheet" type="text/css" />    
</head>
<body>
<h1><?php require_once('access.php'); echo $event_tools_event_name; ?> Calendar Download</h1>  

This page lets you download information into your calendar program
on your computer.  It works with Apple iCal, Google Calendar, 
Microsoft Exchange
and should work with any calendar program that accepts
a &quot;.ics&quot; format file.
<p>

<!-- works with calendar_form.php to download a custom .ics file -->
<p>

Select the types of events to include in your download, 
then click &quot;Download&quot;.
The selected information will download automatically.
What happens next depends on how your computer program is configured.

<p>
In some cases,
the download will open automatically in your calendar program, which will then
ask how you want to handle the new information.  
If you can insert the information as a
new &quot;category&quot; or new calendar, 
we recommend you do that so the <?php require_once('access.php'); echo $event_tools_event_name; ?> information
will be kept separate from all the other information in your calendar.

<p>
In other cases, the file will be downloaded to the usual location for downloads.
You'll then have to drag it onto your calendar icon, or open it with your 
calendar program.

<form method="get" action="calendar_download.php">

<?php

// -------------------------------------------------------------------------
// Part of EventTools, a package for managing model railroad meeting information
//
// By Bob Jacobsen, jacobsen@mac.com, Copyright 2010, 2011
// -------------------------------------------------------------------------

parse_str($_SERVER["QUERY_STRING"], $args);

echo '<h4>My Events</h4>';

echo '<input name="purchases" id="purchases" type="checkbox" ';
if ($args["purchases"] == "on") echo ' checked="yes" ';
echo '>My Purchased Tours</input><br/>';
echo '<input name="shopcart" id="shopcart" type="checkbox" ';
if ($args["shopcart"] == "on") echo ' checked="yes" ';
echo '>My Shopping Cart</input><br/>';
echo '(Please enter your email address: <input  name="email">)<br/>';

echo '<h4>Full Schedule</h4>';

echo '<p>The following will put a lot of concurrent items in your calendar, because there are a lot of clinics and tours. ';
echo 'It might be best to download them one at a time.<p/>';

echo '<input name="clinics" id="clinics" type="checkbox" ';
if ($args["clinics"] == "on") echo 'checked="yes" ';
echo '>All Clinics</input><br/>';

echo '<input name="general" id="general" type="checkbox" ';
if ($args["general"] == "on") echo 'checked="yes" ';
echo '>All General and Prototype Tours</input><br/>';

echo '<input name="layout" id="layout" type="checkbox" ';
if ($args["layout"] == "on") echo 'checked="yes" ';
echo '>All Layout Tours</input><br/>';

echo '<input name="misc" id="misc" type="checkbox" ';
if ($args["misc"] == "on") echo 'checked="yes" ';
echo '>Other Events</input><br/>';

echo '<h4>Formatting Options</h4>';

echo "\nMost calendar programs want their event listings in plain text. ";
echo "\nIf your program accepts HTML-formatted event listings, you can select HTML below.<br/>";
echo '<input name="text" id="text" type="radio" value="on"';
if ($args["text"] != "off") echo ' checked ';
echo '>Text<br/>';
echo '<input name="text" id="text" type="radio" value="off"';
if ($args["text"] == "off") echo ' checked ';
echo '>HTML<br/>';


?>
<p/>
<button type="submit" name="download" value="yes" id="download">Download</button>
</form>

</body>
</html>
