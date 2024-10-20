<?php
class DanhMucSp {
    private $conn;

    public $category_id;
    public $category_name;
    public $description;

    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function read(){
        $query  = "SELECT * FROM danhmucsp ORDER BY category_id  DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function show(){
        $query = "SELECT * FROM danhmucsp WHERE category_id=? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->category_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->category_id = $row['category_id'];
        $this->category_name = $row['category_name'];
        $this->description = $row['description'];
        return $stmt;
    }

    public function create(){
        $query = "INSERT INTO danhmucsp SET category_name=:category_name, description=:description";
        $stmt = $this->conn->prepare($query);

        $this->category_name = htmlspecialchars(strip_tags($this->category_name));        
        $this->description = htmlspecialchars(strip_tags($this->description));        
    
        $stmt->bindParam('category_name', $this->category_name);
        $stmt->bindParam('description', $this->description);

        if($stmt->execute()){
            return true;
        }
        printf("Error %s.\n" .$stmt->error);
        return false;
    }

    public function update(){
        $query = "UPDATE danhMucSp SET category_name=:category_name, description=:description
        WHERE category_id=:category_id";
        $stmt = $this->conn->prepare($query);

        $this->category_name = htmlspecialchars(strip_tags($this->category_name));
        $this->description = htmlspecialchars(strip_tags($this->description));

        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':category_name', $this->category_name);
        $stmt->bindParam(':description', $this->description);

        if($stmt->execute()){
            return true;
        }
        printf("Error %s.\n" .$stmt->error);
        return false;
    }

    Public function delete(){
        $query = "DELETE FROM danhMucSp WHERE category_id=:category_id";
        $stmt = $this->conn->prepare($query);

        $this->category_id = htmlspecialchars(strip_tags($this->category_id));

        $stmt->bindParam(':category_id', $this->category_id);

        if($stmt->execute()){
            return true;
        }
        printf("Error: %s.\n", $stmt->error);
        return false;
    }


    public function exists(){
        $query = "SELECT COUNT(*) as count FROM danhmucsp WHERE category_id = :category_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $this->category_id);
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