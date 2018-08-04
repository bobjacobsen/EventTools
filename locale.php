<?php

// -------------------------------------------------------------------------
// Part of EventTools, a package for managing model railroad meeting information
//
// By Bob Jacobsen, jacobsen@mac.com, Copyright 2010, 2011
// -------------------------------------------------------------------------

// -------------------------------------------------------------------------

// By-location Formatting interface and classes

function format_clinic_cell($result,$i,$width,$num,$url) {
    $name = htmlspecialchars(mysql_result($result,$i,"name"));
    echo "  <td colspan=\"".$width."\" class=\"et-clbl-td-clinic\"><table class=\"et-clbl-sub-table\">\n";
    echo "     <tr class=\"et-clbl-sub-tr1\"><td class=\"et-clbl-sub-td1\"><span class=\"et-clbl-sub-name\">";
    echo "      <a href=\"".$url.mysql_result($result,$i,"number")."\">".$name."</a>\n";                 
    echo "      </span></td></tr>\n";
    echo "     <tr class=\"et-clbl-sub-tr2\"><td class=\"et-clbl-sub-td1\"><span class=\"et-clbl-sub-presenter\">".htmlspecialchars(mysql_result($result,$i,"clinic_presenter"))."</span></td></tr>\n";
    // tags
    echo "     <tr class=\"et-clbl-sub-tr3\"><td class=\"et-clbl-sub-td1\"><span class=\"et-clbl-sub-tags\">";
    echo htmlspecialchars(mysql_result($result,$i,"tag_name"));
    while ( ($i < $num-1) && 
                (mysql_result($result,$i,"name") == mysql_result($result,$i+1,"name")) &&
                (mysql_result($result,$i,"start_date") == mysql_result($result,$i+1,"start_date"))
           ) {
        $i++;
        echo ", ".htmlspecialchars(mysql_result($result,$i,"tag_name"));
    }
    echo "</span></td>\n";
    echo "   </table></td>\n";
}

function short_time($time) {
    return substr($time, -8, 5);
}
function cell_from_time($starttimes,$time) {
    $n = count($starttimes);
    $t = short_time($time);
    for ($i = 0; $i < $n; $i++)
        if ($t <= $starttimes[$i] )
            return $i;
    return $n;
}

function output_clinic_glue($width) {
    if ($width > 0)
        echo "  <td colspan=\"".$width."\" class=\"et-clbl-td-clinic-empty\"><span class=\"et-clbl-span-clinic-empty\"></span></td>\n";
}

function output_clinic_location($result,$rowindexes,$starttimes,$num,$url) {
    $location = $rowindexes[0];
    
    $rows = array();
    $row = array();
    // loop over entries, forming a row of non-overlaps
    $n = count($rowindexes);
    $done = FALSE;
    while (!$done) {
        $start = "00:00";
        $done = TRUE;
        for ($i=0; $i < $n; $i++) {
            if ($rowindexes[$i] != -1 ) {
                if ($start <= short_time(mysql_result($result,$rowindexes[$i],"start_date")) ) {
                    $row[] = $rowindexes[$i];
                    $start = short_time(mysql_result($result,$rowindexes[$i],"end_date"));
                    $rowindexes[$i] = -1;
                } else {
                    // we're skipping one, have to repeat
                    $done = FALSE;
                }
            }
        }
        $rows[] = $row;
        $row = array();
    }
    
    $first = TRUE;
    foreach ($rows as $r) {
        echo "<tr class=\"et-clbl-tr-\">\n";

        if ($first) {
            // first row gets header
            echo "  <th rowspan=\"".count($rows)."\" class=\"et-clbl-th-location\">".htmlspecialchars(mysql_result($result,$location,"location_name"))."</th>\n";
        }
        $first = FALSE;
        
        // put out cells
        $cell = 0;
        foreach ($r as $index) {
            // glue in front if needed
            $c = cell_from_time($starttimes,mysql_result($result,$index,"start_date"));
            output_clinic_glue($c-$cell);
            $cell = $c;
            $c = cell_from_time($starttimes,mysql_result($result,$index,"end_date"));
            $w = $c - $cell;
            $cell = $c;
            format_clinic_cell($result,$index,$w,$num,$url);
        }
        // glue in back if needed
        output_clinic_glue(count($starttimes)-$cell);
        echo "</tr>\n";
    }
    
}


