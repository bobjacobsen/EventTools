<?php

// -------------------------------------------------------------------------
// Part of EventTools, a package for managing X2011west information
//
// By Bob Jacobsen, rgj1927@pacbell.net, Copyright 2010, 2011
// -------------------------------------------------------------------------

// -------------------------------------------------------------------------
//
// Functions for check listings
//

// Listing layouts
// used in e.g. format_all_layouts.php
//
function list_layout_heading($result,$i) {
    echo '<h4>'.warnOnEmpty(mysql_result($result,$i,"layout_owner_firstname"),"first name")
                .' '.errorOnEmpty(mysql_result($result,$i,"layout_owner_lastname"), "last name").'</h4>';
    echo 'layout name: '.errorOnEmpty(mysql_result($result,$i,"layout_name"),"name").'<br>';       
    echo 'short desc: '.warnOnEmpty(mysql_result($result,$i,"layout_short_description"),"short desc").'<br>';       
    echo 'long desc:  '.errorOnEmpty(mysql_result($result,$i,"layout_long_description"),"long desc").'<br>';  
    echo "<p>\n";
    echo 'status code:  '.warnOnEmpty(mysql_result($result,$i,"event_status_name"),"").'<br>';

    if (!checkShowLayoutStatus($result,$i) && (mysql_result($result,$i,"number") !="")) {
        if (checkShowStatus($result,$i)) 
            echo '<span class="et-error-missing">Error: Unapproved layout visible on approved tour</span><br/>';
        else
            echo '<span class="et-warning-missing">Layout on proposed tour but not approved</span><br/>';
    }
    echo "<p>\n";
    
    echo 'scale:  '.errorOnEmpty(mysql_result($result,$i,"layout_scale"),"scale").'<br>';  
    echo 'prototype:  '.warnOnEmpty(mysql_result($result,$i,"layout_prototype"),"proto").'<br>';  
    echo 'era:  '.warnOnEmpty(mysql_result($result,$i,"layout_era"),"era").'<br>';  
    echo 'scenery:  '.warnOnEmpty(mysql_result($result,$i,"layout_scenery"),"scenery").'<br>';  
    echo 'size:  '.warnOnEmpty(mysql_result($result,$i,"layout_size"),"size").'<br>';  
    echo 'mainline length:  '.warnOnEmpty(mysql_result($result,$i,"layout_mainline_length"),"main len").'<br>';  
    echo 'plan type:  '.warnOnEmpty(mysql_result($result,$i,"layout_plan_type"),"plan type").'<br>';  
    echo 'ops scheme:  '.warnOnEmpty(mysql_result($result,$i,"layout_ops_scheme"),"ops scheme").'<br>';  
    echo 'control:  '.warnOnEmpty(mysql_result($result,$i,"layout_control"),"control").'<br>';  
    echo 'num ops:  '.warnOnEmpty(mysql_result($result,$i,"layout_num_ops"),"numops").'<br>';  
    echo 'accessibility:  '.warnOnEmpty(mysql_result($result,$i,"accessibility_name"),"accessible").'<br>';  
    echo 'wheelchair access:  '.mysql_result($result,$i,"layout_wheelchair_access").'<br>';  
    echo 'duckunder entry:  '.mysql_result($result,$i,"layout_duckunder_entry").'<br>';  
    echo 'owner url:  '.mysql_result($result,$i,"layout_owner_url").'<br>';  
    echo 'first name:  '.warnOnEmpty(mysql_result($result,$i,"layout_owner_firstname"),"first name").'<br>';  
    echo 'last name:  '.errorOnEmpty(mysql_result($result,$i,"layout_owner_lastname"),"last name").'<br>';  
    echo 'phone:  '.warnOnEmpty(mysql_result($result,$i,"layout_owner_phone"),"phone").'<br>';  
    echo 'call time:  '.mysql_result($result,$i,"layout_owner_call_time").'<br>';  
    echo 'email:  '.mysql_result($result,$i,"layout_owner_email").'<br>';  
    echo '<br>';
    echo 'street address:  '.errorOnEmpty(mysql_result($result,$i,"layout_street_address"),"address").'<br>';  
    echo 'city:  '.errorOnEmpty(mysql_result($result,$i,"layout_city"),"city").'<br>';  
    echo 'state:  '.errorOnEmpty(mysql_result($result,$i,"layout_state"),"state").'<br>';  
    echo 'post code:  '.warnOnEmpty(mysql_result($result,$i,"layout_postcode"),"zip").'<br>';  
    echo '<br>';
    echo 'Fidelity to prototype:  '.mysql_result($result,$i,"layout_fidelity").'<br>';  
    echo 'Rigor:  '.mysql_result($result,$i,"layout_rigor").'<br>';  
    echo 'Documentation:  '.mysql_result($result,$i,"layout_documentation").'<br>';  
    echo 'Session pace:  '.mysql_result($result,$i,"layout_session_pace").'<br>';  
    echo 'Car forwarding:  '.mysql_result($result,$i,"layout_car_forwarding").'<br>';  
    echo 'Tone:  '.mysql_result($result,$i,"layout_tone").'<br>';  
    echo 'Dispatched by (primary):  '.mysql_result($result,$i,"layout_dispatched_by1").'<br>';  
    echo 'Dispatched by (secondary):  '.mysql_result($result,$i,"layout_dispatched_by2").'<br>';  
    echo 'Communications:  '.mysql_result($result,$i,"layout_communications").'<br>';  
    
}

