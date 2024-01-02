Shop Project
============

A shop project (online shop), built with HTML, CSS, JS, PHP and MySQL.

Set-up
------

1. Start your server environment (I use XAMPP and turn on Apache and MySQL). If this is
your first time running this project, run ``http://localhost/Shop/scripts/dbScript.php``,
which will create the database and associated tables first.

2. Then run ``http://localhost/Shop/index.php``, now the shop project should work.

Features
--------

* Search functionality to find an item in the shop
* The ability to purchase multiple of one item
* The ability to remove a specific order from the cart, or all orders

Future improvements
-------------------

* The ability to reduce the quantity of an item bought
* Recommend 3-4 items after purchasing some

Pages
-----

* `index.php` - the main page which contains all the items available from the shop.
Each shop item is dynamically created using JS. Also has a search bar in the header,
allowing the user to search for specific shop items.
* `item.php?[item-slug]` - the item page which contains the name, price and description
for a particular item. Similar to the `index.php` shop item, the page contents are
dynamically created using JS based off the item slug. Users can add between 1 and 10
of an item to their cart.
* `cart.php` - the cart page which contains all the items a user has added to their cart.
Similar to the `index.php` shop item, each cart item is dynamically created using JS.
In this page, the user can remove specific cart orders or all of them, and purchase all
the items in their cart.

API commands/arguments
-------------
`action` - the action to perform on the database. List of valid actions:
```
atc: add to cart
rfc: remove from cart
fi: fetch item(s)
gnci: get number of cart items
gci: get item(s) in cart
purchase: purchase item(s) from cart
```

`type` - type of item to add/remove. Used with `rfc` action. List of valid types:
```
all: remove all items from the cart
[item-slug]: add/remove an item with a specific slug to/from the cart
```

`items` - what items to fetch from the cart. Used with `fi` action. List of valid items:
```
all: fetch all items from the cart
[item-slug]: fetch an item with a specific slug to/from the cart
```

`num` - number of items to add/remove to/from the cart. Used with `atc` or `rfc` actions. List of valid numbers:
* The purchase ID of the item to be removed from the cart
* The quantity of item to be purchased