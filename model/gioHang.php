<?php
class GioHang {
    private $conn;
    private $table_name = "giohang";

    public $cart_id;
    public $customer_id;
    public $product_id;
    public $quantity;
    public $added_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function addToCart() {
        // Check if the product already exists in the cart
        $check_query = "SELECT cart_id, quantity FROM " . $this->table_name . 
                       " WHERE customer_id = :customer_id AND product_id = :product_id";
        
        $stmt = $this->conn->prepare($check_query);
        $stmt->bindParam(":customer_id", $this->customer_id);
        $stmt->bindParam(":product_id", $this->product_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // If the product exists, update the quantity
            $new_quantity = $result['quantity'] + $this->quantity;
            
            $query = "UPDATE " . $this->table_name . 
                     " SET quantity = :quantity WHERE cart_id = :cart_id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":quantity", $new_quantity);
            $stmt->bindParam(":cart_id", $result['cart_id']);
            
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } else {
            // If the product doesn't exist, insert a new record
            $insert_query = "INSERT INTO " . $this->table_name . "
                             (customer_id, product_id, quantity) 
                             VALUES (:customer_id, :product_id, :quantity)";
            
            $insert_stmt = $this->conn->prepare($insert_query);
            
            // Bind values
            $insert_stmt->bindParam(":customer_id", $this->customer_id);
            $insert_stmt->bindParam(":product_id", $this->product_id);
            $insert_stmt->bindParam(":quantity", $this->quantity);
            
            if ($insert_stmt->execute()) {
                return true;
            }
            return false;
        }
    }


    // Lấy giỏ hàng của khách hàng
    public function getCartByCustomer() {
        $query = "SELECT c.cart_id, c.customer_id, c.product_id, c.quantity, c.added_at,
                        p.product_name, p.price, p.thumbnail_image
                 FROM " . $this->table_name . " c
                 JOIN sanpham p ON c.product_id = p.product_id
                 WHERE c.customer_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->customer_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Cập nhật số lượng sản phẩm trong giỏ hàng
    public function updateQuantity() {
        $query = "UPDATE " . $this->table_name . 
                " SET quantity = ? WHERE cart_id = ? AND customer_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam("iii", $this->quantity, $this->cart_id, $this->customer_id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Xóa sản phẩm khỏi giỏ hàng
    public function removeFromCart() {
        $query = "DELETE FROM " . $this->table_name . 
                " WHERE customer_id =:customer_id AND product_id =:product_id";     
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":customer_id", $this->customer_id);
        $stmt->bindParam(":product_id", $this->product_id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Xóa toàn bộ giỏ hàng của khách hàng
    public function clearCart() {
        $query = "DELETE FROM " . $this->table_name . " WHERE customer_id =:customer_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":customer_id", $this->customer_id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function updateQuantities($items) {
        $query = "UPDATE " . $this->table_name . " 
                  SET quantity = :quantity 
                  WHERE customer_id = :customer_id AND product_id = :product_id";
        
        $stmt = $this->conn->prepare($query);
    
        // Loop through each item in the array and bind parameters
        foreach ($items as $item) {
            $stmt->bindParam(":quantity", $item['quantity']);
            $stmt->bindParam(":customer_id", $this->customer_id);
            $stmt->bindParam(":product_id", $item['product_id']);
    
            // Execute each update
            if (!$stmt->execute()) {
                return false;
            }
        }
        return true;
    }



}

?>