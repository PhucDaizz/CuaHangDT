<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once('../../config/db.php');
    include_once('../../model/sanPham.php');
    include_once '../../middleware/check_role.php';


    if(checkRole('admin')) {
        // Kiểm tra nếu phương thức là DELETE
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            // Kết nối tới cơ sở dữ liệu
            $db = new db();
            $connect = $db->connect();
    
            $sanPham = new SanPham($connect);
    
            // Kiểm tra nếu có ID được cung cấp
            if (isset($_GET['id'])) {
                $sanPham->product_id = $_GET['id'];
    
                // Kiểm tra nếu sản phẩm tồn tại
                if ($sanPham->exists()) {
                    // Nếu tồn tại, tiến hành xoá
                    if ($sanPham->delete()) {
                        echo json_encode(array('message' => 'sanPham Deleted'));
                    } else {
                        echo json_encode(array('message' => 'sanPham Not Deleted'));
                    }
                } else {
                    // Nếu không tồn tại, trả về thông báo
                    echo json_encode(array('message' => 'sanPham ID does not exist'));
                }
            } else {
                // Nếu không có ID trên URL, trả về thông báo
                echo json_encode(array('message' => 'No sanPham ID provided'));
            }
        } else {
            // Nếu không phải là phương thức DELETE, trả về thông báo lỗi
            echo json_encode(array('message' => 'Invalid request method. Only DELETE requests are allowed.'));
        }
    }
    else {
        exit();
    }

    
?>
