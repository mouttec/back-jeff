<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorization, X-Requested-With");
include_once "../../config/Database.php";
include_once "../../models/Partner.php";

$db = new Database();
$conn = $db->connect();
$partner = new Partner($conn);

$decodedData = json_decode(file_get_contents("php://input"));

$partner->idPartner = $decodedData->idPartner;
$partner->statusPartner = $decodedData->statusPartner;
$result = $partner->updatePartnerStatus($partner);

if ($result) {
    echo json_encode(["message" => "Le statut du partenaire a été modifié !"]);
} else {
    echo json_encode(["message" => "Le statut du partenaire n'a pas été modifié..."]);
}
