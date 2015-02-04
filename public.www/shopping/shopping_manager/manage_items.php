<?php
include_once "../shopping_lib.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <title>Manage Items</title>
</head>
<body>
<?php
$form_name = get_str_value($_REQUEST, "form_name");
$action = get_str_value($_REQUEST, "action");

if($form_name == "add_item" || $form_name == "update_item")
{
    $item_description = get_str_value($_REQUEST, "item_description");
    $item_remaining = get_str_value($_REQUEST, "item_remaining");
    
    if($form_name == "add_item")
    {
        add_item($item_description, $item_remaining);
    }
    elseif($form_name == "update_item")
    {
        $item_id = get_int_value($_REQUEST, "item_id");
        update_item($item_id, $item_description, $item_remaining);
    }
    
    // redirect back to the list of items
    redirect("manage_items.php");
}
// add in the delete option here eventually
// we will have to check that the item is not in an offer or in a group


if($action == "edit" || $action == "add")
{
    if($action == "edit")
    {
        $item_id = get_int_value($_REQUEST, "item_id");
        $item = get_item_info($item_id);
        $form_name = "update_item";
        $button_text = "Update Item";
    }
    else
    {
        $item_id = 0;
        $item = array("desc" => "", "remaining" => 0);
        $form_name = "add_item";
        $button_text = "Add Item";
    }
?>
    <form action="manage_items.php" method="POST">
        <table>
            <tr>
                <td>Description:</td>
                <td><input type="text" name="item_description" size="100" value="<?php print($item["desc"]); ?>"></td>
            </tr>
            <tr>
                <td>Remaining:</td>
                <td><input type="text" name="item_remaining" size="5" value="<?php print($item["remaining"]); ?>"></td>
            </tr>
        </table>
        <input type="submit" value="<?php print($button_text); ?>">
        <input type="hidden" name="item_id" value="<?php print($item_id); ?>">
        <input type="hidden" name="form_name" value="<?php print($form_name); ?>">
    </form>
<?php
}
else
{
    $items = get_shopping_items();

    $item_html = "";

    foreach($items as $item)
    {
        $item_html .= "<tr><td><a href=\"manage_items.php?action=edit&item_id=$item[item_id]\">edit</a></td><td>$item[desc]</td><td>$item[remaining]</td></tr>";
    }
    ?>
    <div><a href="manage_items.php?action=add">+ add new item</a></div>
    <table>
        <tr>
            <td>&nbsp;</td>
            <td>Item Description</td>
            <td>Remaining</td>
        </tr>
<?php print($item_html); ?>
    </table>
<?php
}
?>
</body>
</html>