<?php

// -------------------------------------------------------------------------
// Part of EventTools, a package for managing X2011west information
//
// By Bob Jacobsen, rgj1927@pacbell.net, Copyright 2010, 2011
// -------------------------------------------------------------------------

// -------------------------------------------------------------------------

// Formatting interface and classes

interface Event_Formatter {
    public function format_heading($result,$i,$url=NONE);
    public function format_subitem($result,$i,$url=NONE);
    public function get_major_key($result,$i);
    public function select_statement($where=NONE, $order=NONE);
    public function default_where();
    public function default_order();
}

function format_as_table($formatter, $where=NONE, $order=NONE, $url=NONE) { // note argument order differs
    global $opts, $event_tools_db_prefix;
    mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
    @mysql_select_db($opts['db']) or die( "Unable to select database");
        
    //echo $formatter->select_statement($where, $order)."<br/>\n";
    $result=mysql_query($formatter->select_statement($where, $order));
    
    $i=0;
    $num=mysql_numrows($result);
    //echo "num: ".$num."<br/>\n";
    if ($num == 0) {
        mysql_close();
        return;
    }

    $lastmajorkey= "";
    $first = 0;
    
    while ($i < $num) {
        if ($lastmajorkey != $formatter->get_major_key($result,$i)) {
            $lastmajorkey = $formatter->get_major_key($result,$i);
    
           if (checkShowEventStatus($result,$i)) {
                // end any existing table
                if ($first != 0) {
                    echo "</table>\n";
                }
                $first = 1;
                
                // start a new table
                echo "\n";
                echo "<table border=\"1\" class=\"et-lt-table\">\n";
        
                $formatter->format_heading($result,$i, $url);
            }
        }
            
        if (checkShowEventStatus($result,$i)) $formatter->format_subitem($result,$i, $url);
        
        $i++;
    }
    
    echo '</table>';

    mysql_close();    
}

// -------------------------------------------------------------------------
//
// Requests for formatted listings
//

// 
// Listing of layout tours, including the sublist of layouts
//

function format_all_layout_tours_as_3table($where=NONE, $order=NONE, $url=NONE, $formatter=NONE) {
    
    if ($formatter==NONE) $formatter = new Layout_Tours_as_3Table;
    
    format_as_table($formatter, $where, $order, $url);
}

function format_all_layout_tours_as_8table($where=NONE, $order=NONE, $url=NONE, $formatter=NONE) {
    
    if ($formatter==NONE) $formatter = new Layout_Tours_as_8Table;
    
    format_as_table($formatter, $where, $order, $url);
}

// 
// Listing of general tours
//

function format_all_general_tours_as_3table($where=NONE, $order=NONE, $url=NONE, $formatter=NONE) {
    
    if ($formatter==NONE) $formatter = new General_Tours_as_3Table;
    
    format_as_table($formatter, $where, $order);
}

function format_all_general_tours_as_8table($where=NONE, $order=NONE, $url=NONE, $formatter=NONE) {
    
    if ($formatter==NONE) $formatter = new General_Tours_as_8Table;
    
    format_as_table($formatter, $where, $order);
}

function simple_table($table_name, $var_names, $where=NONE, $order=NONE) {
    global $opts, $event_tools_db_prefix;

    if ($order==NONE) $order = "layout_owner_lastname, layout_owner_firstname";

    if ($where != NONE) $where = "WHERE ".$where." ";
    else $where = " ";
    
    $query="
        SELECT  *
        FROM ".$event_tools_db_prefix."eventtools_".$table_name."
        ".$where."
        ORDER BY ".$order."
        ;
    ";
    //echo $query;

    table_from_query($query, $var_names);
}

function table_from_query($query, $var_names) {
    global $opts, $event_tools_db_prefix;

    mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
    @mysql_select_db($opts['db']) or die( "Unable to select database");
    
    //echo $query;
    $result=mysql_query($query);
    
    $i=0;
    $num=mysql_numrows($result);
    //echo $num;
    
    while ($i < $num) {
        $j = 0;
        echo "<tr>\n";
        $row = mysql_fetch_assoc($result);

        while ($j < count($var_names)) {
    
            simple_table_format_cell($j, $row, $var_names[$j]);
    
            $j++;
        }
        echo "</tr>\n";
        $i++;
    }    
}

