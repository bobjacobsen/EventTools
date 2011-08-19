<?php

// -------------------------------------------------------------------------
// Part of EventTools, a package for managing X2011west information
//
// By Bob Jacobsen, rgj1927@pacbell.net, Copyright 2010, 2011
// -------------------------------------------------------------------------

// -------------------------------------------------------------------------
//
// Appearance utility functions
//

function checkShowEventStatus($result,$i) {
    global $event_tools_show_min_value;
    $status = (int)mysql_result($result,$i,"event_status_code");
    return $status >= $event_tools_show_min_value;
}

function checkShowLayoutStatus($result,$i) {
    global $event_tools_show_min_layout_value;
    $status = (int)mysql_result($result,$i,"layout_status_code");
    return $status >= $event_tools_show_min_layout_value;
}

function checkShowStatus($result,$i) {
    global $event_tools_show_min_value;
    $status = (int)mysql_result($result,$i,"status_code");
    return $status >= $event_tools_show_min_value;
}


// -------------------------------------------------------------------------
//
// Time and date utility functions
//

//
// Convert "2011-07-03 13:00:00" to "Friday"
//
function day_from_long_format($date) {
    if (check_missing_time($date)) {
        return show_missing_time($date);
    }
    $d = date_create($date, new DateTimeZone('America/Los_Angeles'));
    return $d->format('l');
}

//
// Convert "2011-07-03 13:00:00" to "Friday July 3"
//
function daydate_from_long_format($date) {
    if (check_missing_time($date)) {
        return show_missing_time($date);
    }
    $d = date_create($date, new DateTimeZone('America/Los_Angeles'));
    return $d->format('l F j');
}

//
// Convert "2011-07-03 13:00:00" to "July 3"
//
function date_from_long_format($date) {
    if (check_missing_time($date)) {
        return show_missing_time($date);
    }
    $d = date_create($date, new DateTimeZone('America/Los_Angeles'));
    return $d->format('F j');
}

//
// Convert "2011-07-03 13:00:00" to "Friday, 1:00 PM"
function daytime_from_long_format($date) {
    if (check_missing_time($date)) {
        return show_missing_time($date);
    }
    $d = date_create($date, new DateTimeZone('America/Los_Angeles'));
    return $d->format('l, g:i A');
}

//
// Convert "2011-07-03 13:00:00" to "Friday July 3, 1:00 PM"
function daydatetime_from_long_format($date) {
    if (check_missing_time($date)) {
        return show_missing_time($date);
    }
    $d = date_create($date, new DateTimeZone('America/Los_Angeles'));
    return $d->format('l F j, g:i A');
}

//
// Convert "2011-07-03 13:00:00" to "1:00 PM"
function time_from_long_format($date) {
    if (check_missing_time($date)) {
        return show_missing_time($date);
    }
    $d = date_create($date, new DateTimeZone('America/Los_Angeles'));
    return $d->format('g:i A');
}

//
// Check for missing time
function check_missing_time($date) {
    if ($date == "") { return TRUE; }
    if ($date == "0000-00-00 00:00:00") { return TRUE; }
    if ($date == "2011-07-01 00:00:00") { return TRUE; }
    if (substr($date,-8) == "00:00:00") { return TRUE; }
    return FALSE;
}
function show_missing_time($date) {
    global $event_tools_replace_on_data_error;
    if ($event_tools_replace_on_data_error)
        return "<span class=\"et-error-missing\">(date time)</span>";
    else
        return "<span class=\"et-error-missing\">".$date."</span>";            
}

function tourPeriod($start, $end) {
    global $event_tools_replace_on_data_error;
    if (check_missing_time($start) ) {
        $r = "<span class=\"et-error-missing\">( ";
        if ($event_tools_replace_on_data_error) {
            if (check_missing_time($start)) {
                $r = $r."start ";
            }
            if (check_missing_time($end)) {
                $r = $r." end ";
            }
        } else {
            $r = $r.$start." ".$end." ";
        } 
        return $r." )</span>";
    }

    $s = date_create($start, new DateTimeZone('America/Los_Angeles'))->format('Hi');
    $e = date_create($end, new DateTimeZone('America/Los_Angeles'))->format('Hi');

    if ($s >  0000 && $e == 0000) return "";
    if (              $e <= 1200) return "Morning";
    if ($s >= 1630              ) return "Evening";
    if ($s>= 1000 && $s<=1200 && $e>=1200 && $e<=1500) return "Mid Day";
    if ($s <  1200 && $e >  1200) return "All Day";
    if ($s >= 1200 && $e <= 1800) return "Afternoon";
    return "";
}

// -------------------------------------------------------------------------
//
// HTML utilities
//
function warnOnEmpty($item, $name) {
    global $event_tools_replace_on_data_warn;
    if (checkEmpty($item)) {
        if ($event_tools_replace_on_data_warn)
            return "<span class=\"et-warning-missing\">(".$name.")</span>";
        else
            return "<span class=\"et-warning-missing\">".$item."</span>";            
    } else {
        return $item;
    }
}
function warnOnZero($item, $name) {
    global $event_tools_replace_on_data_warn;
    if ((int)$item == 0) {
        if ($event_tools_replace_on_data_warn)
            return "<span class=\"et-warning-missing\">(".$name." unset)</span>";
        else
            return "<span class=\"et-warning-missing\">".$item."</span>";            
    } else {
        return $item;
    }
}
function warnOnNeg($item, $name) {
    global $event_tools_replace_on_data_warn;
    if ((int)$item < 0) {
        if ($event_tools_replace_on_data_warn)
            return "<span class=\"et-warning-missing\">(".$name." = 0)</span>";
        else
            return "<span class=\"et-warning-missing\">".$item."</span>";            
    } else {
        return $item;
    }
}

