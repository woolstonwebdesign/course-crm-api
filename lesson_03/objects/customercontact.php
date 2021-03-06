<?php
class CustomerContact {

    private $conn;
    private $table_name = "customercontact";

    public $Id;
    public $FirstName;
    public $Surname;
    public $EmailAddress;
    public $CustomerId;
    public $IsVisible;
    public $CustomerName;

    public function __construct($db) {
        $this->conn = $db;
    }

    function read($customerId) {

        $query = "SELECT c.Name 'CustomerName', cc.* 
            FROM " . $this->table_name . " cc 
            INNER JOIN customer c ON c.Id = cc.CustomerId
            WHERE cc.CustomerId = " . $customerId . "
            ORDER BY cc.FirstName ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function read_one($id) {

        $query = "SELECT c.Name 'CustomerName', cc.* 
            FROM " . $this->table_name . " cc 
            INNER JOIN customer c ON c.Id = cc.CustomerId
            WHERE cc.Id = " . $id;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function create() {

        $query = "INSERT INTO " . $this->table_name . "
            SET
                FirstName = :FirstName, Surname = :Surname, 
                EmailAddress = :EmailAddress, CustomerId = :CustomerId,
                IsVisible = :IsVisible";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":FirstName", $this->FirstName);
        $stmt->bindParam(":Surname", $this->Surname);
        $stmt->bindParam(":EmailAddress", $this->EmailAddress);
        $stmt->bindParam(":CustomerId", $this->CustomerId);
        $stmt->bindParam(":IsVisible", $this->IsVisible);

        if ($stmt->execute()) {
            $this->Id = $this->conn->lastInsertId();
            return $this->Id;
        }

        return false;
    }
}