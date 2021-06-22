<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorization, X-Requested-With");
include_once "../../config/Database.php";
include_once "../../models/Booking.php";
include_once "../../models/Customer.php";
include_once "../../models/Car.php";
include_once "../../models/Address.php";

$db = new Database();
$conn = $db->connect();
$booking = new Booking($conn);
$customer = new Customer($conn);
$car = new Car($conn);
$address = new Address($conn);

$decodedData = json_decode(file_get_contents("php://input"));

if (empty($decodedData->idCustomer)) {
    $customer->firstNameCustomer = $decodedData->firstNameCustomer;
    $customer->lastNameCustomer = $decodedData->lastNameCustomer;
    $customer->phoneCustomer = $decodedData->phoneCustomer;
    if (isset($decodedData->mailCustomer)) {
        $customer->mailCustomer = $decodedData->mailCustomer;
    } else {        
        $customer->mailCustomer = "Non renseigné";
    }
    if (isset($decodedData->dateOfBirthdayCustomer)) {
        $customer->dateOfBirthdayCustomer = $decodedData->dateOfBirthdayCustomer;
    } else {
        $customer->dateOfBirthdayCustomer = "Non renseigné";
    }
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $maxLength = strlen($chars);
    $randomStr = '';
    for ($i = 0; $i < 30; $i++) {
        $randomStr .= $chars[rand(0, $maxLength - 1)];
    }
    $customer->mixedPassword = $randomStr;
    $customer->idBillingAddress = '0';
    $customer->createCustomer($customer);
    //idCustomer créée
    $thisCustomer = new Customer($conn);
    $thisCustomer->firstNameCustomer = $decodedData->firstNameCustomer;
    $thisCustomer->lastNameCustomer = $decodedData->lastNameCustomer;
    $thisCustomer = $thisCustomer->searchCustomerByNames($thisCustomer);
} else {
    $customer->idCustomer = $decodedData->idCustomer;
    $thisCustomer = $customer->searchCustomerById($customer);
}
$customer->idCustomer = $thisCustomer['idCustomer'];

$car->idCustomer = $customer->idCustomer;
if (empty($decodedData->idCar)) {
    $car->licensePlateCar = $decodedData->licensePlateCar;
    $car->brandCar = $decodedData->brandCar;
    $car->modelCar = $decodedData->modelCar;
    $car->dateOfCirculationCar = $decodedData->dateOfCirculationCar;
    $car->motorizationCar = $decodedData->motorizationCar;
    $car->versionCar = $decodedData->versionCar;
    $car->colorCar = $decodedData->colorCar;
    $car->createCar($car);
    $thisCar = $car->searchCarByPlate($car);
    //$thisCar->idCar créé
} else {
    $car->idCar = $decodedData->idCar;
    $thisCar = $car->searchCarById($car);
}
$car->idCar = $thisCar['idCar'];
$car->bindCustomerToCar($car);

if (!empty($decodedData->addressBack)) {
    //adresse retour = partenaire > domicile client
    $address->idCustomer = $customer->idCustomer;
    $address->address = $decodedData->addressBack;
    $address->createAddress($address);
    $addressBack = $address->searchAddressId($address);
    $customer->idBillingAddress = $addressBack['idAddress'];
    $booking->idAddressBack = $addressBack['idAddress'];
    $booking->dateBack = $decodedData->dateBack;
    $booking->hoursBack = $decodedData->hoursBack;
    $booking->distanceBack = $decodedData->distanceBack;
    $booking->durationBack = $decodedData->durationBack;
} else {
    $booking->idAddressBack = NULL;
    $booking->dateBack = NULL;
    $booking->hoursBack = NULL;
    $booking->distanceBack = NULL;
    $booking->durationBack = NULL;
}

if (!empty($decodedData->addressForth)) {
    //adresse aller = domicile client > partenaire
    $address->idCustomer = $customer->idCustomer;
    $address->address = $decodedData->addressForth;
    $address->createAddress($address);
    $addressForth = $address->searchAddressId($address);
    $customer->idBillingAddress = $addressForth['idAddress'];
    $booking->idAddressForth = $addressForth['idAddress'];
    $booking->dateForth = $decodedData->dateForth;
    $booking->hoursForth = $decodedData->hoursForth;
    $booking->distanceForth = $decodedData->distanceForth;
    $booking->durationForth = $decodedData->durationForth;
} else {
    $booking->idAddressForth = NULL;
    $booking->dateForth = NULL;
    $booking->hoursForth = NULL;
    $booking->distanceForth = NULL;
    $booking->durationForth = NULL;
}

//On bind l'adresse de facturation
$customer->bindIdBillingAddress($customer);

$booking->idCustomer = $customer->idCustomer;
$booking->idPartner = $decodedData->idPartner;
$booking->formulaBooking = $decodedData->formulaBooking;
$booking->statusBooking = 'Confirmée';
$booking->idCar = $car->idCar;
$booking->idAgency = $decodedData->idAgency;
$booking->priceBooking = $decodedData->priceBooking;
$booking->originBooking = $decodedData->originBooking;
$booking->carProcces = $decodedData->carProcces;

$result = $booking->createBooking($booking);

if ($result) {
    echo json_encode([ "message" => "La réservation a été créée !" ]);
} else {     
    echo json_encode([ "message" => "La réservation n'a pas pu être créée..." ]);
}