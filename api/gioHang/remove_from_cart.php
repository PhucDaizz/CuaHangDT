<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: DELETE");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once '../../config/db.php';
    include_once '../../model/gioHang.php';
    include_once '../../middleware/check_role.php';

    if(checkRole('user')){
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

            $customer_id = getCustomerIdFromToken();
            $data = json_decode(file_get_contents("php://input"));

            if (!empty($customer_id) && !empty($data->product_id) ) {
                $db = new db();
                $connect = $db->connect();
                $cart = new GioHang($connect);
        
                $cart->customer_id = $customer_id;
                $cart->product_id = $data->product_id;
        
                if ($cart->removeFromCart()) {
                    http_response_code(200);
                    echo json_encode(array("message" => "Product was removed from cart."));
                } else {
                    http_response_code(503);
                    echo json_encode(array("message" => "Unable to remove product from cart."));
                }
            } else {
                http_response_code(400);
                echo json_encode(array("message" => "Unable to remove product from cart. Data is incomplete."));
            }
        }
    }
?>