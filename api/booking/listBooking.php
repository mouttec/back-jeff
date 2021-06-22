<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");
include_once "../../config/Database.php";
include_once "../../models/Booking.php";
include_once "../../models/Customer.php";
include_once "../../models/Car.php";

$db = new Database();
$conn = $db->connect();
$booking = new Booking($conn);
$customer = new Customer($conn);
$car = new Car($conn);

if (isset($_GET['idBooking'])) {
    $booking->idBooking = $_GET['idBooking'];
    $thisBooking = $booking->searchBookingById();
    $customer->idCustomer = $thisBooking['idCustomer'];
    $thisCustomer = $customer->searchCustomerById($customer);
    $car->idCar = $thisBooking['idCar'];
    $thisCar = $car->searchCarById($car);
    $result = array();
    array_push($result, $thisBooking, $thisCustomer, $thisCar);
} else {
    if (isset($_GET['idPartner'])) {
        $booking->idPartner = $_GET['idPartner'];
        $bookings = $booking->searchBookingsByPartner($booking);
    } elseif (isset($_GET['idCustomer'])) {
        $booking->idCustomer = $_GET['idCustomer'];
        $bookings = $booking->searchBookingsByCustomer($booking);
    } elseif (isset($_GET['idAgency'])) {
        $booking->idAgency = $_GET['idAgency'];
        $bookings = $booking->searchBookingsByAgency($booking);
    } elseif (isset($_GET['dateBooking'])) {
        $booking->dateBooking = $_GET['dateBooking'];
        $bookings = $booking->searchBookingsByDay($booking);
    } else {
        $bookings = $booking->listBookings();
    }
    $counter = $bookings->rowCount();
    if ($counter > 0) {
        $bookings_array = array();
        while ($row = $bookings->fetch()) {
            extract($row);
            if ($statusBooking = 'confirme') {
                if (!is_null($dateForth)) {
                    $datecode = implode('-', array_reverse(explode('/', $dateForth)));
                    $timecode = str_replace(':', '', $hoursForth);
                } else {
                    $datecode = implode('-', array_reverse(explode('/', $dateBack))); 
                    $timecode = str_replace(':', '', $hoursBack);               
                }
                $booking_item = [
                         "idBooking" => $idBooking,
                         "idCustomer" => $idCustomer,
                         "idPartner" => $idPartner,
                         "hoursForth" => $hoursForth,
                         "dateForth" => $dateForth,
                         "statusBooking" => $statusBooking,
                         "formulaBooking" => $formulaBooking,
                         "dateBack" => $dateBack,
                         "hoursBack" => $hoursBack,
                         "idCar" => $idCar,
                         "idAddressForth" => $idAddressForth,
                         "idAddressBack" => $idAddressBack,
                         "idAgency" => $idAgency,
                         "distanceForth" => $distanceForth,
                         "durationForth" => $durationForth,
                         "distanceBack" => $distanceBack,
                         "durationBack" => $durationBack,
                         "originBooking" => $originBooking,
                         "carProcess" => $carProcces,
                         "dateBooking" => $dateBooking,
                         "datecode" => $datecode,
                         "timecode" => $timecode
                ];
                $customer->idCustomer = $idCustomer;
                $thisCustomer = $customer->searchCustomerById($customer);
                $car->idCar = $idCar;
                $thisCar = $car->searchCarById($car);
                $booking_item['customerData'] = $thisCustomer;
                $booking_item['carData'] = $thisCar;
                // array_push($booking_item, $thisCustomer, $thisCar);
                array_push($bookings_array, $booking_item);
            }
        }
        if ($counter > 1) {
            $date  = array_column($bookings_array, 'datecode');
            $time = array_column($bookings_array, 'timecode');
            array_multisort($date, SORT_ASC, $time, SORT_ASC, $bookings_array);
        }
        $result = $bookings_array;
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