// 
// Listing of layouts, including the sublist of layout tours they're on
//
// Also checks status of tours, adds op sessions at the end
function format_all_layouts_as_table($url=NONE, $where=NONE, $order=NONE) {
    global $opts, $event_tools_db_prefix, $event_tools_href_add_on;
    global $event_tools_show_min_value;

    mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
    @mysql_select_db($opts['db']) or die( "Unable to select database");
    
    if ($order==NONE) $order = "layout_owner_lastname, layout_owner_firstname";

    if ($where != NONE) $where = "WHERE ".$where." AND ";
    else $where = "WHERE ";
    $where .= " ( status_code >= ".$event_tools_show_min_value." OR id IS NULL ) ";  // good or blank status required
    
    $query="
        SELECT  *
        FROM ".$event_tools_db_prefix."eventtools_layout_with_layout_tours
        ".$where."
        ORDER BY ".$order."
        ;
    ";
    $result=mysql_query($query);
    
    $query="
        SELECT  *
        FROM ".$event_tools_db_prefix."eventtools_opsession_name
        ORDER BY start_date
        ;
    ";
    $resultOps=mysql_query($query);
    $numOps=mysql_numrows($resultOps);
    
    $i=0;
    $num=mysql_numrows($result);
    //echo "num: ".$num."<br/>\n";
    if ($num == 0) {
        mysql_close();
        return;
    }

    $lastmajorkey= "";
    $first = 0;
    
    while ($i < $num) {
        $majorkey = mysql_result($result,$i,"layout_id");
        
        // skip if not at sufficient level _and_ no associated tour
        if (!checkShowLayoutStatus($result, $i) && mysql_result($result,$i,"id")=='') {
            echo "\n<!-- skip ".$i;
            echo " ".mysql_result($result,$i,"layout_owner_firstname");
            echo " ".mysql_result($result,$i,"layout_owner_lastname");
            echo " #".mysql_result($result,$i,"layout_status_code");
            echo '# -->'."\n";
            $i++;
            continue;
        } else {
            echo "\n<!-- processing ".$i;
            echo " ".mysql_result($result,$i,"layout_owner_firstname");
            echo " ".mysql_result($result,$i,"layout_owner_lastname");
            echo " #".mysql_result($result,$i,"layout_status_code");
            echo '# -->'."\n";
        }
        
        if ($lastmajorkey != $majorkey) {
            $lastmajorkey = $majorkey;
    
            // end any existing table
            if ($first != 0) {
                echo "</table>\n";
            }
            $first = 1;
            
            // start a new table
            echo "\n";
            echo "<table border=\"1\" class=\"et-layout-table\">\n";
    
            // line 1 - Name
            echo "<tr class=\"et-layout-tr1\">\n";
            echo "  <td colspan=\"3\" class=\"et-layout-td1\">\n";
            echo "    <span class=\"et-layout-name\">\n";
            echo "      <a name=\"".mysql_result($result,$i,"layout_id")."\"></a>\n";
            echo "     ".errorOnEmpty(htmlspecialchars(mysql_result($result,$i,"layout_name")),"name");
            echo "      </span> \n";
            echo "  </td>\n";
            echo "</tr>\n";
        
            // line 2 - owner, city
            echo "<tr class=\"et-layout-tr2\">\n";
            echo "    <td colspan=\"2\" class=\"et-layout-td1\">\n";
            echo "        <span class=\"et-layout-owner\">".warnOnEmpty(htmlspecialchars(mysql_result($result,$i,"layout_owner_firstname")),"first").
                        " ".warnOnEmpty(htmlspecialchars(mysql_result($result,$i,"layout_owner_lastname")), "last")."</span> \n";
            echo "    </td>\n";
            echo "    <td class=\"et-layout-td3\">\n";
            echo "        <span class=\"et-layout-city\">".warnOnEmpty(htmlspecialchars(mysql_result($result,$i,"layout_city")),"city")."</span> \n";
            echo "    </td>\n";
            echo "</tr>\n";
            
            // line 3 - short description
            echo "<tr class=\"et-layout-tr3\">\n";
            echo "    <td colspan=\"3\" class=\"et-layout-td1\">\n";
            echo "        <span class=\"et-layout-shortdesc\">".errorOnEmpty(htmlspecialchars(mysql_result($result,$i,"layout_short_description")),"short description")."</span> \n";
            echo "    </td>\n";
            echo "</tr>\n";
        
            // line 4 - scale, prototype, URL
            echo "<tr class=\"et-layout-tr4\">\n";
            echo "    <td class=\"et-layout-td1\">\n";
            echo "        <span class=\"et-layout-scale\">".errorOnEmpty(htmlspecialchars(mysql_result($result,$i,"layout_scale")), "scale")."</span> \n";
            echo "    </td>\n";
            echo "    <td class=\"et-layout-td2\">\n";
            echo "        <span class=\"et-layout-prototype\">".warnOnEmpty(htmlspecialchars(mysql_result($result,$i,"layout_prototype")),"proto")."</span> \n";
            echo "    </td>\n";
            echo "    <td class=\"et-layout-td3\">\n";
            echo "        <span class=\"et-lt-layout-owner_url\">";
            echo "          <a ".$event_tools_href_add_on." href=\"".mysql_result($result,$i,"layout_owner_url")."\">".mysql_result($result,$i,"layout_owner_url")."</a>\n";
            echo "        </span>\n";
            echo "    </td>\n";
            echo "</tr>\n";
        
            // line 5 - scenery, size, mainline length
            echo "<tr class=\"et-layout-tr5\">\n";

            $scenery = warnOnEmpty(htmlspecialchars(mysql_result($result,$i,"layout_scenery")), "scenery");
            if (mysql_result($result,$i,"layout_scenery") !='') $scenery = 'Scenery: '.$scenery;
            echo "    <td class=\"et-layout-td1\">\n";
            echo "        <span class=\"et-layout-scenery\">".$scenery."</span> \n";
            echo "    </td>\n";
            echo "    <td class=\"et-layout-td2\">\n";
            echo "        <span class=\"et-layout-size\">".warnOnEmpty(htmlspecialchars(mysql_result($result,$i,"layout_size")),"size")."</span> \n";
            echo "    </td>\n";
            echo "    <td class=\"et-layout-td3\">\n";
            echo "        <span class=\"et-layout-mainline_length\">".warnOnEmpty(htmlspecialchars(mysql_result($result,$i,"layout_mainline_length")), "main len")."</span> \n";
            echo "    </td>\n";
            echo "</tr>\n";
        
            // line 6 - plan type, ops scheme, control
            echo "<tr class=\"et-layout-tr6\">\n";
            echo "    <td class=\"et-layout-td1\">\n";
            echo "        <span class=\"et-layout-plantype\">".warnOnEmpty(htmlspecialchars(mysql_result($result,$i,"layout_plan_type")), "plan")."</span> \n";
            echo "    </td>\n";
            echo "    <td class=\"et-layout-td2\">\n";
            echo "        <span class=\"et-layout-opsscheme\">".warnOnEmpty(htmlspecialchars(mysql_result($result,$i,"layout_ops_scheme")), "ops scheme")."</span> \n";
            echo "    </td>\n";
            echo "    <td class=\"et-layout-td3\">\n";
            echo "        <span class=\"et-layout-control\">".warnOnEmpty(htmlspecialchars(mysql_result($result,$i,"layout_control")), "control")."</span> \n";
            echo "    </td>\n";
            echo "</tr>\n";
        
            // line 7 - accessibility, era, photos
            if (mysql_result($result,$i,"accessibility_display") != "") {
                $accessibility = mysql_result($result,$i,"accessibility_display");
            } else {
                $accessibility = NONE;
            }
            echo "<tr class=\"et-layout-tr7\">\n";
            echo "    <td class=\"et-layout-td1\">\n";
            echo "        <span class=\"et-layout-accessibility\">".warnOnEmpty($accessibility, "accessibility")."</span> \n";
            echo "    </td>\n";
            echo "    <td class=\"et-layout-td2\">\n";
            echo "        <span class=\"et-layout-era\">".warnOnEmpty(htmlspecialchars(mysql_result($result,$i,"layout_era")), "era")."</span> \n";
            echo "    </td>\n";
            echo "    <td class=\"et-layout-td3\">\n";
            if (mysql_result($result,$i,"layout_photo_url") != '')
                echo '<a href="'.htmlspecialchars(mysql_result($result,$i,"layout_photo_url")).'">Photos</a>';
            echo "    </td>\n";
            echo "</tr>\n";
        
            // line 8 - long description
            echo "<tr class=\"et-layout-tr8\">\n";
            echo "    <td colspan=\"3\" class=\"et-layout-td1\">\n";
            echo "        <div class=\"et-layout-longdesc\">".errorOnEmpty(mysql_result($result,$i,"layout_long_description"), "long desc")."</div> \n";
            echo "    </td>\n";
            echo "</tr>\n";

            // list all op sessions this layout belongs to
            $id = mysql_result($result,$i,"layout_id");
            $op = 0;
            while ($op < $numOps) {
                if (mysql_result($resultOps,$op,"layout_id1") == $id || mysql_result($resultOps,$op,"layout_id2") == $id) {
                    // Hit, display a row
                    echo '<tr class="et-layout-tr-ops">';
                    echo '<td class="et-layout-tr-ops-td1">';
                    echo '<span class="et-layout-ops-time">';
                    echo 'Op Session on '.mysql_result($resultOps,$op,"presenting_time");
                    echo '</span>';
                    echo '</td>';
                    echo '<td class="et-layout-tr-ops-td1">';
                    echo '<span class="et-layout-ops-location">';
                    // handle two-fers
                    if (mysql_result($resultOps,$op,"layout_id1") == $id && mysql_result($resultOps,$op,"layout_id2") != 0) {
                        echo 'Combined with <a href="?layoutid='.mysql_result($resultOps,$op,"layout_id2").'">'.mysql_result($resultOps,$op,"layout_owner_firstname2").
                                ' '.mysql_result($resultOps,$op,"layout_owner_lastname2").' '.mysql_result($resultOps,$op,"layout_name2").
                                '</a>';
                    } else if (mysql_result($resultOps,$op,"layout_id2") == $id) {
                        echo 'Combined with <a href="?layoutid='.mysql_result($resultOps,$op,"layout_id1").'">'.
                                mysql_result($resultOps,$op,"layout_owner_firstname1").
                                ' '.mysql_result($resultOps,$op,"layout_owner_lastname1").' '.mysql_result($resultOps,$op,"layout_name1").
                                '</a>';
                    } else {
                        // show regional location in field
                        echo mysql_result($resultOps,$op,"location");
                    }
                    echo '</span>';
                    echo '</td>';
                    echo '<td class="et-layout-tr-ops-td1">';
                    echo '<span class="et-layout-ops-distanceTime">';
                    echo mysql_result($resultOps,$op,"distance");
                    if (mysql_result($resultOps,$op,"distance")!='' && mysql_result($resultOps,$op,"travel_time")!='')
                        echo ', ';
                    echo mysql_result($resultOps,$op,"travel_time");
                    echo '</span>';
                    echo '</td>';
                    echo '</tr>';
                }
                $op++;
            }
        }
        // list all layout tours this layout belongs to
        // checkShowStatus used because there are two statuses in the record, layout and tour, should have been selected in WHERE
        if (checkShowStatus($result,$i) && (mysql_result($result,$i,"number") !="")) {  // only approved-status non-null tours
            echo "<tr class=\"et-layout-tr-tour\">\n";
            echo "  <td colspan=\"2\" class=\"et-layout-td1\">\n";
            echo "    <span class=\"et-layout-tourNumber\">\n";
            echo "      <a href=\"".$url.mysql_result($result,$i,"number")."\">";
            echo "      ".mysql_result($result,$i,"number");
            echo "    </a></span>\n";
            echo "    <span class=\"et-layout-tourShortName\">".htmlspecialchars(mysql_result($result,$i,"name"))."</span> \n";
            
            $date = daytime_from_long_format(mysql_result($result,$i,"start_date"))
                        ." - ".
                    time_from_long_format(mysql_result($result,$i,"end_date"));
            
            echo "  </td>\n";
            echo "  <td colspan=\"3\" class=\"et-layout-td2\">\n";
            echo "    <span class=\"et-layout-tourDateTime\">".$date."</span>\n";
            echo "  </td>\n";
            echo "</tr>\n";
        }
        $i++;
    }
    
    echo "</table>\n";
    
    // done, clean up
    
    mysql_close();    

}


