<?php
/**
 * Shared security helpers — CSRF, session hardening, response headers.
 */

function initSecureSession(){
    if(session_status() === PHP_SESSION_ACTIVE){
        return;
    }

    $secure = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off");

    session_set_cookie_params(array(
        "lifetime" => 0,
        "path"     => "/",
        "httponly" => true,
        "samesite" => "Lax",
        "secure"   => $secure
    ));

    session_start();

    if(empty($_SESSION["_session_init"])){
        session_regenerate_id(true);
        $_SESSION["_session_init"] = 1;
    }
}

function sendSecurityHeaders(){
    header("X-Content-Type-Options: nosniff");
    header("X-Frame-Options: SAMEORIGIN");
    header("Referrer-Policy: strict-origin-when-cross-origin");
}

function csrfToken(){
    initSecureSession();
    if(empty($_SESSION["csrf_token"])){
        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
    }
    return $_SESSION["csrf_token"];
}

function csrfField(){
    return '<input type="hidden" name="csrf_token" value="' .
        htmlspecialchars(csrfToken(), ENT_QUOTES, "UTF-8") . '">';
}

function verifyCsrf($token){
    initSecureSession();
    if(!is_string($token) || $token === ""){
        return false;
    }
    if(empty($_SESSION["csrf_token"])){
        return false;
    }
    return hash_equals($_SESSION["csrf_token"], $token);
}

function requireCsrfPost(){
    $token = $_POST["csrf_token"] ?? "";
    if(!verifyCsrf($token)){
        http_response_code(403);
        die("Invalid security token. Please refresh the page and try again.");
    }
}

function sanitizeText($value, $maxLen = 500){
    $value = trim((string)$value);
    if(strlen($value) > $maxLen){
        $value = substr($value, 0, $maxLen);
    }
    return $value;
}

function esc($value){
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

?>
