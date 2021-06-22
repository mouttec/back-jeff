<?php
class Booking {

    private $conn;
    private $table = "bookings";

    public $idBooking;
    public $idCustomer;
    public $idPartner;
    public $hoursForth;
    public $dateForth;
    public $statusBooking;
    public $formulaBooking;
    public $dateBack;
    public $hoursBack;
    public $idCar;
    public $idAddressForth;
    public $idAddressBack;
    public $idAgency;
    public $distanceForth;
    public $durationForth;
    public $distanceBack;
    public $durationBack;
    public $originBooking;
    public $priceBooking;
    public $carProcces;

    public function __construct($db) 
    {
        $this->conn = $db;
    }

    public function createBooking() 
    {
        if (!is_null($this->idAddressForth) && !is_null($this->idAddressBack)) {
            $query = "
            INSERT INTO "
                . $this->table .
                " SET
                idCustomer = :idCustomer,
                idPartner = :idPartner,
                hoursForth = :hoursForth,
                dateForth = :dateForth,
                statusBooking = :statusBooking,
                formulaBooking = :formulaBooking,
                dateBack = :dateBack,
                hoursBack = :hoursBack,        
                idCar = :idCar,
                idAddressForth = :idAddressForth,
                idAddressBack = :idAddressBack,
                idAgency = :idAgency,
                distanceForth = :distanceForth,
                durationForth = :durationForth,
                distanceBack = :distanceBack,
                durationBack = :durationBack,
                priceBooking = :priceBooking,
                originBooking = :originBooking,
                carProcces = :carProccess
                ";
            $stmt = $this->conn->prepare($query);

            $params = [
                "idCustomer" => htmlspecialchars(strip_tags($this->idCustomer)),
                "idPartner" => htmlspecialchars(strip_tags($this->idPartner)),
                "hoursForth" => htmlspecialchars(strip_tags($this->hoursForth)),
                "dateForth" => htmlspecialchars(strip_tags($this->dateForth)),
                "statusBooking" => htmlspecialchars(strip_tags($this->statusBooking)),
                "formulaBooking" => htmlspecialchars(strip_tags($this->formulaBooking)),
                "dateBack" => htmlspecialchars(strip_tags($this->dateBack)),
                "hoursBack" => htmlspecialchars(strip_tags($this->hoursBack)),
                "idCar" => htmlspecialchars(strip_tags($this->idCar)),
                "idAddressForth" => htmlspecialchars(strip_tags($this->idAddressForth)),
                "idAddressBack" => htmlspecialchars(strip_tags($this->idAddressBack)),
                "idAgency" => htmlspecialchars(strip_tags($this->idAgency)),
                "distanceForth" => htmlspecialchars(strip_tags($this->distanceForth)),
                "durationForth" => htmlspecialchars(strip_tags($this->durationForth)),
                "distanceBack" => htmlspecialchars(strip_tags($this->distanceBack)),
                "durationBack" => htmlspecialchars(strip_tags($this->durationBack)),
                "priceBooking" => htmlspecialchars(strip_tags($this->priceBooking)),
                "originBooking" => htmlspecialchars(strip_tags($this->originBooking)),
                "carProcces" => htmlspecialchars(strip_tags($this->carProcces))
            ];

            if($stmt->execute($params)) {
                return true;
            }
            return false;
        }
        elseif (!is_null($this->idAddressForth)) {
            $query = "
                INSERT INTO "
                . $this->table .
                " SET
                idCustomer = :idCustomer,
                idPartner = :idPartner,
                hoursForth = :hoursForth,
                dateForth = :dateForth,
                statusBooking = :statusBooking,
                formulaBooking = :formulaBooking,
                idCar = :idCar,
                idAddressForth = :idAddressForth,
                idAgency = :idAgency,
                distanceForth = :distanceForth,
                durationForth = :durationForth,
                priceBooking = :priceBooking,
                originBooking = :originBooking,
                carProcces =:carProcces
                ";
            $stmt = $this->conn->prepare($query);

            $params = [
                "idCustomer" => htmlspecialchars(strip_tags($this->idCustomer)),
                "idPartner" => htmlspecialchars(strip_tags($this->idPartner)),
                "hoursForth" => htmlspecialchars(strip_tags($this->hoursForth)),
                "dateForth" => htmlspecialchars(strip_tags($this->dateForth)),
                "statusBooking" => htmlspecialchars(strip_tags($this->statusBooking)),
                "formulaBooking" => htmlspecialchars(strip_tags($this->formulaBooking)),
                "idCar" => htmlspecialchars(strip_tags($this->idCar)),
                "idAddressForth" => htmlspecialchars(strip_tags($this->idAddressForth)),
                "idAgency" => htmlspecialchars(strip_tags($this->idAgency)),
                "distanceForth" => htmlspecialchars(strip_tags($this->distanceForth)),
                "durationForth" => htmlspecialchars(strip_tags($this->durationForth)),
                "priceBooking" => htmlspecialchars(strip_tags($this->priceBooking)),
                "originBooking" => htmlspecialchars(strip_tags($this->originBooking)),
                "carProcces" => htmlspecialchars(strip_tags($this->carProcces))
            ];

            if($stmt->execute($params)) {
                return true;
            }
            return false;
        }
        elseif (!is_null($this->idAddressBack)) {
            $query = "
                INSERT INTO "
                . $this->table .
                " SET
                idCustomer = :idCustomer,
                idPartner = :idPartner,
                statusBooking = :statusBooking,
                formulaBooking = :formulaBooking,
                dateBack = :dateBack,
                hoursBack = :hoursBack,        
                idCar = :idCar,
                idAddressBack = :idAddressBack,
                idAgency = :idAgency,
                distanceBack = :distanceBack,
                durationBack = :durationBack,
                priceBooking = :priceBooking,
                originBooking = :originBooking,
                carProcces =:carProcces
                ";
            $stmt = $this->conn->prepare($query);

            $params = [
                "idCustomer" => htmlspecialchars(strip_tags($this->idCustomer)),
                "idPartner" => htmlspecialchars(strip_tags($this->idPartner)),
                "statusBooking" => htmlspecialchars(strip_tags($this->statusBooking)),
                "formulaBooking" => htmlspecialchars(strip_tags($this->formulaBooking)),
                "dateBack" => htmlspecialchars(strip_tags($this->dateBack)),
                "hoursBack" => htmlspecialchars(strip_tags($this->hoursBack)),
                "idCar" => htmlspecialchars(strip_tags($this->idCar)),
                "idAddressBack" => htmlspecialchars(strip_tags($this->idAddressBack)),
                "idAgency" => htmlspecialchars(strip_tags($this->idAgency)),
                "distanceBack" => htmlspecialchars(strip_tags($this->distanceBack)),
                "durationBack" => htmlspecialchars(strip_tags($this->durationBack)),
                "priceBooking" => htmlspecialchars(strip_tags($this->priceBooking)),
                "originBooking" => htmlspecialchars(strip_tags($this->originBooking)),
                "carProcces" => htmlspecialchars(strip_tags($this->carProcces))
            ];

            if($stmt->execute($params)) {
                return true;
            }
            return false;
        } else {
            return false;
        }


    }

