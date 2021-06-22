<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
include_once "../../config/Database.php";
include_once "../../models/PartnerInvoices.php";

$db = new Database();
$conn = $db->connect();
$partnerInvoice = new PartnerInvoices($conn);

$decodedData = json_decode(file_get_contents("php://input"));

// if (isset($decodedData->idInvoice)) {
//     $partnerInvoice->idInvoice = $decodedData->idInvoice;
//     $result = $custo->searchInvoiceById($partnerInvoice);
// } else {
//     $partnerInvoice->idPartner = $decodedData->idPartner;
//     $partnerInvoice->invoiceDate = $decodedData->invoiceDate;
//     if ((isset($decodedData->idPartner)) && (isset($decodedData->invoiceDate))) {
//         $invoices = $partnerInvoice->listInvoicesByPartnerAndMonth($partnerInvoice);
//     } elseif (isset($decodedData->invoiceDate)) {
//         $invoices = $partnerInvoice->listInvoicesByMonth($partnerInvoice);        
//     } elseif (isset($decodedData->idPartner)) {
//         $invoices = $partnerInvoice->listInvoicesPartner($partnerInvoice);        
//     } else {
//         $invoices = $partnerInvoice->listInvoices();
//     }
//     $counter = $invoices->rowCount();
//     if ($counter > 0) {
//         $invoices_array = array();
//         while($row = $invoices->fetch()) {
//             extract($row);
//             $invoice_item = [
//                 "idInvoice" => $idInvoice,
//                 "invoiceNumber" => $invoiceNumber,
//                 "invoiceLines" => $invoiceLines,
//                 "amountInvoice" => $amountInvoice,
//                 "invoiceDate" => $invoiceDate,
//                 "idPartner" => $idPartner,
//                 "idCustomer" => $idCustomer,
//                 "idBooking" => $idBooking,
//                 "idContract" => $idContract,
//                 "idCar" => $idCar
//             ];
//             array_push($invoices_array, $invoice_item);
//         }
//         $result = $invoices_array;
//     }
// }

if (isset($_GET['idInvoice'])) {
    $partnerInvoice->idInvoice = $_GET['idInvoice'];
    $thisPartnerInvoice = $partnerInvoice->searchInvoiceById();
} else {
    if (isset($_GET['idPartner'])) {
        $partnerInvoice->idPartner = $_GET['idPartner'];
        $partnerInvoices = $partnerInvoice->listInvoicesByPartner($partnerInvoice);
    } elseif (isset($_GET['invoiceDate'])) {
        $partnerInvoice->invoiceDate = $_GET['invoiceDate'];
        $partnerInvoices = $partnerInvoice->listInvoicesByMonth($partnerInvoice);
    } else {
        $partnerInvoices = $partnerInvoice->listInvoices();
    }
    $counter = $partnerInvoices->rowCount();
    if ($counter > 0) {
        $invoices_array = array();
        while($row = $partnerInvoices->fetch()) {
            extract($row);
            $invoice_item = [
                "idInvoice" => $idInvoice,
                "invoiceNumber" => $invoiceNumber,
                "invoiceLines" => $invoiceLines,
                "amountInvoice" => $amountInvoice,
                "invoiceDate" => $invoiceDate,
                "idPartner" => $idPartner,
                "idCustomer" => $idCustomer,
                "idBooking" => $idBooking,
                "idContract" => $idContract,
                "idCar" => $idCar
            ];
            array_push($invoices_array, $invoice_item);
        }
        $result = $invoices_array;
    }
}

if (isset($result) && !empty($result)) {
    echo json_encode($result);
} else { 
    http_response_code(404); 
}