<?php

// -------------------------------------------------------------------------
// Part of EventTools, a package for managing model railroad meeting information
//
// By Bob Jacobsen, jacobsen@mac.com, Copyright 2010, 2011
// -------------------------------------------------------------------------

// -------------------------------------------------------------------------
//
// email utilities
//

define('EMAIL_FROM', $event_tools_notify_email_address);

function sendNotificationEmail($to, $subject, $body) {
    $from = "From:" . EMAIL_FROM;
    $headers = $from;
    mail($to,$subject,$body,$headers);
}


?>
