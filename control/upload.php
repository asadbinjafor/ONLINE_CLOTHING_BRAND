<?php
include_once __DIR__ . "/app.php";

function uploadProfilePicture($fieldName, $oldPicture, &$errors){
    if(empty($_FILES[$fieldName]["name"])){
        return $oldPicture;
    }
    if($_FILES[$fieldName]["error"] !== UPLOAD_ERR_OK){
        $errors["profile_picture"] = "File upload error. Please try again.";
        return $oldPicture;
    }
    $allowedTypes = array("image/jpeg" => "jpg", "image/png" => "png");
    $maxSize      = 2 * 1024 * 1024;
    $tmpName      = $_FILES[$fieldName]["tmp_name"];
    $fileType     = mime_content_type($tmpName);
    if(!isset($allowedTypes[$fileType])){
        $errors["profile_picture"] = "Only JPEG or PNG profile picture is allowed";
        return $oldPicture;
    }
    if($_FILES[$fieldName]["size"] > $maxSize){
        $errors["profile_picture"] = "Profile picture must be 2MB or less";
        return $oldPicture;
    }
    ensureUploadDir(PROFILE_UPLOAD_DIR);
    $fileName   = "profile_" . time() . "_" . rand(1000, 9999) . "." . $allowedTypes[$fileType];
    $uploadPath = PROFILE_UPLOAD_DIR . $fileName;
    if(move_uploaded_file($tmpName, $uploadPath)){
        return $fileName;
    }
    $errors["profile_picture"] = "Profile picture upload failed";
    return $oldPicture;
}

function uploadProductImage($fieldName, $oldImage, &$errors){
    if(empty($_FILES[$fieldName]["name"])){
        return $oldImage;
    }
    if($_FILES[$fieldName]["error"] !== UPLOAD_ERR_OK){
        $errors["image"] = "File upload error. Please try again.";
        return $oldImage;
    }
    $allowedTypes = array("image/jpeg" => "jpg", "image/png" => "png");
    $maxSize      = 2 * 1024 * 1024;
    $tmpName      = $_FILES[$fieldName]["tmp_name"];
    $fileType     = mime_content_type($tmpName);
    if(!isset($allowedTypes[$fileType])){
        $errors["image"] = "Only JPEG or PNG image is allowed";
        return $oldImage;
    }
    if($_FILES[$fieldName]["size"] > $maxSize){
        $errors["image"] = "Product image must be 2MB or less";
        return $oldImage;
    }
    ensureUploadDir(PRODUCT_UPLOAD_DIR);
    $fileName   = "product_" . time() . "_" . rand(1000, 9999) . "." . $allowedTypes[$fileType];
    $uploadPath = PRODUCT_UPLOAD_DIR . $fileName;
    if(move_uploaded_file($tmpName, $uploadPath)){
        return $fileName;
    }
    $errors["image"] = "Product image upload failed";
    return $oldImage;
}

function deleteProductImageFile($imagePath){
    if(!empty($imagePath)){
        $fullPath = PRODUCT_UPLOAD_DIR . $imagePath;
        if(file_exists($fullPath)){
            unlink($fullPath);
        }
    }
}
?>
