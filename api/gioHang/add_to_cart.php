<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once '../../config/db.php';
    include_once '../../model/gioHang.php';
    include_once '../../middleware/check_role.php';

    if(checkRole('user')){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents("php://input"));
        
            if (!empty($data->product_id) && !empty($data->quantity)) {
                $db = new db();
                $connect = $db->connect();
                $cart = new GioHang($connect);

                $customer_id = getCustomerIdFromToken();
        
                $cart->customer_id = $customer_id;
                $cart->product_id = $data->product_id;
                $cart->quantity = $data->quantity;
        
                if ($cart->addToCart()) {
                    http_response_code(201);
                    echo json_encode(array("message" => "Product added to cart successfully."));
                } else {
                    http_response_code(503);
                    echo json_encode(array("message" => "Unable to add product to cart."));
                }
            } else {
                http_response_code(400);
                echo json_encode(array("message" => "Unable to add product to cart. Data is incomplete."));
            }
        }
    }
?>