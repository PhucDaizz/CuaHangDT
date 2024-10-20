<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once('../../config/db.php');
    include_once('../../model/danhMucSP.php');

    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        // Kết nối tới cơ sở dữ liệu
        $db = new db();
        $connect = $db->connect();
    
        $danhMucSP = new DanhMucSp($connect);
    
        if(isset($_GET['category_id'])) {
            $danhMucSP->category_id  = $_GET['category_id'];
    
            if($danhMucSP->exists()) {
                if ($danhMucSP->delete()) {
                    echo json_encode(array('message' => 'danhMucSP Deleted'));
                } else {
                    echo json_encode(array('message' => 'danhMucSP Not Deleted'));
                }
            } else {
                // Nếu không tồn tại, trả về thông báo
                echo json_encode(array('message' => 'danhMucSP ID does not exist'));
            }
        } else {
            // Nếu không có ID trên URL, trả về thông báo
            echo json_encode(array('message' => 'No danhMucSP ID provided'));
        }
    } else{
        echo json_encode(array('message' => 'Invalid request method. Only DELETE requests are allowed.'));
    }


?>