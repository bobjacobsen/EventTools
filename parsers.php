<?php

// -------------------------------------------------------------------------
// Part of EventTools, a package for managing model railroad meeting information
//
// By Bob Jacobsen, jacobsen@mac.com, Copyright 2010, 2011
// -------------------------------------------------------------------------

// -------------------------------------------------------------------------
//
// Parse query strings into "where" strings
//
// First, a bunch of individual routines so
// you can control which queries are available
// on a particular page.
//
// Further down are standard queries for page types
//
// Each takes an existing "where" string, ands
// adds a specific limitation if it's present.
//
// Do first:
// parse_str($_SERVER["QUERY_STRING"], $args);


// tag=AA  for clinic and misc event tags
function where_add_changed($args, $where=NULL) {
    if (isset($args["changed"])) {
        $r = " mark_changed != '' ";
        if ($where != NULL) return $where." AND ".$r;
        else return $r;
    } else {
        return $where;
    }
}

// tag=AA  for clinic and misc event tags
function where_add_tag($args, $where=NULL) {
    if (isset($args["tag"])) {
        $r = " tag_name = \"".$args["tag"]."\"";
        if ($where != NULL) return $where." AND ".$r;
        else return $r;
    } else {
        return $where;
    }
}

// date=n  tours, clinics starting on July n
function where_add_date($args, $where=NULL) {
    if (isset($args["date"])) {
        $r = " start_date LIKE '2021-".$args["date"]."%'";
        if ($where != NULL) return $where." AND ".$r;
        else return $r;
    } else {
        return $where;
    }
}

// number=AA  tours, events, clinic number
function where_add_number($args, $where=NULL) {
    if (isset($args["number"])) {
        $r = " number = '".$args["number"]."'";
        if ($where != NULL) return $where." AND ".$r;
        else return $r;
    } else {
        return $where;
    }
}

// id=n  tours, events, clinic ID number
function where_add_id($args, $where=NULL) {
    if (isset($args["id"])) {
        $r = " id = ".$args["id"];
        if ($where != NULL) return $where." AND ".$r;
        else return $r;
    } else {
        return $where;
    }
}

// layoutid=n  layout ID number
function where_add_layoutid($args, $where=NULL) {
    if (isset($args["layoutid"])) {
        $r = " layout_id = ".$args["layoutid"];
        if ($where != NULL) return $where." AND ".$r;
        else return $r;
    } else {
        return $where;
    }
}

// name=AA  tours, events, clinic names
function where_add_name($args, $where=NULL) {
    if (isset($args["name"])) {
        $r = " name LIKE '%".$args["name"]."%'";
        if ($where != NULL) return $where." AND ".$r;
        else return $r;
    } else {
        return $where;
    }
}

// name=AA  layoutname
function where_add_layoutname($args, $where=NULL) {
    if (isset($args["layoutname"])) {
        $r = " layout_name LIKE '%".$args["layoutname"]."%'";
        if ($where != NULL) return $where." AND ".$r;
        else return $r;
    } else {
        return $where;
    }
}

// layoutname=AA  layoutname
function where_add_owner($args, $where=NULL) {
    if (isset($args["owner"])) {
        $r = " layout_owner_firstname LIKE '%".$args["owner"]."%' OR layout_owner_lastname LIKE '%".$args["owner"]."%' ";
        if ($where != NULL) return $where." AND ".$r;
        else return $r;
    } else {
        return $where;
    }
}

// access=n  layouts or layout tours with accessibility better than n
function where_add_access($args, $where=NULL) {
    if (isset($args["access"])) {
        $r = " layout_accessibility LIKE '%".$args["access"]."%'";
        if ($where != NULL) return $where." AND ".$r;
        else return $r;
    } else {
        return $where;
    }
}

// presenter=AA  clinics with presenter like AA
function where_add_presenter($args, $where=NULL) {
    if (isset($args["presenter"])) {
        $r = " clinic_presenter LIKE '%".$args["presenter"]."%'";
        if ($where != NULL) return $where." AND ".$r;
        else return $r;
    } else {
        return $where;
    }
}

// scale=AA  layouts or layout tours with scale like AA
function where_add_scale($args, $where=NULL) {
    if (isset($args["scale"])) {
        $r = " CONCAT(CONCAT(\" \",layout_scale),\" \") REGEXP BINARY ' ".$args["scale"]." '";
        if ($where != NULL) return $where." AND ".$r;
        else return $r;
    } else {
        return $where;
    }
}

