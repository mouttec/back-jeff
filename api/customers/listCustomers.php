<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");
include_once "../../config/Database.php";
include_once "../../models/Customer.php";
include_once "../../models/Address.php";

$db = new Database();
$conn = $db->connect();
$customer = new Customer($conn);
$address = new Address($conn);

if (isset($_GET['idCustomer'])) {
    $customer->idCustomer = $_GET['idCustomer'];
    $thisCustomer = $customer->searchCustomerById($customer);
    $address->idAddress = $thisCustomer['idBillingAddress'];
    $thisAddress = $address->searchAddress($address);
    $thisCustomer['billingAddress'] = $thisAddress['address'];
    $result = $thisCustomer;
} else {
    if (isset($_GET['idPartner'])) {
        $customer->idPartner = $_GET['idPartner'];
        $customers = $customer->searchCustomersByPartner($customer);
    } else if (isset($_GET['statusCustomer'])) {
		$customer->statusCustomer = $_GET['statusCustomer'];
		$customers = $customer->listCustomerStatus($customer);
	} else {
        $customers = $customer->listCustomers();
    }
    $counter = $customers->rowCount();
    if ($counter > 0) {
        $customers_array = array();
        while ($row = $customers->fetch()) {
            extract($row);
            $address->idAddress = $idBillingAddress;
            $billingAddress = $address->searchAddress($address);
            $customer_item = [
                 "idCustomer" => $idCustomer,
                 "billingAddress" => $billingAddress['address'],
                 "firstNameCustomer" => $firstNameCustomer,
                 "lastNameCustomer" => $lastNameCustomer,
                 "dateOfBirthdayCustomer" => $dateOfBirthdayCustomer,
                 "phoneCustomer" => $phoneCustomer,
                 "mailCustomer" => $mailCustomer
            ];
            array_push($customers_array, $customer_item);
        }
        $result = $customers_array;
    }
}

if (isset($result) && !empty($result)) {
    echo json_encode($result);
} else { 
    http_response_code(404); 
}