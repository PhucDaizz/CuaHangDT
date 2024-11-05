<?php
include_once __DIR__ . '/../config/core.php';
include_once __DIR__ . '/../vendor/firebase/php-jwt/src/BeforeValidException.php';
include_once __DIR__ . '/../vendor/firebase/php-jwt/src/ExpiredException.php';
include_once __DIR__ . '/../vendor/firebase/php-jwt/src/SignatureInvalidException.php';
include_once __DIR__ . '/../vendor/firebase/php-jwt/src/JWT.php';
include_once __DIR__ . '/../vendor/firebase/php-jwt/src/Key.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function checkRole($required_role) {
    global $key; // từ config/core.php
    
    $headers = apache_request_headers();
    // $jwt = isset($headers['Authorization']) ? $headers['Authorization'] : '';
    $jwt = isset($headers['Authorization']) ? trim(str_replace('Bearer', '', $headers['Authorization'])) : '';


    if($jwt) {
        try {
            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
            
            if($decoded->data->role == $required_role) {
                return true;
            } else {
                http_response_code(403);
                echo json_encode(array("message" => "Access denied. Requires " . $required_role . " role."));
                return false;
            }
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(array(
                "message" => "Access denied.",
                "error" => $e->getMessage()
            ));
            return false;
        }
    } else {
        http_response_code(401);
        echo json_encode(array("message" => "Access denied."));
        return false;
    }
}
function getCustomerIdFromToken() {
    global $key;
    // Lấy token từ header
    $headers = getallheaders();
    $jwt = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : null;
    
    if (!$jwt) {
        throw new Exception('Token không tồn tại');
    }
    
    try {
        // Giải mã token
        $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
        
        // Lấy customer_id từ payload của token
        return $decoded->data->customer_id;
        
    } catch (Exception $e) {
        throw new Exception('Token không hợp lệ');
    }
}