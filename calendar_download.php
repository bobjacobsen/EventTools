<?php
// -------------------------------------------------------------------------
// Part of EventTools, a package for managing model railroad meeting information
//
// By Bob Jacobsen, jacobsen@mac.com, Copyright 2010, 2011
// -------------------------------------------------------------------------
include_once('mysql2i.class.php'); // migration step

require_once('access.php');
require_once('utilities.php');
require_once('formatting.php');
require_once('calendar/includes/iCalcreator.class.php');

function calendarArray($date) {
    $result = array();
    $result['year'] = substr( $date,0, 4);
    $result['month'] = substr( $date, 5, 2);
    $result['day'] = substr( $date, 8, 2 );
    $result['hour'] = substr( $date, 11, 2);
    $result['min'] = substr( $date, 14, 2);
    $result['sec'] = substr( $date, 17, 2);
    return $result;
}

function addStdContent($result,$i,$vevent,$asText,$id,$summary) {
    $start = calendarArray(mysql_result($result,$i,"start_date"));
    $end = calendarArray(mysql_result($result,$i,"end_date"));
    $description = mysql_result($result,$i,"description");
    if ($asText) $description = htmlToText($description);
    
    $vevent->setProperty( 'dtstart', $start);
    $vevent->setProperty( 'dtend',   $end);
    $vevent->setProperty( 'summary', $summary );  // event title
    $vevent->setProperty( 'description', $description );  // shows up in "comment"
    $vevent->setProperty( 'UID', $id."@kc2018.org" );  // needed for multiple downloads

}

function storegroup($v, $result) {
    $i = 0;
    $num = mysql_numrows($result);
    //echo "[".$num."]";
    $lastmajorkey = '';
    
    while ($i < $num) {
    
        // ensure synch, shouldn't be needed
        if ($lastmajorkey != mysql_result($result,$i,"start_date").mysql_result($result,$i,"name")) {
            $lastmajorkey = mysql_result($result,$i,"start_date").mysql_result($result,$i,"name");
            $vevent = new vevent();
    
            $summary = (mysql_result($result,$i,"number")!="" ? mysql_result($result,$i,"number")." ": "").mysql_result($result,$i,"name");
            addStdContent($result,$i,$vevent,$asText, "layouttour".mysql_result($result,$i,"id"), $summary);
            //echo $summary.'<br/>';            
            $v->setComponent ( $vevent );
        }
    
        $i++;
    }
}

function loadPurchases($v, $asText=TRUE, $email) {
    global $opts, $event_tools_db_prefix;

    // first do general tours
    $query="
        SELECT *
            FROM ((".$event_tools_db_prefix."customers
                LEFT JOIN ".$event_tools_db_prefix."orders
                ON ".$event_tools_db_prefix."customers.customers_id = ".$event_tools_db_prefix."orders.customers_id )
                LEFT JOIN ".$event_tools_db_prefix."orders_products
                ON ".$event_tools_db_prefix."orders.orders_id = ".$event_tools_db_prefix."orders_products.orders_id )
                LEFT JOIN ".$event_tools_db_prefix."eventtools_general_tours
                ON ".$event_tools_db_prefix."orders_products.products_model = ".$event_tools_db_prefix."eventtools_general_tours.number           
            WHERE ".$event_tools_db_prefix."customers.customers_email_address = '".$email."' 
            ORDER BY ".$event_tools_db_prefix."eventtools_general_tours.number
            ;
        ";
    //echo $query;
    $result=mysql_query($query);
        
    storegroup($v, $result);
    
    // then repeat for layout tours
    $query="
        SELECT *
            FROM ((".$event_tools_db_prefix."customers
                LEFT JOIN ".$event_tools_db_prefix."orders
                ON ".$event_tools_db_prefix."customers.customers_id = ".$event_tools_db_prefix."orders.customers_id )
                LEFT JOIN ".$event_tools_db_prefix."orders_products
                ON ".$event_tools_db_prefix."orders.orders_id = ".$event_tools_db_prefix."orders_products.orders_id )
                LEFT JOIN ".$event_tools_db_prefix."eventtools_layout_tours
                ON ".$event_tools_db_prefix."orders_products.products_model = ".$event_tools_db_prefix."eventtools_layout_tours.number           
            WHERE ".$event_tools_db_prefix."customers.customers_email_address = '".$email."' 
            ORDER BY ".$event_tools_db_prefix."eventtools_layout_tours.number
            ;
        ";
    //echo '<p>'.$query;
    $result=mysql_query($query);
    
    storegroup($v, $result);
}

