<?php

// -------------------------------------------------------------------------
// Part of EventTools, a package for managing convention information
//
// By Bob Jacobsen, jacobsen@mac.com, Copyright 2010, 2011, 2012
// -------------------------------------------------------------------------
include_once('mysql2i.class.php'); // migration step

global $event_tools_require_authenticate, $event_tools_require_user,
    $opts, $event_tools_db_prefix, $REMOTE_USER, $event_tools_user_email_log_skip;

//
// Simple DB-based security.  If there's a row for the
// user ID, access is granted.  If not, not.
//

if ($event_tools_require_user_authenticate  || $event_tools_require_user_id ) {
    // required to get user name
    if (array_key_exists('PHP_AUTH_USER', $_SERVER)) {
        $user = $_SERVER["PHP_AUTH_USER"];
        $REMOTE_USER = $user;  // for phpMyEdit
    } else {
        $REMOTE_USER = "";
        $user = "";
    }

    if ($event_tools_require_user_id) {
        // requires a user ID
        if ($REMOTE_USER == NULL || $REMOTE_USER == "") {
            // fail
            header('WWW-Authenticate: Basic realm="EventTools Admin"');
            header('HTTP/1.0 401 Unauthorized');
            return;
        }
    }
    if ($event_tools_require_user_authenticate) {
        // required to check

        $query="
            SELECT *
            FROM ".$event_tools_db_prefix."eventtools_users
            WHERE user_name = '".strtolower($user)."'
            AND user_pwd = '".strtolower($_SERVER['PHP_AUTH_PW'])."'
            ;";


        // open database
        require_once('access_and_open.php');

        $result=mysql_query($query);
        $num = 0;
        if ($result)
            $num = mysql_numrows($result);

        if ($num == 0) {
            // fail
            header('WWW-Authenticate: Basic realm="EventTools Admin"');
            header('HTTP/1.0 401 Unauthorized');
        } else {
            $event_tools_user_email_log_skip = mysql_result($result,0,"user_email_log_skip");
        }
        mysql_close();
    }
}
?>
