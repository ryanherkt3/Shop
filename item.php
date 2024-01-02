<!DOCTYPE html>
<html lang="en">
	<head>
		<?php echo file_get_contents('html/head.html') ?>
	</head>
	<body>
		<?php echo file_get_contents('html/header.html') ?>

		<div class="shop-item"></div>

		<div class="quantity">
			<input type="number" class="quantity-input" min="1" value="1" max="10"></input>
			<div class="button add-to-cart">Add to Cart</div>
		</div>
	</body>
</html>