function loadShoppingCart($v, $asText=TRUE, $email) {
    global $opts, $event_tools_db_prefix;

    // first do general tours
    $query="
        SELECT *
            FROM ((".$event_tools_db_prefix."customers
                LEFT JOIN ".$event_tools_db_prefix."customers_basket
                ON ".$event_tools_db_prefix."customers.customers_id = ".$event_tools_db_prefix."customers_basket.customers_id )
                LEFT JOIN ".$event_tools_db_prefix."products
                ON ".$event_tools_db_prefix."customers_basket.products_id = ".$event_tools_db_prefix."products.products_id )
                LEFT JOIN ".$event_tools_db_prefix."eventtools_general_tours
                ON ".$event_tools_db_prefix."products.products_model = ".$event_tools_db_prefix."eventtools_general_tours.number           
            WHERE ".$event_tools_db_prefix."customers.customers_email_address = '".$email."' 
            ORDER BY ".$event_tools_db_prefix."eventtools_general_tours.number
            ;
        ";
    //echo $query;
    $result=mysql_query($query);
        
    storegroup($v, $result);
    
    // then repeat for layout tours
    $query="
        SELECT *
            FROM ((".$event_tools_db_prefix."customers
                LEFT JOIN ".$event_tools_db_prefix."customers_basket
                ON ".$event_tools_db_prefix."customers.customers_id = ".$event_tools_db_prefix."customers_basket.customers_id )
                LEFT JOIN ".$event_tools_db_prefix."products
                ON ".$event_tools_db_prefix."customers_basket.products_id = ".$event_tools_db_prefix."products.products_id )
                LEFT JOIN ".$event_tools_db_prefix."eventtools_layout_tours
                ON ".$event_tools_db_prefix."products.products_model = ".$event_tools_db_prefix."eventtools_layout_tours.number           
            WHERE ".$event_tools_db_prefix."customers.customers_email_address = '".$email."' 
            ORDER BY ".$event_tools_db_prefix."eventtools_layout_tours.number
            ;
        ";
    //echo '<p>'.$query;
    $result=mysql_query($query);
    
    storegroup($v, $result);
}

function loadClinics($v, $asText=TRUE, $where=NONE) {
    global $opts, $event_tools_db_prefix;

    if ($where != NONE) {
        $where = " WHERE ".$where;
    }
    
    $query="
        SELECT *
        FROM ".$event_tools_db_prefix."eventtools_clinics_with_tags
        ".$where."
        ORDER BY start_date, end_date, name
        ;
    ";
    //echo $query;
    $result=mysql_query($query);
    
    
    $i = 0;
    $num = mysql_numrows($result);
    $lastmajorkey = '';
    
    while ($i < $num) {
    
        // ensure synch, shouldn't be needed
        if ($lastmajorkey != mysql_result($result,$i,"start_date").mysql_result($result,$i,"name")) {
            $lastmajorkey = mysql_result($result,$i,"start_date").mysql_result($result,$i,"name");
            $vevent = new vevent();
    
            $summary = "Clinic: ".mysql_result($result,$i,"name");
            addStdContent($result,$i,$vevent,$asText,"clinc-".mysql_result($result,$i,"id"), $summary);
    
            $location = mysql_result($result,$i,"location_name");
            $clinic_url = mysql_result($result,$i,"clinic_url");
            
            $vevent->setProperty( 'LOCATION', $location );
            if ($clinic_url != "") {
                $vevent->setProperty( 'URL', $clinic_url );
            }
    
            // duplicate records hold tags; NULL tag if 1st record has no tag
            $tags = array();
            $k = $i;
            
            while ($k < $num) {
                if ($lastmajorkey == mysql_result($result,$k,"start_date").mysql_result($result,$k,"name")) {
                    // tag
                    $tags[] = mysql_result($result,$k,"tag_name");
                    $k++;
                } else {
                    break;
                }
            }
            
            $vevent->setProperty( 'categories', $tags);
    
            $v->setComponent ( $vevent );
        }
    
        $i++;
    }
}

