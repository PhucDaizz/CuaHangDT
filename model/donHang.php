<?php
class DonHang {
    private $conn;
    private $table_name = "donhang";

    public $order_id;
    public $customer_id;
    public $order_date;
    public $total_amount;
    public $status;
    public $shipping_address;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function createOrder() {
        $query = "INSERT INTO " . $this->table_name . " (customer_id, total_amount, status, shipping_address) 
                  VALUES (:customer_id, :total_amount, :status, :shipping_address)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":customer_id", $this->customer_id);
        $stmt->bindParam(":total_amount", $this->total_amount);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":shipping_address", $this->shipping_address);

        if ($stmt->execute()) {
            $this->order_id = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }

    public function getAllOrderById(){
        $query = "SELECT order_date, total_amount, status, shipping_address FROM " .$this->table_name. " WHERE customer_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->customer_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}

?>