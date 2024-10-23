<?php
// Required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

// Include Composer autoloader
require_once __DIR__ . '/../../vendor/autoload.php';

// Include Cloudinary config file
require_once __DIR__ . '/../../config/cloudinary_config.php';  
// Import Cloudinary Upload API
use Cloudinary\Api\Upload\UploadApi;

// Include database và model
require_once '../../config/db.php';
require_once '../../model/sanPham.php';
include_once '../../middleware/check_role.php';


if(checkRole('admin')) {
    // Kiểm tra phương thức request
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Database connection
        $db = new db();
        $connect = $db->connect();
        $sanPham = new SanPham($connect);

        // Kiểm tra dữ liệu đầu vào
        if (isset($_POST['product_name']) && isset($_POST['price']) && isset($_POST['stock']) &&
            !empty($_POST['product_name']) && !empty($_POST['price']) && !empty($_POST['stock'])) {

            // Gán dữ liệu cơ bản
            $sanPham->product_name = $_POST['product_name'];
            $sanPham->description = $_POST['description'] ?? '';
            $sanPham->price = $_POST['price'];
            $sanPham->stock = $_POST['stock'];
            $sanPham->category_id = $_POST['category_id'] ?? null;
            $sanPham->supplier_id = $_POST['supplier_id'] ?? null;
            $sanPham->created_at = date('Y-m-d H:i:s');
            $sanPham->updated_at = date('Y-m-d H:i:s');

            try {
                $upload = new UploadApi();

                // Upload ảnh đại diện
                if (isset($_FILES['thumbnail_image']) && $_FILES['thumbnail_image']['error'] === UPLOAD_ERR_OK) {
                    $thumbnail_upload = $upload->upload($_FILES['thumbnail_image']['tmp_name'], [
                        'folder' => 'products/thumbnails'
                    ]);
                    $sanPham->thumbnail_image = $thumbnail_upload['secure_url'];
                }

                // Upload ảnh chi tiết
                for ($i = 1; $i <= 3; $i++) {
                    $field_name = "detail_image{$i}";
                    if (isset($_FILES[$field_name]) && $_FILES[$field_name]['error'] === UPLOAD_ERR_OK) {
                        $detail_upload = $upload->upload($_FILES[$field_name]['tmp_name'], [
                            'folder' => 'products/details'
                        ]);
                        $property = "detail_image{$i}";
                        $sanPham->$property = $detail_upload['secure_url'];
                    }
                }

                // Tạo sản phẩm
                if($sanPham->create()) {
                    http_response_code(201);
                    echo json_encode(array("message" => "Done"));
                } else {
                    http_response_code(503);
                    echo json_encode(array("message" => "Failed to create"));
                }

            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode(array(
                    "message" => "Upload failed",
                    "error" => $e->getMessage()
                ));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Invalid input: product_name, price, and stock are required"));
        }
    } else {
        http_response_code(405);
        echo json_encode(array('message' => 'Invalid request method. Only POST requests are allowed.'));
    }
}
else {
    exit();
}

?>