    public function listBookings() 
    {
        $query = "
            SELECT *
            FROM "
            . $this->table . " 
            ORDER BY
            idBooking DESC";
        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        return $stmt;
    }

    public function searchBookingById() 
    {
        $query = "
        SELECT *
        FROM "
        . $this->table . " 
        WHERE idBooking = :idBooking";
        $stmt = $this->conn->prepare($query);

        $params = ["idBooking" => htmlspecialchars(strip_tags($this->idBooking))];

        if($stmt->execute($params)) {
            $row = $stmt->fetch();
            return $row;
        }
        return false;
    }

    public function searchBookingsByPartner() 
    {
        $query = "
        SELECT *
        FROM bookings
        WHERE idPartner = :idPartner";
        $stmt = $this->conn->prepare($query);

        $params = ["idPartner" => htmlspecialchars(strip_tags($this->idPartner))];

        if($stmt->execute($params)) {
            return $stmt;
        }
        return false;
    }

    public function searchBookingsByCustomer() 
    {
        $query = "
        SELECT *
        FROM bookings
        WHERE idCustomer = :idCustomer
        ORDER BY dateBack ASC, dateForth ASC";
        $stmt = $this->conn->prepare($query);

        $params = ["idCustomer" => htmlspecialchars(strip_tags($this->idCustomer))];

        if($stmt->execute($params)) {
            return $stmt;
        }
        return false;
    }

    public function searchBookingsByAgency() 
    {
        $query = "
        SELECT *
        FROM bookings
        WHERE idAgency = :idAgency";
        $stmt = $this->conn->prepare($query);

        $params = ["idAgency" => htmlspecialchars(strip_tags($this->idAgency))];

        if($stmt->execute($params)) {
            return $stmt;
        }
        return false;
    }

    public function searchBookingsByDay() 
    {
        $query = "
        SELECT *
        FROM bookings
        WHERE dateForth = :dateForth
        ORDER BY hoursForth ASC";
        $stmt = $this->conn->prepare($query);

        $params = ["dateForth" => htmlspecialchars(strip_tags($this->dateForth))];

        if($stmt->execute($params)) {
            return $stmt;
        }
        return false;
    }

