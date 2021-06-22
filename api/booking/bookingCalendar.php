<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorization, X-Requested-With");
include_once "../../config/Database.php";
include_once "../../models/Booking.php";
include_once "../../models/Teammate.php";

$db = new Database();
$conn = $db->connect();
$booking = new Booking($conn);
$teammate = new Teammate($conn);

$decodedData = json_decode(file_get_contents("php://input"));

//Recherche du nombre de teammates "drivers" disponibles dans l'agence concernée 
// if (isset($decodedData->idAgency)) {
//     $teammate->idAgency = $decodedData->idAgency;
//     $teammates = $teammate->searchTeammatesByAgency($teammate);
//     $counter = $teammates->rowCount();
//     if ($counter > 0) {
//         $driversInAgency = 0;
//         while($row = $teammates->fetch()) {
//             extract($row);
//             if (($jobTeammate == 'driver') && ($statusTeammate == 1)) {
//                 $driversInAgency += 1;
//             }
//         }
//     }
// } else {
//     $driversInAgency = 1;
// }

// $quarters = ['00', '15', '30', '45'];
// $shifts = array(); 
// for ($h = 7; $h <= 20; $h++) {
//     foreach ($quarters as $quarter) {
//         if (strlen($h) == 1) {
//             $timecode = '0'.$h.':'.$quarter;
//         } else {
//             $timecode = $h.':'.$quarter;
//         }
//         //On retire les créaneaux non disponibles dans le calendrier
//         if (($timecode != '07:00') && ($timecode != '07:15') && ($timecode != '20:30') && ($timecode != '20:45')) {
//             $shifts[$timecode] = $driversInAgency;
//         }
//     }
// }

$quarters = ['30'];
$shiftsAvailable = array(); 
//Modification des plages d'heures : $h = heure mini, $h <= heure maxi
for ($h = 7; $h <= 18; $h++) {
    foreach ($quarters as $quarter) {
        if (strlen($h) == 1) {
            $shift = '0'.$h.':'.$quarter;
        } else {
            $shift = $h.':'.$quarter;
        }
        //On retire les créaneaux non disponibles dans le calendrier
        if ($shift != '12:30') {
           array_push($shiftsAvailable, $shift);
        }
    }
}

$calendar = array();
$closureDays = ['Saturday', 'Sunday'];
$year = intval(strftime('%Y'));
$easterDate = easter_date($year);
$easterDay = date('j', $easterDate);
$easterMonth = date('n', $easterDate);
$easterYear = date('Y', $easterDate);
$holidays = array(
        // Jours fériés fixes
        mktime(0, 0, 0, 1, 1, $year),// 1er janvier
        mktime(0, 0, 0, 5, 1, $year),// Fête du travail
        mktime(0, 0, 0, 5, 8, $year),// Victoire des alliés
        mktime(0, 0, 0, 7, 14, $year),// Fête nationale
        mktime(0, 0, 0, 8, 15, $year),// Assomption
        mktime(0, 0, 0, 11, 1, $year),// Toussaint
        mktime(0, 0, 0, 11, 11, $year),// Armistice
        mktime(0, 0, 0, 12, 25, $year),// Noël
        // Jour feries qui dépendent de Pâques
        mktime(0, 0, 0, $easterMonth, $easterDay + 2, $easterYear),// Lundi de Pâques
        mktime(0, 0, 0, $easterMonth, $easterDay + 40, $easterYear),// Ascension
        mktime(0, 0, 0, $easterMonth, $easterDay + 51, $easterYear),// Pentecôte
);
// Formatage au format Y-m-d des dates
foreach ($holidays as $key => $value) {
    $holidays[$key] = date('Y-m-d', $value);    
}

for ($i = 0; $i < 60; $i++) {
    $day = array();
    $day = [
        "idBookingCalendar" => $i+1,
        "dateBookingCalendar" => date('Y-m-d', strtotime('+'.$i.' days')),
    ];
    foreach ($shiftsAvailable as $key => $shift) {
        $day['h'.($key+1).'bookingCalendar'] = $shift.'-'.date('Y-m-d', strtotime('+'.$i.' days'));
    }
    if(!in_array(date('l', strtotime('+'.$i.' days')), $closureDays) && !in_array(date('Y-m-d', strtotime('+'.$i.' days')), $holidays))  {
        array_push($calendar, $day);
    }
}

// $booking->idAgency = $decodedData->idAgency;
$bookings = $booking->searchBookingsForCalendar();
$counter = $bookings->rowCount();
if ($counter > 0) {
    while ($row = $bookings->fetch()) {
        extract($row);
        if ($statusBooking == 'confirme') {
            if (!is_null($dateForth)) {
                //On donne à $dateForth le format YYYY-MM-DD
                // $dateForth =  implode('-', array_reverse(explode('/', $dateForth)));
                //$durationDelayInQuarters correspond au nombre de quart d'heure que prend la presta
                // $durationDelayInQuarters = round(($durationForth+20)/15);
                // for ($i = 0; $i <= $durationDelayInQuarters; $i++) {
                //     $calendar[$dateForth][date('H:i', strtotime($hoursForth.' +'.$i*15.' minutes'))] -= 1;
                // }
                $hoursForth = substr($hoursForth, 0, 5);
                $dayKey = array_search($dateForth, array_column($calendar, 'dateBookingCalendar'));
                $hourKey = array_search($hoursForth.'-'.$dateForth, $calendar[$dayKey]);
                $calendar[$dayKey][$hourKey] = 'Réservé';
            }
            if (!is_null($dateBack)) {
                // $dateBack =  implode('-', array_reverse(explode('/', $dateBack)));
                // $durationDelayInQuarters = round(($durationBack+20)/15);
                // for ($i = 0; $i <= $durationDelayInQuarters; $i++) {
                //     $calendar[$dateBack][date('H:i', strtotime($hoursBack.' +'.$i*15.' minutes'))] -= 1;
                // }
                $hoursBack = substr($hoursBack, 0, 5);
                $dayKey = array_search($dateBack, array_column($calendar, 'dateBookingCalendar'));
                $hourKey = array_search($hoursBack.'-'.$dateBack, $calendar[$dayKey]);
                $calendar[$dayKey][$hourKey] = 'Réservé';
            }
        }
    }
}

if (isset($calendar) && !empty($calendar)) {
    echo json_encode($calendar);
} else { 
    http_response_code(404); 
}