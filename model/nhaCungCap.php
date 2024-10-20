<?php
class NhaCungCap {
    private $conn;

    public $supplier_id;
    public $supplier_name;
    public $contact_email;
    public $phone_number;
    public $address;


    public function __construct($db) {
        $this->conn = $db;
    }


    public function read() {
        $query = "SELECT * FROM nhacungcap ORDER BY supplier_id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function show(){
        $query = "SELECT * FROM nhacungcap WHERE supplier_id=? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->supplier_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->supplier_id = $row['supplier_id'];
        $this->supplier_name = $row['supplier_name'];
        $this->contact_email = $row['contact_email'];
        $this->phone_number = $row['phone_number'];
        $this->address = $row['address'];
        return $stmt;
    }

    public function create(){
        $query = "INSERT INTO nhacungcap SET supplier_name=:supplier_name, contact_email=:contact_email, phone_number=:phone_number, address=:address";
        $stmt = $this->conn->prepare($query);

        $this->supplier_name = htmlspecialchars(strip_tags($this->supplier_name));        
        $this->contact_email = htmlspecialchars(strip_tags($this->contact_email));    
        $this->phone_number = htmlspecialchars(strip_tags($this->phone_number));        
        $this->address = htmlspecialchars(strip_tags($this->address));        

    
        $stmt->bindParam('supplier_name', $this->supplier_name);
        $stmt->bindParam('contact_email', $this->contact_email);
        $stmt->bindParam('phone_number', $this->phone_number);
        $stmt->bindParam('address', $this->address);

        if($stmt->execute()){
            return true;
        }
        printf("Error %s.\n" .$stmt->error);
        return false;
    }
    
    public function update(){
        $query = "UPDATE nhacungcap SET supplier_name=:supplier_name, contact_email=:contact_email, phone_number=:phone_number, address=:address
        WHERE supplier_id=:supplier_id";
        $stmt = $this->conn->prepare($query);

        $this->supplier_name = htmlspecialchars(strip_tags($this->supplier_name));
        $this->contact_email = htmlspecialchars(strip_tags($this->contact_email));
        $this->phone_number = htmlspecialchars(strip_tags($this->phone_number));
        $this->address = htmlspecialchars(strip_tags($this->address));

        $stmt->bindParam(':supplier_id', $this->supplier_id);
        $stmt->bindParam(':supplier_name', $this->supplier_name);
        $stmt->bindParam(':contact_email', $this->contact_email);
        $stmt->bindParam(':phone_number', $this->phone_number);
        $stmt->bindParam(':address', $this->address);

        if($stmt->execute()){
            return true;
        }
        printf("Error %s.\n" .$stmt->error);
        return false;
    }

    public function delete(){
        $query = "DELETE FROM nhacungcap WHERE supplier_id=:supplier_id";
        $stmt = $this->conn->prepare($query);

        $this->supplier_id = htmlspecialchars(strip_tags($this->supplier_id));

        $stmt->bindParam(':supplier_id', $this->supplier_id);

        if($stmt->execute()){
            return true;
        }
        printf("Error: %s.\n", $stmt->error);
        return false;
    }


    public function exists(){
        $query = "SELECT COUNT(*) as count FROM nhacungcap WHERE supplier_id = :supplier_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':supplier_id', $this->supplier_id);
        $stmt->execute();
    
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Nếu tìm thấy ít nhất một bản ghi, trả về true
        if($row['count'] > 0){
            return true;
        }
    
        return false;
    }

}


?>
