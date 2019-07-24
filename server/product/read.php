<?php
    // required headers
    include_once '../config/database.php';
    include_once '../objects/product.php';
     
    // instantiate database and product object
    $database = new Database();
    $db = $database->getConnection();
     
    // initialize object
    $product = new Product($db);

    // read product will be here
    $stmt = $product->read();
    $num = $stmt->rowCount();

    if ($num > 0) {
        $product_arr = array();
        $product_arr["records"] = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $product_item = array(
                "id" => $id,
                "name" => $name,
                "description" => html_entity_decode($description),
                "price" => $price,
                "category_id" => $category_id,
                "category_name" => $category_name
            );

            array_push($product_arr["records"], $product_item);
        }

        http_response_code(200);
        echo json_encode($product_arr);
    } else {
 
        // set response code - 404 Not found
        http_response_code(404);
     
        // tell the user no products found
        echo json_encode(
            array("message" => "No products found.")
        );
    }

?>