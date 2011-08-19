<?php

// -------------------------------------------------------------------------
// Part of EventTools, a package for managing X2011west information
//
// By Bob Jacobsen, rgj1927@pacbell.net, Copyright 2010, 2011
// -------------------------------------------------------------------------

// -------------------------------------------------------------------------

// Formatting interface and classes

interface Event_Indexer {
    public function format_item($result,$i,$url);
    public function select_statement($where, $order);
    public function default_where();
    public function default_order();
    public function getKey($result,$i);
}

function format_as_index($formatter, $url, $where=NONE, $order=NONE) {
    global $opts, $event_tools_db_prefix;
    mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
    @mysql_select_db($opts['db']) or die( "Unable to select database");
    
    //echo $formatter->select_statement($where, $order);
    $result=mysql_query($formatter->select_statement($where, $order));
    
    $i=0;
    $num=mysql_numrows($result);
    //echo $num;
    
    echo "<table border=\"1\" class=\"et-lt-table\">\n";

    $lastkey = "";
    
    while ($i < $num) {
        
        if ($lastkey != $formatter->getKey($result,$i)) {
            $lastkey = $formatter->getKey($result,$i);
            
            $formatter->format_item($result,$i,$url);
        }
        
        $i++;
    }
    
    echo '</table>';

    mysql_close();    
}

// -------------------------------------------------------------------------
//
// Requests for indexes
//

function index_layout_tours($url, $where=NONE, $order=NONE,$formatter=NULL) {
    
    if ($formatter==NULL) $formatter = new Index_Layout_Tours_as_Table;
    
    format_as_index($formatter, $url, $where, $order);
}

function index_general_tours($url, $where=NONE, $order=NONE,$formatter=NULL) {
    
    if ($formatter==NULL) $formatter = new Index_General_Tours_as_Table;
    
    format_as_index($formatter, $url, $where, $order);
}

function index_clinics($url, $where=NONE, $order=NONE,$formatter=NULL) {
    
    if ($formatter==NULL) $formatter = new Index_Clinics_as_Table;
    
    format_as_index($formatter, $url, $where, $order);
}

function index_misc_events($url, $where=NONE, $order=NONE,$formatter=NULL) {
    
    if ($formatter==NULL) $formatter = new Index_Misc_Events_as_Table;
    
    format_as_index($formatter, $url, $where, $order);
}

function index_layouts($url, $where=NONE, $order=NONE,$formatter=NULL) {
    
    if ($formatter==NULL) $formatter = new Index_Layouts_as_Table;
    
    format_as_index($formatter, $url, $where, $order);
}

function index_ops($url, $where=NONE, $order=NONE,$formatter=NULL) {
    
    if ($formatter==NULL) $formatter = new Index_Ops_as_Table;
    
    format_as_index($formatter, $url, $where, $order);
}


// -------------------------------------------------------------------------
//
// Formatter classes
//

function show_status_link_or_cost($result,$i) {
    global $event_tools_db_prefix, $eventtools_cartlink, $eventtools_lookup_flag, $eventtools_lookup_result;
    if (!$eventtools_lookup_flag) {
        $eventtools_lookup_flag = TRUE;
        
        // load a cache between model/tour_number and product ID
        $select = "
        SELECT products_model, products_id
            FROM ".$event_tools_db_prefix."products
            ;
        ";

        $product_lookup_result=mysql_query($select);
        while ($row = mysql_fetch_assoc($product_lookup_result)) {
            $eventtools_lookup_result[$row['products_model']] = $row['products_id'];
        }
        
        // ugly hack: Manually enter GNQ -> (Nonquet item)
        $eventtools_lookup_result['GNQ'] = '67';
        
    }

    if (((float)mysql_result($result,$i,"tour_price"))>=0) {
        $cost = "$".mysql_result($result,$i,"tour_price");
    } else {
        if ($event_tools_replace_on_data_error)
            $cost = "<span class=\"et-error-missing\">(no price)</span>";
        else
            $cost = "<span class=\"et-error-missing\">TBA</span>";
    }

    $product = $eventtools_lookup_result[mysql_result($result,$i,"number")];
    if (mysql_result($result,$i,"event_status_code") == '60' && $product != NULL) {
        return '<a href="'.$eventtools_cartlink.'/index.php?main_page=product_info&products_id='
            .$product.'">'
            .$cost
            .'</a>';
    } else {
        return mysql_result($result,$i,"event_status_display");
    }
}

