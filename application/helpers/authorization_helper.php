<?php

class Authorization
{
    public static function validateTimestamp($token)
    {
        $CI = &get_instance();
        $token = self::validateToken($token);
        $timeout = $CI->config->item('token_timeout');
        if (empty($timeout) || !isset($token->iat)) {
            return $token;
        }
        if ($token != false) {
            if (!(time() - $token->iat < ($timeout * 60))) {
                throw new Exception("Token expired", 777);
            }
            return $token;
        }
        return false;
    }

    public static function validateToken($token)
    {
        $CI = &get_instance();
        return JWT::decode($token, $CI->config->item('jwt_key'));
    }

    public static function generateToken($data)
    {
        $data['iat'] = time();
        $CI = &get_instance();
        return JWT::encode($data, $CI->config->item('jwt_key'));
    }

    public static function v4uuid()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,
            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
}