function loadMiscEvents($v, $asText=TRUE, $where=NONE) {
    global $opts, $event_tools_db_prefix;

    if ($where != NONE) {
        $where = " WHERE ".$where;
    }
    
    $query="
        SELECT *
        FROM ".$event_tools_db_prefix."eventtools_misc_events_with_tags
        ".$where."
        ORDER BY start_date, end_date, name
        ;
    ";
    //echo $query;
    $result=mysql_query($query);
    
    
    $i = 0;
    $num = mysql_numrows($result);
    $lastmajorkey = '';
    
    while ($i < $num) {
    
        // ensure synch, shouldn't be needed
        if ($lastmajorkey != mysql_result($result,$i,"start_date").mysql_result($result,$i,"name")) {
            $lastmajorkey = mysql_result($result,$i,"start_date").mysql_result($result,$i,"name");
            $vevent = new vevent();
    
            $summary = mysql_result($result,$i,"name");
            addStdContent($result,$i,$vevent,$asText,"misc-".mysql_result($result,$i,"id"), $summary);
    
            $location = mysql_result($result,$i,"location_name");
            $misc_url = mysql_result($result,$i,"misc_url");
            
            $vevent->setProperty( 'LOCATION', $location );
            if ($misc_url != "") {
                $vevent->setProperty( 'URL', $misc_url );
            }
    
            // duplicate records hold tags; NULL tag if 1st record has no tag
            $tags = array();
            $k = $i;
            
            while ($k < $num) {
                if ($lastmajorkey == mysql_result($result,$k,"start_date").mysql_result($result,$k,"name")) {
                    // tag
                    $tags[] = mysql_result($result,$k,"tag_name");
                    $k++;
                } else {
                    break;
                }
            }
            
            $vevent->setProperty( 'categories', $tags);
    
            $v->setComponent ( $vevent );
        }
    
        $i++;
    }
}

function loadLayoutTours($v, $asText=TRUE, $where=NONE) {
    global $opts, $event_tools_db_prefix;

    if ($where != NONE) {
        $where = " WHERE ".$where;
    }
    
    $query="
        SELECT *
            FROM ".$event_tools_db_prefix."eventtools_layout_tour_with_layouts
            ORDER BY number, layout_tour_link_order
            ;
        ";
    //echo $query;
    $result=mysql_query($query);
    
    
    $i = 0;
    $num = mysql_numrows($result);
    $lastmajorkey = '';
    
    while ($i < $num) {
    
        // ensure synch, shouldn't be needed
        if ($lastmajorkey != mysql_result($result,$i,"start_date").mysql_result($result,$i,"name")) {
            $lastmajorkey = mysql_result($result,$i,"start_date").mysql_result($result,$i,"name");
            $vevent = new vevent();
    
            $summary = (mysql_result($result,$i,"number")!="" ? mysql_result($result,$i,"number")." ": "").mysql_result($result,$i,"name");
            addStdContent($result,$i,$vevent,$asText, "layouttour".mysql_result($result,$i,"id"), $summary);
            
            $v->setComponent ( $vevent );
        }
    
        $i++;
    }
}

