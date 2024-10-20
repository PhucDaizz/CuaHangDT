<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/db.php';
include_once '../../model/nguoiDung.php';

include_once '../../middleware/check_role.php';

$database = new db();
$db = $database->connect();

$user = new User($db);

$data = json_decode(file_get_contents("php://input"));

if(checkRole("admin")) {
    $user->username = $data->username;
    $user->email = $data->email;
    $user->password = $data->password;
    $user->phone_number = $data->phone_number;

    $role = "admin";
    $user->role = $role;
    
    if($user->create()) {
        http_response_code(200);
        echo json_encode(array("message" => "User was created."));
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Unable to create user."));
    }
} else {
    exit();
}

