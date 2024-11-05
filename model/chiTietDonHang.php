<?php
class ChiTietDonHang {
    private $conn;
    private $table_name = "chitietdonhang";

    public $order_detail_id;
    public $order_id;
    public $product_id;
    public $quantity;
    public $unit_price;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function createOrderDetail() {
        $query = "INSERT INTO " . $this->table_name . " (order_id, product_id, quantity, unit_price) 
                  VALUES (:order_id, :product_id, :quantity, :unit_price)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":order_id", $this->order_id);
        $stmt->bindParam(":product_id", $this->product_id);
        $stmt->bindParam(":quantity", $this->quantity);
        $stmt->bindParam(":unit_price", $this->unit_price);

        return $stmt->execute();
    }
}



?>