// 
// Listing of operating layouts
//
function format_all_ops_as_table($url=NONE, $where=NONE, $order=NONE) {
    global $opts, $event_tools_db_prefix, $event_tools_href_add_on;
    global $event_tools_show_min_value;

    mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
    @mysql_select_db($opts['db']) or die( "Unable to select database");
    
    if ($order==NONE) $order = "layout_owner_lastname";
    if ($where==NONE) $where =" WHERE l1.ops_layout_id != 0 OR l2.ops_layout_id2 != 0";
    
    $query="
        SELECT  ".$event_tools_db_prefix."eventtools_layouts . *

        FROM (
            ".$event_tools_db_prefix."eventtools_layouts LEFT JOIN ".$event_tools_db_prefix."eventtools_opsession l1
            ON ".$event_tools_db_prefix."eventtools_layouts.layout_id = l1.ops_layout_id
            )
            LEFT JOIN ".$event_tools_db_prefix."eventtools_opsession l2
            ON ".$event_tools_db_prefix."eventtools_layouts.layout_id = l2.ops_layout_id2
        ".$where."
        GROUP BY layout_id
        ORDER BY ".$order."
        ;
    ";
    $result=mysql_query($query);
    
    $i=0;
    $num=mysql_numrows($result);
    //echo "num: ".$num."<br/>\n";
    if ($num == 0) {
        mysql_close();
        return;
    }
    $alt = 1;
    
    // start a new table
    echo "\n";
    echo "<table border=\"1\" class=\"et-ops-table\">\n";
    
    echo '<tr>
        <th>Host</th>
        <th>Railroad</th>
        <th>Size</th>
        <th>Scenery</th>
        <th>Fidelity to Prototype</th>
        <th>Rigor</th>
        <th>Documentation</th>
        <th>Pace of Session</th>
        <th>Car Forwarding</th>
        <th>Tone</th>
        <th>Dispatch Method</th>
        <th>Control System</th>
        <th>Communications</th>
        <th>Photos</th>
        </tr>'."\n";
        
    while ($i < $num) {
   
        if ($alt > 0 ) {
            echo "<tr>\n";
        } else {
            echo '<tr class="altrow">'."\n";
        }
        $alt = -$alt;
        
        // Owner
        echo "  <td class=\"et-ops-td01\">\n";
        echo "    <span class=\"et-ops-host\">\n";
        echo "      <a href=\"".$url.mysql_result($result,$i,"layout_id")."\">\n";
        echo "     ".htmlspecialchars(mysql_result($result,$i,"layout_owner_lastname"));
        echo "      </a></span> \n";
        echo "  </td>\n";
    
        // Name
        echo "  <td class=\"et-ops-td02\">\n";
        echo "    <span class=\"et-ops-name\">\n";
        echo "      <a href=\"".$url.mysql_result($result,$i,"layout_id")."\">\n";
        echo "     ".htmlspecialchars(mysql_result($result,$i,"layout_name"));
        echo "      </a></span> \n";
        echo "  </td>\n";
    
    
        // Size
        echo "  <td class=\"et-ops-td03\">\n";
        echo "    <span class=\"et-ops-size\">\n";
        echo "        ".htmlspecialchars(mysql_result($result,$i,"layout_size"));
        echo "      </span> \n";
        echo "  </td>\n";
    
        // Scenery
        echo "  <td class=\"et-ops-td04\">\n";
        echo "    <span class=\"et-ops-scenery\">\n";
        echo "        ".htmlspecialchars(mysql_result($result,$i,"layout_scenery"));
        echo "      </span> \n";
        echo "  </td>\n";

        // Fidelity
        echo "  <td class=\"et-ops-td05\">\n";
        echo "    <span class=\"et-ops-fidelity\">\n";
        echo "        ".htmlspecialchars(mysql_result($result,$i,"layout_fidelity"));
        echo "      </span> \n";
        echo "  </td>\n";
        
        // Rigor
        echo "  <td class=\"et-ops-td06\">\n";
        echo "    <span class=\"et-ops-rigor\">\n";
        echo "        ".htmlspecialchars(mysql_result($result,$i,"layout_rigor"));
        echo "      </span> \n";
        echo "  </td>\n";
        
        // Documentation
        echo "  <td class=\"et-ops-td07\">\n";
        echo "    <span class=\"et-ops-documentation\">\n";
        echo "        ".htmlspecialchars(mysql_result($result,$i,"layout_documentation"));
        echo "      </span> \n";
        echo "  </td>\n";
        
        // Pace of session
        echo "  <td class=\"et-ops-td08\">\n";
        echo "    <span class=\"et-ops-pace\">\n";
        echo "        ".htmlspecialchars(mysql_result($result,$i,"layout_session_pace"));
        echo "      </span> \n";
        echo "  </td>\n";
        
        // Car forwarding
        echo "  <td class=\"et-ops-td09\">\n";
        echo "    <span class=\"et-ops-forwarding\">\n";
        echo "        ".htmlspecialchars(mysql_result($result,$i,"layout_car_forwarding"));
        echo "      </span> \n";
        echo "  </td>\n";
        
        // Tone
        echo "  <td class=\"et-ops-td10\">\n";
        echo "    <span class=\"et-ops-tone\">\n";
        echo "        ".htmlspecialchars(mysql_result($result,$i,"layout_tone"));
        echo "      </span> \n";
        echo "  </td>\n";
        
        // Dispatch
        echo "  <td class=\"et-ops-td11\">\n";
        echo "    <span class=\"et-ops-dispatch\">\n";
        echo "        ".htmlspecialchars(mysql_result($result,$i,"layout_dispatched_by1"));
        if (! (mysql_result($result,$i,"layout_dispatched_by2") == "N/A" || mysql_result($result,$i,"layout_dispatched_by2") == "Unknown") )
            echo ' '.mysql_result($result,$i,"layout_dispatched_by2");
        echo "      </span> \n";
        echo "  </td>\n";
        
        // Controls
        echo "  <td class=\"et-ops-td12\">\n";
        echo "    <span class=\"et-ops-controls\">\n";
        echo "        ".htmlspecialchars(mysql_result($result,$i,"layout_control"));
        echo "</span> \n";
        echo "  </td>\n";
        
        // Communications
        echo "  <td class=\"et-ops-td13\">\n";
        echo "    <span class=\"et-ops-comms\">\n";
        echo "        ".htmlspecialchars(mysql_result($result,$i,"layout_communications"));
        echo "      </span> \n";
        echo "  </td>\n";

        // Photos
        echo "  <td class=\"et-ops-td14\">\n";
        echo "    <span class=\"et-ops-photos\">\n";
        if (mysql_result($result,$i,"layout_photo_url") != '')
            echo '<a href="'.htmlspecialchars(mysql_result($result,$i,"layout_photo_url")).'">Photos</a>';
        echo "      </span> \n";
        echo "  </td>\n";

        echo "</tr>";

        $i++;
    }
    
    echo "</table>\n";
    
    // done, clean up
    
    mysql_close();    

}

// 
// Listing of operating layout addresses
//
function format_all_ops_addresses($url=NONE, $where=NONE, $order=NONE) {
    global $opts, $event_tools_db_prefix, $event_tools_href_add_on;
    global $event_tools_show_min_value;

    mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
    @mysql_select_db($opts['db']) or die( "Unable to select database");
    
    if ($order==NONE) $order = "layout_owner_lastname";
    if ($where==NONE) $where =" WHERE l1.ops_layout_id != 0 OR l2.ops_layout_id2 != 0";
    
    $query="
        SELECT  ".$event_tools_db_prefix."eventtools_layouts . *

        FROM (
            ".$event_tools_db_prefix."eventtools_layouts LEFT JOIN ".$event_tools_db_prefix."eventtools_opsession l1
            ON ".$event_tools_db_prefix."eventtools_layouts.layout_id = l1.ops_layout_id
            )
            LEFT JOIN ".$event_tools_db_prefix."eventtools_opsession l2
            ON ".$event_tools_db_prefix."eventtools_layouts.layout_id = l2.ops_layout_id2
        ".$where."
        GROUP BY layout_id
        ORDER BY ".$order."
        ;
    ";
    $result=mysql_query($query);
    
    $i=0;
    $num=mysql_numrows($result);
    //echo "num: ".$num."<br/>\n";
    if ($num == 0) {
        mysql_close();
        return;
    }
    $alt = 1;
    
    // start a new table
    echo "\n";
    echo "<table border=\"1\" class=\"et-ops-table\">\n";
    
    echo '<tr>
        <th>Host</th>
        <th>Railroad</th>
        <th>Address</th>
        <th>Phone</th>
        </tr>'."\n";
        
    while ($i < $num) {
   
        if ($alt > 0 ) {
            echo "<tr>\n";
        } else {
            echo '<tr class="altrow">'."\n";
        }
        $alt = -$alt;
        
        // Owner
        echo "  <td class=\"et-ops-td01\">\n";
        echo "    <span class=\"et-ops-host\">\n";
        echo "      <a href=\"".$url.mysql_result($result,$i,"layout_id")."\">\n";
        echo "     ".htmlspecialchars(mysql_result($result,$i,"layout_owner_lastname"));
        echo "      </a></span> \n";
        echo "  </td>\n";
    
        // Name
        echo "  <td class=\"et-ops-td02\">\n";
        echo "    <span class=\"et-ops-name\">\n";
        echo "      <a href=\"".$url.mysql_result($result,$i,"layout_id")."\">\n";
        echo "     ".htmlspecialchars(mysql_result($result,$i,"layout_name"));
        echo "      </a></span> \n";
        echo "  </td>\n";
    
    
        // Address
        echo "  <td class=\"et-ops-td03\">\n";
        echo "    <span class=\"et-ops-address\">\n";
        echo "        ".htmlspecialchars(mysql_result($result,$i,"layout_street_address"));
        echo ", ".htmlspecialchars(mysql_result($result,$i,"layout_city"));
        echo "      </span> \n";
        echo "  </td>\n";
    
        // City
        echo "  <td class=\"et-ops-td04\">\n";
        echo "    <span class=\"et-ops-city\">\n";
        echo "        ".htmlspecialchars(mysql_result($result,$i,"layout_owner_phone"));
        echo "      </span> \n";
        echo "  </td>\n";
        

        echo "</tr>";

        $i++;
    }
    
    echo "</table>\n";
    
    // done, clean up
    
    mysql_close();    

}

