<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");
include_once "../../config/Database.php";
include_once "../../models/TechnicalControlInvoice.php";

$db = new Database();
$conn = $db->connect();
$technicalControlInvoice = new TechnicalControlInvoice($conn);

if (isset($_GET['idTechnicalControlInvoice'])) {
    $technicalControlInvoice->idTechnicalControlInvoice = $_GET['idTechnicalControlInvoice'];
    $result = $technicalControlInvoice->searchInvoiceById($technicalControlInvoice);
} else {
    if (isset($_GET['idPartner'])) {
        $technicalControlInvoice->idPartner = $_GET['idPartner'];
        $invoices = $technicalControlInvoice->searchInvoicesByPartner($technicalControlInvoice);        
    } else {
        $technicalControlInvoices = $technicalControlInvoice->listInvoices();
    }
    $counter = $technicalControlInvoices->rowCount();
    if ($counter > 0) {
        $technicalControlInvoices_array = array();
        while ($row = $technicalControlInvoices->fetch()) {
            extract($row);
            $technicalControlInvoice_item = [
                 "idTechnicalControlInvoice" => $idTechnicalControlInvoice,
                 "idPartner" => $idPartner,
                 "monthlyInvoice" => $monthlyInvoice,
                 "urlInvoice" => $urlInvoice,
                 "priceInvoice" => $priceInvoice
            ];
            array_push($technicalControlInvoices_array, $technicalControlInvoice_item);
        }
        $result = $technicalControlInvoices_array;
        if (isset($_GET['listLength'])) {
            array_splice($result, 0, -$_GET['listLength']);
        }
    }
}

if (isset($result) && !empty($result)) {
    echo json_encode($result);
} else { 
    http_response_code(404); 
}