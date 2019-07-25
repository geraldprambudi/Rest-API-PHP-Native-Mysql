<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/core.php';
include_once '../shared/utilities.php';
include_once '../config/database.php';
include_once '../objects/product.php';
 

    $utilities = new Utilities();
    $database = new Database();
    $db = $database->getConnection();

    $product = new Product($db);

    // Query product
    $stmt = $product->readPaging($from_record_num, $records_per_page);
    
    $num = $stmt->rowCount();

    if ($num > 0) {
        $product_arr = array();
        $product_arr["records"] = array();
        $product_arr["paging"]  = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $product_item = array (
                "id" => $id,
                "name" => $name,
                "description" => html_entity_decode($description),
                "price" => $price,
                "category_id" => $category_id,
                "category_name" => $category_name
            );

            array_push($product_arr["records"], $product_item);
        }

        $total_rows = $product->count();
        $page_url   = "{$home_url}product/read_paging.php?";
        $paging = $utilities->getPaging($page, $total_rows, $record_per_page, $page_url);
        $product_arr["paging"] = $paging;

        http_response_code(200);

        echo json_encode($product_arr);
    } else {
        http_response_code(404);
        echo json_encode(array("message " => "No product found. "));
    }

?>