abstract class Index_Tours_as_Table implements Event_Indexer {
    public function format_item($result,$i,$url) {
        global $event_tools_replace_on_data_error;
        
        if (checkShowEventStatus($result,$i)) {

            $cost = show_status_link_or_cost($result,$i);
            
            $date = daytime_from_long_format(mysql_result($result,$i,"start_date"))
                        ." - ".
                    time_from_long_format(mysql_result($result,$i,"end_date"));
            
    
            echo "<tr class=\"et-lt-tour-tr1\">\n";
            echo "  <td class=\"et-lt-tour-td1\">\n";
            echo "    <span class=\"et-lt-tour-tourNumber\">\n";
            echo "      <a href=\"".$url.mysql_result($result,$i,"number")."\">".mysql_result($result,$i,"number")."</a>\n";
            echo "    </span>\n";
            echo "  </td>\n";
            echo "  <td class=\"et-lt-tour-td2\">\n";
            echo "    <span class=\"et-lt-tour-tourName\">\n";
            echo "      <a href=\"".$url.mysql_result($result,$i,"number")."\">".htmlspecialchars(mysql_result($result,$i,"name"))."</a>\n";
            echo "    </span>\n";
            echo "  </td>\n";
            echo "  <td class=\"et-lt-tour-td3\">\n";        
            echo "    <span class=\"et-lt-tour-tourDateTime\">".$date."</span>\n";
            echo "  </td>\n";
            echo "  <td class=\"et-lt-tour-td4\">\n";        
            echo "    <span class=\"et-lt-tour-tourCost\">".$cost."</span>\n";
            echo "  </td>\n";
            echo "</tr>\n";
        }
    }

    public function default_where() { return ""; }
    public function default_order() { return " SUBSTRING(`number`,-4), number "; }
    public function getKey($result,$i) {
        return mysql_result($result,$i,"number").mysql_result($result,$i,"start_date").mysql_result($result,$i,"name");
    }
}

class Index_Layout_Tours_as_Table extends Index_Tours_as_Table {

    public function select_statement($where, $order) {
        global $event_tools_db_prefix;

        if ($where != NONE) {
            $w = ' WHERE '.$where;
        } else {
            $w = $this->default_where();
        }
        
        if ($order != NONE) {
            $o = $order;
        } else {
            $o = $this->default_order();
        }

        return "
            SELECT *
                FROM ".$event_tools_db_prefix."eventtools_layout_tour_with_layouts
                ".$w."
                ORDER BY ".$o."
                ;
            ";
    }
}

class Index_General_Tours_as_Table extends Index_Tours_as_Table {

    public function select_statement($where, $order) {
        global $event_tools_db_prefix;

        if ($where != NONE) {
            $w = ' WHERE '.$where;
        } else {
            $w = $this->default_where();
        }
        
        if ($order != NONE) {
            $o = $order;
        } else {
            $o = $this->default_order();
        }

        return "
            SELECT *
                FROM ".$event_tools_db_prefix."eventtools_general_tour_with_status
                ".$w."
                ORDER BY ".$o."
                ;
            ";
    }
}

class Index_Clinics_as_Table extends Index_Tours_as_Table {