function format_clinics_by_loc($url=NONE, $where=NONE) {
    global $opts, $event_tools_db_prefix, $event_tools_dates, $event_tools_clinics_start_times;
    
    mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
    @mysql_select_db($opts['db']) or die( "Unable to select database");
    
    $dates = $event_tools_dates; // from access.php
    $k = 0;
    
    if($where!=NONE) {
        $where = " AND ".$where." ";
    } else {
        $where ="";
    }
    
    while ($k < count($dates)) { 
        
        // query for start times
        $query="
            SELECT DISTINCT start_date
            FROM ".$event_tools_db_prefix."eventtools_clinics_with_tags
            WHERE start_date LIKE '".$dates[$k]."%'
            ".$where."
            ORDER BY start_date
            ;
        ";
        //echo $query;
        
        $result=mysql_query($query);
        
        $i = 0;
        $num = mysql_numrows($result);
    
        if ($num==0) {
            $k++;
            continue;
        }
    
        // fixed time slots regardless
        $starttimes = $event_tools_clinics_start_times;
    
        // add dynamic entries
        while ($i < $num) {
            $starttimes[$i] = substr(mysql_result($result,$i,"start_date"), -8, 5);
            $i++;
        }
    
        // remove duplicates   
        sort( $starttimes, SORT_STRING);
        $starttimes = array_values(array_unique( $starttimes ));
                
        // now query for clinics
        $query="
            SELECT *
            FROM ".$event_tools_db_prefix."eventtools_clinics_with_tags
            WHERE start_date LIKE '".$dates[$k]."%'
            ".$where."
            ORDER BY location_name, start_date, name, tag_name
            ;
        ";
        
        $result=mysql_query($query);
        
        $i = 0;
        $num = mysql_numrows($result);
        
        if ($num==0) {
            $k++;
            continue;
        }
        
        $lastmajorkey = "";
            
        echo "<h2 class=\"et-clbl-h2\" >".daydate_from_long_format($dates[$k])."</h2>\n";
        echo "<table border=\"1\" class=\"et-clbl-table\">\n";
        
        $j = 0;
        // header row
        echo "<tr class=\"et-clbl-tr-head\"><th class=\"et-clbl-th-corner\">Location</th>";
        while ($j < count($starttimes)) { echo "<th class=\"et-clbl-th-time\">".$starttimes[$j++]."</th>"; }
        echo "</tr>\n";
        
        $k++;    
    
        // loop over locations, using SQL query order
        while ($i < $num) {
        
            // search for new location
            if ($lastmajorkey != mysql_result($result,$i,"location_name")) {
                $lastmajorkey = mysql_result($result,$i,"location_name");
                
                // gather the elements for this location, this date
                $rowindexes = array();
                
                while ($i < $num) {
                    if ($lastmajorkey == mysql_result($result,$i,"location_name")) {
                        $rowindexes[] = $i;

                        // skip dups due to tags
                        while ( ($i < $num-1) && 
                                    (mysql_result($result,$i,"name") == mysql_result($result,$i+1,"name")) &&
                                    (mysql_result($result,$i,"start_date") == mysql_result($result,$i+1,"start_date"))
                               ) {
                            $i++;
                        }
                        $i++;
                    } else {
                        break;
                    }
                }

                output_clinic_location($result,$rowindexes,$starttimes,$num,$url);
            } else {
                echo "\nerror lining up at i=".$i."\n";
            }
            
        } // end of loop over locations
        
        // done, clean up
        
        echo "</tr>\n";
        echo "</table>\n";
    
    }
    
    mysql_close();    
}

///////////////////////////////////

function format_misc_cell($result,$i,$width,$num,$url) {
    $name = htmlspecialchars(mysql_result($result,$i,"name"));
    echo "  <td colspan=\"".$width."\" class=\"et-mebl-td-clinic\"><table class=\"et-mebl-sub-table\">\n";
    echo "     <tr class=\"et-mebl-sub-tr1\"><td class=\"et-mebl-sub-td1\"><span class=\"et-mebl-sub-name\">";
    echo "      <a href=\"".$url.mysql_result($result,$i,"number")."\">".$name."</a>\n";                 
    echo "      </span></td></tr>\n";
    // tags
    echo "     <tr class=\"et-mebl-sub-tr3\"><td class=\"et-mebl-sub-td1\"><span class=\"et-mebl-sub-tags\">";
    echo htmlspecialchars(mysql_result($result,$i,"tag_name"));
    while ( ($i < $num-1) && 
                (mysql_result($result,$i,"name") == mysql_result($result,$i+1,"name")) &&
                (mysql_result($result,$i,"start_date") == mysql_result($result,$i+1,"start_date"))
           ) {
        $i++;
        echo ", ".htmlspecialchars(mysql_result($result,$i,"tag_name"));
    }
    echo "</span></td>\n";
    echo "   </table></td>\n";
}

function output_misc_glue($width) {
    if ($width > 0)
        echo "  <td colspan=\"".$width."\" class=\"et-mebl-td-misc-event-empty\"><span class=\"et-mebl-span-misc-event-empty\"></span></td>\n";
}