//
function list_tour_in_layout($result,$i) {
    echo mysql_result($result,$i,"number").' '.mysql_result($result,$i,"name").' ('.mysql_result($result,$i,"start_date").' to '.mysql_result($result,$i,"end_date").')<br>';
}

// Listing tours (any type)
// used in e.g. list_all_layout_tours.php
//

function list_tour_heading($result,$i) {
    echo '<hr><h4>'.errorOnEmpty(mysql_result($result,$i,"number"),"number").' ';
    echo errorOnEmpty(mysql_result($result,$i,"name"),"tour name").'</h4>';
    
    echo 'description:  '.errorOnEmpty(mysql_result($result,$i,"description"),"description").'<br>';  
    echo "<p>\n";
    echo 'status code:  '.warnOnEmpty(mysql_result($result,$i,"event_status_name"),"").'<br>';  
    echo "<p>\n";
    
    echo 'tour number:  '.errorOnEmpty(mysql_result($result,$i,"number"),"number").'<br>';  
    echo 'start date:  '.errorOnMissingTime(mysql_result($result,$i,"start_date"),"start").'<br>';  
    echo 'end date:  '.errorOnMissingTime(mysql_result($result,$i,"end_date"),"end").'<br>';  
    echo 'price:  '.errorOnNegative(mysql_result($result,$i,"tour_price"),"price").'<br>';  
    echo 'seats:  '.errorOnZero(mysql_result($result,$i,"tour_seats"),"seats").'<br>';  
    echo 'bus type:  '.warnOnEmpty(mysql_result($result,$i,"tour_bus_type"),"bus type").'<br>';  
    echo 'buses:  '.warnOnZero(mysql_result($result,$i,"tour_buses"),"buses").'<br>';  
    echo 'mileage:  '.warnOnZero(mysql_result($result,$i,"tour_mileage"),"miles").'<br>';  

}

//
function list_layout_in_tour($result,$i) {
    echo warnOnEmpty(mysql_result($result,$i,"layout_name"),"layout name").' ('
        .warnOnEmpty(mysql_result($result,$i,"layout_owner_firstname"),"first name").' '
        .warnOnEmpty(mysql_result($result,$i,"layout_owner_lastname"), "last name").'  &lt;'
        .warnOnEmpty(mysql_result($result,$i,"layout_owner_email"), "email").'&gt;) ';
    echo errorOnEmpty(mysql_result($result,$i,"layout_short_description"),"short description");
    echo '<br>';
}

function list_clinic($result,$i) {
    echo '<hr><h4>'.errorOnEmpty(mysql_result($result,$i,"number"),"number").' ';
    echo errorOnEmpty(mysql_result($result,$i,"name"),"clinic name").'</h4>';
    
    echo 'description:  '.errorOnEmpty(mysql_result($result,$i,"description"),"description").'<br>';  
    echo "<p>\n";
    echo 'status code:  '.warnOnEmpty(mysql_result($result,$i,"status_code"),"status code").'<br>';  
    
    echo 'clinic number:  '.errorOnEmpty(mysql_result($result,$i,"number"),"number").'<br>';  
    echo 'start date:  '.errorOnEmpty(mysql_result($result,$i,"start_date"),"start").'<br>';  
    echo 'end date:  '.errorOnEmpty(mysql_result($result,$i,"end_date"),"end").'<br>';  

    echo 'presenter:  '.warnOnEmpty(mysql_result($result,$i,"clinic_presenter"),"presenter").'<br>';  
    echo 'email:  '.warnOnEmpty(mysql_result($result,$i,"clinic_presenter_email"),"email").'<br>';  
    echo 'location:  '.mysql_result($result,$i,"location_name").'<br>';  
    echo 'clinic URL:  '.mysql_result($result,$i,"clinic_url").'<br>';  

}

function list_clinic_xml($result,$i) {
    echo "<clinic>\n";

    echo '  <number>'.htmlspecialchars(mysql_result($result,$i,"number"))."</number>\n";
    echo '  <name>'.htmlspecialchars(mysql_result($result,$i,"name"))."</name>\n";
    echo '  <start>'.htmlspecialchars(mysql_result($result,$i,"start_date"))."</start>\n";
    echo '  <presenter>'.htmlspecialchars(mysql_result($result,$i,"clinic_presenter"))."</presenter>\n";
    echo '  <email>'.htmlspecialchars(mysql_result($result,$i,"clinic_presenter_email"))."</email>\n";

    echo "</clinic>\n";
}

function list_misc_events($result,$i) {
    echo '<hr><h4>'.errorOnEmpty(mysql_result($result,$i,"number"),"number").' ';
    echo errorOnEmpty(mysql_result($result,$i,"name"),"event name").'</h4>';
    
    echo 'description:  '.warnOnEmpty(mysql_result($result,$i,"description"),"description").'<br>';  
    echo "<p>\n";
    echo 'status code:  '.warnOnEmpty(mysql_result($result,$i,"status_code"),"status code").'<br>';  
    
    echo 'event number:  '.errorOnEmpty(mysql_result($result,$i,"number"),"number").'<br>';  
    echo 'start date:  '.errorOnEmpty(mysql_result($result,$i,"start_date"),"start").'<br>';  
    echo 'end date:  '.errorOnEmpty(mysql_result($result,$i,"end_date"),"end").'<br>';  

    echo 'location:  '.mysql_result($result,$i,"location_name").'<br>';  
    echo 'event URL:  '.mysql_result($result,$i,"clinic_url").'<br>';  

}

?>
