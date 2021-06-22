<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorization, X-Requested-With");

include_once("../../config/Database.php");
include_once("../../models/Partner.php");
include_once "../../models/UserSession.php";

$db = new Database();
$conn = $db->connect();
$partner = new Partner($conn);

$decodedData = json_decode(file_get_contents("php://input"));
$partner->usernamePartner = $decodedData->usernamePartner;
$password = htmlspecialchars($decodedData->password);

$partnerExists = $partner->searchPartnerByUsername($partner);

//Si un partner existe avec cet username et que le password matche
if (!empty($partnerExists)) {
	if (password_verify($password, $partnerExists['mixedPassword'])) {
		$session = new UserSession();
		$session->userId = $partnerExists['idPartner'];
		$session->userStatus = 'partner';
		$session->userName = $partner->usernamePartner;
		$session->create($session);
		echo json_encode($partnerExists);
	} else {
		echo json_encode('Le mot de passe est erroné');
	}
} else {
	echo json_encode('Le partnenaire n\'existe pas');
}