// 
// Listing of operating sessions
//
function format_all_ops_by_day($url=NONE, $where=NONE, $order=NONE, $start_date_limit=NONE, $link_field=NONE) {
    // layout_local_url and layout_photo_url were
    // added to the $event_tools_db_prefix."eventtools_opsession_name
    // as layout_local_url1, layout_local_url2 and layout_photo_url1, layout_photo_url2
    // so they could be selected in $link_field.  Use e.g. $link_field="layout_local_url"
    
    global $opts, $event_tools_db_prefix, $event_tools_href_add_on;
    global $event_tools_show_min_value;

    mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
    @mysql_select_db($opts['db']) or die( "Unable to select database");
    
    if ($order==NONE) $order = " layout_owner_lastname1, layout_owner_lastname2, layout_name1, layout_name2, start_date ";
    else $order = " layout_owner_lastname1, layout_owner_lastname2, layout_name1, layout_name2, start_date, ".$order;
        
    if ($where==NONE) $where = " ";
    
    $query="
        SELECT  *

        FROM ".$event_tools_db_prefix."eventtools_opsession_name
        ".$where."
        ORDER BY ".$order."
        
        ;
    ";
    //echo $query;
    
    $result=mysql_query($query);
    
    $i=0;
    $num=mysql_numrows($result);
    //echo "num: ".$num."<br/>\n";
    if ($num == 0) {
        echo "No matching operating session definitions found. <p>\n";
    }
    $alt = 1;
    
    // start a new table
    echo "\n";
    echo "<table border=\"1\" class=\"et-faobd-table\">\n";
    
    // generate table headings from first, last date
    $first_string =  "2200-01-01 00:00:00";
    $last_string =  "1999-01-01 00:00:00";
    // default is nothing before this month, specify argument if you want to see the past
    if ($start_date_limit == NONE) {
        $now = new DateTime();
        $start_date_limit = $now->format("Y-m")."-01 00:00:00";
        //echo '['.$start_date_limit.']';
    }
    for ($j=0; $j<$num; $j++) {
        if ((mysql_result($result,$j,"start_date") < $first_string) &&  (mysql_result($result,$j,"start_date") > $start_date_limit) ) {
                    $first_string = mysql_result($result,$j,"start_date");
        }
        if (mysql_result($result,$j,"start_date") > $last_string) $last_string = mysql_result($result,$j,"start_date");
    }
    //echo 'dates: '.$first_string.' '.$last_string.'<p>';
    // following assumes that event doesn't cross end of year
    $first_date = DateTime::createFromFormat('Y-m-d H:i:s', $first_string);
    $last_date = DateTime::createFromFormat('Y-m-d H:i:s', $last_string);
    $days = (intval($last_date->format('z'))-intval($first_date->format('z')));
    //echo 'days: '.$days.'<p>';
    if ($days < 1) {
       echo "Some session dates are probably wrong, found ".$first_string." through ".$last_string."; is status parameter right?<p>";
    }    
    if ($days > 10) {
       $days = 10;  // limit to width; more probably due to bad dates
       echo "Some session dates are probably wrong, found ".$first_string." through ".$last_string."; is status parameter right?<p>";
    }
    $headings = array(); // like [Wed 03, Thu 04]
    $dates = array();    // like [2008-01-03, 2008-01-04]
    
    $day = $first_date;
    for ($j=0; $j<=$days; $j++) {
        $headings[] = $day->format('D').'<br>'.$day->format('Y-m-d');
        $dates[] = $day->format('Y-m-d');
        $day->add(new DateInterval('P1D'));
    }    
        
    $total_seats = array(count($dates));

    // columns
    echo '
        <col class="et-faobd-col-1"/>
        <col class="et-faobd-col-2"/>
        <col class="et-faobd-col-3"/>
        <col class="et-faobd-col-4"/>
        <colgroup class="et-faobd-colgroup-days">';
        
    for ($i = 0; $i < count($headings); $i++) {
        echo '  
            <col class="et-faobd-col-'.($i+5).'"/>';
    }
    echo '
        </colgroup>';

    // headings
    echo "\n".'<thead class="et-faobd-thread">
    <tr class="et-faobd-th">
        <th class="et-faobd-th-1">Host</th>
        <th class="et-faobd-th-2">Railroad</th>
        <th class="et-faobd-th-3">Distance/Time</th>
        <th class="et-faobd-th-4">Crew</th>';
        
    for ($i = 0; $i < count($headings); $i++) {
        echo '
        <th class="et-faobd-col-day-'.$i.'">'.$headings[$i].'</th>';
    }
    echo '
    </tr>'."\n</thead>\n\n<tbody class=\"et-faobd-tbody\">\n\n";
    
    $i = 0;
    while ($i < $num) {
   
        // skip blank
        if (mysql_result($result,$i,"layout_name1") == '') { $i++; continue;}
        
        if ($alt > 0 ) {
            echo "\n<tr>\n";
        } else {
            echo "\n".'<tr class="altrow">'."\n";
        }
        $alt = -$alt;
        
        // compute destination page for link
        $link_url1 = $url.mysql_result($result,$i,"layout_id1");
        $link_url2 = $url.mysql_result($result,$i,"layout_id2");
        if ($link_field != NONE) {
            $link_url1 = mysql_result($result,$i,$link_field."1");
            $link_url2 = mysql_result($result,$i,$link_field."2");
        }

        // Owner
        echo "  <td class=\"et-faobd-td-1\">\n";
        echo "    <span class=\"et-faobd-layout-owner\">\n";
        echo '      <a href="'.$link_url1.'">'.
                        mysql_result($result,$i,"layout_owner_lastname1")."</a>\n";
        if (mysql_result($result,$i,"layout_owner_lastname2")!='') {
            echo "    /  <a href=\"".$link_url2."\">".
                        mysql_result($result,$i,"layout_owner_lastname2")."</a>\n";
        }
        echo "    </span>\n";
        echo "  </td>\n";

        // Name
        echo "  <td class=\"et-faobd-td-2\">\n";
        echo "    <span class=\"et-faobd-name\">\n";
        echo "      <a href=\"".$link_url1."\">".
                                htmlspecialchars(mysql_result($result,$i,"layout_name1"))."</a>\n";

        if (mysql_result($result,$i,"layout_name2")!='') {
            echo "     / <a href=\"".$link_url2."\">".
                                htmlspecialchars(mysql_result($result,$i,"layout_name2"))."</a>\n";
        }
        echo "      </span> \n";
        echo "  </td>\n";
    
        // distance
        echo "  <td class=\"et-faobd-td-3\">\n";
        echo "    <span class=\"et-faobd-distance\"> ";
        if (mysql_result($result,$i,"distance")!='' || mysql_result($result,$i,"travel_time")!='') {
            echo mysql_result($result,$i,"distance");
            if (mysql_result($result,$i,"distance")!='' && mysql_result($result,$i,"travel_time")!='')
                echo '/ ';
            echo mysql_result($result,$i,"travel_time");
        }
        echo "&nbsp;</span></td> \n";
        
        // slots
        echo "  <td class=\"et-faobd-td-4\">\n";
        echo "    <span class=\"et-faobd-spaces\">\n";
        echo "        ".htmlspecialchars(mysql_result($result,$i,"spaces"));
        echo "      </span> \n";
        echo "  </td>\n";

        
        // start processing dates to fill in row
        
        for ($j = 0; $j < count($dates); $j++) {  // loop over dates
            echo "\n".'  <td class="et-faobd-td-'.($j+5).'">';
            // if match up, display and advance
            $full = FALSE;
            $first = TRUE;
            while ($dates[$j] == substr(mysql_result($result,$i,"start_date"),0,10)) {
                $full = TRUE;
                if (! $first) echo '<hr/>';
                $first = FALSE;
                echo "\n".'    <span class="et-faobd-session-status-'.mysql_result($result,$i,"status_code").'">';
                echo "\n".'      <span class="et-faobd-sessions">'.time_from_long_format(mysql_result($result,$i,"start_date")).'</span></span><br/>';
                $total_seats[$j] = $total_seats[$j] + mysql_result($result,$i,"spaces");
                if (($i!=$num-1) 
                        && mysql_result($result,$i,"show_name") == mysql_result($result,$i+1,"show_name")
                        && mysql_result($result,$i,"distance") == mysql_result($result,$i+1,"distance")
                        && mysql_result($result,$i,"travel_time") == mysql_result($result,$i+1,"travel_time")
                        ) {
                    $i++;
                } else {
                    break; // no more sessions for this layout today; go to the next cell
                }
            }
            // if not match up, skip cell
            if (! $full) {
                ; //echo "\n".'    <span class="et-faobd-empty"></span>';
            }

            echo '</td>';
        }
        
        // end processing dates
        echo "</tr>";

        $i++;
    }
    
    echo "\n</tbody>\n\n";
    
    // issues
    //     1) getting the marged cell across the bottom to hold the label/header
    //              how to do something like a varible colspan?
    //
    //     If we put in separate cells, maybe with border removed/modified/optimized
    //              how to we show just one label as columns are displayed/suppressed?
    //
    echo '<tr class="et-faobd-slots-total">
          <th class=".et-faobd-slots-label" align="right" colspan="4">Total Slots Each Day</th>';
    for ($j = 0; $j < count($dates); $j++) {
        echo '<td>'.$total_seats[$j].'</td>';
    }
    echo '</tr>';
    
    echo "</table>\n";
    
    // done, clean up
    
    mysql_close();    

}


