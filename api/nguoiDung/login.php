<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/db.php';
include_once '../../model/nguoiDung.php';

$database = new db();
$db = $database->connect();
$user = new User($db);

$data = json_decode(file_get_contents("php://input"));

$user->email = $data->email;
$email_exists = $user->emailExists();

include_once '../../config/core.php';
include_once '../../vendor/firebase/php-jwt/src/BeforeValidException.php';
include_once '../../vendor/firebase/php-jwt/src/ExpiredException.php';
include_once '../../vendor/firebase/php-jwt/src/SignatureInvalidException.php';
include_once '../../vendor/firebase/php-jwt/src/JWT.php';
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

if($email_exists && password_verify($data->password, $user->password)) {
    $token = array(
       "iss" => $iss,
       "aud" => $aud,
       "iat" => $iat,
       "nbf" => $nbf,
       "exp" => $exp,
       "data" => array(
           "customer_id" => $user->customer_id,
           "username" => $user->username,
           "email" => $user->email,
           "role" => $user->role
       )
    );

    http_response_code(200);

    $jwt = JWT::encode($token, $key, 'HS256');
    echo json_encode(
        array(
            "message" => "Successful login.",
            "jwt" => $jwt
        )
    );
} else {
    http_response_code(401);
    echo json_encode(array("message" => "Login failed."));
}