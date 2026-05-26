<?php
define("ROOT_DIR", dirname(__DIR__));
define("PROFILE_UPLOAD_DIR",  ROOT_DIR . "/uploads/profile/");
define("PROFILE_UPLOAD_WEB",  "../uploads/profile/");
define("PRODUCT_UPLOAD_DIR", ROOT_DIR . "/uploads/products/");
define("PRODUCT_UPLOAD_WEB", "../uploads/products/");
define("REMEMBER_SECRET", "wtproject07_clothing_2354604_3");

function ensureUploadDir($dir){
    if(!is_dir($dir)){
        mkdir($dir, 0755, true);
    }
}
?>
