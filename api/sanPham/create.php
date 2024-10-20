<?php
    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once('../../config/db.php');
    include_once('../../model/sanPham.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $db = new db();
        $connect = $db->connect();
        $sanPham = new SanPham($connect);
    
        $data = json_decode(file_get_contents("php://input"));
    
        // Kiểm tra xem các dữ liệu có null hoặc không tồn tại không
        if (isset($data->product_name) && isset($data->price) &&  isset($data->stock) &&
            !empty($data->product_name) && !empty($data->price && !empty($data->stock))) {
            
            // Gán dữ liệu nếu tất cả đều hợp lệ
            $sanPham->product_name = $data->product_name;
            $sanPham->description = $data->description;
            $sanPham->price = $data->price;
            $sanPham->stock = $data->stock;
            $sanPham->category_id = $data->category_id;
            $sanPham->supplier_id = $data->supplier_id;
            $sanPham->created_at = $data->created_at;
            $sanPham->updated_at = $data->updated_at;
    
            // Thực hiện tạo mới
            if($sanPham->create()){
                echo json_encode(array("message" => "Done"));
            } else {
                echo json_encode(array("message" => "Failed to create"));
            }
    
        } else {
            // Trả về thông báo lỗi nếu có dữ liệu null hoặc rỗng
            echo json_encode(array("message" => "Invalid input: product_name , price are required"));
        }

    } else {
        echo json_encode(array('message' => 'Invalid request method. Only POST requests are allowed.'));
    }

?>
