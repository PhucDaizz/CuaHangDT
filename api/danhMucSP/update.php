<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once('../../config/db.php');
    include_once('../../model/danhMucSP.php');
    include_once '../../middleware/check_role.php';

    if(checkRole('admin')) {
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $db = new db();
            $connect = $db->connect();
        
            $danhMucSP = new DanhMucSp($connect);
        
            // Lấy category_id từ URL
            if (isset($_GET['category_id'])) {
                $danhMucSP->category_id = $_GET['category_id'];
            } else {
                echo json_encode(array('message' => 'category_id is missing'));
                exit();
            }
        
            // Lấy dữ liệu từ body request
            $data = json_decode(file_get_contents("php://input"));
        
            // Kiểm tra xem các trường cần thiết có tồn tại không
            if (isset($data->category_name) && isset($data->description)) {
                $danhMucSP->category_name = $data->category_name;
                $danhMucSP->description = $data->description;
        
                // Kiểm tra xem danh mục sản phẩm có tồn tại không
                if($danhMucSP->exists()){
                    if($danhMucSP->update()){
                        echo json_encode(array('message' => 'danhMucSP Updated'));
                    } else {
                        echo json_encode(array('message' => 'danhMucSP Not Updated'));
                    }
                } else {
                    echo json_encode(array('message' => 'danhMucSP ID does not exist'));
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
