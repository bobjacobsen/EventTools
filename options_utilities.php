<?php

// -------------------------------------------------------------------------
// Part of EventTools, a package for managing model railroad meeting information
//
// By Bob Jacobsen, jacobsen@mac.com, Copyright 2010, 2011, 2016
// -------------------------------------------------------------------------

//
// Utilities for handling the customer options
//

// -------------------------------------------------------------------------
// return a SELECT statement
// (not terminated; follow by WHERE, ORDER, etc and ; as needed)
// that gives access to the user selections as value0 through valueN columns
//
function options_select_statement() {
    global $event_tools_db_prefix;
    // get the list of extras
    $queryExtras="
        SELECT *
            FROM (
            ".$event_tools_db_prefix."eventtools_customer_options
            )
            ORDER BY customer_option_order
            ;
        ";
    // echo $queryExtras;
    $resultExtras=mysql_query($queryExtras);
    $numExtras= mysql_numrows($resultExtras);

    $query="
        SELECT prorail2020_customers.customers_id, customers_firstname, customers_lastname, customers_telephone, customers_cellphone, opsreq_person_email, customers_create_date, customers_updated_date, opsreq_priority
        ";

    // loop over extras and add column
    $i = 0;
    while ($i < $numExtras) {
        $query=$query.", table".$i.".customer_option_value_value AS value".$i."  ";
        $query=$query.", table".$i.".customer_option_id AS id".$i."  ";
        $i++;
    }

    // add main join
    $query=$query."
            FROM (
            ".$event_tools_db_prefix."eventtools_opsession_req LEFT JOIN ".$event_tools_db_prefix."customers
            ON ".$event_tools_db_prefix."eventtools_opsession_req.opsreq_person_email = ".$event_tools_db_prefix."customers.customers_email_address ";


    // loop over extras and add join
    $i = 0;
    while ($i < $numExtras) {
        $query=$query." LEFT JOIN ".$event_tools_db_prefix."eventtools_customer_option_values AS table".$i."
                ON ".$event_tools_db_prefix."customers.customers_id =  table".$i.".customers_id  AND table".$i.".customer_option_id = '".mysql_result($resultExtras,$i,"customer_option_id")."' ";
        $i++;
    }

    $query = $query." ) ";

    //echo $query;
    return $query;
}


?>