    public function select_statement($where, $order) {
        global $event_tools_db_prefix;

        if ($where != NONE) {
            $w = ' WHERE '.$where;
        } else {
            $w = $this->default_where();
        }
        
        if ($order != NONE) {
            $o = $order;
        } else {
            $o = $this->default_order();
        }

        return "
            SELECT *
                FROM ".$event_tools_db_prefix."eventtools_clinics_with_tags
                ".$w."
                ORDER BY ".$o."
                ;
            ";
    }
    public function format_item($result,$i,$url) {
    
        $date = daytime_from_long_format(mysql_result($result,$i,"start_date"))
                    ." - ".
                time_from_long_format(mysql_result($result,$i,"end_date"));
        

        echo "<tr class=\"et-clinic-tr1\">\n";
        echo "  <td class=\"et-clinic-td1\">\n";
        echo "    <span class=\"et-clinic-name\">\n";
        echo "      <a href=\"".$url.mysql_result($result,$i,"number")."\">".htmlspecialchars(mysql_result($result,$i,"name"))."</a>\n";
        echo "    </span>\n";
        echo "  </td>\n";
        echo "  <td class=\"et-clinic-td2\">\n";
        echo "    <span class=\"et-clinic-presenter\">".mysql_result($result,$i,"clinic_presenter")."</span>\n";
        echo "  </td>\n";
        echo "  <td class=\"et-clinic-td3\">\n";        
        echo "    <span class=\"et-clinic-times\">".$date."</span>\n";
        echo "  </td>\n";
        echo "  <td class=\"et-clinic-td4\">\n";        
        echo "    <span class=\"et-clinic-location\">".mysql_result($result,$i,"location_name")."</span>\n";
        echo "  </td>\n";
        echo "</tr>\n";
    }
    public function default_order() { return " start_date, name "; }
}

class Index_Misc_Events_as_Table extends Index_Tours_as_Table {

    public function select_statement($where, $order) {
        global $event_tools_db_prefix;

        if ($where != NONE) {
            $w = ' WHERE '.$where;
        } else {
            $w = $this->default_where();
        }
        
        if ($order != NONE) {
            $o = $order;
        } else {
            $o = $this->default_order();
        }

        return "
            SELECT *
                FROM ".$event_tools_db_prefix."eventtools_misc_events_with_tags
                ".$w."
                ORDER BY ".$o."
                ;
            ";
    }
    public function format_item($result,$i,$url) {
    
        $date = daytime_from_long_format(mysql_result($result,$i,"start_date"))
                    ." - ".
                time_from_long_format(mysql_result($result,$i,"end_date"));
        

        echo "<tr class=\"et-misc-tr1\">\n";
        echo "  <td class=\"et-misc-td1\">\n";
        echo "    <span class=\"et-misc-name\">\n";
        echo "      <a href=\"".$url.mysql_result($result,$i,"id")."\">".htmlspecialchars(mysql_result($result,$i,"name"))."</a>\n";
        echo "    </span>\n";
        echo "  </td>\n";
        echo "  <td class=\"et-misc-td2\">\n";        
        echo "    <span class=\"et-misc-times\">".$date."</span>\n";
        echo "  </td>\n";
        echo "  <td class=\"et-misc-td3\">\n";        
        echo "    <span class=\"et-misc-location\">".mysql_result($result,$i,"location_name")."</span>\n";
        echo "  </td>\n";
        echo "</tr>\n";
    }
    public function default_order() { return " start_date, name "; }
}

class Index_Layouts_as_Table implements Event_Indexer {
    public function format_item($result,$i,$url) {
    
        echo "<tr class=\"et-lt-layout-tr1\">\n";
        echo "  <td class=\"et-lt-layout-td1\">\n";
        echo "    <span class=\"et-lt-layout-name\">\n";
        echo "      <a href=\"".$url.mysql_result($result,$i,"layout_id")."\">".htmlspecialchars(mysql_result($result,$i,"layout_name"))."</a>\n";
        echo "    </span>\n";
        echo "  </td>\n";
        echo "  <td class=\"et-lt-layout-td2\">\n";
        echo "    <span class=\"et-lt-layout-owner\">\n";
        echo "      <a href=\"".$url.mysql_result($result,$i,"layout_id")."\">".htmlspecialchars(mysql_result($result,$i,"layout_owner_firstname")." ".mysql_result($result,$i,"layout_owner_lastname"))."</a>\n";
        echo "    </span>\n";
        echo "  </td>\n";
        echo "  <td class=\"et-lt-layout-td3\">\n";        
        echo "    <span class=\"et-lt-layout-city\">".htmlspecialchars(mysql_result($result,$i,"layout_city"))."</span>\n";
        echo "  </td>\n";
        echo "</tr>\n";
    }

