<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/core.php';
include_once '../config/database.php';
include_once '../objects/product.php';

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);

$keywords = isset($_GET["s"]) ? $_GET["s"] : "";

$stmt = $product->search($keywords);
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
    http_response_code(503);
    echo json_encode(array("message " => "Unable to create product"));
}

?>