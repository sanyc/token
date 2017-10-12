<?php

namespace think;

use Config;
use think\Exception;
use Firebase\JWT\JWT;

class Token
{
    public static $key = '5500a628e5533f0f656bed44f71d2837';

    public static function encodeJwt($data)
    {
        return JWT::encode($data, self::$key);
    }

    public static function decodeJwt($token)
    {
        try {
            return JWT::decode($token, self::$key, array('HS256'));
        } catch (\Exception $e) {
            throw new Exception("Check failed", -1003);            
        }
    }
    
    /**
     * 校验sign
     * @return [type] [description]
     */
    public static function checkSign($data)
    {
        if (isset($data['sign']) && $data['sign'] === self::mixSign($data)) {
            return true;
        }
        throw new Exception("Source check error >" . $data['sign'] . '|' . self::mixSign($data), -1002);
    }

    public static function createSign($data)
    {
        return self::mixSign($data);
    }

    /**
     * 组合sign
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    protected static function mixSign($data)
    {
        $data = array_diff_key($data, array_flip(['_url', 'sign', 's', '_token', 'action']));
        ksort($data);

        $str = '';
        foreach ($data as $k => $value) {
            $value = urldecode($value);
            if ($value || $value === '0') {
                $str .= $k . $value;
            }
        }

        $str .= 'key' . Config::get('KEY') ? : self::$key;
        return md5($str);
    }
}
