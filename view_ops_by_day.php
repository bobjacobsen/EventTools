<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Operating Sessions vs Day</title>

    <link href="layouts.css" rel="stylesheet" type="text/css" />

</head>
<body>
<h2>Operating Session vs Day</h2>
<a href="index.php">Back to main page</a>
<p>

<!-- styling a particular item -->

<style TYPE="text/css">

    /* center everything */
    .et-faobd-table { text-align: center; }

    /* except left align owner and layout name cells */
    .et-faobd-td-1 { text-align: left; }
    .et-faobd-td-2 { text-align: left; }

    /* dont display the distance/time (3) or crew (4) columns */

    .et-faobd-th-3 { display: none; }
    .et-faobd-td-3 { display: none; }
    .et-faobd-th-4 { display: none; }
    .et-faobd-td-4 { display: none; }

    .et-faobd-slots-total{ display: none; } /* don't display totals */

    /* sessions with "bonus" status show green  */
    .et-faobd-session-status-50 { background: #80FF80; }


</style>

<?php
require_once('access.php');
require_once('utilities.php');
require_once('formatting.php');
require_once('parsers.php');

// Over-ride globals for full visibility here
$event_tools_show_min_value = 0;
$event_tools_replace_on_data_warn = TRUE;  // TRUE replace with text, FALSE leave as is
$event_tools_replace_on_data_error = TRUE;  // TRUE replace with text, FALSE leave as is

$where = parse_layout_query();
$order = parse_order();

if ($where == NULL) $where = "";
if ($where != "") $where = " WHERE ".$where;

format_all_ops_by_day("edit_layouts_all.php?layoutid=", $where, $order, NULL, "layout_photo_url" );

?>
</body>
</html>
