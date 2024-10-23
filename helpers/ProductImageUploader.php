<?php
require 'vendor/autoload.php';
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

class ProductImageUploader {
    private $cloudinary;
    
    public function __construct() {
        // Cấu hình Cloudinary
        Configuration::instance([
            'cloud' => [
                'cloud_name' => 'your_cloud_name',
                'api_key' => 'your_api_key',
                'api_secret' => 'your_api_secret'
            ]
        ]);
        
        $this->cloudinary = new UploadApi();
    }
    
    public function uploadProductImages($product_id, $files) {
        $response = [
            'display_images' => [],
            'detail_images' => []
        ];
        
        try {
            // Upload ảnh trưng bày
            if (isset($files['display_images'])) {
                foreach ($files['display_images'] as $image) {
                    $result = $this->cloudinary->upload($image['tmp_name'], [
                        'folder' => "products/{$product_id}/display",
                        'public_id' => 'display_' . uniqid(),
                        'overwrite' => true
                    ]);
                    
                    $response['display_images'][] = [
                        'url' => $result['secure_url'],
                        'public_id' => $result['public_id']
                    ];
                }
            }
            
            // Upload ảnh chi tiết
            if (isset($files['detail_images'])) {
                foreach ($files['detail_images'] as $image) {
                    $result = $this->cloudinary->upload($image['tmp_name'], [
                        'folder' => "products/{$product_id}/details",
                        'public_id' => 'detail_' . uniqid(),
                        'overwrite' => true
                    ]);
                    
                    $response['detail_images'][] = [
                        'url' => $result['secure_url'],
                        'public_id' => $result['public_id']
                    ];
                }
            }
            
            return [
                'success' => true,
                'data' => $response
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}

// API Endpoint để xử lý upload ảnh
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $uploader = new ProductImageUploader();
    
    $result = $uploader->uploadProductImages($product_id, $_FILES);
    
    header('Content-Type: application/json');
    echo json_encode($result);
}