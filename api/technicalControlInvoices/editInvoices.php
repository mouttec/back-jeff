<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorization, X-Requested-With");
include_once "../../config/Database.php";
include_once "../../models/TechnicalControlInvoice.php";

$db = new Database();
$conn = $db->connect();
$technicalControlInvoice = new TechnicalControlInvoice($conn);

$decodedData = json_decode(file_get_contents("php://input"));

$technicalControlInvoice->idPartner = $decodedData->idPartner;
$technicalControlInvoice->monthlyInvoice = $decodedData->monthlyInvoice;
$technicalControlInvoice->priceInvoice = $decodedData->priceInvoice;

$uploadDirectory = 'factureTEchnicalControl/';
$extensions = [
    'jpg',
    'jpeg',
    'png',
    'gif'
];

echo json_encode($_FILES['saveName']);
echo json_encode($_FILES['saveName']['error']);


if (isset($_FILES) && !empty($_FILES['image'])) {
    echo json_encode('isset$_FILES ok');
    $extension = strtolower(pathinfo($_FILES['image']['name'],PATHINFO_EXTENSION));
    if (in_array($extension, $extensions)) {
		$saveName = htmlspecialchars(strip_tags($decodedData->idPartner)).'-'.htmlspecialchars(strip_tags($decodedData->monthlyInvoice)).'-'.uniqid().$extension;
		move_uploaded_file($_FILES['image']['tmp_name'], '../../' . $uploadDirectory . $saveName);
		$technicalControlInvoice->urlInvoice = $saveName;
    } else {
        echo json_encode('Le format de l\'image '. $_FILES['image']['name'] .' n\'est pas bon');
    }
}

    $result = $technicalControlInvoice->createInvoice($technicalControlInvoice);

if ($result) {
    echo json_encode([ "message" => "La facture a été éditée !" ]);
}  else { 
    echo json_encode([ "message" => "La facture n'a pas pu être éditée..." ]);
}