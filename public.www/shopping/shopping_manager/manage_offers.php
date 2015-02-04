<?php
include_once "../shopping_lib.php";
?>
<!doctype html>
<html>
<head>
    <title>Manage Offers</title>
</head>
<body>
<?php
$form_name = get_str_value($_REQUEST, "form_name");
$action = get_str_value($_REQUEST, "action");

if($form_name == "add_offer" || $form_name == "update_offer")
{
    $offer_description = get_str_value($_REQUEST, "offer_description");
    $offer_keywords = get_str_value($_REQUEST, "offer_keywords");
    $offer_price = get_float_value($_REQUEST, "offer_price");
    
    if($form_name == "add_offer")
    {
        $offer_id = add_offer($offer_description, $offer_keywords, $offer_price);
    }
    elseif($form_name == "update_offer")
    {
        $offer_id = get_int_value($_REQUEST, "offer_id");
        update_offer($offer_id, $offer_description, $offer_keywords, $offer_price);
    }
    
    // redirect to editing this offer
    redirect("manage_offers.php?action=edit&offer_id=$offer_id");
}
// add in the delete option here eventually
// we will have to check that the offer is not in an order or in a group

if($form_name == "add_item")
{
    $offer_id = get_int_value($_REQUEST, "offer_id");
    $item_id = get_int_value($_REQUEST, "item_id");
    $item_quantity = get_int_value($_REQUEST, "item_quantity");
    
    add_item_to_offer($offer_id, $item_id, $item_quantity);
    
    // redirect to editing this offer
    redirect("manage_offers.php?action=edit&offer_id=$offer_id");
}


if($action == "edit" || $action == "add")
{
    if($action == "edit")
    {
        $offer_id = get_int_value($_REQUEST, "offer_id");
        $offer = get_offer_info($offer_id);
        $form_name = "update_offer";
        $button_text = "Update Offer";
        $item_list = get_items_in_offer($offer_id);
        $item_list_html = "";
        foreach($item_list as $item)
        {
            $item_list_html .= "<tr><td>$item[desc]</td><td>$item[quantity]</td></tr>";
        }
    }
    else
    {
        $offer_id = 0;
        $offer = array("description" => "", "keywords" => "", "price" => 0);
        $form_name = "add_offer";
        $button_text = "Add Offer";
        $item_list_html = "";
    }
    
    $items = get_items();
    $item_ids = array();
    $item_descriptions = array();
    foreach($items as $item)
    {
        $item_ids[] = $item["item_id"];
        $item_descriptions[] = $item["desc"];
    }
    $item_dropdown = get_html_dropdown($item_ids, $item_descriptions, "item_id", 0);
?>
    <div style="margin-bottom: 20px;">
        <a href="manage_offers.php">Return to offer list</a>
        <a href="manage_offers.php?action=add">Create new offer</a>
    </div>
    <form action="manage_offers.php" method="POST">
        <table>
            <tr>
                <td>Description:</td>
                <td><input type="text" name="offer_description" size="50" value="<?php print($offer["description"]); ?>"></td>
            </tr>
            <tr>
                <td>Keywords:</td>
                <td><input type="text" name="offer_keywords" size="50" value="<?php print($offer["keywords"]); ?>"></td>
            </tr>
            <tr>
                <td>Price:</td>
                <td><input type="text" name="offer_price" size="5" value="<?php print($offer["price"]); ?>"></td>
            </tr>
        </table>
        <input type="submit" value="<?php print($button_text); ?>">
        <input type="hidden" name="offer_id" value="<?php print($offer_id); ?>">
        <input type="hidden" name="form_name" value="<?php print($form_name); ?>">
    </form>
<?php
    if($action == "edit")
    {
?>
    <hr>
    <div>Items in Offer</div>
    <table>
        <tr>
            <td>Item Description</td>
            <td>Quantity</td>
        </tr>
<?php print($item_list_html); ?>
    </table>
    <div style="margin-top: 30px;">Add new item to offer</div>
    <form action="manage_offers.php" method="POST">
        <table>
            <tr>
                <td>Item</td>
                <td>Quantity</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><?php print($item_dropdown); ?></td>
                <td><input type="text" name="item_quantity" size="5" value="1"></td>
                <td>
                    <input type="submit" value="Add Item">
                    <input type="hidden" name="form_name" value="add_item">
                    <input type="hidden" name="offer_id" value="<?php print($offer_id); ?>">
                </td>
            </tr>
        </table>
    </form>
<?php
    }
}
else
{
    $offers = get_shopping_offers();

    $offer_html = "";

    foreach($offers as $offer)
    {
        $offer_html .= "<tr><td><a href=\"manage_offers.php?action=edit&offer_id=$offer[offer_id]\">edit</a></td><td>$offer[description]</td><td>$offer[keywords]</td><td>\$$offer[price]</td></tr>";
    }
    ?>
    <div><a href="manage_offers.php?action=add">+ add new offer</a></div>
    <table>
        <tr>
            <td>&nbsp;</td>
            <td>Offer Description</td>
            <td>Keywords</td>
            <td>Price</td>
        </tr>
<?php print($offer_html); ?>
    </table>
<?php
}
?>
</body>
</html>