function output_misc_location($result,$rowindexes,$starttimes,$num,$url) {
    $location = $rowindexes[0];
    
    $rows = array();
    $row = array();
    // loop over entries, forming a row of non-overlaps
    $n = count($rowindexes);
    $done = FALSE;
    while (!$done) {
        $start = "00:00";
        $done = TRUE;
        for ($i=0; $i < $n; $i++) {
            if ($rowindexes[$i] != -1 ) {
                if ($start <= short_time(mysql_result($result,$rowindexes[$i],"start_date")) ) {
                    $row[] = $rowindexes[$i];
                    $start = short_time(mysql_result($result,$rowindexes[$i],"end_date"));
                    $rowindexes[$i] = -1;
                } else {
                    // we're skipping one, have to repeat
                    $done = FALSE;
                }
            }
        }
        $rows[] = $row;
        $row = array();
    }
    
    $first = TRUE;
    foreach ($rows as $r) {
        echo "<tr class=\"et-mebl-tr-\">\n";

        if ($first) {
            // first row gets header
            echo "  <th rowspan=\"".count($rows)."\" class=\"et-mebl-th-location\">".htmlspecialchars(mysql_result($result,$location,"location_name"))."</th>\n";
        }
        $first = FALSE;
        
        // put out cells
        $cell = 0;
        foreach ($r as $index) {
            // glue in front if needed
            $c = cell_from_time($starttimes,mysql_result($result,$index,"start_date"));
            output_misc_glue($c-$cell);
            $cell = $c;
            $c = cell_from_time($starttimes,mysql_result($result,$index,"end_date"));
            $w = $c - $cell;
            $cell = $c;
            format_misc_cell($result,$index,$w,$num,$url);
        }
        // glue in back if needed
        output_misc_glue(count($starttimes)-$cell);
        echo "</tr>\n";
    }
    
}

function format_misc_events_by_loc($url=NONE, $where=NONE) {
    global $opts, $event_tools_db_prefix, $event_tools_dates, $event_tools_misc_event_start_times;
    
    mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
    @mysql_select_db($opts['db']) or die( "Unable to select database");
    
    $dates = $event_tools_dates; // from access.php
    $k = 0;
    
    if($where!=NONE) {
        $where = " AND ".$where." ";
    } else {
        $where ="";
    }
    
    while ($k < count($dates)) { 
        
        // query for start times
        $query="
            SELECT DISTINCT start_date
            FROM ".$event_tools_db_prefix."eventtools_misc_events_with_tags
            WHERE start_date LIKE '".$dates[$k]."%'
            ".$where."
            ORDER BY start_date
            ;
        ";
        //echo $query;
        
        $result=mysql_query($query);
        
        $i = 0;
        $num = mysql_numrows($result);
    
        if ($num==0) {
            $k++;
            continue;
        }
    
        // fixed time slots regardless
        $starttimes = $event_tools_misc_event_start_times; // access.php
    
        // add dynamic entries
        while ($i < $num) {
            $starttimes[$i] = substr(mysql_result($result,$i,"start_date"), -8, 5);
            $i++;
        }
    
        // remove duplicates   
        sort( $starttimes, SORT_STRING);
        $starttimes = array_values(array_unique( $starttimes ));
                
        // now query for misc events
        $query="
            SELECT *
            FROM ".$event_tools_db_prefix."eventtools_misc_events_with_tags
            WHERE start_date LIKE '".$dates[$k]."%'
            ".$where."
            ORDER BY location_name, start_date, name, tag_name
            ;
        ";
        
        $result=mysql_query($query);
        
        $i = 0;
        $num = mysql_numrows($result);
        
        if ($num==0) {
            $k++;
            continue;
        }
        
        $lastmajorkey = "";
            
        echo "<h2 class=\"et-mebl-h2\" >".daydate_from_long_format($dates[$k])."</h2>\n";
        echo "<table border=\"1\" class=\"et-mebl-table\">\n";
        
        $j = 0;
        // header row
        echo "<tr class=\"et-mebl-tr-head\"><th class=\"et-mebl-th-corner\">Location</th>";
        while ($j < count($starttimes)) { echo "<th class=\"et-mebl-th-time\">".$starttimes[$j++]."</th>"; }
        echo "</tr>\n";
        
        $k++;    
    
        // loop over locations, using SQL query order
        while ($i < $num) {
        
            // search for new location
            if ($lastmajorkey != mysql_result($result,$i,"location_name")) {
                $lastmajorkey = mysql_result($result,$i,"location_name");
                
                // gather the elements for this location, this date
                $rowindexes = array();
                
                while ($i < $num) {
                    if ($lastmajorkey == mysql_result($result,$i,"location_name")) {
                        $rowindexes[] = $i;

                        // skip dups due to tags
                        while ( ($i < $num-1) && 
                                    (mysql_result($result,$i,"name") == mysql_result($result,$i+1,"name")) &&
                                    (mysql_result($result,$i,"start_date") == mysql_result($result,$i+1,"start_date"))
                               ) {
                            $i++;
                        }
                        $i++;
                    } else {
                        break;
                    }
                }

                output_misc_location($result,$rowindexes,$starttimes,$num,$url);
            } else {
                echo "\nerror lining up at i=".$i."\n";
            }
            
        } // end of loop over locations
        
        // done, clean up
        
        echo "</tr>\n";
        echo "</table>\n";
    
    }
    
    mysql_close();    
}


?>
