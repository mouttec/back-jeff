<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");
include_once "../../config/Database.php";
include_once "../../models/Prices.php";

$db = new Database();
$conn = $db->connect();
$price = new Price($conn);

if (isset($_GET['idPrice'])) {
    $price->idPrice = $_GET['idPrice'];
    $result = $price->searchPriceById($price);
} else if (isset($_GET['typePrice'])) {
    $price->typePrice = $_GET['typePrice'];
    $result = $price->searchPriceByType($price);
} else {
    $prices = $price->listPrices();
    $counter = $prices->rowCount();
    if ($counter > 0) {
        $prices_array = array();
        while($row = $prices->fetch()) {
            extract($row);
            $price_item = [
                "idPrice" => $idPrice,
                "typePrice" => $typePrice,
                "amount" => $amount
            ];
            array_push($prices_array, $price_item);
        }
        $result = $prices_array;
    }
}

if (isset($result) && !empty($result)) {
    echo json_encode($result);
} else { 
    http_response_code(404); 
}