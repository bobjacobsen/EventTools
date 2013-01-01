<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Operating Session Attendees</title>

    <link href="tours.css" rel="stylesheet" type="text/css" />    

</head>
<body>
<h2>Operating Session Attendees</h2>  
<a href="index.php">Back to main page</a>
<p>

<?php
        require_once('access.php'); require_once('utilities.php'); require_once('formatting.php');
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
        if ($order == NONE || $order == '') $order = 'customers_email_address';
        
        echo '<table border="1">';
        echo '<tr>
            <th><a href="index_attendees?order=customers_firstname">First</a></th>
            <th><a href="index_attendees?order=customers_lastname">Last</a></th>
            <th><a href="index_attendees?order=customers_email_address">Email</a></th>
            <th><a href="index_attendees?order=entry_street_address">Street</a></th>
            <th><a href="index_attendees?order=entry_city">City</a></th>
            <th><a href="index_attendees?order=entry_state">State</a></th>
            <th><a href="index_attendees?order=entry_postcode">Zip</a></th>
            <th><a href="index_attendees?order=customers_telephone">Phone</a></th>
            <th><a href="index_attendees?order=customers_cellphone">Cell</a></th>
            </tr>';
            
        simple_table('opsession_req_with_user_info', 
            array('customers_firstname', 'customers_lastname', 'customers_email_address', 'entry_street_address', 'entry_city', 'entry_state', 'entry_postcode', 'customers_telephone', 'customers_cellphone'),
            NONE, $order
        );
        echo '</table>';
?>
</body>
</html>
