<?php
class BookingCalendar {
    private $conn;
    private $table = "bookingcalendar";
    public $idBookingCalendar;
    public $datebookingCalendar;
    public $h1bookingCalendar;
    public $h2bookingCalendar;
    public $h3bookingCalendar;
    public $h4bookingCalendar;
    public $h5bookingCalendar;
    public $h6bookingCalendar;
    public $h7bookingCalendar;
    public $h8bookingCalendar;
    public $h9bookingCalendar;
    public $h10bookingCalendar;
    public $h11bookingCalendar;
    public function __construct($db) {
        $this->conn = $db;
    }
    public function listCalendar() {
        $query = "
            SELECT *
            FROM "
            . $this->table;
        $stmt = $this->conn->prepare($query);
        if ($stmt->execute()) {
            return $stmt;
        }
        return false;
    }
    public function searchCalendarById() {
        $query = "
        SELECT *
        FROM "
        . $this->table . " 
        WHERE idBookingCalendar = :idBookingCalendar
        LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $params = ["idBookingCalendar" => htmlspecialchars(strip_tags($this->idBookingCalendar))];
        if ($stmt->execute($params)) {
            $row = $stmt->fetch();    
            return $row;
        }
        return false;
    }
}