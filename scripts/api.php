<?php 
$apiRequest = json_decode(file_get_contents('php://input'), true);
$sqlStatement = '';

// Check if DB (and table) exists
$conn = mysqli_connect('localhost', 'root', '', '');
$val = mysqli_query($conn, "SHOW DATABASES LIKE 'shopdb'");

if (mysqli_num_rows($val) === 0) {
    echo json_encode(array('errMsg' => 'Run scripts/dbScript.php to create the database first!'));
    http_response_code(400);
    exit(-1);
}
else {
    $db = mysqli_select_db($conn, 'shopdb');
}

// Check if API request and action argument exists
if (!$apiRequest || !$apiRequest['action']) {
    echo json_encode(array('errMsg' => 'Invalid request to API!'));
    http_response_code(400);
    exit(-1);
}
else if ($apiRequest['action'] === 'fi' || $apiRequest['action'] === 'gci') {
    $sqlStatement = "SELECT * FROM items";

    if ($apiRequest['action'] === 'gci') {
        $sqlStatement = "SELECT items.slug, items.itemName, items.itemDesc, purchases.quantity, purchases.itemPrice, purchases.purchaseId 
            FROM purchases
            INNER JOIN items ON purchases.slug=items.slug
        ";
    }
    else if (!$apiRequest['items']) {
        echo json_encode(array('errMsg' => 'Items argument not supplied in fi request!'));
        http_response_code(400);
        exit(-1);
    }
    else if ($apiRequest['items'] !== 'all') {
        // Check if items argument is a valid shop item
        $slugs = mysqli_query($conn, "SELECT slug FROM items");

        $rows = mysqli_fetch_all($slugs);
        $slugsArray = array();

        foreach ($rows as $row) {
            foreach ($row as $key => $value) {
                array_push($slugsArray, $value);
            }
        }

        if (in_array($apiRequest['items'], $slugsArray)) {
            $sqlStatement = $sqlStatement . " WHERE slug = '$apiRequest[items]'";
        }
        else {
            echo json_encode(array('errMsg' => 'Attempted to fetch non-existent item!'));
            http_response_code(400);
            exit(-1);
        }
    }
}
else if ($apiRequest['action'] === 'gnci') {
    $sqlStatement = "SELECT * FROM purchases";
}
else if ($apiRequest['action'] === 'atc') {
    $sqlStatement = "INSERT INTO purchases (slug, quantity, itemPrice) VALUES (
        '$apiRequest[type]', '$apiRequest[num]', '$apiRequest[price]'
    );";
}
else if ($apiRequest['action'] === 'rfc') {
    $sqlStatement = "DELETE FROM purchases";

    if (!$apiRequest['type']) {
        echo json_encode(array('errMsg' => 'Item to remove from cart not supplied!'));
        exit(-1);
    }
    else if ($apiRequest['type'] !== 'all') {
        // Check if items argument is a valid shop item
        $slugs = mysqli_query($conn, "SELECT slug FROM items");

        $rows = mysqli_fetch_all($slugs);
        $slugsArray = array();

        foreach ($rows as $row) {
            foreach ($row as $key => $value) {
                array_push($slugsArray, $value);
            }
        }

        if (in_array($apiRequest['type'], $slugsArray)) {
            $sqlStatement = "DELETE FROM purchases 
                WHERE slug = '$apiRequest[type]' AND purchaseId = '$apiRequest[num]'";
        }
        else {
            echo json_encode(array('errMsg' => 'Attempted to remove non-existent item from cart!'));
            http_response_code(400);
            exit(-1);
        }
    }
}
else if ($apiRequest['action'] === 'purchase') {
    $sqlStatement = "DELETE FROM purchases";
}
else {
    echo json_encode(array('errMsg' => 'Invalid API action supplied!'));
    http_response_code(400);
    exit(-1);
}

$result = mysqli_query($conn, $sqlStatement);

if ($apiRequest['action'] === 'purchase' ||
    ($apiRequest['action'] === 'rfc' && $apiRequest['type'] === 'all')) {
    $result = mysqli_query($conn, "ALTER TABLE purchases AUTO_INCREMENT = 1");
}

if ($apiRequest['action'] === 'atc' ||
    ($apiRequest['action'] === 'rfc' && $apiRequest['type'] === 'all')) {
    echo json_encode(array("success" => 1));
}
else {
    // Send the array back as a JSON object
    $rows = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    echo json_encode($rows);
}
?>