// 
// Listing of clinics
//
function format_all_clinics_as_2table($where="", $order="start_date, end_date") {
    global $opts, $event_tools_db_prefix, $event_tools_href_add_on;

    mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
    @mysql_select_db($opts['db']) or die( "Unable to select database");
    
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
    //echo "num: ".$num."<br/>\n";
    if ($num == 0) {
        mysql_close();
        return;
    }

    $lastmajorkey = mysql_result($result,0,"start_date");
    
    echo "<table border=\"1\" class=\"et-clinic\">\n";
    
    while ($i < $num) {
    
        if ($lastmajorkey != mysql_result($result,$i,"start_date")) {
            $lastmajorkey = mysql_result($result,$i,"start_date");
            echo "</table>\n";
            echo "<p/>\n";
            echo "<table border=\"1\" class=\"et-clinic\">\n";
        }
        
        echo "<tr class=\"et-clinic-tr1\">\n";
        echo "  <td class=\"et-clinic-td1\">\n";
        echo "    <a name=\"".mysql_result($result,$i,"number")."\"></a>\n";
        echo "    <span class=\"et-clinic-name\">".mysql_result($result,$i,"name")."</span>\n";
        echo "  </td>\n";
        echo "  <td class=\"et-clinic-td2\">\n";
        echo "    <span class=\"et-clinic-time\">".daydatetime_from_long_format(mysql_result($result,$i,"start_date"))." - ".time_from_long_format(mysql_result($result,$i,"end_date"))."</span>\n";
        echo "  </td>\n";
        echo "</tr>\n";
    
        if (mysql_result($result,$i,"clinic_presenter")!='') {
            $clinic_presenter = mysql_result($result,$i,"clinic_presenter");
        } else {
            $clinic_presenter = "(no presenter name provided)";
        }
        if (mysql_result($result,$i,"description")!='') {
            $description = mysql_result($result,$i,"description");
        } else {
            $description = "(no description provided)";
        }
    
        echo "<tr class=\"et-clinic-tr2\">\n";
        echo "  <td class=\"et-clinic-td1\">\n";
        echo "    <span class=\"et-clinic-presenter\">".$clinic_presenter."</span>\n";
        echo "  </td>\n";
        echo "  <td class=\"et-clinic-td2\">\n";
        echo "    <span class=\"et-clinic-location\">".mysql_result($result,$i,"location_name")."</span>\n";
        echo "  </td>\n";
        echo "</tr>\n";
        
        echo "<tr class=\"et-clinic-tr3\">\n";
        echo "  <td colspan=\"2\" class=\"et-clinic-td1\">\n";
        echo "    <div class=\"et-clinic-description\">".$description."</div>\n";
        echo "  </td>\n";
        echo "</tr>\n";
        
        echo "<tr class=\"et-clinic-tr4\">\n";
        echo "  <td colspan=\"2\" class=\"et-clinic-td1\">\n";
        if (mysql_result($result,$i,"clinic_url")!='') {
            echo "    <span class=\"et-clinic-url\"><a ".$event_tools_href_add_on." href=\"".mysql_result($result,$i,"clinic_url")."\">".mysql_result($result,$i,"clinic_url")."</a></span>\n";
        }
        echo "  </td>\n";
        echo "</tr>\n";
        
        // process tags
        echo "<tr class=\"et-clinic-tr5\">\n";
        echo "  <td colspan=\"2\" class=\"et-clinic-td1\"><span class=\"et-clinic-tags\">\n";
        echo mysql_result($result,$i,"tag_name");
        while ( ($i < $num-1) && 
                    (mysql_result($result,$i,"name") == mysql_result($result,$i+1,"name"))
               ) {
            $i++;
            echo ",\n".mysql_result($result,$i,"tag_name");
        }
        echo "\n  </span></td>\n";
        echo "</tr>\n";
        
        // spare line at end
        echo "<tr class=\"et-clinic-tr6\">\n"; // blank line
        echo "  <td colspan=\"2\" class=\"et-clinic-td1\">\n";
        echo "  </td>\n";
        echo "</tr>\n";
        
        $i++;
    }
    
    // done, clean up
    
    echo "</table>\n";
    
    mysql_close();    
}

function format_all_clinics_as_3table($where="", $order="start_date, end_date") {
    global $opts, $event_tools_db_prefix, $event_tools_href_add_on;

    mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
    @mysql_select_db($opts['db']) or die( "Unable to select database");
    
//     if ($where != NONE) {
//         $where = " WHERE `status_code` < 80 AND ".$where;
//     } else {
//         $where = " WHERE `status_code` < 80 ";
//     }
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
    //echo "num: ".$num."<br/>\n";
    if ($num == 0) {
        mysql_close();
        return;
    }
    
    $lastmajorkey = mysql_result($result,0,"id");
    
    echo "<table border=\"1\" class=\"et-clinic\">\n";
    
    while ($i < $num) {
    
        if ($lastmajorkey != mysql_result($result,$i,"id")) {
            $lastmajorkey = mysql_result($result,$i,"id");
            echo "</table>\n";
            echo "<p/>\n";
            echo "<table border=\"1\" class=\"et-clinic\">\n";
        }
        
        echo "<tr class=\"et-clinic-tr1\">\n";
        echo "  <td class=\"et-clinic-td1\">\n";
        echo "    <a name=\"".mysql_result($result,$i,"number")."\"></a>\n";
        echo "    <span class=\"et-clinic-name\">".mysql_result($result,$i,"name")."</span>\n";
        echo "  </td>\n";
        echo "  <td class=\"et-clinic-td2\">\n";
        echo "    <span class=\"et-clinic-date\">".daydate_from_long_format(mysql_result($result,$i,"start_date"))."</span>\n";
        echo "  </td>\n";
        echo "  <td class=\"et-clinic-td3\">\n";
        echo "    <span class=\"et-clinic-times\">".time_from_long_format(mysql_result($result,$i,"start_date"))." - ".time_from_long_format(mysql_result($result,$i,"end_date"))."</span>\n";
        echo "  </td>\n";
        echo "</tr>\n";
    
        if (mysql_result($result,$i,"clinic_presenter")!='' || !$event_tools_replace_on_data_warn) {
            $clinic_presenter = mysql_result($result,$i,"clinic_presenter");
        } else {
            $clinic_presenter = "(no presenter name provided)";
        }
        if (mysql_result($result,$i,"description")!='' || !$event_tools_replace_on_data_warn) {
            $description = mysql_result($result,$i,"description");
        } else {
            $description = "(no description provided)";
        }
    
        echo "<tr class=\"et-clinic-tr2\">\n";
        echo "  <td colspan=\"2\" class=\"et-clinic-td1\">\n";
        echo "    <span class=\"et-clinic-presenter\">".$clinic_presenter."</span>\n";
        echo "  </td>\n";
        echo "  <td class=\"et-clinic-td2\">\n";
        echo "    <span class=\"et-clinic-location\">".mysql_result($result,$i,"location_name")."</span>\n";
        echo "  </td>\n";
        echo "</tr>\n";
        
        echo "<tr class=\"et-clinic-tr3\">\n";
        echo "  <td colspan=\"3\" class=\"et-clinic-td1\">\n";
        echo "    <div class=\"et-clinic-description\">".$description."</div>\n";
        echo "  </td>\n";
        echo "</tr>\n";
        
        // URL line if present
        if (mysql_result($result,$i,"clinic_url") != '') { 
            echo "<tr class=\"et-clinic-tr4\">\n";
            echo "  <td colspan=\"3\" class=\"et-clinic-td1\">\n";
            if (mysql_result($result,$i,"clinic_url")!='') {
                echo "    <span class=\"et-clinic-url\"><a  ".$event_tools_href_add_on." href=\"".mysql_result($result,$i,"clinic_url")."\">".mysql_result($result,$i,"clinic_url")."</a></span>\n";
            }
            echo "  </td>\n";
            echo "</tr>\n";
        }
        
        // process tags if present
        if (mysql_result($result,$i,"tag_name") != '' ) {
            echo "<tr class=\"et-clinic-tr5\">\n";
            echo "  <td colspan=\"3\" class=\"et-clinic-td1\"><span class=\"et-clinic-tags\">\n";
            echo mysql_result($result,$i,"tag_name");
            while ( ($i < $num-1) && 
                        (mysql_result($result,$i,"id") == mysql_result($result,$i+1,"id"))
                   ) {
                $i++;
                echo ",\n".htmlspecialchars(mysql_result($result,$i,"tag_name"));
            }
            echo "\n  </span></td>\n";
            echo "</tr>\n";
        }
                
        $i++;
    }
    
    // done, clean up
    
    echo "</table>\n";
    
    mysql_close();    
}

