<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once('../../config/db.php');
    include_once('../../model/sanPham.php');

    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        $db = new db();
        $connect = $db->connect();
    
        $sanPham = new SanPham($connect);
    
        // Lấy product_id từ URL
        if (isset($_GET['id'])) {
            $sanPham->product_id = $_GET['id'];
        } else {
            echo json_encode(array('message' => 'product_id is missing'));
            exit();
        }
    
        // Lấy dữ liệu từ body request
        $data = json_decode(file_get_contents("php://input"));
    
        // Kiểm tra xem các trường cần thiết có tồn tại không
        if (isset($data->product_name) && isset($data->price) && isset($data->stock)) {
            $sanPham->product_name = $data->product_name;
            $sanPham->price = $data->price;
            $sanPham->description = $data->description;
            $sanPham->stock = $data->stock;
            $sanPham->category_id = $data->category_id;
            $sanPham->supplier_id = $data->supplier_id;
    
            // Kiểm tra xem danh mục sản phẩm có tồn tại không
            if($sanPham->exists()){
                if($sanPham->update()){
                    echo json_encode(array('message' => 'sanPham Updated'));
                } else {
                    echo json_encode(array('message' => 'sanPham Not Updated'));
                }
            } else {
                echo json_encode(array('message' => 'sanPham ID does not exist'));
            }
        } else {
            echo json_encode(array('message' => 'Invalid input: category_name and description are required'));
        }

    } else{
        echo json_encode(array('message' => 'Invalid request method. Only PUT requests are allowed.'));
    }


?>
