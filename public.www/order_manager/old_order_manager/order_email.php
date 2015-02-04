<?php
include "../common/database.php";

$link = db_connect();

$query="Select order_id, cu_login, filled_by, delivered, If(UNIX_TIMESTAMP(date_last_updated) = 0,'never',DATE_FORMAT(date_last_updated, '%c/%e at %l:%i %p')) as date_last_updated, DATE_FORMAT(date_ordered, '%c/%e/%y') as date_ordered From orders WHERE delivered = 0 AND cancelled = 0 Order by cu_login";

$result = mysql_query($query, $link);

while($row = mysql_fetch_assoc($result))
{
    $total = 0;
	$order_str = "";
    $order_id = $row['order_id'];
    $username = $row['cu_login'];
    
    // create cancel key and store in database
    
    $query = "SELECT i.desc, quantity, price FROM items_in_orders AS io LEFT JOIN items AS i ON i.item_id = io.item_id WHERE order_id = $order_id ORDER BY display_order";
    
    $result2 = mysql_query($query, $link);
    
    while($row2 = mysql_fetch_assoc($result2))
    {
        $description = $row2['desc'];
        $quantity = $row2['quantity'];
        $price = $row2['price'];
        $order_str .= "Item: $description\nPrice: \$$price\nQuantity: $quantity\n\n";
        $total += $quantity * $price;
    }
    
    $order_str .= "-------------\nTotal: \$" . number_format($total, 2, '.', ',');
    
    // send confirmation email
    $message = "Order Id: $order_id\nYour order summary is provided below. If you wish to cancel your order, copy and paste the following url into your browser:\n\nhttp://people.clemson.edu/~siam/cancel_order.php?order_id=$order_id\n\nIf you wish to change your order, use the url provided above to cancel your existing order and then place a new order at http://people.clemson.edu/~siam/shop.php.\n\n" . $order_str . "\n\nIf you have any further questions or to submit your payment please contact any of the SIAM officers.\n\nNate Black E-3a\nMariah Magagnotti E-8\nJeff Beyerl E-3a\nMaria Stopak E-3a\n\n";

    $message = wordwrap($message,100);
     
    $headers = "From: SIAM Sales <siam@clemson.edu>";

    $output = mail($username . "@clemson.edu", "SIAM order summary", $message, $headers);
    //$output = mail("nblack@clemson.edu", "SIAM order summary (Order Id: $order_id)", $message, $headers);
}
?>