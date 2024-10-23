<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once('../../config/db.php');
    include_once('../../model/nhaCungCap.php');
    include_once '../../middleware/check_role.php';

    if(checkRole('admin')) {
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $db = new db();
            $connect = $db->connect();
        
            $nhaCungCap = new NhaCungCap($connect);
        
            // Lấy supplier_id từ URL
            if (isset($_GET['id'])) {
                $nhaCungCap->supplier_id = $_GET['id'];
            } else {
                echo json_encode(array('message' => 'supplier_id is missing'));
                exit();
            }
        
            // Lấy dữ liệu từ body request
            $data = json_decode(file_get_contents("php://input"));
        
            // Kiểm tra xem các trường cần thiết có tồn tại không
            if (isset($data->supplier_name) && isset($data->contact_email)) {
                $nhaCungCap->supplier_name = $data->supplier_name;
                $nhaCungCap->contact_email = $data->contact_email;
                $nhaCungCap->phone_number = $data->phone_number;
                $nhaCungCap->address = $data->address;
        
                // Kiểm tra xem danh mục sản phẩm có tồn tại không
                if($nhaCungCap->exists()){
                    if($nhaCungCap->update()){
                        echo json_encode(array('message' => 'nhaCungCap Updated'));
                    } else {
                        echo json_encode(array('message' => 'nhaCungCap Not Updated'));
                    }
                } else {
                    echo json_encode(array('message' => 'nhaCungCap ID does not exist'));
                }
            } else {
                echo json_encode(array('message' => 'Invalid input: category_name and description are required'));
            }
        } else{
            echo json_encode(array('message' => 'Invalid request method. Only PUT requests are allowed.'));
        }
    }
    else{
        exit();
    }


?>
