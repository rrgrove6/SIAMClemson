<?php
include_once "../shopping_lib.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <title>Manage Groups</title>
</head>
<body>
<?php
$form_name = get_str_value($_REQUEST, "form_name");
$action = get_str_value($_REQUEST, "action");

if($form_name == "add_group" || $form_name == "update_group")
{
    $group_description = get_str_value($_REQUEST, "group_description");
    $group_image = get_str_value($_REQUEST, "group_image");
    $display_order = get_float_value($_REQUEST, "display_order");
    
    if($form_name == "add_group")
    {
        $group_id = add_group($group_description, $group_image, $display_order);
    }
    elseif($form_name == "update_group")
    {
        $group_id = get_int_value($_REQUEST, "group_id");
        update_group($group_id, $group_description, $group_image, $display_order);
    }
    
    // redirect to editing this offer
    redirect("manage_groups.php?action=edit&group_id=$group_id");
}
// add in the delete option here eventually

if($form_name == "add_offer")
{
    $group_id = get_int_value($_REQUEST, "group_id");
    $offer_id = get_int_value($_REQUEST, "offer_id");
    $link_description = get_str_value($_REQUEST, "link_description");
    $offer_display_order = get_int_value($_REQUEST, "offer_display_order");
    
    add_offer_to_group($group_id, $offer_id, $link_description, $offer_display_order);
    
    // redirect to editing this group
    redirect("manage_groups.php?action=edit&group_id=$group_id");
}


if($action == "edit" || $action == "add")
{
    if($action == "edit")
    {
        $group_id = get_int_value($_REQUEST, "group_id");
        $group = get_group_info($group_id);
        $form_name = "update_group";
        $button_text = "Update Group";
        $offer_list = get_offers_in_group($group_id);
        $offer_list_html = "";
        foreach($offer_list as $offer)
        {
            $offer_list_html .= "<tr><td>$offer[description]</td><td>$offer[link_description]</td><td>$offer[offer_display_order]</td></tr>";
        }
    }
    else
    {
        $group_id = 0;
        $group = array("group_description" => "", "group_image" => "", "display_order" => 0);
        $form_name = "add_group";
        $button_text = "Add Group";
        $offer_list_html = "";
    }
    
    $offers = get_offers();
    $offer_ids = array();
    $offer_descriptions = array();
    foreach($offers as $offer)
    {
        $offer_ids[] = $offer["offer_id"];
        $offer_descriptions[] = $offer["description"];
    }
    $offer_dropdown = get_html_dropdown($offer_ids, $offer_descriptions, "offer_id", 0);
?>
    <div style="margin-bottom: 20px;"><a href="manage_groups.php">Return to group list</a></div>
    <form action="manage_groups.php" method="POST">
        <table>
            <tr>
                <td>Description:</td>
                <td><input type="text" name="group_description" size="50" value="<?php print($group["group_description"]); ?>"></td>
            </tr>
            <tr>
                <td>Group Image:</td>
                <td><input type="text" name="group_image" size="50" value="<?php print($group["group_image"]); ?>"></td>
            </tr>
            <tr>
                <td>Display Order:</td>
                <td><input type="text" name="display_order" size="5" value="<?php print($group["display_order"]); ?>"></td>
            </tr>
        </table>
        <input type="submit" value="<?php print($button_text); ?>">
        <input type="hidden" name="group_id" value="<?php print($group_id); ?>">
        <input type="hidden" name="form_name" value="<?php print($form_name); ?>">
    </form>
<?php
    if($action == "edit")
    {
?>
    <hr>
    <div>Offers in Group</div>
    <table>
        <tr>
            <td>Offer Description</td>
            <td>Link Description</td>
            <td>Display Order</td>
        </tr>
<?php print($offer_list_html); ?>
    </table>
    <div style="margin-top: 30px;">Add new offer to group</div>
    <form action="manage_groups.php" method="POST">
        <table>
            <tr>
                <td>Offer</td>
                <td>Link Description</td>
                <td>Display Order</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><?php print($offer_dropdown); ?></td>
                <td><input type="text" name="link_description" size="25" value=""></td>
                <td><input type="text" name="offer_display_order" size="5" value="1"></td>
                <td>
                    <input type="submit" value="Add Offer">
                    <input type="hidden" name="form_name" value="add_offer">
                    <input type="hidden" name="group_id" value="<?php print($group_id); ?>">
                </td>
            </tr>
        </table>
    </form>
<?php
    }
}
else
{
    $groups = get_shopping_groups();

    $group_html = "";

    foreach($groups as $group)
    {
        $group_html .= "<tr><td><a href=\"manage_groups.php?action=edit&group_id=$group[group_id]\">edit</a></td><td>$group[group_description]</td><td>$group[group_image]</td><td>$group[display_order]</td></tr>";
    }
    ?>
    <div><a href="manage_groups.php?action=add">+ add new group</a></div>
    <table>
        <tr>
            <td>&nbsp;</td>
            <td>Group Description</td>
            <td>Group Image</td>
            <td>Display Order</td>
        </tr>
<?php print($group_html); ?>
    </table>
<?php
}
?>
</body>
</html>