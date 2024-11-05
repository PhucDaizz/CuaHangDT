<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: PUT");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once '../../config/db.php';
    include_once '../../model/gioHang.php';
    include_once '../../middleware/check_role.php';

    if(checkRole('user')){
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $data = json_decode(file_get_contents("php://input"), true);
            
            $customer_id = getCustomerIdFromToken();
            
            if (!empty($data['items']) && !empty($customer_id) && is_array($data['items'])) {
                $db = new db();
                $connect = $db->connect();
                $cart = new GioHang($connect);


                $cart->customer_id = $customer_id;
                $items = $data['items'];
        
                if ($cart->updateQuantities($items)) {
                    http_response_code(200);
                    echo json_encode(array("message" => "Cart quantity was updated."));
                } else {
                    http_response_code(503);
                    echo json_encode(array("message" => "Unable to update cart quantity."));
                }
            } else {
                http_response_code(400);
                echo json_encode(array("message" => "Unable to update cart quantity. Data is incomplete."));
            }
        }
    }

?>