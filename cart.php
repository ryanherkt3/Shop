<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Cart | Shop Project</title>
		<?php echo file_get_contents('html/head.html') ?>
	</head>
	<body>
		<?php echo file_get_contents('html/header.html') ?>

		<div class="item-list">
			<div class="key hidden">
				<div class="placeholder"></div>
				<div class="item-name">Item Name</div>
				<div class="item-price">Price</div>
			</div>
		</div>

		<div class="cart-actions hidden">
			<div class="button empty-cart">Empty Cart</div>
			<div class="button purchase-items">Purchase Items</div>
		</div>
		<div class="no-items-found center hidden"></div>
	</body>
</html>
