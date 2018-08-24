<?php

namespace think;

use think\Exception;
use Firebase\JWT\JWT;

class Token
{
    public static $key    = '5500a628e5533f0f656bed44f71d2837';
    public static $leeway = 3600;

    public static function encodeJwt($data, $key = NULL, $leeway = NULL)
    { 
        $data = array_merge($data, [
            "exp" => time() + ($leeway ? : self::$leeway)
        ]);
        return JWT::encode($data, $key ? : self::$key);
    }

    public static function decodeJwt($token, $key = NULL)
    {
        try {
            return JWT::decode($token, $key ? : self::$key, array('HS256'));
        } catch (\Exception $e) {
            throw new Exception($e->getMessage(), -1003);            
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

        $str .= 'key' . config('TOKEN_KEY') ? : self::$key;
        return md5($str);
    }
}
