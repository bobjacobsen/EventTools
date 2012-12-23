<?
	require_once('access.php'); 
	require('utilities.php'); 

        if ($_REQUEST['radio1'] != 'none') $_REQUEST['v_'.$_REQUEST['radio1']] = '11';
        if ($_REQUEST['radio3'] != 'none') $_REQUEST['v_'.$_REQUEST['radio3']] = '12';

	$message = "<span style=\"color:red;font-weight:bold\">";

	if ( !formcomplete() ) {
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' )
			$message .= "Please complete all values";
		$message .= "</span>";
		showform( $message );
		return;
	}

	$now = time();	

	// add to local log file.
	$datastring = get_request_args(date("m/d/y g:i a T",$now),";")."\r\n";
	if (!$fp = fopen("datafile.dtf", "a")) {
	   print "Cannot open \"datafile.dtf\"\n";
	   exit;
	}
	fwrite( $fp, $datastring );
	fclose( $fp );

	// send email with info

    // change the email address on the next line
	$to = "rgj1927@pacbell.net";
	
	$subject = "Bayrails Registration FYI ".$_REQUEST["fname"]." ".$_REQUEST["lname"];
	$headers = sprintf("From:  BayRails V Registration Form <Registrar@BayRails.com>\r\n");
	$message = sprintf("%s %s has registered\r\n\r\n", $_REQUEST["fname"], $_REQUEST["lname"]);
	$message .= "A tabular version can be found at http://www.bayrails.com/eventtools/edit_ops_all.php\r\n\r\n";

    // add fill dump
	$message .= get_request_args(date("m/d/y g:i a T",$now)."\r\n","\r\n");

    // and send
	mail($to, $subject, $message, $headers);
	
	// process into database
	require( 'ops_incremental_request.php');

	// show confirmation on page
	$page = <<<END
	<h1>Thank you for Registering for BayRails V</h1>
	Your info has been emailed to $to, stored and will be processed shortly.<br><br>
	You can expect to hear from us within the next few days, and if you think you've been forgotten or
	are being ignored, please feel free to send a direct email plea to $to .<br><br>
	
END;
	print $page;

	// end of code here

// check that required values are present
function formcomplete() {

	$musthave = array();
	$musthave[] = "fname";
	$musthave[] = "lname";
	$musthave[] = "email";
	$musthave[] = "phone";
	$musthave[] = "street";
	$musthave[] = "city";
	$musthave[] = "state";
	$musthave[] = "zip";
	
	foreach ( $musthave as $i ) {
		if ( !isset( $_REQUEST[ $i ] ) || ($_REQUEST[ $i ] == "") )
			return(0);
	}
	return(1);
}

// show the HTML form
function showform( $message ) {
	 print $message;
	 $page =  <<<END

    <html>
    <body>
	<h2>Register for BayRails V</h2>

	<form action="" method="get">
	<table border=1 class="VisTable">
	<tr valign="top"><td width="40%">
		<table border=0>
			<tr><td colspan=2>Who are you?</td></tr>
			<tr><td>First Name</td><td><input type=text size=25 name=fname></td></tr>
			<tr><td>Last Name</td><td><input type=text size=25 name=lname></td></tr>
			<tr><td>Email Address</td><td><input type=text size=25 name=email></td></tr>
			<tr><td>Telephone</td><td><input type=text size=15 name=phone></td></tr>
			<tr><td>Cell Phone</td><td><input type=text size=15 name=cell></td></tr>
			<tr><td>Street Address</td><td><input type=text size=25 name=street></td></tr>
			<tr><td>City</td><td><input type=text size=25 name=city></td></tr>
			<tr><td>State</td><td><input type=text size=15 name=state></td></tr>
			<tr><td>ZIP code</td><td><input type=text size=15 name=zip></td></tr>
END;

	print $page;

	require('ops_req_tools.php');
	create_option_entries();

$page =  <<<END

		</table>
		<hr/>
		<table border="0">
            <tr><td colspan=2><input type="radio" name="radio1" value="15">&nbsp;Sign me up for the Steve Hayes/John Zach session on Wednesday</td></tr>
            <tr><td colspan=2><input type="radio" name="radio1" value="27">&nbsp;Sign me up for the Kent Williams session on Wednesday.</td></tr>
			<tr><td colspan=2><input type="radio" name="radio1" value="none" checked>&nbsp;No thank you.</td></tr>
		</table>
		<hr/>
		<table border="0">
		    <tr><td colspan=2><input type="radio" name="radio3" value="28">&nbsp;Sign me up for the Dave Houston session on Sunday.</td></tr>
            <tr><td colspan=2><input type="radio" name="radio3" value="29">&nbsp;Sign me up for the Ed Merrin session on Sunday.</td></tr>
			<tr><td colspan=2><input type="radio" name="radio3" value="none" checked>&nbsp;No thank you.</td></tr>
		</table>
	</td><td width="40%">
		<table border=0>
			<tr><td colspan=2><a href="#notes">**</a> Please rank your preferred venues:</td></tr>
END;

	print $page;

	create_request_entries(10,' status_code >= 60');

$page =  <<<END

		</table>
		
	</td>
	</tr>
	<tr><td colspan=3><br>Comments<div style="text-align:center"><textarea name="comments" rows=10 cols=60></textarea></div><br></td></tr>
	</table>

	<input type=submit value="Submit registration">&nbsp;&nbsp;
	<input type=reset value="Clear this form">
	</form>
    </body>
    </html>
    
END;
	print $page;
}


?>
