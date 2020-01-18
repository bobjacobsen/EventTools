<?php require_once('access_and_open.php'); require_once('secure.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Op Session Update Requests</title>
    <style type="text/css">
        span.released { background: #ffffff; }
        span.assigned { background: #b0b0ff; }
        span.conflict { background: #ffc0c0; }
        span.filled   { background: #c0ffc0; }
        span.disabled { background: #ff8080; }
        
        td.session { width: 600px; }
    </style>
</head>
<body>
<h1>Op Session Update Requests</h1>  
<a href="index.php">Back to main page</a><p/>

<?php

include_once('mysql2i.class.php'); // migration step
require_once('ops_assign_common.php');

function show_update_request_line($result,$i,$cycle,$show_name,$start_date,$variable) { // $variable is like "opsreq_pri1"
    if ( mysql_result($result,$i,$variable) != mysql_result($result,$i,"ops_id") 
            && (mysql_result($result,$i,$variable) > "1" || mysql_result($result,$i,"ops_id") > "1")) {
        echo '<table><tr>';
        echo '<td><form method="get" action="ops_assign_update.php"><input type="hidden" name="cy" value="'.$cycle.'">
              <input type="hidden" name="id" value="'.mysql_result($result,$i,"opsreq_req_status_id").'">
              <input type="hidden" name="req" value="'.mysql_result($result,$i,$variable).'">
              <input type="submit" name="op" value="Update"/></form></td><td>';
        echo mysql_result($result,$i,"opsreq_person_email").' {'.mysql_result($result,$i,"opsreq_req_status_id").'} ';
        if (mysql_result($result,$i,"forced") != "0") {
            echo ' <b>forced setting</b> '.mysql_result($result,$i,"req_num");
        } else {
            echo ' current request '.mysql_result($result,$i,"req_num");
        }
        echo " from [".mysql_result($result,$i,"ops_id")."] "
            .mysql_result($result,$i,"show_name").' '.mysql_result($result,$i,"start_date")
            ." to ["
            .mysql_result($result,$i,$variable)."] ".$show_name[mysql_result($result,$i,$variable)].' '.$start_date[mysql_result($result,$i,$variable)];
        if ($show_name[mysql_result($result,$i,$variable)]=="") echo '&lt;none&gt;';
        echo '<br/></td></tr></table>';
    }
}


// parse out arguments
parse_str($_SERVER["QUERY_STRING"], $args);

// first, see if there's a "?cy=" or "?start=" in the arguments
if (! ($args["cy"]) ) {
    echo 'This page has to be entered from the <a href="ops_assign_group.php">grouping page</a>.<p/>';
    echo "Please click on that link and start there.<p/>";
    return;
} else {
    $cycle = $args["cy"];
}

// here, the cycle name exists, 
echo '<h2>Cycle: '.$cycle.'</h2>';

// open db

global $opts, $event_tools_db_prefix, $event_tools_href_add_on, $cycle;
mysql_connect($opts['hn'],$opts['un'],$opts['pw']);
@mysql_select_db($opts['db']) or die( "Unable to select database");

// head buttons

echo '<form method="get" action="ops_assign_group.php">';
echo '<input type="hidden" name="cy" value="'.$cycle.'">';
echo '<button type="submit">Return to grouping</button></form>';

echo '<form method="get" action="ops_assign_set.php">';
echo '<input type="hidden" name="cy" value="'.$cycle.'">';
echo '<button type="submit">Return to assignment</button></form>';

echo '<form method="get" action="ops_assign_update.php">';
echo '<input type="hidden" name="cy" value="'.$cycle.'">';
echo '<button type="submit">Refresh/Update</button></form>';

// Process inputs
if ($args["op"] == "Update") {
    $id = $args["id"];
    $req = $args["req"];
    
    $query = "UPDATE ".$event_tools_db_prefix."eventtools_opsreq_req_status
                SET status='0', ops_id='".$req."', forced='0'
                WHERE opsreq_req_status_id = '".$id."'
                ;";
    //echo '<p>'.$query.'<p>';
    mysql_query($query);

} else if ($args["op"] == "Delete") {
    // delete by removing connection between request and group
    echo "Internal error, delete should have been automatic with removal of request row<br/>";
    
} else if ($args["op"] == "Add") {
    // skip if already exists
    $query="
        SELECT  *
        FROM ".$event_tools_db_prefix."eventtools_ops_group_session_assignments
        WHERE opsreq_person_email = '".$args["id"]."'
            AND opsreq_group_cycle_name = '".$cycle."'
        ;
    ";
    
    $reqs=mysql_query($query);
    $nums = mysql_numrows($reqs);
    if ($nums != 0) {
        echo "Skipping duplicate add for ".$args["id"]."</br>";
    } else {
        // make the entire line for a new user
        $query="
            SELECT  *
            FROM ".$event_tools_db_prefix."eventtools_opsession_req
            WHERE opsreq_person_email = '".$args["id"]."'
            ;
        ";
        
        $reqs=mysql_query($query);
        $nums = mysql_numrows($reqs);
        if ($nums != 1) echo "wrong number of email matches: ".$nums;
        insert_one_ops_request_structure($cycle, $reqs, 0);
    }    
}

// Basic approach: walk through cycle as if making a new one,
// adding changes as needed.

// first, make sure everybody is present.

$query="
    SELECT  *
    FROM ".$event_tools_db_prefix."eventtools_opsession_req
    ORDER BY opsreq_person_email
    ;
";

//echo $query;
$reqs=mysql_query($query);
$nums = mysql_numrows($reqs);

$query="
    SELECT  *
    FROM ".$event_tools_db_prefix."eventtools_ops_group_session_assignments
    WHERE opsreq_group_cycle_name = '".$cycle."'
    GROUP BY opsreq_person_email
    ORDER BY opsreq_person_email
    ;
";

//echo $query;
$reqd=mysql_query($query);
$numd = mysql_numrows($reqd);

$j = 0;
$i = 0;

echo '<h3>New and Removed Users</h3>';

while ($i < $nums && $j < $numd) {
    // echo "Compare ".mysql_result($reqs,$i,"opsreq_person_email")." to ".mysql_result($reqd,$j,"opsreq_person_email")."<br/>";
    if (strtolower(mysql_result($reqs,$i,"opsreq_person_email")) == strtolower(mysql_result($reqd,$j,"opsreq_person_email"))){
        $i++;
        $j++;
        continue;
    }
    // not equal, new person?
    if (strtolower(mysql_result($reqs,$i,"opsreq_person_email")) < strtolower(mysql_result($reqd,$j,"opsreq_person_email"))){
        if (   (mysql_result($reqs,$i,"opsreq_pri1") > "1")
            || (mysql_result($reqs,$i,"opsreq_pri2") > "1")
            || (mysql_result($reqs,$i,"opsreq_pri3") > "1")
            || (mysql_result($reqs,$i,"opsreq_pri4") > "1")
            || (mysql_result($reqs,$i,"opsreq_pri5") > "1")
            || (mysql_result($reqs,$i,"opsreq_pri6") > "1")
            || (mysql_result($reqs,$i,"opsreq_pri7") > "1")
            || (mysql_result($reqs,$i,"opsreq_pri8") > "1")
            || (mysql_result($reqs,$i,"opsreq_pri9") > "1")
            || (mysql_result($reqs,$i,"opsreq_pri10") > "1")
            || (mysql_result($reqs,$i,"opsreq_pri11") > "1")
            || (mysql_result($reqs,$i,"opsreq_pri12") > "1")
             ) {

            echo '<table><tr>';
            echo '<td><form method="get" action="ops_assign_update.php"><input type="hidden" name="cy" value="'.$cycle.'">
                  <input type="hidden" name="id" value="'.mysql_result($reqs,$i,"opsreq_person_email").'"><input type="submit" name="op" value="Add"/></form></td>';
            echo '<td>New person: '.mysql_result($reqs,$i,"opsreq_person_email").'</td>';
            echo '</tr></table>';
        } else {
            // echo "New person with no requests<br/>";
        }
        $i++;
        continue;
    }
    if (strtolower(mysql_result($reqs,$i,"opsreq_person_email")) > strtolower(mysql_result($reqd,$j,"opsreq_person_email")) ){
        echo '<table><tr>';
        echo '<td><form method="get" action="ops_assign_update.php"><input type="hidden" name="cy" value="'.$cycle.'">
              <input type="hidden" name="id" value="'.mysql_result($reqd,$j,"opsreq_group_req_link_id").'"><input type="submit" name="op" value="Delete"/></form></td>';
        echo '<td>Deleted person: '.mysql_result($reqd,$j,"opsreq_person_email").'</td>';
        echo '</tr></table>';
        $j++;
        continue;
    }
    echo "(Should not have occurred)<p/>";
    
}
while ($i < $nums) {
    if (   (mysql_result($reqs,$i,"opsreq_pri1") > "1")
            || (mysql_result($reqs,$i,"opsreq_pri2") > "1")
            || (mysql_result($reqs,$i,"opsreq_pri3") > "1")
            || (mysql_result($reqs,$i,"opsreq_pri4") > "1")
            || (mysql_result($reqs,$i,"opsreq_pri5") > "1")
            || (mysql_result($reqs,$i,"opsreq_pri6") > "1")
            || (mysql_result($reqs,$i,"opsreq_pri7") > "1")
            || (mysql_result($reqs,$i,"opsreq_pri8") > "1")
            || (mysql_result($reqs,$i,"opsreq_pri9") > "1")
            || (mysql_result($reqs,$i,"opsreq_pri10") > "1")
            || (mysql_result($reqs,$i,"opsreq_pri11") > "1")
            || (mysql_result($reqs,$i,"opsreq_pri12") > "1")
             ) {
        echo '<table><tr>';
        echo '<td><form method="get" action="ops_assign_update.php"><input type="hidden" name="cy" value="'.$cycle.'">
              <input type="hidden" name="id" value="'.mysql_result($reqs,$i,"opsreq_person_email").'"><input type="submit" name="op" value="Add"/></form></td>';
        echo '<td>New person: '.mysql_result($reqs,$i,"opsreq_person_email").'</td>';
        echo '</tr></table>';
    } else {
        // echo "New person with no requests<br/>";
    }
    $i++;
}
while ($j < $numd) {
    echo '<table><tr>';
    echo '<td><form method="get" action="ops_assign_update.php"><input type="hidden" name="cy" value="'.$cycle.'">
          <input type="hidden" name="id" value="'.mysql_result($reqd,$j,"opsreq_group_req_link_id").'"><input type="submit" name="op" value="Delete"/></form></td>';
    echo '<td>Deleted person: '.mysql_result($reqd,$j,"opsreq_person_email").'</td>';
    echo '</tr></table>';
    $j++;
}

echo '<h3>Changed Requests</h3>';

$query= "
    SELECT * 
        FROM ".$event_tools_db_prefix."eventtools_opsession_name
";
//echo $query;
$result=mysql_query($query);
$num = mysql_numrows($result);

$start_date = array();
$show_name = array();
for ($i = 0; $i < $num; $i++) {
    $start_date[mysql_result($result,$i,"ops_id")] = mysql_result($result,$i,"start_date");
    $show_name[mysql_result($result,$i,"ops_id")] = mysql_result($result,$i,"show_name");
}

// now go back through and check session requests
$query="
    SELECT customers_firstname, customers_lastname, opsreq_person_email, ".$event_tools_db_prefix."eventtools_opsreq_group.opsreq_group_id, 
            opsreq_group_cycle_name, opsreq_comment, ".$event_tools_db_prefix."eventtools_opsreq_group_req_link.opsreq_id,
            opsreq_group_req_link_id, entry_city, entry_state, opsreq_req_status_id,
            ops_id, req_num, start_date, show_name, forced,
            opsreq_pri1, opsreq_pri2, opsreq_pri3, opsreq_pri4, opsreq_pri5, opsreq_pri6, opsreq_pri7, opsreq_pri8,
            opsreq_pri9, opsreq_pri10, opsreq_pri11, opsreq_pri12
        FROM (((((
        ".$event_tools_db_prefix."eventtools_opsession_req LEFT JOIN ".$event_tools_db_prefix."customers
        ON ".$event_tools_db_prefix."eventtools_opsession_req.opsreq_person_email = ".$event_tools_db_prefix."customers.customers_email_address
        ) JOIN ".$event_tools_db_prefix."eventtools_opsreq_group_req_link
        USING ( opsreq_id )
        ) JOIN ".$event_tools_db_prefix."eventtools_opsreq_group
        USING ( opsreq_group_id )
        ) JOIN ".$event_tools_db_prefix."eventtools_opsreq_req_status
        USING ( opsreq_group_req_link_id )
        ) JOIN ".$event_tools_db_prefix."eventtools_opsession_name
        USING ( ops_id )
        ) LEFT JOIN ".$event_tools_db_prefix."address_book
        ON ".$event_tools_db_prefix."customers.customers_default_address_id = ".$event_tools_db_prefix."address_book.address_book_id
        WHERE opsreq_group_cycle_name = '".$cycle."'
        ;";

//echo $query;
$result=mysql_query($query);
$num = mysql_numrows($result);

for ($i = 0; $i < $num; $i++) {
    switch (mysql_result($result,$i,"req_num")) {
        case "1":
            show_update_request_line($result,$i,$cycle,$show_name,$start_date,"opsreq_pri1");
            break;
        case "2":
            show_update_request_line($result,$i,$cycle,$show_name,$start_date,"opsreq_pri2");
            break;
        case "3":
            show_update_request_line($result,$i,$cycle,$show_name,$start_date,"opsreq_pri3");
            break;
        case "4":
            show_update_request_line($result,$i,$cycle,$show_name,$start_date,"opsreq_pri4");
            break;
        case "5":
            show_update_request_line($result,$i,$cycle,$show_name,$start_date,"opsreq_pri5");
            break;
        case "6":
            show_update_request_line($result,$i,$cycle,$show_name,$start_date,"opsreq_pri6");
            break;
        case "7":
            show_update_request_line($result,$i,$cycle,$show_name,$start_date,"opsreq_pri7");
            break;
        case "8":
            show_update_request_line($result,$i,$cycle,$show_name,$start_date,"opsreq_pri8");
            break;
        case "9":
            show_update_request_line($result,$i,$cycle,$show_name,$start_date,"opsreq_pri9");
            break;
        case "10":
            show_update_request_line($result,$i,$cycle,$show_name,$start_date,"opsreq_pri10");
            break;
        case "11":
            show_update_request_line($result,$i,$cycle,$show_name,$start_date,"opsreq_pri11");
            break;
        case "12":
            show_update_request_line($result,$i,$cycle,$show_name,$start_date,"opsreq_pri12");
            break;
    }
}


return;

?>
</body>
</html>
