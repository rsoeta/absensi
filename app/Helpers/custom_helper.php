<?php

if (!function_exists('encrypt_url')) {
    function encrypt_url($string)
    {
        $key = 'secret_key_pakenjeng_tangguh'; // Bisa diganti sesuai keinginan
        $iv  = 'secret_iv_pakenjeng_tangguh';

        $encrypt_method = "AES-256-CBC";
        $key_hash = hash('sha256', $key);
        $iv_hash  = substr(hash('sha256', $iv), 0, 16);

        $output = openssl_encrypt($string, $encrypt_method, $key_hash, 0, $iv_hash);

        // Jadikan URL-safe (ganti karakter +, /, dan = yang sering merusak URL)
        return str_replace(['+', '/', '='], ['-', '_', '~'], base64_encode($output));
    }
}

if (!function_exists('decrypt_url')) {
    function decrypt_url($string)
    {
        $key = 'secret_key_pakenjeng_tangguh';
        $iv  = 'secret_iv_pakenjeng_tangguh';

        $encrypt_method = "AES-256-CBC";
        $key_hash = hash('sha256', $key);
        $iv_hash  = substr(hash('sha256', $iv), 0, 16);

        // Kembalikan karakter URL-safe ke format base64 normal
        $string = base64_decode(str_replace(['-', '_', '~'], ['+', '/', '='], $string));

        return openssl_decrypt($string, $encrypt_method, $key_hash, 0, $iv_hash);
    }
}