function format_all_clinics_as_table_ip($where="", $order="start_date, end_date, name") {
    global $opts, $event_tools_db_prefix, $event_tools_href_add_on;

    mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
    @mysql_select_db($opts['db']) or die( "Unable to select database");
    
    if ($where != NONE) {
        $where = " WHERE ".$where;
    }
    
    if ($order == NONE) {
        $order = "  start_date, end_date, name ";
    }
    
    $query="
        SELECT *
        FROM ".$event_tools_db_prefix."eventtools_clinics_with_tags
        ".$where."
        ORDER BY ".$order."
        ;
    ";
    //echo $query;
    $result=mysql_query($query);


    $i = 0;
    $num = mysql_numrows($result);
    //echo "num: ".$num."<br/>\n";
    if ($num == 0) {
        mysql_close();
        return;
    }
    
    $lastmajorkey = mysql_result($result,0,"id");
    
    echo "<table class=\"et-clinic\">\n";
    
    while ($i < $num) {
    
        if ($lastmajorkey != mysql_result($result,$i,"id")) {
            $lastmajorkey = mysql_result($result,$i,"id");
            echo "</table>\n";
            echo "<p/>\n";
            echo "<table class=\"et-clinic\">\n";
        }
        


        if (mysql_result($result,$i,"clinic_presenter")!='' || !$event_tools_replace_on_data_warn) {
            $clinic_presenter = mysql_result($result,$i,"clinic_presenter");
        } else {
            $clinic_presenter = "(no presenter name provided)";
        }
        echo "<tr class=\"et-clinic-tr1\">\n";
        echo "  <td colspan=\"2\" class=\"et-clinic-td1\">\n";
        echo "    <span class=\"et-clinic-presenter\">".$clinic_presenter."</span>\n";
        echo "  </td>\n";
        echo "  <td class=\"et-clinic-td2\">\n";
        echo "  </td>\n";
        echo "</tr>\n";

        echo "<tr class=\"et-clinic-tr2\">\n";
        echo "  <td colspan=\"2\" class=\"et-clinic-td1\">\n";
        echo "    <span class=\"et-clinic-name\">".mysql_result($result,$i,"name")."</span>\n";
        echo "  </td>\n";
        echo "</tr>\n";


        echo "<tr class=\"et-clinic-tr3\">\n";
        echo "  <td class=\"et-clinic-td1\">\n";
        echo "    <span class=\"et-clinic-date\">".daydate_from_long_format(mysql_result($result,$i,"start_date"))."</span>\n";
        echo "    <span class=\"et-clinic-times\">".time_from_long_format(mysql_result($result,$i,"start_date"))." - ".time_from_long_format(mysql_result($result,$i,"end_date"))."</span>\n";
        echo "  </td>\n";
        echo "  <td class=\"et-clinic-td2\">\n";
        echo "    <span class=\"et-clinic-location\">".mysql_result($result,$i,"location_name")."</span>\n";
        echo "  </td>\n";
        echo "</tr>\n";
    
        if (mysql_result($result,$i,"description")!='' || !$event_tools_replace_on_data_warn) {
            $description = mysql_result($result,$i,"description");
        } else {
            $description = "(no description provided)";
        }
            
        echo "<tr class=\"et-clinic-tr4\">\n";
        echo "  <td colspan=\"2\" class=\"et-clinic-td1\">\n";
        echo "    <div class=\"et-clinic-description\">".$description."</div>\n";
        echo "  </td>\n";
        echo "</tr>\n";
        
        // URL line if present
        if (mysql_result($result,$i,"clinic_url") != '') { 
            echo "<tr class=\"et-clinic-tr4\">\n";
            echo "  <td colspan=\"3\" class=\"et-clinic-td1\">\n";
            if (mysql_result($result,$i,"clinic_url")!='') {
                echo "    <span class=\"et-clinic-url\"><a  ".$event_tools_href_add_on." href=\"".mysql_result($result,$i,"clinic_url")."\">".mysql_result($result,$i,"clinic_url")."</a></span>\n";
            }
            echo "  </td>\n";
            echo "</tr>\n";
        }
        
        // process tags if present
        if (mysql_result($result,$i,"tag_name") != '' ) {
            echo "<tr class=\"et-clinic-tr5\">\n";
            echo "  <td colspan=\"3\" class=\"et-clinic-td1\"><span class=\"et-clinic-tags\">\n";
            echo mysql_result($result,$i,"tag_name");
            while ( ($i < $num-1) && 
                        (mysql_result($result,$i,"id") == mysql_result($result,$i+1,"id"))
                   ) {
                $i++;
                echo ",\n".htmlspecialchars(mysql_result($result,$i,"tag_name"));
            }
            echo "\n  </span></td>\n";
            echo "</tr>\n";
        }
                
        $i++;
    }
    
    // done, clean up
    
    echo "</table>\n";
    
    mysql_close();    
}

function format_all_misc_events_as_3table($where="", $order="start_date, end_date") {
    global $opts, $event_tools_db_prefix, $event_tools_href_add_on;

    mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
    @mysql_select_db($opts['db']) or die( "Unable to select database");
    
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
    //echo "num: ".$num."<br/>\n";
    if ($num == 0) {
        mysql_close();
        return;
    }

    $lastmajorkey = mysql_result($result,0,"start_date");
    
    echo "<table border=\"1\" class=\"et-misc\">\n";
    
    while ($i < $num) {
    
        if ($lastmajorkey != mysql_result($result,$i,"start_date")) {
            $lastmajorkey = mysql_result($result,$i,"start_date");
            echo "</table>\n";
            echo "<p/>\n";
            echo "<table border=\"1\" class=\"et-misc\">\n";
        }
        
        echo "<tr class=\"et-misc-tr1\">\n";
        echo "  <td class=\"et-misc-td1\">\n";
        echo "    <a name=\"".mysql_result($result,$i,"number")."\"></a>\n";
        echo "    <span class=\"et-misc-name\">".htmlspecialchars(mysql_result($result,$i,"name"))."</span>\n";
        echo "  </td>\n";
        echo "  <td class=\"et-misc-td2\">\n";
        echo "    <span class=\"et-misc-date\">".daydate_from_long_format(mysql_result($result,$i,"start_date"))."</span>\n";
        echo "  </td>\n";
        echo "  <td class=\"et-misc-td3\">\n";
        echo "    <span class=\"et-misc-times\">".time_from_long_format(mysql_result($result,$i,"start_date"))." - ".time_from_long_format(mysql_result($result,$i,"end_date"))."</span>\n";
        echo "  </td>\n";
        echo "</tr>\n";
    
        if (mysql_result($result,$i,"description")!='') {
            $description = mysql_result($result,$i,"description");
        } else {
            $description = "(no description provided)";
        }
    
        echo "<tr class=\"et-misc-tr2\">\n";
        echo "  <td colspan=\"2\" class=\"et-misc-td1\">\n";
        echo "  </td>\n";
        echo "  <td class=\"et-misc-td2\">\n";
        echo "    <span class=\"et-misc-location\">".htmlspecialchars(mysql_result($result,$i,"location_name"))."</span>\n";
        echo "  </td>\n";
        echo "</tr>\n";
        
        echo "<tr class=\"et-misc-tr3\">\n";
        echo "  <td colspan=\"3\" class=\"et-misc-td1\">\n";
        echo "    <div class=\"et-misc-description\">".$description."</div>\n";
        echo "  </td>\n";
        echo "</tr>\n";
        
        echo "<tr class=\"et-misc-tr4\">\n";
        echo "  <td colspan=\"3\" class=\"et-misc-td1\">\n";
        if (mysql_result($result,$i,"misc_url")!='') {
            echo "    <span class=\"et-misc-url\"><a ".$event_tools_href_add_on." href=\"".mysql_result($result,$i,"misc_url")."\">".mysql_result($result,$i,"misc_url")."</a></span>\n";
        }
        echo "  </td>\n";
        echo "</tr>\n";
        
        // process tags
        echo "<tr class=\"et-misc-tr5\">\n";
        echo "  <td colspan=\"3\" class=\"et-misc-td1\"><span class=\"et-misc-tags\">\n";
        echo htmlspecialchars(mysql_result($result,$i,"tag_name"));
        while ( ($i < $num-1) && 
                    (mysql_result($result,$i,"name") == mysql_result($result,$i+1,"name")) && 
                    (mysql_result($result,$i,"id") == mysql_result($result,$i+1,"id"))
               ) {
            $i++;
            echo ",\n".htmlspecialchars(mysql_result($result,$i,"tag_name"));
        }
        echo "\n  </span></td>\n";
        echo "</tr>\n";
        
        // spare line at end
        echo "<tr class=\"et-misc-tr6\">\n"; // blank line
        echo "  <td colspan=\"3\" class=\"et-misc-td1\">\n";
        echo "  </td>\n";
        echo "</tr>\n";
        
        $i++;
    }
    
    // done, clean up
    
    echo "</table>\n";
    
    mysql_close();    
}