    public function default_where() { return ""; }
    public function default_order() { return "layout_name"; }

    public function select_statement($where, $order) {
        global $event_tools_db_prefix;

        if ($where != NONE) {
            $w = ' WHERE '.$where;
        } else {
            $w = $this->default_where();
        }
        
        if ($order != NONE) {
            $o = $order;
        } else {
            $o = $this->default_order();
        }

        return "
            SELECT *
                FROM ".$event_tools_db_prefix."eventtools_layouts
                ".$w."
                ORDER BY ".$o."
                ;
            ";
    }
    public function getKey($result,$i) {
        return mysql_result($result,$i,"layout_id").mysql_result($result,$i,"layout_id").mysql_result($result,$i,"layout_owner_lastname");
    }
}

class Index_Ops_as_Table extends Index_Layouts_as_Table {
    public function select_statement($where, $order) {
        global $event_tools_db_prefix;

        if ($where != NONE) {
            $w = ' WHERE '.$where;
        } else {
            $w = $this->default_where();
        }
        
        if ($order != NONE) {
            $o = $order;
        } else {
            $o = $this->default_order();
        }

        return "
            SELECT *
                FROM ".$event_tools_db_prefix."eventtools_opsession_name
                ".$w."
                ORDER BY ".$o."
                ;
            ";
    }
    public function default_order() { return "layout_owner_lastname1, start_date"; }
    
    public function format_item($result,$i,$url) {
    
        echo "<tr class=\"et-lt-layout-tr1\">\n";
        echo "  <td class=\"et-lt-layout-td1\">\n";
        echo "    <span class=\"et-lt-layout-owner\">\n";
        echo "      <a href=\"".$url.mysql_result($result,$i,"layout_id1")."\">".
                        htmlspecialchars(mysql_result($result,$i,"layout_owner_firstname1")." ".
                        mysql_result($result,$i,"layout_owner_lastname1"))."</a>\n";
        if (mysql_result($result,$i,"layout_owner_lastname2")!='') {
            echo "    /  <a href=\"".$url.mysql_result($result,$i,"layout_id2")."\">".
                        htmlspecialchars(mysql_result($result,$i,"layout_owner_firstname2")." ".
                        mysql_result($result,$i,"layout_owner_lastname2"))."</a>\n";
        }
        echo "    </span>\n";
        echo "  </td>\n";
        echo "  <td class=\"et-lt-layout-td2\">\n";
        echo "    <span class=\"et-lt-layout-name\">\n";
        echo "      <a href=\"".$url.mysql_result($result,$i,"layout_id1")."\">".
                                htmlspecialchars(mysql_result($result,$i,"layout_name1"))."</a>\n";

        if (mysql_result($result,$i,"layout_name2")!='') {
            echo "     / <a href=\"".$url.mysql_result($result,$i,"layout_id2")."\">".
                                htmlspecialchars(mysql_result($result,$i,"layout_name2"))."</a>\n";
        }
        echo "    </span>\n";
        echo "  </td>\n";
        echo "  <td class=\"et-lt-layout-td3\">\n";        
        echo "    <span class=\"et-lt-layout-opstime\">";
        echo "        ".htmlspecialchars(mysql_result($result,$i,"presenting_time"));
        if (mysql_result($result,$i,"distance")!='' || mysql_result($result,$i,"travel_time")!='') {
            echo " (";
            echo mysql_result($result,$i,"distance");
            if (mysql_result($result,$i,"distance")!='' && mysql_result($result,$i,"travel_time")!='')
                echo ', ';
            echo mysql_result($result,$i,"travel_time");
            echo ")";
        }
        echo "    </span>\n";
        echo "  </td>\n";
        echo "</tr>\n";
    }
    public function getKey($result,$i) {
        return mysql_result($result,$i,"presenting_time").mysql_result($result,$i,"layout_name1").mysql_result($result,$i,"layout_owner_lastname1");
    }
}

?>
