<?php
    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once('../../config/db.php');
    include_once('../../model/danhMucSP.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $db = new db();
        $connect = $db->connect();
        $danhMucSP = new DanhMucSp($connect);
    
        $data = json_decode(file_get_contents("php://input"));
    
        // Kiểm tra xem các dữ liệu có null hoặc không tồn tại không
        if (isset($data->category_name) && isset($data->description) && 
            !empty($data->category_name) && !empty($data->description)) {
            
            // Gán dữ liệu nếu tất cả đều hợp lệ
            $danhMucSP->category_name = $data->category_name;
            $danhMucSP->description = $data->description;
    
            // Thực hiện tạo mới
            if($danhMucSP->create()){
                echo json_encode(array("message" => "Done"));
            } else {
                echo json_encode(array("message" => "Failed to create"));
            }
    
        } else {
            // Trả về thông báo lỗi nếu có dữ liệu null hoặc rỗng
            echo json_encode(array("message" => "Invalid input: category_id, category_name, and description are required"));
        }
    } else{
        echo json_encode(array('message' => 'Invalid request method. Only POST requests are allowed.'));
    }

?>