function format_all_clinics_as_4table($where="", $order="start_date, end_date") {
    global $opts, $event_tools_db_prefix, $event_tools_href_add_on;

    mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
    @mysql_select_db($opts['db']) or die( "Unable to select database");
    
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
    //echo "num: ".$num."<br/>\n";
    if ($num == 0) {
        mysql_close();
        return;
    }

    $lastmajorkey = mysql_result($result,0,"id");
    
    echo "<table border=\"1\" class=\"et-clinic\">\n";
    
    while ($i < $num) {
    
        if ($lastmajorkey != mysql_result($result,$i,"id")) {
            $lastmajorkey = mysql_result($result,$i,"id");
            echo "</table>\n";
            echo "\n";
            echo "<table border=\"1\" class=\"et-clinic\">\n";
        }
        
        echo "<tr class=\"et-clinic-tr1\">\n";
        echo "  <td class=\"et-clinic-td1\">\n";
        echo "    <a name=\"".mysql_result($result,$i,"id")."\"></a>\n";
        echo "    <span class=\"et-clinic-name\">".htmlspecialchars(mysql_result($result,$i,"name"))."</span>\n";
        echo "  </td>\n";
        echo "  <td class=\"et-clinic-td2\">\n";
        echo "    <span class=\"et-clinic-date\">".daydate_from_long_format(mysql_result($result,$i,"start_date"))."</span>\n";
        echo "  </td>\n";
        echo "  <td class=\"et-clinic-td3\">\n";
        echo "    <span class=\"et-clinic-start\">".time_from_long_format(mysql_result($result,$i,"start_date"))."</span>\n";
        echo "  </td>\n";
        echo "  <td class=\"et-clinic-td4\">\n";
        echo "    <span class=\"et-clinic-end\">".time_from_long_format(mysql_result($result,$i,"end_date"))."</span>\n";
        echo "  </td>\n";
        echo "</tr>\n";
    
        if (mysql_result($result,$i,"clinic_presenter")!='') {
            $clinic_presenter = htmlspecialchars(mysql_result($result,$i,"clinic_presenter"));
        } else {
            $clinic_presenter = "(no presenter name provided)";
        }
        if (mysql_result($result,$i,"description")!='') {
            $description = mysql_result($result,$i,"description");
        } else {
            $description = "(no description provided)";
        }
    
        echo "<tr class=\"et-clinic-tr2\">\n";
        echo "  <td colspan=\"3\" class=\"et-clinic-td1\">\n";
        echo "    <span class=\"et-clinic-presenter\">".$clinic_presenter."</span>\n";
        echo "  </td>\n";
        echo "  <td class=\"et-clinic-td2\">\n";
        echo "    <span class=\"et-clinic-location\">".htmlspecialchars(mysql_result($result,$i,"location_name"))."</span>\n";
        echo "  </td>\n";
        echo "</tr>\n";
        
        echo "<tr class=\"et-clinic-tr3\">\n";
        echo "  <td colspan=\"4\" class=\"et-clinic-td1\">\n";
        echo "    <div class=\"et-clinic-description\">".$description."</div>\n";
        echo "  </td>\n";
        echo "</tr>\n";
        
        echo "<tr class=\"et-clinic-tr4\">\n";
        echo "  <td colspan=\"4\" class=\"et-clinic-td1\">\n";
        if (mysql_result($result,$i,"clinic_url")!='') {
            echo "    <span class=\"et-clinic-url\"><a ".$event_tools_href_add_on." href=\"".mysql_result($result,$i,"clinic_url")."\">".mysql_result($result,$i,"clinic_url")."</a></span>\n";
        }
        echo "  </td>\n";
        echo "</tr>\n";
        
        // process tags
        echo "<tr class=\"et-clinic-tr5\">\n";
        echo "  <td colspan=\"4\" class=\"et-clinic-td1\"><span class=\"et-clinic-tags\">\n";
        echo htmlspecialchars(mysql_result($result,$i,"tag_name"));
        while ( ($i < $num-1) && 
                    (mysql_result($result,$i,"id") == mysql_result($result,$i+1,"id"))
               ) {
            $i++;
            echo ",\n".htmlspecialchars(mysql_result($result,$i,"tag_name"));
        }
        echo "\n  </span></td>\n";
        echo "</tr>\n";
        
        // spare line at end
        echo "<tr class=\"et-clinic-tr6\">\n"; // blank line
        echo "  <td colspan=\"4\" class=\"et-clinic-td1\">\n";
        echo "  </td>\n";
        echo "</tr>\n";
        
        $i++;
    }
    
    // done, clean up
    
    echo "</table>\n";
    
    mysql_close();    
}


// -------------------------------------------------------------------------
//
// Formatter classes for (original) 3-column tables
//

abstract class Tours_as_3Table implements Event_Formatter {
    public function format_heading($result,$i,$url=NULL) {
        global $event_tools_replace_on_data_error;
        
        if (mysql_result($result,$i,"description")!='') {
            $description = htmlspecialchars(mysql_result($result,$i,"description"));
        } else {
            $description = "<span class=\"et-warning-missing\">(no description provided)</span>";
        }
    
        echo "<tr class=\"et-lt-tour-tr1\">\n";
        echo "  <td colspan=\"3\" class=\"et-lt-tour-td1\">\n";
        echo "    <span class=\"et-lt-tour-tourNumber\" id=\"tour_".mysql_result($result,$i,"number")."\">\n";
        echo "      <a name=\"tour_".mysql_result($result,$i,"number")."\"></a>\n";
        echo "      ".mysql_result($result,$i,"number");
        echo "    </span>\n";
        echo "    <span class=\"et-lt-tour-tourShortName\">".htmlspecialchars(mysql_result($result,$i,"name"))."</span> \n";
        
        $date = daytime_from_long_format(mysql_result($result,$i,"start_date"))
                    ." - ".
                time_from_long_format(mysql_result($result,$i,"end_date"));
        
        echo "    <span class=\"et-lt-tour-tourDateTime\">".$date."</span>\n";
        echo "  </td>\n";
        echo "</tr>\n";
        echo "<tr class=\"et-lt-tour-tr2\">\n";
        echo "    <td colspan=\"3\" class=\"et-lt-tour-td1\">\n";
        echo "        <div class=\"et-lt-tour-tourComment\">".$description."</div> \n";
        
        if (((float)mysql_result($result,$i,"tour_price")) >=0 ) {
            $cost = "$".mysql_result($result,$i,"tour_price");
        } else {
            if ($event_tools_replace_on_data_error)
                $cost = "<span class=\"et-error-missing\">(no price)</span>";
            else
                $cost = "<span class=\"et-error-missing\">TBA</span>";
        }
        echo "        <span class=\"et-lt-tour-tourCost\">".$cost."</span>\n";
        echo "    </td>\n";
        echo "</tr>\n";
    }

    public function get_major_key($result,$i) {
        return mysql_result($result,$i,"id");
    }

    public function default_where() { return ""; }
}

class Layout_Tours_as_3Table extends Tours_as_3Table {

    public function format_subitem($result,$i,$url=NONE) {
        global $event_tools_href_add_on;
        
        // format the layout information
        if (mysql_result($result,$i,"layout_name")!='') {
            $layout_name = htmlspecialchars(mysql_result($result,$i,"layout_name"));
        } else {
            $layout_name = "<span class=\"et-warning-missing\">(no layout name provided)</span>";
        }
        if (mysql_result($result,$i,"layout_short_description")!='') {
            $layout_short_description = htmlspecialchars(mysql_result($result,$i,"layout_short_description"));
        } else {
            $layout_short_description = "<span class=\"et-warning-missing\">(no layout short description provided)</span>";
        }
        if (mysql_result($result,$i,"layout_long_description")!='') {
            $layout_long_description = mysql_result($result,$i,"layout_long_description");
        } else {
            $layout_long_description = "<span class=\"et-warning-missing\">(no layout long description provided)</span>";
        }
    
        $owner = htmlspecialchars(mysql_result($result,$i,"layout_owner_firstname").' '.mysql_result($result,$i,"layout_owner_lastname"));
        
        echo "<tr class=\"et-lt-layout-tr1\">\n";
        echo "    <td class=\"et-lt-layout-td1\">\n";
        echo "        <span class=\"et-lt-tour-scale\">".htmlspecialchars(mysql_result($result,$i,"layout_scale"))."</span>\n";
        echo "    </td>\n";
        echo "    <td class=\"et-lt-layout-td2\">\n";
        echo "        <span class=\"et-lt-tour-owner\">".$owner."</span>\n";
        echo "    </td>\n";
        echo "    <td class=\"et-lt-layout-td3\">\n";
        echo "        <span class=\"et-lt-tour-layoutName\">".$layout_name."</span>\n";
        echo "    </td>\n";
        echo "</tr>\n";
        echo "<tr class=\"et-lt-layout-tr2\">\n";
        echo "    <td class=\"et-lt-layout-td1\">\n";
        echo "        <span class=\"et-lt-tour-size\">".htmlspecialchars(mysql_result($result,$i,"layout_size"))."</span>\n";
        echo "    </td>\n";
        echo "    <td class=\"et-lt-layout-td2\">\n";
        echo "        <span class=\"et-lt-tour-controls\">".htmlspecialchars(mysql_result($result,$i,"layout_control"))."</span>\n";
        echo "    </td>\n";
        echo "    <td class=\"et-lt-layout-td3\">\n";
        echo "        <span class=\"et-lt-layout-owner_url\">";
        echo "          <a ".$event_tools_href_add_on." href=\"".mysql_result($result,$i,"layout_owner_url")."\">".mysql_result($result,$i,"layout_owner_url")."</a>\n";
        echo "        </span>\n";
        echo "    </td>\n";
        echo "</tr>\n";
        echo "<tr class=\"et-lt-layout-tr3\">\n";
        echo "    <td colspan=\"3\" class=\"et-lt-layout-td1\">\n";
        echo "        <div class=\"et-lt-layout-layoutComments\">".$layout_long_description."</div>\n";
        echo "    </td>\n";
        echo "</tr>\n";
        echo "<tr class=\"et-lt-layout-tr4\"><td colspan=\"3\" class=\"et-lt-layout-endBlank\"></td></tr>\n";
    }

