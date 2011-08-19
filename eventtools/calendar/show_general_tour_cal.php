<?php $path = $_SERVER['SERVER_NAME'].dirname( $_SERVER['SCRIPT_NAME'] ); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>X2011West General Tour Calendar</title>
<META http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE" />
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE" />

<!-- include prototype javascript framework -->
<script type="text/javascript" src="http://<?php echo $path;?>/includes/js/prototype.js"></script>
<!-- include tinycal lang file -->
<script type="text/javascript" src="http://<?php echo $path;?>/includes/js/tinycal.lang.en.js"></script>
<!-- include tinycal functions -->
<script type="text/javascript" src="http://<?php echo $path;?>/includes/js/tinycal.mini.js"></script>
</head>

<body id="body">

<!-- fire up tinycal box -->
<script type="text/javascript" src="http://<?php echo $path;?>/generaltours.config.js"></script>

</body>
</html>