// gauge=AA  layouts or layout tours with scale like AA
function where_add_gauge($args, $where=NULL) {
    if (isset($args["gauge"])) {
        $r = " CONCAT(CONCAT(\" \",layout_gauge),\" \") REGEXP BINARY ' ".$args["gauge"]." '";
        if ($where != NULL) return $where." AND ".$r;
        else return $r;
    } else {
        return $where;
    }
}

// prototype=AA  layouts or layout tours with prototype like AA
function where_add_prototype($args, $where=NULL) {
    if (isset($args["prototype"])) {
        $r = " layout_prototype LIKE '%".$args["prototype"]."%'";
        if ($where != NULL) return $where." AND ".$r;
        else return $r;
    } else {
        return $where;
    }
}

// layout=AA  layouts or layout tours with layout name like AA
function where_add_layout($args, $where=NULL) {
    if (isset($args["layout"])) {
        $r = " layout_name LIKE '%".$args["layout"]."%'";
        if ($where != NULL) return $where." AND ".$r;
        else return $r;
    } else {
        return $where;
    }
}

// match=AA for tours and events
//           searches (most of) the text fields
//           see next for checking layout fields
function where_add_match_event($args, $where=NULL) {
    if (isset($args["match"])) {
        $r = " ( ".
            " name LIKE '%".urldecode($args["match"])."%' OR ".
            " description LIKE '%".urldecode($args["match"])."%' ".
            " ) ";
        if ($where != NULL) return $where." AND ".$r;
        else return $r;
    } else {
        return $where;
    }
}

// match=AA  for layout tours
//           searches (most of) the text fields
function where_add_match_layout_tour($args, $where=NULL) {
    if (isset($args["match"])) {
        $r = " ( ".
            " name LIKE '%".urldecode($args["match"])."%' OR ".
            " description LIKE '%".urldecode($args["match"])."%' OR ".
            " layout_name LIKE '%".urldecode($args["match"])."%' OR ".
            " layout_short_description LIKE '%".urldecode($args["match"])."%' OR ".
            " layout_long_description LIKE '%".urldecode($args["match"])."%' OR ".
            " layout_prototype LIKE '%".urldecode($args["match"])."%' OR ".
            " layout_era LIKE '%".urldecode($args["match"])."%' OR ".
            " layout_plan_type LIKE '%".urldecode($args["match"])."%' OR ".
            " layout_ops_scheme LIKE '%".urldecode($args["match"])."%' OR ".
            " layout_control LIKE '%".urldecode($args["match"])."%' OR ".
            " layout_city LIKE '%".urldecode($args["match"])."%' OR ".
            " layout_owner_firstname LIKE '%".urldecode($args["match"])."%' OR ".
            " layout_owner_lastname LIKE '%".urldecode($args["match"])."%' ".
            " ) ";
        if ($where != NULL) return $where." AND ".$r;
        else return $r;
    } else {
        return $where;
    }
}

// match=AA  for layout
//           searches (most of) the text fields
function where_add_match_layout($args, $where=NULL) {
    if (isset($args["match"])) {
        $r = " ( ".
            " layout_name LIKE '%".urldecode($args["match"])."%' OR ".
            " layout_short_description LIKE '%".urldecode($args["match"])."%' OR ".
            " layout_long_description LIKE '%".urldecode($args["match"])."%' OR ".
            " layout_prototype LIKE '%".urldecode($args["match"])."%' OR ".
            " layout_era LIKE '%".urldecode($args["match"])."%' OR ".
            " layout_plan_type LIKE '%".urldecode($args["match"])."%' OR ".
            " layout_ops_scheme LIKE '%".urldecode($args["match"])."%' OR ".
            " layout_control LIKE '%".urldecode($args["match"])."%' OR ".
            " layout_city LIKE '%".urldecode($args["match"])."%' OR ".
            " layout_owner_firstname LIKE '%".urldecode($args["match"])."%' OR ".
            " layout_owner_lastname LIKE '%".urldecode($args["match"])."%' ".
            " ) ";
        if ($where != NULL) return $where." AND ".$r;
        else return $r;
    } else {
        return $where;
    }
}

