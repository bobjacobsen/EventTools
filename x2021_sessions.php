<?php
require_once('eventtools2021/access_and_open.php'); require_once('eventtools2021/secure_customer.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">

<!-- #BeginTemplate "master.dwt" -->

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="author" content="David A. Grenier" />
<meta name="keywords" content="nmra 2021 convention, santa clara, model railroading, clinics, excursions, prototype tours, non-rail, contest, layout, design, layout design sig, ldsig, national model railroad association, nmra" />
<meta name="description" content="This is the official website of the Rails By The Bay 2021 National Virtual Convention of the National Model Railroad Association from Santa Clara, California, July 6-10, 2021." />


<!-- #BeginEditable "doctitle" -->
<title>Sessions - Rails By The Bay 2021 NMRA Convention</title>


<style type="text/css">
/* was 12 em */
.colWidth150 {
	width: 4em;
}
.startTime 	{
	text-align: center;
	width: 170px;
}

.et-clbl-sub-name {
}
.et-clbl-sub-td1 {
     /*    background-color: lightblue; */
}
table.agenda tr:nth-child(odd) {background-color: #6dabdc;} /* dark  #d1e1d0;*/
table.agenda  tr:nth-child(even) {background-color: #FFFAB2;} /* light  #eaf2ea;*/
table.agenda td  {
	padding: 8px 10px 5px 10px;
	font-family:Arial, Helvetica, sans-serif;
	font-size: 1.1em;
	font-weight:  bold;
	text-align: center;
}
table.agenda th  {
	padding: 8px 10px 5px 10px;
	background-color: #004990;
	text-align: left;
	font-family:Arial, Helvetica, sans-serif;
	font-size: 1.1em;
	font-weight:  bold;
	color: white;
}
.pill-nav a {
	display: inline-block;
	background-color: red;
	color: black;
	text-align: center;
	padding: 125px 8px 125px 8px;
	text-decoration: none;
	font-size: 34px;
/*	transform: rotate(-90deg);*/
	border-radius: 45px;
}

.pill-nav a:hover {
  background-color: blue;
  color: white;
}

.pill-nav a.active {
  background-color: dodgerblue;
  color: white;
}

.ghButton a 	{
	background-color: red;
	color: white;
	width: 90%;
	font-size: 48px;
	font-weight: bold;
	padding: 14px;
	border-radius: 35px;
	padding: 15px 328px 15px 328px;
	margin: 0 auto 0 auto;

	text-decoration: none;
	text-align: center;
}

.ghButton a:hover  {
	 background-color: blue;
 	 color: white;
}
</style>


<!-- #EndEditable -->
<link href="http://www.pcrnmra.org/NMRA2021/css/nmra2021.css" rel="stylesheet" type="text/css" />
<link href="http://www.pcrnmra.org/NMRA2021/css/nmra2021print.css" rel="stylesheet" type="text/css" media="print" />
<link rel="icon" href='http://www.pcrnmra.org/NMRA2021/images/favicon.ico' type="image/x-icon" />
<link rel='shortcut icon' href='http://www.pcrnmra.org/NMRA2021/images/favicon.ico' type='image/xicon' />

<script src="http://www.pcrnmra.org/NMRA2021/java/external_links.js" type="text/javascript"></script>

</head>

<body>

<!-- BEGIN CONTAINER (WRAPPER) -->
<div id="container">

	<!-- BEGIN MASTHEAD    width="210" height="150"  -->
	<div id="masthead">
		<a href="http://www.pcrnmra.org/NMRA2021/index.html">
		<img src="http://www.pcrnmra.org/NMRA2021/images/RBTBlogo.png" alt="Rails By The Bay 2021 logo" style="float: left; margin: 10px 0 10px 15px;" width="200" /></a>
		<div id="siteTitle">
		<span class="pcrText"><br />
			NMRA 2021 Virtual National Convention</span>
			<span class="convName"><br />
			RAILS BY THE BAY</span><br />
			<span class="pcrText">July 6 &ndash; 10, 2021</span><br />
			<span class="pcrText">Santa Clara, California</span>
		</div>
	</div>
	<!-- END MASTHEAD -->

	<!-- BEGIN PAGE CONTENT (INCLUDES LEFT SIDE BAR & MAIN CONTENT BOX) -->
	<div id="page_content">

		<!-- BEGIN LEFT SIDEBAR -->
		<div id="sidebar">
			<ul>
				<li><a href="http://www.pcrnmra.org/NMRA2021/zoomtips.html" style="background-color: #FFFF00; font-size: 1.4em; font-weight: bold;">ZOOM<br />TIPS</a></li>

				<li><a href="http://www.pcrnmra.org/NMRA2021/news.html">NEWS &amp; UPDATES</a></li>
				<li><a href="http://www.pcrnmra.org/NMRA2021/sched.html">SCHEDULES</a></li>
				<li><a href="http://www.pcrnmra.org/NMRA2021/index.html">Home</a></li>
				<li><a href="http://www.pcrnmra.org/NMRA2021/registration.html">Registration</a></li>
				<!--li><a href="http://www.pcrnmra.org/NMRA2021/hotel.html">Hotel</a></li-->
				<li><a href="http://www.pcrnmra.org/NMRA2021/clinics.html">Clinics</a></li>
				<!--li><a href="http://www.pcrnmra.org/NMRA2021/mwtm.html">Modeling With The Masters</a></li-->
				<li><a href="http://www.pcrnmra.org/NMRA2021/layouts.html">Layout Tours</a></li>
				<li><a href="http://www.pcrnmra.org/NMRA2021/bof.html">Birds of a Feather Breakout Rooms</a></li>
				<li><a href="http://www.pcrnmra.org/NMRA2021/proto.html">Prototype Tours</a></li>
				<!--li><a href="http://www.pcrnmra.org/NMRA2021/contests.html">Contests</a></li-->
				<!--li><a href="http://www.pcrnmra.org/NMRA2021/excursions.html">Excursions</a></li-->
				<li><a href="http://www.pcrnmra.org/NMRA2021/volunteers.html">Volunteers</a></li>
				<!--li><a href="http://www.pcrnmra.org/NMRA2021/hobos.html">Hobos</a></li-->
				<!--li><a href="http://www.pcrnmra.org/NMRA2021/banquet.html">Banquet</a></li-->
				<!--li><a href="http://www.pcrnmra.org/NMRA2021/nonrails.html">Non-Rails</a></li-->
				<!--li><a href="http://www.pcrnmra.org/NMRA2021/store.html">Company Store</a></li-->
				<!--li><a href="http://www.pcrnmra.org/NMRA2021/swapmeet.html">Swap Meet</a></li-->
				<!--li><a href="http://www.pcrnmra.org/NMRA2021/sigs.html">SIGs</a></li-->
				<li><a href="http://www.pcrnmra.org/NMRA2021/ldsig.html">Layout Design SIG</a></li>
				<li><a href="http://www.pcrnmra.org/NMRA2021/committee.html">Committee</a></li>
				<li><a href="http://www.pcrnmra.org/NMRA2021/links.html">Links</a></li>
				<li><a href="http://www.pcrnmra.org/NMRA2021/policies.html">Policies</a></li>				  	</ul>
		</div>
		<!-- END LEFT SIDEBAR -->



		<!-- skip content if not authorized -->

<?php
        if (!isset($_SERVER['PHP_AUTH_USER']) || is_null($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] == "") {
		    echo "You need to enter valid login name and password to access this page.";
		    echo "Please hit refresh/reload on your browser and try again";
		    echo "<p>";
		    echo "If that doesn't work, clear your browser's cache and then restart it";
		    echo "</body></html>";
		    return;
		}
?>

		<!-- BEGIN CONTENT (MAIN CONTENT BOX)  -->
		<div id="content">

<h1>Attendee Video Links</h1>
All times are Pacific Daylight Time (PDT).

<?php
require_once('eventtools2021/access.php');
require_once('eventtools2021/utilities.php');
require_once('eventtools2021/locale.php');
require_once('eventtools2021/parsers.php');

$where = parse_clinic_query();
format_clinics_by_loc("http://www.pcrnmra.org/NMRA2021/clinics.html#", $where);

?>




<!--
<p class="ghButton textCenter"><a href="http://www.pcrnmra.org/NMRA2021/https://us02web.zoom.us/j/main-hall">G R E A T &nbsp;&nbsp;  H A L L</a></p>
	<p class="textCenter redBold">For access to Clinic Goes on and other Birds of a Feather breakout rooms</p>
-->



			<!--div class="textLeft" style="margin-bottom: 3em;">
				<a href="mailto:chair@nmra2021.com?subject=Rails By The Bay 2021 Privacy Policy">Ed Slintak</a><br />Convention Co-Chairs
			</div-->

			<!-- #EndEditable --><br />
			<p class="pageUpdated"> This page last updated: <script type="text/javascript">
			  document.write(document.lastModified);
			  </script></p>

		</div>
		<!-- END CONTENT  (MAIN CONTENT BOX)  -->

	</div>
	<!-- END PAGE CONTENT (INCLUDES LEFT SIDEBAR & MAIN CONTENT BOX  -->

	<!-- BEGIN FOOTER -->
	<div id="footer">
		<p>	<a href="http://www.pcrnmra.org/NMRA2021/news.html">News &amp; Updates</a>  &bull;
			<a href="http://www.pcrnmra.org/NMRA2021/sched.html">Schedules</a> &bull;
			<a href="http://www.pcrnmra.org/NMRA2021/index.html">Home</a>  &bull;
			<a href="http://www.pcrnmra.org/NMRA2021/registration.html">Registration</a>  &bull;
			<a href="http://www.pcrnmra.org/NMRA2021/clinics.html">Clinics</a>  &bull;
			<a href="http://www.pcrnmra.org/NMRA2021/layouts.html">Layout Tours</a> &bull;
			<a href="http://www.pcrnmra.org/NMRA2021/bof.html">Birds of a Feather Breakout Rooms</a> &bull;
			<a href="http://www.pcrnmra.org/NMRA2021/proto.html">Prototype Tours</a> &bull;
			<a href="http://www.pcrnmra.org/NMRA2021/volunteers.html">Volunteers</a> &bull;
			<a href="http://www.pcrnmra.org/NMRA2021/committee.html">Committee</a>  &bull;
			<a href="http://www.pcrnmra.org/NMRA2021/links.html">Links</a>  &bull;
			<a href="http://www.pcrnmra.org/NMRA2021/policies.html">Policies</a>
		</p>
		<p class="copyright">Website Design by Dave Grenier, Rails By The Bay 2021 NMRA National Convention, Pacific Coast Region of NMRA, Inc. <br />
			Copyright © 2021 - <script type="text/javascript">
        var dteNow = new Date();
	var intYear = dteNow.getFullYear();
	document.write(intYear);
</script> Rails By The Bay 2021. All Rights Reserved.</p>

	</div>
	<!-- END FOOTER -->

</div>
<!-- END CONTAINER (WRAPPER) -->

<!-- WiredMinds eMetrics tracking with Enterprise Edition V5.4 START -->
<script type='text/javascript' src='https://count.carrierzone.com/app/count_server/count.js'></script>
<script type='text/javascript'><!--
wm_custnum='f04e932e7b7e5698';
wm_page_name='sessions.html';
wm_group_name='/services/webpages/p/c/pcrnmra.org/public/NMRA2021';
wm_campaign_key='campaign_id';
wm_track_alt='';
wiredminds.count();
// -->
</script>
<!-- WiredMinds eMetrics tracking with Enterprise Edition V5.4 END -->
</body>

<!-- #EndTemplate -->

</html>

