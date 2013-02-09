<?php

// Note that the sample code includes PHP 'require' and 'require_once'
// statements that reference files in the EventTools directory.  If you
// move the sample file to another directory, you'll have to update the file 
// paths in these statements.

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
	} else {
	   fwrite( $fp, $datastring );
	   fclose( $fp );
    }
	// send email with info to committee

    // If needed, change the email address on the next line
	$to = $event_tools_registrar_email_address;
	
	$subject = $event_tools_event_name." Pre-Registration FYI ".$_REQUEST["fname"]." ".$_REQUEST["lname"];
	$headers = sprintf("From:  ".$event_tools_event_name." Pre-Registration Form <".$to.">\r\n");
	$message = sprintf("%s %s has pre-registered\r\n\r\n", $_REQUEST["fname"], $_REQUEST["lname"]);
	$message .= "A tabular version can be found at http://".$_SERVER['SERVER_NAME']."/eventtools/ops_req_single.php?email=".$_REQUEST["email"]."\r\n\r\n";

    // add fill dump
	$message .= get_request_args(date("m/d/y g:i a T",$now)."\r\n","\r\n");

    // and send
	mail($to, $subject, $message, $headers);
	
	// process into database
	require( 'ops_incremental_add_prereg.php');

	// show confirmation on page
	print "<h1>Thank you for Registering for ".$event_tools_event_name."</h1>\n";
	print "Your info has been emailed to ".$to.", stored and will be processed shortly.<br><br>\n";
	print "You can expect to hear from us within the next few days, and if you think you've been forgotten or\n";
	print "are being ignored, please feel free to send a direct email plea to ".$to." .<br><br>\n";

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
    global $event_tools_event_name;
    
	 print $message;
	 $page =  <<<END

    <html>
    <body>
	<h2>Register for 
END;
	print $page.$event_tools_event_name;
	 $page =  <<<END
</h2>

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
    global $event_tools_emergency_contact_info;
    if ($event_tools_emergency_contact_info) {
         $page =  <<<END
                <tr><td>Emergency contact</td><td><input type=text size=15 name=econtact></td></tr>
                <tr><td>Emergency phone</td><td><input type=text size=15 name=ephone></td></tr>
END;
        print $page;
    }

	require('ops_req_tools.php');
	create_option_entries();

$page =  <<<END

		</table>
		<hr/>
	</td><td width="40%">
		
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
