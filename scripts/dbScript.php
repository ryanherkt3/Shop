<?php
    // Connect to MySQL and create database:
    $conn = mysqli_connect('localhost', 'root', '');
    mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS shopdb;");

    // Create the tables:
    $db = mysqli_select_db($conn, 'shopdb');
    mysqli_query($conn, "CREATE TABLE IF NOT EXISTS items (
        itemName varchar(255) NOT NULL,
        itemDesc varchar(500),
        itemPrice float(5,2) NOT NULL,
        slug varchar(255) NOT NULL,
        PRIMARY KEY (slug)
    );");

    mysqli_query($conn, "CREATE TABLE IF NOT EXISTS purchases (
        purchaseId int NOT NULL AUTO_INCREMENT,
        slug varchar(255) NOT NULL,
        quantity int NOT NULL,
        itemPrice float(5,2) NOT NULL, 
        PRIMARY KEY (purchaseId),
        FOREIGN KEY (slug) REFERENCES items(slug)
    );");

    $itemsList = [
        [
            'Beach ball',
            'A coloured beach ball, perfect for kids',
            15.00,
            'beach-ball'
        ],
        [
            'Dell Laptop',
            '15-inch laptop with Windows 11',
            899.00,
            'dell-laptop'
        ],
        [
            'Guitar',
            'A guitar, but not one of Englebert Humperdinck\'s ten',
            79.99,
            'guitar'
        ],
        [
            'Puzzle book',
            'A puzzle book with cryptic crosswords, sudokus, logic puzzles and many more',
            25.00,
            'puzzle-book'
        ],
        [
            'Shane Warne - No Spin',
            'The definitive autobiography of the life of the late Shane Warne, co-written alongside renowned cricket commentator and former Hampshire captain Mark Nicholas',
            59.99,
            'warne-book'
        ],
        [
            'Primo 1.5L Chocolate Milk Drink',
            'A nice chocolate milk drink, perfect for the hot summer days',
            5.99,
            'primo-drink'
        ],
        [
            'Frisbee',
            'A great toy for the kids to use at the beach or the park',
            12.99,
            'frisbee'
        ],
        [
            '1kg Apples',
            'A one kilogram carton of apples',
            18.99,
            'apple-carton'
        ],
        [
            'Storage Bin',
            'A storage bin to put things in, such as clothes or toys',
            31.99,
            'storage-bin'
        ],
        [
            'Mr Bean\'s Holiday',
            'A movie where Mr Bean is in France, where he has to reunite a father with his son after mistakenly separating them at a train station',
            9.99,
            'bean-movie-1'
        ]
    ];

    // Preload database with items, if they don't exist:
    foreach ($itemsList as $item) {
        $sqlStatement = "INSERT IGNORE INTO `items` (itemName, itemDesc, itemPrice, slug) VALUES (
            '".mysqli_escape_string($conn, $item[0])."', '".mysqli_escape_string($conn, $item[1])."', '$item[2]', '$item[3]'
        );";
        
        mysqli_query($conn, $sqlStatement);
    }

    echo 'Database and table successfully created';
    exit(1);
?>