    public function select_statement($where=NONE, $order=NONE) {
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

    public function default_order() { return "number, layout_tour_link_order"; }
}

class General_Tours_as_3Table extends Tours_as_3Table {

    public function format_subitem($result,$i,$url=NONE) {
        // none
    }

    public function select_statement($where=NONE, $order=NONE) {
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
            SELECT ".$event_tools_db_prefix."eventtools_general_tours.*
                FROM ".$event_tools_db_prefix."eventtools_general_tours
                ".$w."
                ORDER BY ".$o."
                ;
            ";
    }

    public function default_order() { return "number"; }
}

// -------------------------------------------------------------------------
//
// Formatter classes for (first update) 8-column tables
//

abstract class Tours_as_8Table implements Event_Formatter {

    public function show_status_or_link($result,$i) {
        global $event_tools_db_prefix, $event_tools_cartlink, $event_tools_lookup_flag, $event_tools_lookup_result;
        if (!$event_tools_lookup_flag) {
            $event_tools_lookup_flag = TRUE;
            
            // load a cache between model/tour_number and product ID
            $select = "
            SELECT products_model, products_id
                FROM ".$event_tools_db_prefix."products
                ;
            ";
    
            // ugly hack: Turn off links (instead of checking Zen DB product status)
            $product_lookup_result=mysql_query($select);
            //while ($row = mysql_fetch_assoc($product_lookup_result)) {
            //    $event_tools_lookup_result[$row['products_model']] = $row['products_id'];
            //}
            
        }
        $product = $event_tools_lookup_result[mysql_result($result,$i,"number")];
        if (mysql_result($result,$i,"event_status_code") == '60' && $product != NULL) {
            return '<a class="status_link" href="'.$event_tools_cartlink.'/index.php?main_page=product_info&products_id='
                .$product.'">'
                .mysql_result($result,$i,"event_status_display")
                .'</a>';
        } else {
            return mysql_result($result,$i,"event_status_display");
        }
    }

    public function format_heading($result,$i,$url=NONE) {
        global $event_tools_replace_on_data_error;

        echo "<tr class=\"et-tour-tr1\">\n";
            echo "<td class=\"et-tour-td1\"><span class=\"et-tour-number\">"
                .errorOnEmpty(htmlspecialchars(mysql_result($result,$i,"number")), "number")
                ."</span></td>\n";
            echo "<td colspan=\"6\" class=\"et-tour-td2-\"><span class=\"et-tour-name\"><a name=\"".mysql_result($result,$i,"number")."\"></a>"
                .errorOnEmpty(htmlspecialchars(mysql_result($result,$i,"name")), "name")
                ."</span></td>\n";
            echo "<td class=\"et-tour-td8-\"><span class=\"et-tour-status\">"
                .$this->show_status_or_link($result,$i)
                ."</span></td>\n";
        echo "</tr>\n";

        echo "<tr class=\"et-tour-tr2\">\n";
            echo "<td class=\"et-tour-td1\"></td>\n";
            echo "<td class=\"et-tour-td2\"><span class=\"et-tour-day\">"
                .day_from_long_format(mysql_result($result,$i,"start_date"))
                ."</span></td>\n";
            echo "<td class=\"et-tour-td3\"><span class=\"et-tour-period\">"
                .tourPeriod(mysql_result($result,$i,"start_date"), mysql_result($result,$i,"end_date"))."</span></td>\n";
            echo "<td class=\"et-tour-td4\"><span class=\"et-tour-date\">"
                .date_from_long_format(mysql_result($result,$i,"start_date"))
                ."</span></td>\n";
            echo "<td class=\"et-tour-td5\"><span class=\"et-tour-spare\">".""."</span></td>\n";
            
            if (mysql_result($result,$i,"tour_price") >= 0 ) { 
                $price = "$".mysql_result($result,$i,"tour_price"); 
            } else { 
                if ($event_tools_replace_on_data_error)
                    $price = "<span class=\"et-error-missing\">(no price)</span>";
                else
                    $price = "<span class=\"et-error-missing\">TBA</span>";
            }
            echo "<td class=\"et-tour-td6\"><span class=\"et-tour-price\">"
                .$price
                ."</span></td>\n";
            
            echo "<td class=\"et-tour-td7\"><span class=\"et-tour-depart\">"
                ."Dep ".time_from_long_format(mysql_result($result,$i,"start_date"))
                ."</span></td>\n";
            
            if ( (substr(mysql_result($result,$i,"start_date"),-8) !='00:00:00') &&
                 (substr(mysql_result($result,$i,"end_date"),-8) =='00:00:00') )
                $r = "";
            else
                $r = "Ret ".time_from_long_format(mysql_result($result,$i,"end_date"));
            echo "<td class=\"et-tour-td8\"><span class=\"et-tour-depart\">"
                .$r
                ."</span></td>\n";
                
        echo "</tr>\n";

        echo "<tr class=\"et-tour-tr3\">\n";
            echo "<td colspan=\"8\" class=\"et-tour-td1\"><div class=\"et-tour-desc\">"
                .errorOnEmpty(mysql_result($result,$i,"description"), "description")
                ."</div></td>\n";
        echo "</tr>\n";


    }

    public function get_major_key($result,$i) {
        return mysql_result($result,$i,"id");
    }

    public function default_where() { return ""; }
}

class Layout_Tours_as_8Table extends Tours_as_8Table {

    public function format_subitem($result,$i,$url=NONE) {
        global $event_tools_href_add_on;
        
        // layout on layout tour
        // line 2 - scale, owner name, layout name (links)
        echo "<tr class=\"et-tour-layout-tr2\">\n";
            echo "<td class=\"et-tour-td1\"><span class=\"et-tour-layout-scale\">"
                .errorOnEmpty(htmlspecialchars(mysql_result($result,$i,"layout_scale")), "scale")
                ."</span></td>\n";
            echo "<td colspan=\"3\" class=\"et-tour-td2\"><span class=\"et-tour-layout-owner\">"
                ."<a href=\"".$url.mysql_result($result,$i,"layout_id")."\">"
                .htmlspecialchars(mysql_result($result,$i,"layout_owner_firstname"))." ".htmlspecialchars(mysql_result($result,$i,"layout_owner_lastname"))
                ."</a></span></td>\n";
            echo "<td colspan=\"4\" class=\"et-tour-td3\"><span class=\"et-tour-layout-name\">"
                ."<a href=\"".$url.mysql_result($result,$i,"layout_id")."\">"
                .warnOnEmpty(htmlspecialchars(mysql_result($result,$i,"layout_name")), "layout name")
                ."</a></span></td>\n";
        echo "</tr>\n";
    
    
        // line 3 - size, scenery, control, access, url
        echo "<tr class=\"et-tour-layout-tr3\">\n";
            echo "<td class=\"et-tour-td1\"><span class=\"et-tour-layout-size\">"
                .warnOnEmpty(htmlspecialchars(mysql_result($result,$i,"layout_size")), "size")
                ."</span></td>\n";
            $scenery = warnOnEmpty(htmlspecialchars(mysql_result($result,$i,"layout_scenery")), "scenery");
            if (mysql_result($result,$i,"layout_scenery") !='') $scenery = 'Scenery: '.$scenery;
            echo "<td class=\"et-tour-td2\"><span class=\"et-tour-layout-scenery\">"
                .$scenery
                ."</span></td>\n";

            echo "<td class=\"et-tour-td3\"><span class=\"et-tour-layout-control\">"
                .warnOnEmpty(htmlspecialchars(mysql_result($result,$i,"layout_control")), "control")
                ."</span></td>\n";
            echo "<td class=\"et-tour-td4\"><span class=\"et-tour-layout-access\">"
                .warnOnEmpty(mysql_result($result,$i,"accessibility_display"), "accessibility")
                ."</span></td>\n";

            echo "<td colspan=\"4\" class=\"et-tour-td4\"><span class=\"et-tour-owner-url\">\n";
            echo "    <a ".$event_tools_href_add_on." href=\"".mysql_result($result,$i,"layout_owner_url")."\">".mysql_result($result,$i,"layout_owner_url")."</a>\n";
            echo "</span></td>\n";

        echo "</tr>\n";
        
        // not used:  prototype, era, main length, plan type, ops scheme
        
        // line 4 - description, preferring short
        echo "<tr class=\"et-tour-layout-tr4\">\n";
            echo "<td colspan=\"8\" class=\"et-tour-td1\"><span class=\"et-tour-layout-desc\">";
            if (mysql_result($result,$i,"layout_short_description") == '')
                echo errorOnEmpty(mysql_result($result,$i,"layout_long_description"), "no short or long description");
            else
                echo mysql_result($result,$i,"layout_short_description");
            echo "</span></td>\n";
        echo "</tr>\n";
    }

    public function select_statement($where=NONE, $order=NONE) {
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

    public function default_order() { return " SUBSTRING(`number`,-4), number, start_date, layout_tour_link_order "; }
}

class General_Tours_as_8Table extends Tours_as_8Table {

    public function format_subitem($result,$i,$url=NONE) {
        // none
    }

    public function select_statement($where=NONE, $order=NONE) {
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

    public function default_order() { return "number"; }
}


?>
