<?php
class SanPham {
    private $conn;

    public $product_id;
    public $product_name;
    public $description;
    public $price;
    public $stock;
    public $category_id;
    public $supplier_id;
    public $created_at;
    public $updated_at;
    public $thumbnail_image;    // Ảnh đại diện
    public $detail_image1;      // Ảnh chi tiết 1
    public $detail_image2;      // Ảnh chi tiết 2
    public $detail_image3;      // Ảnh chi tiết 3

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read($page = 1, $limit = 20, $sort_by='product_id', $sort_order = 'DESC') {
        $offset = ($page - 1) * $limit;
        $allowed_sort_columns = ['product_id', 'product_name', 'price', 'created_at'];
        $allowed_sort_orders = ['ASC', 'DESC'];

        $sort_by = in_array($sort_by, $allowed_sort_columns) ? $sort_by : 'product_id';
        $sort_order = in_array(strtoupper($sort_order), $allowed_sort_orders) ? strtoupper($sort_order) : 'DESC';

        $query = "SELECT * FROM sanpham ORDER BY $sort_by $sort_order LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    public function show(){
        $query = "SELECT * FROM sanpham WHERE product_id=? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->product_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->product_id = $row['product_id'];
        $this->product_name = $row['product_name'];
        $this->description = $row['description'];
        $this->price = $row['price'];
        $this->stock = $row['stock'];
        $this->category_id = $row['category_id'];
        $this->supplier_id = $row['supplier_id'];
        $this->created_at = $row['created_at'];
        $this->updated_at = $row['updated_at'];
        $this->thumbnail_image = isset($row['thumbnail_image']) ? $row['thumbnail_image'] : null;
        $this->detail_image1 = isset($row['detail_image1']) ? $row['detail_image1'] : null;
        $this->detail_image2 = isset($row['detail_image2']) ? $row['detail_image2'] : null;
        $this->detail_image3 = isset($row['detail_image3']) ? $row['detail_image3'] : null;
        return $stmt;
    }

    public function getTotalCount(){
        $query = "SELECT COUNT(*) as total FROM sanpham";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function create(){
        $query = "INSERT INTO sanpham SET product_name=:product_name, description=:description, price=:price, stock=:stock, 
                    category_id=:category_id, supplier_id=:supplier_id, thumbnail_image=:thumbnail_image, detail_image1=:detail_image1, detail_image2=:detail_image2, detail_image3=:detail_image3 ,created_at=:created_at, updated_at=:updated_at ";
        $stmt = $this->conn->prepare($query);

        $this->product_name = htmlspecialchars(strip_tags($this->product_name));        
        $this->description = htmlspecialchars(strip_tags($this->description));    
        $this->price = htmlspecialchars(strip_tags($this->price));        
        $this->stock = htmlspecialchars(strip_tags($this->stock));        
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));        
        $this->supplier_id = htmlspecialchars(strip_tags($this->supplier_id));              
        $this->created_at = htmlspecialchars(strip_tags($this->created_at));        
        $this->updated_at = htmlspecialchars(strip_tags($this->updated_at));        

    
        $stmt->bindParam('product_name', $this->product_name);
        $stmt->bindParam('description', $this->description);
        $stmt->bindParam('price', $this->price);
        $stmt->bindParam('stock', $this->stock);
        $stmt->bindParam('category_id', $this->category_id);
        $stmt->bindParam('supplier_id', $this->supplier_id);
        $stmt->bindParam('thumbnail_image', $this->thumbnail_image);
        $stmt->bindParam('detail_image1', $this->detail_image1);
        $stmt->bindParam('detail_image2', $this->detail_image2);
        $stmt->bindParam('detail_image3', $this->supplier_id);
        $stmt->bindParam('created_at', $this->created_at);
        $stmt->bindParam('updated_at', $this->updated_at);

        if($stmt->execute()){
            return true;
        }
        printf("Error %s.\n" .$stmt->error);
        return false;
    }

    public function delete(){
        $query = "DELETE FROM sanpham WHERE product_id=:product_id";
        $stmt = $this->conn->prepare($query);

        $this->product_id = htmlspecialchars(strip_tags($this->product_id));

        $stmt->bindParam(':product_id', $this->product_id);

        try {
            // Thực thi truy vấn
            if($stmt->execute()){
                return true;  // Xóa thành công
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();  // In ra lỗi nếu có
        }
    
        return false;  // Xóa không thành công
    }

    public function update(){
        $query = "UPDATE sanpham 
          SET product_name = :product_name, 
              price = :price, 
              description = :description, 
              stock = :stock, 
              category_id = :category_id, 
              supplier_id = :supplier_id, 
              updated_at = :updated_at 
          WHERE product_id = :product_id";
        $stmt = $this->conn->prepare($query);

        $this->product_name = htmlspecialchars(strip_tags($this->product_name));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->stock = htmlspecialchars(strip_tags($this->stock));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->supplier_id = htmlspecialchars(strip_tags($this->supplier_id));
        $this->updated_at = date('Y-m-d H:i:s');

        $stmt->bindParam(':product_id', $this->product_id);
        $stmt->bindParam(':product_name', $this->product_name);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':stock', $this->stock);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':supplier_id', $this->supplier_id);
        $stmt->bindParam(':updated_at', $this->updated_at);

        if($stmt->execute()){
            return true;
        }
        printf("Error %s.\n" .$stmt->error);
        return false;
    }

    public function exists(){
        $query = "SELECT COUNT(*) as count FROM sanpham WHERE product_id = :product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_id', $this->product_id);
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