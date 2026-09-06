<?php

if (!function_exists('encrypt_url')) {
    function encrypt_url($string)
    {
        // Menyandikan data dan mengganti karakter yang tidak ramah URL
        return strtr(base64_encode($string), '+/=', '-_~');
    }
}

if (!function_exists('decrypt_url')) {
    function decrypt_url($string)
    {
        // Mengembalikan karakter asli lalu men-decode
        return base64_decode(strtr($string, '-_~', '+/='));
    }
}
