<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorization, X-Requested-With");
include_once "../../config/Database.php";
include_once "../../models/CarProcess.php";

$db = new Database();
$conn = $db->connect();
$carProcess = new CarProcess($conn);

$decodedData = json_decode(file_get_contents("php://input"));

$carProcess->idPartner = $decodedData->idPartner;
$carProcess->idBooking = $decodedData->idBooking;
$carProcess->idAgency = $decodedData->idAgency;
$carProcess->carStatus = $decodedData->carStatus;

if (!empty($decodedData->idProcess)) {
	$carProcess->idProcess = $decodedData->idProcess;
	$result = $carProcess->updateCarProcessStatus($carProcess);
} else { 
    $result = $carProcess->createNewCarProcess($carProcess);
}

if ($result) {
    echo json_encode(["message" => "L'agence a été éditée !"]);
} else {
    echo json_encode(["message" => "L'agence n'a pas pu être éditée..."]);
}