function errorOnEmpty($item, $name) {
    global $event_tools_replace_on_data_error;
    if (checkEmpty($item)) {
        if ($event_tools_replace_on_data_error)
            return "<span class=\"et-error-missing\">(".$name.")</span>";
        else
            return "<span class=\"et-error-missing\">".$item."</span>";            
    } else {
        return $item;
    }
}
function errorOnMissingTime($time, $name) {
    global $event_tools_replace_on_data_error;
    if (check_missing_time($time)) {
        if ($event_tools_replace_on_data_error)
            return "<span class=\"et-error-missing\">".$time." (".$name.")</span>";
        else
            return "<span class=\"et-error-missing\">".$time."</span>";            
    } else {
        return $time;
    }
}
function errorOnZero($item, $name) {
    global $event_tools_replace_on_data_error;
    if ((int)$item == 0) {
        if ($event_tools_replace_on_data_error)
            return "<span class=\"et-error-missing\">(".$name." = 0)</span>";
        else
            return "<span class=\"et-error-missing\">".$item."</span>";            
    } else {
        return $item;
    }
}
function errorOnNegative($item, $name) {
    global $event_tools_replace_on_data_error;
    if ((int)$item < 0) {
        if ($event_tools_replace_on_data_error)
            return "<span class=\"et-error-missing\">(".$name." unset)</span>";
        else
            return "<span class=\"et-error-missing\">".$item."</span>";            
    } else {
        return $item;
    }
}

function checkEmpty($item) {
    if ($item == NONE) return true;
    if (is_string($item) && $item == '') return true;
    if (is_int($item) && $item == 0) return true;
    if (is_float($item) && $item == 0.0) return true;
    return false;
}

// $document should contain an HTML document. 
// This will remove HTML tags, javascript sections 
// and white space. It will also convert some 
// common HTML entities to their text equivalent. 

require_once('class.html2text.inc'); 
function htmlToText($document) {
    $h2t =& new html2text(str_replace("\n"," ",str_replace("\r"," ",$document))); 
    return $h2t->get_text(); 
}


// -------------------------------------------------------------------------
//
// Data access utilities
//

function get_clinic_tags() {
    global $opts, $event_tools_db_prefix;
    mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
    @mysql_select_db($opts['db']) or die( "Unable to select database");

    $query="
        SELECT DISTINCT tag_name
        FROM ".$event_tools_db_prefix."eventtools_clinic_tags
        ORDER BY tag_name
        ;
    ";

    $result=mysql_query($query);
    
    $i = 0;
    $num = mysql_numrows($result);

    $results = array();

    while ($i < $num) {
        $results[] = mysql_result($result,$i,"tag_name");
        $i++;
    }
    return $results;
}

function get_misc_event_tags() {
    global $opts, $event_tools_db_prefix;
    mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
    @mysql_select_db($opts['db']) or die( "Unable to select database");

    $query="
        SELECT DISTINCT tag_name
        FROM ".$event_tools_db_prefix."eventtools_misc_event_tags
        ORDER BY tag_name
        ;
    ";

    $result=mysql_query($query);
    
    $i = 0;
    $num = mysql_numrows($result);

    $results = array();

    while ($i < $num) {
        $results[] = mysql_result($result,$i,"tag_name");
        $i++;
    }
    return $results;
}

function get_clinic_presenters() {
    global $opts, $event_tools_db_prefix;
    mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
    @mysql_select_db($opts['db']) or die( "Unable to select database");

    $query="
        SELECT DISTINCT clinic_presenter
        FROM ".$event_tools_db_prefix."eventtools_clinics
        ORDER BY clinic_presenter
        ;
    ";

    $result=mysql_query($query);
    
    $i = 0;
    $num = mysql_numrows($result);

    $results = array();

    while ($i < $num) {
        $results[] = mysql_result($result,$i,"clinic_presenter");
        $i++;
    }
    return $results;
}

function get_layout_tour_numbers() {
    return get_event_numbers("layout_tours");
}
function get_general_tour_numbers() {
    return get_event_numbers("general_tours");
}
function get_event_numbers($table) {
    global $opts, $event_tools_db_prefix;
    global $event_tools_show_min_value;
    
    mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
    @mysql_select_db($opts['db']) or die( "Unable to select database");

    $query="
        SELECT DISTINCT number, name
        FROM ".$event_tools_db_prefix."eventtools_".$table."
        WHERE status_code >= ".$event_tools_show_min_value."
        ORDER BY number
        ;
    ";

    $result=mysql_query($query);
    
    $i = 0;
    $num = mysql_numrows($result);

    $results = array();

    while ($i < $num) {
        $results[] = mysql_result($result,$i,"number").' '.mysql_result($result,$i,"name");
        $i++;
    }
    return $results;
}

?>
