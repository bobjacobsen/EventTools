<?php

// -------------------------------------------------------------------------
// Part of EventTools, a package for managing X2011west information
//
// By Bob Jacobsen, rgj1927@pacbell.net, Copyright 2010, 2011
// -------------------------------------------------------------------------

global $eventtools_require_authenticate, $eventtools_require_user, 
    $opts, $event_tools_db_prefix, $REMOTE_USER, $event_tools_user_email_log_skip;

//
// Simple DB-based security.  If there's a row for the
// user ID, access is granted.  If not, not.
//

if ($eventtools_require_authenticate  || $eventtools_require_user ) {
    // required to get user name
    $user = $_SERVER['PHP_AUTH_USER'];
    $REMOTE_USER = $user;  // for phpMyEdit

    if ($eventtools_require_authenticate) {
        // required to check
        
        $query="
            SELECT *
            FROM ".$event_tools_db_prefix."eventtools_users
            WHERE user_name = '".strtolower($user)."'
            ;
        ";
    
        // open database
        mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
        @mysql_select_db($opts['db']) or die( "Unable to select database");
    
        $result=mysql_query($query);
        $num = 0;
        if ($result)
            $num = mysql_numrows($result);
        
        if ($num == 0) {
            // fail
            header('WWW-Authenticate: Basic realm="X2011west Admin"');
            header('HTTP/1.0 401 Unauthorized');
        } else {
            $event_tools_user_email_log_skip = mysql_result($result,0,"user_email_log_skip");
        }
        mysql_close();
    }
}
?>
