<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>X2011West Layout Tours</title>
<style type="text/css">

.et-tour-name   { font-family: Tahoma, sans-serif; font-weight:bold; font-size: 12pt;}
.et-tour-number { font-family: Tahoma, sans-serif; font-weight:bold; font-size: 12pt;}

.et-tour-tr2    { font-family: Tahoma, sans-serif; font-size: 11pt; font-weight:bold; }

.et-tour-tr3    { font-family: Tahoma, sans-serif; font-size: 10pt; font-weight:bold; }
.et-tour-tr4    { font-family: Tahoma, sans-serif; font-size: 10pt; font-weight:bold; }

/** hide tour status */
.et-tour-status       { color: #FFFFFF; font-style:normal; }
a:link.status_link    { color: #FFFFFF; font-style:normal; }
a:visited.status_link { color: #FFFFFF; font-style:normal; }
a:hover.status_link   { color: #FFFFFF; font-style:normal; }
a:active.status_link  { color: #FFFFFF; font-style:normal; }

/** try to fix layout size **/
table.et-tour { width: 100%; 	margin-top: 15px; margin-bottom: 15px; }
td.et-tour-td1  { width: 13%; text-align: left; }
td.et-tour-td2  { width: 12%; text-align: left; }
td.et-tour-td3  { width: 13%; text-align: left; }
td.et-tour-td4  { width: 12%; text-align: left; }
td.et-tour-td5  { width: 13%; text-align: left; }
td.et-tour-td6  { width: 12%; text-align: left; }
td.et-tour-td7  { width: 13%; text-align: left; }
td.et-tour-td8  { width: 12%; text-align: left; }
td.et-tour-td2  { width: 13%; text-align: right; }

</style></head><body>
<?php
require_once('access.php');
require_once('utilities.php');
require_once('formatting.php');
require_once('parsers.php');

$where = parse_general_tour_query();
$order = parse_order();

format_all_general_tours_as_8table($where,$order);

?>
</body>
</html>
