<?php require_once('access.php'); require_once('secure.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Contacts</title>

        <link href="tours.css" rel="stylesheet" type="text/css" />    

    </head>
    <body>
    <h2>Contacts</h2>  
    <a href="index.php">Back to main page</a>
    <p>

<?php 
    require_once('utilities.php'); require_once('formatting.php');

        function simple_table_format_cell($index, $row, $name) {
            $val = errorOnEmpty(htmlspecialchars($row[$name]),$name);
            // special cases
            if ($name == 'layout_owner_lastname') {
                $val = errorOnEmpty(htmlspecialchars($row["layout_owner_firstname"]." ".$row["layout_owner_lastname"]),"name");
            } else if ($name == 'layout_owner_firstname') {
                return;
            } else if ($name == 'layout_photo_url') {
                $val = '<a href="'.$row[$name].'">Description & Photos</a>';
            } else if ($name == 'layout_dispatched_by1') {
                if (strlen($row['layout_dispatched_by2']) > 0) {
                    $val = errorOnEmpty(htmlspecialchars($row["layout_dispatched_by1"]."/".$row["layout_dispatched_by2"]),"dispatch");
                }
            } else if ($name == 'layout_dispatched_by2') {
                return;
            }
            echo "  <td>\n";
            echo "    <span class=\"et-".$name."\">\n";
            echo "      <a name=\"".$row[$name]."\"></a>\n";
            echo "     ".$val;
            echo "      </span> \n";
            echo "  </td>\n";
        }
        
        parse_str($_SERVER["QUERY_STRING"], $args);
        $order = $args["order"];
        if ($order == NONE || $order == '') $order = 'customers_lastname';
        
        echo '<table border="1">';
        echo '<tr>
            <th><a href="index_attendees.php?order=customers_firstname">First</a></th>
            <th><a href="index_attendees.php?order=customers_lastname">Last</a></th>
            <th><a href="index_attendees.php?order=customers_email_address">Email</a></th>
            <th><a href="index_attendees.php?order=entry_street_address">Street</a></th>
            <th><a href="index_attendees.php?order=entry_city">City</a></th>
            <th><a href="index_attendees.php?order=entry_state">State</a></th>
            <th><a href="index_attendees.php?order=entry_postcode">Zip</a></th>
            <th><a href="index_attendees.php?order=customers_telephone">Phone</a></th>
            <th><a href="index_attendees.php?order=customers_cellphone">Cell</a></th>';
        if ($event_tools_emergency_contact_info) {
            echo  '<th><a href="index_attendees.php?order=customers_x2011_emerg_contact_name">Emergency<br>Contact</a></th>
                   <th><a href="index_attendees.php?order=customers_x2011_emerg_contact_phone">Emergency<br>Phone</a></th>';
        }
        echo  '<th><a href="index_attendees.php?order=customers_create_date">Created</a></th>';
        echo  '<th><a href="index_attendees.php?order=customers_updated_date">Updated</a></th>
            </tr>';
        
        $table = $event_tools_db_prefix.'customers LEFT JOIN '.$event_tools_db_prefix.'address_book
                ON '.$event_tools_db_prefix.'customers.customers_id = '.$event_tools_db_prefix.'address_book.customers_id
            ';
            
        $query="
            SELECT  *
            FROM ".$table."
            ORDER BY ".$order."
            ;
        ";
        //echo $query;

        if ($event_tools_emergency_contact_info) {
            table_from_query( $query, 
                array('customers_firstname', 'customers_lastname', 'customers_email_address', 'entry_street_address', 'entry_city', 'entry_state', 'entry_postcode', 'customers_telephone', 'customers_cellphone', 'customers_x2011_emerg_contact_name', 'customers_x2011_emerg_contact_phone', 'customers_create_date', 'customers_updated_date')
            );
        } else {
            table_from_query( $query, 
                array('customers_firstname', 'customers_lastname', 'customers_email_address', 'entry_street_address', 'entry_city', 'entry_state', 'entry_postcode', 'customers_telephone', 'customers_cellphone', 'customers_create_date', 'customers_updated_date')
            );
        }
        echo '</table>';
?>
</body>
</html>
