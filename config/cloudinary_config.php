<?php

use Cloudinary\Configuration\Configuration;

Configuration::instance([
    'cloud' => [
      'cloud_name' => '', 
      'api_key' => '', 
      'api_secret' => ''],
    'url' => [
      'secure' => true
    ]
]);