    public function searchBookingsForCalendar() 
    {
        /*$query = "
        SELECT *
        FROM bookings
        WHERE (idAgency = :idAgency AND dateForth >= :startDate AND dateForth <= :endDate)";
        $stmt = $this->conn->prepare($query);
        $params = [
            "idAgency" => htmlspecialchars(strip_tags($this->idAgency)),
            "startDate" => date('Y-m-d'),
            "endDate" => date('Y-m-d', strtotime('+60 days'))
        ];*/
        /* */
        $query = "
            SELECT *
            FROM bookings
            WHERE dateForth BETWEEN :startDate AND :endDate OR dateBack BETWEEN :startDate AND :endDate";
        $stmt = $this->conn->prepare($query);

        $params = [
            "startDate" => date('Y-m-d'),
            "endDate" => date('Y-m-d', strtotime('+60 days'))
        ];

        if($stmt->execute($params)) {
            return $stmt;
        }
        return false;
    }
 
    public function updateBooking() 
    {
        $query = "
            UPDATE "
            . $this->table .
            " SET
            idCustomer = :idCustomer,
            idPartner = :idPartner,
            hoursForth = :hoursForth,
            dateForth = :dateForth,
            statusBooking = :statusBooking,
            formulaBooking = :formulaBooking,
            dateBack = :dateBack,
            hoursBack = :hoursBack,        
            idCar = :idCar,
            idAddressForth = :idAddressForth,
            idAddressBack = :idAddressBack,
            idAgency = :idAgency,
            distanceForth = :distanceForth,
            durationForth = :durationForth,
            distanceBack = :distanceBack,
            durationBack = :durationBack,
            priceBooking = :priceBooking
            WHERE
            idBooking = :idBooking       
        ";
        $stmt = $this->conn->prepare($query);

        $params = [
            "idCustomer" => htmlspecialchars(strip_tags($this->idCustomer)),
            "idPartner" => htmlspecialchars(strip_tags($this->idPartner)),
            "hoursForth" => htmlspecialchars(strip_tags($this->hoursForth)),
            "dateForth" => htmlspecialchars(strip_tags($this->dateForth)),
            "statusBooking" => htmlspecialchars(strip_tags($this->statusBooking)),
            "formulaBooking" => htmlspecialchars(strip_tags($this->formulaBooking)),
            "dateBack" => htmlspecialchars(strip_tags($this->dateBack)),
            "hoursBack" => htmlspecialchars(strip_tags($this->hoursBack)),
            "idCar" => htmlspecialchars(strip_tags($this->idCar)),
            "idAddressForth" => htmlspecialchars(strip_tags($this->idAddressForth)),
            "idAddressBack" => htmlspecialchars(strip_tags($this->idAddressBack)),
            "idAgency" => htmlspecialchars(strip_tags($this->idAgency)),
            "distanceForth" => htmlspecialchars(strip_tags($this->distanceForth)),
            "durationForth" => htmlspecialchars(strip_tags($this->durationForth)),
            "distanceBack" => htmlspecialchars(strip_tags($this->distanceBack)),
            "durationBack" => htmlspecialchars(strip_tags($this->durationBack)),
            "priceBooking" => htmlspecialchars(strip_tags($this->priceBooking)),
            "idBooking" => htmlspecialchars(strip_tags($this->idBooking))
        ];

        if($stmt->execute($params)) {
            return true;
        }
        return false;
    }

    public function updateBookingStatus() 
    {
        $query = "
            UPDATE "
            . $this->table .
            " SET
            statusBooking = :statusBooking,
            WHERE
            idBooking = :idBooking       
        ";
        $stmt = $this->conn->prepare($query);

        $params = [
            "statusBooking" => htmlspecialchars(strip_tags($this->statusBooking)),
            "idBooking" => htmlspecialchars(strip_tags($this->idBooking))
        ];

        if($stmt->execute($params)) {
            return true;
        }
        return false;        
    }

    public function updateBookingCarProcces() 
    {
        $query = "
            UPDATE "
            . $this->table .
            " SET
            carProcces = :carProcces,
            WHERE
            idBooking = :idBooking       
        ";
        $stmt = $this->conn->prepare($query);

        $params = [
            "carProcces" => htmlspecialchars(strip_tags($this->carProcces)),
            "idBooking" => htmlspecialchars(strip_tags($this->idBooking))
        ];

        if($stmt->execute($params)) {
            return true;
        }
        return false;        
    }
    public function cancelBooking() 
    {
        $query = "
            UPDATE "
            . $this->table .
            " SET
            statusBooking = :statusBooking,
            WHERE
            idBooking = :idBooking       
        ";
        $stmt = $this->conn->prepare($query);

        $params = [
            "statusBooking" => 'cancelled',
            "idBooking" => htmlspecialchars(strip_tags($this->idBooking))
        ];

        if($stmt->execute($params)) {
            return true;
        }
        return false; 
    }
}