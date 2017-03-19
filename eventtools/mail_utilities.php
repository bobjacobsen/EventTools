<?php

// -------------------------------------------------------------------------
// Part of EventTools, a package for managing X2011west information
//
// By Bob Jacobsen, rgj1927@pacbell.net, Copyright 2010, 2011
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