// clinic status = works on the status number (show greater or equal)
// and over-rides the global "all less than 70" default
function where_add_clinic_status($args, $where=NULL) {
    global $event_tools_show_min_value;

    if (array_key_exists('status', $args)) {
        $event_tools_show_min_value = (int) $args["status"];
        $r = " status_code >= ".$args["status"];
        if ($where != NULL) return $where." AND ".$r;
        else return $r;
    } else {
        $r = " status_code < 70 ";
        if ($where != NULL) return $where." AND ".$r;
        else return $r;
    }
}

// event status = works on the status number (show greater)
// and over-rides the global
function where_add_status($args, $where=NULL) {
    global $event_tools_show_min_value;

    if (array_key_exists('status', $args)) {
        $event_tools_show_min_value = (int) $args["status"];
        $r = " status_code >= ".$args["status"];
        if ($where != NULL) return $where." AND ".$r;
        else return $r;
    }
    return $where;
}

// layout status = works on the status number (show greater)
// and over-rides the global
function where_add_layout_status($args, $where=NULL) {
    global $event_tools_show_min_value;

    if (array_key_exists('status', $args)) {
        $event_tools_show_min_value = (int) $args["status"];
        $r = " layout_status_code >= ".$args["status"];
        if ($where != NULL) return $where." AND ".$r;
        else return $r;
    }
    return $where;
}

// tour type - limits general tours to "G" or "P" numbers
function where_add_general_tour_type($args, $where=NULL) {
    global $event_tools_show_min_value;

    if (array_key_exists('type', $args)) {
        $r = " number  LIKE '%".$args["type"]."%' ";
        if ($where != NULL) return $where." AND ".$r;
        else return $r;
    }
    return $where;
}

// control=AA  layouts or layout tours with control name like AA
function where_add_control($args, $where=NULL) {
    if (isset($args["control"])) {
        $r = " layout_control LIKE '%".$args["control"]."%'";
        if ($where != NULL) return $where." AND ".$r;
        else return $r;
    } else {
        return $where;
    }
}

// era=AA  layouts or layout tours with era name like AA
function where_add_era($args, $where=NULL) {
    if (isset($args["era"])) {
        $r = " layout_era LIKE '%".$args["era"]."%'";
        if ($where != NULL) return $where." AND ".$r;
        else return $r;
    } else {
        return $where;
    }
}

// class=AA  layouts or layout tours with era name like AA
function where_add_class($args, $where=NULL) {
    if (isset($args["class"])) {
        $r = " layout_class LIKE '%".$args["class"]."%'";
        if ($where != NULL) return $where." AND ".$r;
        else return $r;
    } else {
        return $where;
    }
}

// theme=AA  layouts or layout tours with era name like AA
function where_add_theme($args, $where=NULL) {
    if (isset($args["theme"])) {
        $r = " layout_theme LIKE '%".$args["theme"]."%'";
        if ($where != NULL) return $where." AND ".$r;
        else return $r;
    } else {
        return $where;
    }
}

// scenery=AA  layouts or layout tours with scenery like AA
function where_add_scenery($args, $where=NULL) {
    if (isset($args["scenery"])) {
        $r = " layout_scenery LIKE '%".$args["scenery"]."%'";
        if ($where != NULL) return $where." AND ".$r;
        else return $r;
    } else {
        return $where;
    }
}

// size=AA  layouts or layout tours with size like AA
function where_add_size($args, $where=NULL) {
    if (isset($args["size"])) {
        $r = " layout_size LIKE '%".$args["size"]."%'";
        if ($where != NULL) return $where." AND ".$r;
        else return $r;
    } else {
        return $where;
    }
}

// where=    Use last, if you want people to be able to provide
//           their own 'where' string
function where_add_where($args, $where=NULL) {
    if (isset($args["where"])) {
        return urldecode($args["where"]);
    } else {
        return $where;
    }
}

// -------------------------------------------------------------------------
//
// Standard queries for page types.
//
// These do the argument parsing for you

function parse_clinic_query() {
    parse_str($_SERVER["QUERY_STRING"], $args);
    $where = where_add_clinic_status($args, NULL);
    $where = where_add_changed($args, $where);
    $where = where_add_tag($args, $where);
    $where = where_add_number($args, $where);
    $where = where_add_id($args, $where);
    $where = where_add_name($args, $where);
    $where = where_add_presenter($args, $where);
    $where = where_add_date($args, $where);
    $where = where_add_match_event($args, $where);
    $where = where_add_where($args, $where);
    //echo $where;
    return $where;
}

