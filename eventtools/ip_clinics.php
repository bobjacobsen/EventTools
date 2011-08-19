<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
<title>X2011West Clinics</title><style type="text/css">

/** Highlight name **/
tr.et-clinic-tr1 { font-family: Tahoma, sans-serif;}
.et-clbl-sub-tr1 { font-family: Tahoma, sans-serif;}

.et-clinic-presenter { font-family: Tahoma, sans-serif; font-weight:bold; font-size: 14pt;}
.et-clinic-name { font-family: Tahoma, sans-serif; font-size: 12pt; font-style:italic;}
.et-clinic-times { font-family: Tahoma, sans-serif; font-size: 12pt;}
.et-clinic-date { font-family: Tahoma, sans-serif; font-size: 12pt;}
.et-clinic-location { font-family: Tahoma, sans-serif; font-size: 12pt;}
.et-clinic-description { font-family: Tahoma, sans-serif; font-size: 10pt;}


/** try to fix layout size **/
table.et-clinic { width: 100%; 	margin-top: 15px; margin-bottom: 15px; }
td.et-clinic-td1  { width: 50%; text-align: left; }
td.et-clinic-td2  { width: 50%; text-align: right; }

/** tags **/
.et-clinic-tags { font-family: Tahoma, sans-serif;}
.et-clbl-sub-tr3 { font-family: Tahoma, sans-serif;}


</style></head><body>

<?php
require_once('access.php');
require_once('utilities.php');
require_once('formatting.php');
require_once('parsers.php');

$where = parse_clinic_query();
$order = parse_order();

format_all_clinics_as_table_ip($where, $order);

?>

</body>
</html>
