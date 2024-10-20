<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once('../../config/db.php');
    include_once('../../model/nhaCungCap.php');

    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        // Kết nối tới cơ sở dữ liệu
        $db = new db();
        $connect = $db->connect();
    
        $nhacungCap = new NhaCungCap($connect);
    
        if(isset($_GET['id'])) {
            $nhacungCap->supplier_id  = $_GET['id'];
    
            if($nhacungCap->exists()) {
                if ($nhacungCap->delete()) {
                    echo json_encode(array('message' => 'nhacungCap Deleted'));
                } else {
                    echo json_encode(array('message' => 'nhacungCap Not Deleted'));
                }
            } else {
                // Nếu không tồn tại, trả về thông báo
                echo json_encode(array('message' => 'nhacungCap ID does not exist'));
            }
        } else {
            // Nếu không có ID trên URL, trả về thông báo
            echo json_encode(array('message' => 'No nhacungCap ID provided'));
        }

    } else{
        echo json_encode(array('message' => 'Invalid request method. Only DELETE requests are allowed.'));
    }


?>