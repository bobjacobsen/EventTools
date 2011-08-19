<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>X2011West Clinic</title>

    <link href="clinics.css" rel="stylesheet" type="text/css" />    

</head>
<body>
<h1>X2011West Clinic</h1>  
<a href="index.php">Back to main page</a>
<p>

<?php
require_once('access.php');
require_once('utilities.php');
require_once('formatting.php');
require_once('parsers.php');

$where = parse_clinic_query();

format_all_clinics_as_3table($where);

?>

</body>
</html>
