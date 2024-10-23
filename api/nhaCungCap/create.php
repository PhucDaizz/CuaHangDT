<?php
    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once('../../config/db.php');
    include_once('../../model/nhaCungCap.php');
    include_once '../../middleware/check_role.php';

    if(checkRole('admin')) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = new db();
            $connect = $db->connect();
            $nhaCungCap = new NhaCungCap($connect);
        
            $data = json_decode(file_get_contents("php://input"));
        
            // Kiểm tra xem các dữ liệu có null hoặc không tồn tại không
            if (isset($data->supplier_name) && isset($data->contact_email) && 
                !empty($data->supplier_name) && !empty($data->contact_email)) {
                
                // Gán dữ liệu nếu tất cả đều hợp lệ
                $nhaCungCap->supplier_name = $data->supplier_name;
                $nhaCungCap->contact_email = $data->contact_email;
                $nhaCungCap->phone_number = $data->phone_number;
                $nhaCungCap->address = $data->address;
        
                // Thực hiện tạo mới
                if($nhaCungCap->create()){
                    echo json_encode(array("message" => "Done"));
                } else {
                    echo json_encode(array("message" => "Failed to create"));
                }
        
            } else {
                // Trả về thông báo lỗi nếu có dữ liệu null hoặc rỗng
                echo json_encode(array("message" => "Invalid input: supplier_name , contact_email are required"));
            }
        } else{
            echo json_encode(array('message' => 'Invalid request method. Only POST requests are allowed.'));
        }
    }
    else{
        exit();
    }


?>
