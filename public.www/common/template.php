<?php
include_once "general_funcs.php";

function print_header()
{
	// get cart tag
	$cart = get_cart();
	$cart_html = "";

	if(count($cart) > 0)
	{
		$count_str = "";
	
		if(count($cart) == 1)
		{
			$count_str = "1 item in your cart";
		}
		else
		{
			$count_str = count($cart) . " items in your cart";
		}
		
		$cart_html = "<!--<a href=\"/~siam/shopping/cart.php\" title=\"$count_str\" style=\"position: relative; float: right;\"><img src=\"/~siam/img/cart_full.png\" alt=\"$count_str\" style=\"border: none;\" ></a>-->";
	}
	else
	{
		$cart_html = "<!--<a href=\"/~siam/shopping/cart.php\" title=\"Your cart is empty\" style=\"position: relative; float: right;\"><img src=\"/~siam/img/cart.png\" style=\"border: none;\" alt=\"Your cart is empty\"></a>-->";
	}

echo <<<EOH
<div style="width: 1000px; margin: 0 auto; text-align: left;"> <!-- start content -->
<div><a href="http://people.clemson.edu/~siam/" title="Clemson SIAM student chapter"><img alt="SIAM logo" src="/~siam/img/siam.png" style="display: inline; vertical-align: middle; border: none; width: 200px; height: 80px; float: left; margin-bottom: 10px;"></a><p style="font-family: Arial; font-weight: bold; font-size: 32px; text-align: center; margin-bottom: 0px; margin-top: 20px;">Society for Industrial and Applied Mathematics</p><p style="font-family: Arial; font-weight: bold; font-size: 14pt; text-align: center; margin-top: 10px;">Clemson University Student Chapter</p></div>
<hr style="color: #ff6206; clear: left;">
<div id="menu">
<ul>
	<li><a href="/~siam/">Home</a></li>
	<li><a href="/~siam/about.php">About SIAM</a></li>
	<li><a href="/~siam/galleries/list.php">Galleries</a></li>
    <li><a href="/~siam/gss/main.php">GSS</a></li>
	<li><a href="/~siam/archives.php">Archives</a></li>
	<li><a href="/~siam/links.php">Links</a></li>
	<li><a href="/~siam/shop.php">Shop</a></li>
</ul>
$cart_html
</div>
<hr style="color: #ff6206; clear: left; display: block;">
EOH;
}

function print_footer()
{
$last_modified = filemtime(basename($_SERVER['PHP_SELF']));

$update_time = date("n/j/y", $last_modified);
echo <<<EOF
<hr style="color: #ff6206;">
	<span style="float: left; text-align: left; font-size: 10pt; color: #6666CC;">Submit comments <a href="/~siam/comments.php" style="color: #FF6633;">here</a></span>
	<div style="padding: 0 10px 0 0; float: right; text-align: right;">
		<span style="font-size: 10pt; color: #6666CC;">Last updated: $update_time</span><br>
		<small style="color: rgb(102, 102, 204);">&copy; Clemson SIAM chapter</small>
	</div>
</div> <!-- end content -->
EOF;
}
?>