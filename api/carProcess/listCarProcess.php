<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");
include_once "../../config/Database.php";
include_once "../../models/CarProcess.php";

$db = new Database();
$conn = $db->connect();
$carProcess = new CarProcess($conn);

if (isset($_GET['idProcess'])) {
    $carProcess->idProcess = $_GET['idProcess'];
    $result = $carProcess->searchCarProcessById($carProcess);
} elseif (isset($_GET['idBooking'])) {
    $carProcess->idBooking = $_GET['idBooking'];
    $result = $carProcess->listCarProcessesByBooking($carProcess);
} else {
    if (isset($_GET['idPartner'])) {
        $carProcess->idPartner = $_GET['idPartner'];
        $carsProcess = $carProcess->listCarProcessesByPartner($carProcess);
    } else {
        $carsProcess = $carProcess->listCarsInProcess();
    }
    $counter = $carsProcess->rowCount();
    if ($counter > 0) {
        $carProcess_array = array();
        while ($row = $carsProcess->fetch()) {
            extract($row);
            $carProcess_item = [
                 "idProcess" => $idProcess,
                 "idCar" => $idCar,
                 "idPartner" => $idPartner,
                 "idBooking" => $idBooking,
                 "idAgency" => $idAgency,
                 "carStatus" => $carStatus
            ];
            array_push($carProcess_array, $carProcess_item);
        }
        $result = $carProcess_array;
    }
}

if (isset($result) && !empty($result)) {
    echo json_encode($result);
} else { 
    http_response_code(404); 
}