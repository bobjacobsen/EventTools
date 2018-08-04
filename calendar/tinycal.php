<?php $path = $_SERVER['SERVER_NAME'].dirname( $_SERVER['SCRIPT_NAME'] ); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<!--
copyright (c) 2008-2009 Kjell-Inge Gustafsson kigkonsult
www.kigkonsult.se/tinycal/index.php
ical@kigkonsult.se
updated 20090105
-->
<html>
<head>
<title>tinyCal 2.2 test</title>
<META http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<META name="author"      content="kigkonsult - Kjell-Inge Gustafsson" />
<META name="copyright"   content="2008-2009 kigkonsult" />
<META name="keywords"    content="ical, calendar, icalendar, xml, rss, rfc2445, php, create, generate, calender" />
<META name="description" content="tinycal calendar box" />
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE" />
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE" />
<link href="./images/favicon.ico" rel="shortcut icon"/>
<!-- include prototype javascript framework -->
<script type="text/javascript" src="http://<?php echo $path;?>/includes/js/prototype.js"></script>
<!-- include tinycal lang file -->
<script type="text/javascript" src="http://<?php echo $path;?>/includes/js/tinycal.lang.en.js"></script>
<!-- include tinycal functions -->
<script type="text/javascript" src="http://<?php echo $path;?>/includes/js/tinycal.mini.js"></script>
</head>

<body id="body">
<?php echo "<small>test location:<br />$path</small><br /><br />\n"; ?>

<!-- fire up tinycal box -->
<script type="text/javascript" src="http://<?php echo $path;?>/tinycal.config.js"></script>

</body>
</html>