function parse_misc_event_query() {
    parse_str($_SERVER["QUERY_STRING"], $args);
    $where = where_add_status($args, NULL);
    $where = where_add_changed($args, $where);
    $where = where_add_tag($args, $where);
    $where = where_add_number($args, $where);
    $where = where_add_id($args, $where);
    $where = where_add_name($args, $where);
    $where = where_add_date($args, $where);
    $where = where_add_match_event($args, $where);
    $where = where_add_where($args, $where);
    //echo $where;
    return $where;
}

function parse_general_tour_query() {
    parse_str($_SERVER["QUERY_STRING"], $args);
    $where = where_add_status($args, NULL);
    $where = where_add_changed($args, $where);
    $where = where_add_number($args, $where);
    $where = where_add_id($args, $where);
    $where = where_add_name($args, $where);
    $where = where_add_date($args, $where);
    $where = where_add_match_event($args, $where);
    $where = where_add_where($args, $where);
    $where = where_add_general_tour_type($args, $where);
    //echo $where;
    return $where;
}

function parse_layout_tour_query() {
    parse_str($_SERVER["QUERY_STRING"], $args);
    $where = where_add_status($args, NULL);
    $where = where_add_changed($args, $where);
    $where = where_add_number($args, $where);
    $where = where_add_id($args, $where);
    $where = where_add_layoutid($args, $where);
    $where = where_add_name($args, $where);
    $where = where_add_scale($args, $where);
    $where = where_add_prototype($args, $where);
    $where = where_add_layout($args, $where);
    $where = where_add_date($args, $where);
    $where = where_add_access($args, $where);
    $where = where_add_match_layout_tour($args, $where);
    $where = where_add_where($args, $where);
    //echo $where;
    return $where;
}

function parse_layout_query() {
    parse_str($_SERVER["QUERY_STRING"], $args);
    $where = where_add_layout_status($args, NULL);
    $where = where_add_changed($args, $where);
    $where = where_add_number($args, $where);
    $where = where_add_id($args, $where);
    $where = where_add_name($args, $where);
    $where = where_add_layoutname($args, $where);
    $where = where_add_layoutid($args, $where);
    $where = where_add_scale($args, $where);
    $where = where_add_prototype($args, $where);
    $where = where_add_layout($args, $where);
    $where = where_add_date($args, $where);
    $where = where_add_access($args, $where);
    $where = where_add_match_layout($args, $where);
    $where = where_add_owner($args, $where);

    $where = where_add_control($args, $where);
    $where = where_add_gauge($args, $where);
    $where = where_add_era($args, $where);
    $where = where_add_class($args, $where);
    $where = where_add_theme($args, $where);
    $where = where_add_scenery($args, $where);
    $where = where_add_size($args, $where);

    $where = where_add_where($args, $where);
    //echo $where;
    return $where;
}

function parse_order() {
    parse_str($_SERVER["QUERY_STRING"], $args);
    if (isset($args["order"])) {
        if ($args["order"] == "status") return " status_code ";
        if ($args["order"] == "lstatus") return " layout_status_code ";
        if ($args["order"] == "name") return " name ";
        if ($args["order"] == "number") return " number ";
        if ($args["order"] == "date") return " start_date ";
        if ($args["order"] == "time") return " start_date ";
        if ($args["order"] == "price") return " tour_price ";
        if ($args["order"] == "presenter") return " clinic_presenter ";
        if ($args["order"] == "clinic_room") return " clinic_location_code ";
        if ($args["order"] == "misc_room") return " misc_location_code ";
        if ($args["order"] == "city") return " layout_city ";
        if ($args["order"] == "owner") return " layout_owner_firstname, layout_owner_lastname ";
        if ($args["order"] == "lastname") return " layout_owner_lastname ";
        if ($args["order"] == "layoutname") return " layout_name ";
        if ($args["order"] == "create") return " customers_create_date ";
        if ($args["order"] == "update") return " customers_updated_date ";
        if ($args["order"] == "clastname") return " customers_lastname ";
        if ($args["order"] == "cfirstname") return " customers_firstname ";
        if ($args["order"] == "email") return " customers_email_address ";
        if ($args["order"] == "category") return " opsreq_priority DESC ";
        return NULL;
    } else {
        return NULL;
    }
}

?>