function loadGeneralTours($v, $asText=TRUE, $where=NONE) {
    global $opts, $event_tools_db_prefix;

    if ($where != NONE) {
        $where = " WHERE ".$where;
    }
    
    $query="
        SELECT *
        FROM ".$event_tools_db_prefix."eventtools_general_tour_with_status
        ORDER BY start_date,  number
        ;
    ";
    //echo $query;
    $result=mysql_query($query);
    
    
    $i = 0;
    $num = mysql_numrows($result);
    $lastmajorkey = '';
    
    while ($i < $num) {
    
        // ensure synch, shouldn't be needed
        if ($lastmajorkey != mysql_result($result,$i,"start_date").mysql_result($result,$i,"name")) {
            $lastmajorkey = mysql_result($result,$i,"start_date").mysql_result($result,$i,"name");
            $vevent = new vevent();
    
            $summary = (mysql_result($result,$i,"number")!="" ? mysql_result($result,$i,"number")." ": "").mysql_result($result,$i,"name");
            addStdContent($result,$i,$vevent,$asText,"generaltour".mysql_result($result,$i,"id"), $summary);
    
            $v->setComponent ( $vevent );
        }
    
        $i++;
    }
}

// --------- start direct content --------------

// parse arguments
parse_str($_SERVER["QUERY_STRING"], $args);

// open the file

$v = new vcalendar();
$v->setConfig( 'unique_id', 'kc2018.org' );
$v->setProperty( 'method', 'PUBLISH' );
$v->setProperty( "x-wr-calname", "KC 2018" );
$v->setProperty( "X-WR-CALDESC", "Calendar Description" );
$v->setProperty( "X-WR-TIMEZONE", "America/Chicago" );
$v->setProperty( "tzid", "US-Central" );
$v->setProperty( "tzname", "CDT" );
$v->setProperty( "tzoffsetfrom", "-0500" );

// open database
mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
@mysql_select_db($opts['db']) or die( "Unable to select database");

$asText = FALSE; // convert any HTML in the description to text; given as URL argument, don't change
if($args["text"]=="on" || $args["text"]=="true") $asText = TRUE;

// write events
$purchases = FALSE;
if($args["purchases"]=="no" || $args["purchases"]=="false") $purchases = FALSE;
if($args["purchases"]=="on" || $args["purchases"]=="true") $purchases = TRUE;
if(strstr($args["types"],"p")) $purchases = TRUE;
if ($purchases == TRUE) loadPurchases($v, $asText, $args["email"]);

$shopping = FALSE;
if($args["shopcart"]=="no" || $args["shopcart"]=="false") $shopping = FALSE;
if($args["shopcart"]=="on" || $args["shopcart"]=="true") $shopping = TRUE;
if(strstr($args["types"],"s")) $shopping = TRUE;
if ($shopping == TRUE) loadShoppingCart($v, $asText, $args["email"]);

$clinics = TRUE;
if($args["clinics"]=="no" || $args["clinics"]=="false") $clinics = FALSE;
if($args["clinics"]=="on" || $args["clinics"]=="true") $clinics = TRUE;
if(strstr($args["types"],"c")) $clinics = TRUE;
$where = " status_code <= 70 ";
if ($clinics == TRUE) loadClinics($v, $asText, $where);

$misc = TRUE;
if($args["misc"]=="no" || $args["misc"]=="false") $misc = FALSE;
if($args["misc"]=="on" || $args["misc"]=="true") $misc = TRUE;
if(strstr($args["types"],"m")) $misc = TRUE;
$where = NONE;
if ($misc == TRUE) loadMiscEvents($v, $asText, $where);

$layouttours = TRUE;
if($args["layout"]=="no" || $args["layout"]=="false") $layouttours = FALSE;
if($args["layout"]=="on" || $args["layout"]=="true") $layouttours = TRUE;
if(strstr($args["types"],"l")) $layouttours = TRUE;
$where = " status_code >= 40 AND status_code <= 70 ";
if ($layouttours == TRUE) loadLayoutTours($v, $asText, $where);

$generaltours = TRUE;
if($args["general"]=="no" || $args["general"]=="false") $generaltours = FALSE;
if($args["general"]=="on" || $args["general"]=="true") $generaltours = TRUE;
if(strstr($args["types"],"g")) $generaltours = TRUE;
$where = " status_code >= 40 AND status_code <= 70 ";
if ($generaltours == TRUE) loadGeneralTours($v, $asText, $where);

// done, close database
mysql_close();    

// return the calendar file to force download
$v->returnCalendar();


?>