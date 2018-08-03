<?php
/**
 * Created by PhpStorm.
 * User: 韦腾赟
 * Date: 2018/7/9
 * Time: 11:33
 */

if (!function_exists('aes_encode')) {

    /**
     * @param $plain_text 要加密的文本
     * @param string $key 加密key
     * @return string
     */
    function aes_encode($plain_text, $key = AES_KEY) {
        return base64_encode(openssl_encrypt($plain_text, "aes-256-cbc", $key, true, str_repeat(chr(0), 16)));
    }
}


if (!function_exists('aes_decode')) {
    /**
     * @param $base64_text 要解密的文本
     * @param string $key 加密key
     * @return string
     */
    function aes_decode($base64_text, $key = AES_KEY) {
        return openssl_decrypt(base64_decode($base64_text), "aes-256-cbc", $key, true, str_repeat(chr(0), 16));
    }
}

if (!function_exists('redis')) {
    function redis() {
        static $redis;
        if (!isset($redis)) {
            try {
                $redis = new Redis();
                $redis->connect(REDIS_HOST, REDIS_PORT);
                REDIS_PWD && $redis->auth(REDIS_PWD);
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
        return $redis;
    }
}

if (!function_exists('my_trim')) {
    /**
     * 去掉数组每个元素两头的空白
     * @param array $param
     * @return bool
     */
    function my_trim(array &$param) {
        foreach ($param as &$value) {
            if (is_array($value)) {
                $this->my_trim($value);
            } elseif (is_scalar($value)) {
                $value = trim($value);
            } else {
                continue;
            }
        }
        return true;
    }
}





