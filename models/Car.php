<?php
class Car 
{
    private $conn;
    private $table = "cars";

    public $idCar;
    public $idCustomer;
    public $licensePlateCar;
    public $brandCar;
    public $modelCar;
    public $dateOfCirculationCar;
    public $motorizationCar;
    public $colorCar;
    public $versionCar;
    public $urlGrayCard;

    public function __construct($db) 
    {
        $this->conn = $db;
    }

    public function createCar() 
    {
        $query = "
            INSERT INTO "
            . $this->table .
            " SET
            licensePlateCar = :licensePlateCar,
            brandCar = :brandCar,
            modelCar = :modelCar,
            dateOfCirculationCar = :dateOfCirculationCar,
            motorizationCar = :motorizationCar,
            colorCar = :colorCar,
            versionCar = :versionCar
        ";
        $response = "SELECT idCar FROM " .$this->table." ORDER BY idCar DESC LIMIT 1";
        $stmt = $this->conn->prepare($query);

        $params = [
            "licensePlateCar" => htmlspecialchars(strip_tags($this->licensePlateCar)),
            "brandCar" => htmlspecialchars(strip_tags($this->brandCar)),
            "modelCar" => htmlspecialchars(strip_tags($this->modelCar)),
            "dateOfCirculationCar" => htmlspecialchars(strip_tags($this->dateOfCirculationCar)),
            "motorizationCar" => htmlspecialchars(strip_tags($this->motorizationCar)),
            "colorCar" => htmlspecialchars(strip_tags($this->colorCar)),
            "versionCar" => htmlspecialchars(strip_tags($this->versionCar))
        ];

        if($stmt->execute($params)) {
            $resp = $this->conn->prepare($response);
            $resp->execute();
            return $resp;
        }
        return false;
    }

    public function listCars() 
    {
        $query = "
        SELECT *
        FROM "
        . $this->table;
        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }

    public function searchCarsByCustomer() 
    {
        $query = "
            SELECT *
            FROM " . $this->table . "
            WHERE idCustomer = :idCustomer
            ORDER BY idCar ASC";
        $stmt = $this->conn->prepare($query);

        $params = ["idCustomer" => htmlspecialchars(strip_tags($this->idCustomer))];

        if($stmt->execute($params)) {
            return $stmt;
        }
        return false;
    }

    public function searchCarByPlate() 
    {
        $query = "
            SELECT *
            FROM " . $this->table . "
            WHERE licensePlateCar = :licensePlateCar";
        $stmt = $this->conn->prepare($query);

        $params = ["licensePlateCar" => htmlspecialchars(strip_tags($this->licensePlateCar))];

        if($stmt->execute($params)) {
            $row = $stmt->fetch();
            return $row;
        }
        return false;
    }

    public function searchCarById() 
    {
        $query = "
            SELECT *
            FROM " . $this->table . "
            WHERE idCar = :idCar";
        $stmt = $this->conn->prepare($query);

        $params = ["idCar" => htmlspecialchars(strip_tags($this->idCar))];

        if($stmt->execute($params)) {
            $row = $stmt->fetch();
            return $row;
        }
        return false;
    }

    public function addGrayCardToCar()
    {
        $query = "
            UPDATE "
            . $this->table .
            " SET
            urlGrayCard = :urlGrayCard
            WHERE
            licensePlateCar = :licensePlateCar       
        ";
        $stmt = $this->conn->prepare($query);

        $params = [
            "urlGrayCard" => htmlspecialchars(strip_tags($this->urlGrayCard)),
            "licensePlateCar" => htmlspecialchars(strip_tags($this->licensePlateCar))
        ];

        if($stmt->execute($params)) {
            return true;
        }
        return false;
    }

    public function bindCustomerToCar() 
    {
        $query = "
            UPDATE "
            . $this->table .
            " SET
            idCustomer = :idCustomer
            WHERE
            licensePlateCar = :licensePlateCar       
        ";
        $stmt = $this->conn->prepare($query);

        $params = [
            "idCustomer" => htmlspecialchars(strip_tags($this->idCustomer)),
            "licensePlateCar" => htmlspecialchars(strip_tags($this->licensePlateCar))
        ];

        if($stmt->execute($params)) {
            return true;
        }
        return false;
    }

    public function updateCar() 
    {
        $query = "
            UPDATE "
            . $this->table .
            " SET
            idCustomer = :idCustomer,
            licensePlateCar = :licensePlateCar,
            brandCar = :brandCar,
            modelCar = :modelCar,
            dateOfCirculationCar = :dateOfCirculationCar,
            motorizationCar = :motorizationCar,
            colorCar = :colorCar,
            versionCar = :versionCar
            WHERE
            idCar = :idCar       
        ";
        $stmt = $this->conn->prepare($query);

        $params = [
            "idCustomer" => htmlspecialchars(strip_tags($this->idCustomer)),
            "licensePlateCar" => htmlspecialchars(strip_tags($this->licensePlateCar)),
            "brandCar" => htmlspecialchars(strip_tags($this->brandCar)),
            "modelCar" => htmlspecialchars(strip_tags($this->modelCar)),
            "dateOfCirculationCar" => htmlspecialchars(strip_tags($this->dateOfCirculationCar)),
            "motorizationCar" => htmlspecialchars(strip_tags($this->motorizationCar)),
            "colorCar" => htmlspecialchars(strip_tags($this->colorCar)),
            "versionCar" => htmlspecialchars(strip_tags($this->versionCar)),
            "idCar" => htmlspecialchars(strip_tags($this->idCar))
        ];

        if($stmt->execute($params)) {
            return true;
        }
        return false;
    }

    public function unbindCarFromCustomer() 
    {
        $query = "
            UPDATE "
            . $this->table .
            " SET
            idCustomer = :idCustomer
            WHERE
            idCar = :idCar       
        ";
        $stmt = $this->conn->prepare($query);

        $params = [
            "idCustomer" => "",
            "idCar" => htmlspecialchars(strip_tags($this->idCar))
        ];

        if($stmt->execute($params)) {
            return true;
        }
        return false;         
    }   
}