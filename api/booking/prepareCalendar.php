<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorization, X-Requested-With");
include_once "../../config/Database.php";
include_once "../../models/Booking.php";
include_once "../../models/Customer.php";

$db = new Database();
$conn = $db->connect();
$booking = new Booking($conn);
$customer = new Customer($conn);


if (isset($_GET['idPartner'])) {
    $booking->idPartner = $_GET['idPartner'];
    $bookings = $booking->searchBookingsByPartner($booking);
} else {
    $bookings = $booking->listBookings();
}
$counter = $bookings->rowCount();
if ($counter > 0) {
    $calendar = array();
    while ($row = $bookings->fetch()) {
        extract($row);
        if ($statusBooking == 'confirme') {
            $customer->idCustomer = $idCustomer;
            $thisCustomer = $customer->searchCustomerById($customer);
            if (!is_null($dateForth)) {
                $dateForth =  implode('-', array_reverse(explode('/', $dateForth)));
                $datetimeStart = $dateForth.' '.$hoursForth;
                $durationDelay = round(($durationForth+20)/15)*15;
                $datetimeEnd = $dateForth.' '.date('H:i:s', strtotime($hoursForth.' +'.$durationDelay.' minutes'));
                switch ($formulaBooking) {
                    case 'technicalControl':
                        $color = ['yellow' => ['primary' => '#A1A1A1', 'secondary' => '#A1A1A1']];
                        break;
                    case 'round':
                        $color = ['blue' => ['primary' => '#B1B1B1’', 'secondary' => '#B1B1B1’']];
                        break;
                    case 'forth':
                        $color = ['orange' => ['primary' => '#EF7B15’', 'secondary' => '#EF7B15’']];
                        break;
                    case 'back':
                        $color = ['orange' => ['primary' => '#EF7B15’', 'secondary' => '#EF7B15’']];
                        break;
                    default:
                        $color = [];
                        break;
                }
                $booking_item = [
                    'fullNameCustomer' => $thisCustomer['firstNameCustomer'].' '.$thisCustomer['lastNameCustomer'],
                    'start' => $datetimeStart,
                    'end' => $datetimeEnd,
                    'idBooking' => $idBooking,
                    'idPartner' => $idPartner,
                    'formulaBooking' => $formulaBooking,
                    'color' => $color
                ];
                array_push($calendar, $booking_item);
            }
            if (!is_null($dateBack)) {
                $dateBack =  implode('-', array_reverse(explode('/', $dateBack)));
                $datetimeStart = $dateBack.' '.$hoursBack;
                $durationDelay = round(($durationBack+20)/15)*15;
                $datetimeEnd = $dateBack.' '.date('H:i:s', strtotime( $hoursBack.' +'.$durationDelay.' minutes'));
                switch ($formulaBooking) {
                    case 'technicalControl':
                        $color = ['yellow' => ['primary' => '#A1A1A1', 'secondary' => '#A1A1A1']];
                        break;
                    case 'round':
                        $color = ['blue' => ['primary' => '#B1B1B1’', 'secondary' => '#B1B1B1’']];
                        break;
                    case 'forth':
                        $color = ['orange' => ['primary' => '#EF7B15’', 'secondary' => '#EF7B15’']];
                        break;
                    case 'back':
                        $color = ['orange' => ['primary' => '#EF7B15’', 'secondary' => '#EF7B15’']];
                        break;
                    default:
                        $color = [];
                        break;
                }
                $booking_item = [
                    'fullNameCustomer' => $thisCustomer['firstNameCustomer'].' '.$thisCustomer['lastNameCustomer'],
                    'start' => $datetimeStart,
                    'end' => $datetimeEnd,
                    'idBooking' => $idBooking,
                    'idPartner' => $idPartner,
                    'formulaBooking' => $formulaBooking,
                    'color' => $color
                ];
                array_push($calendar, $booking_item);
            }
        }
    }
}

if (isset($calendar) && !empty($calendar)) {
    echo json_encode($calendar);
} else { 
    http_response_code(404); 
}