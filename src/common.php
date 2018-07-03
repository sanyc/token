<?php

use think\Token;

if (!function_exists('jwt_encode_token')) {
	/**
	 * [jwt_encode_token description]
	 * @param  [type] $value [description]
	 * @param  [type] $key   [description]
	 * @return [type]        [description]
	 */
	function jwt_encode_token($value, $key = NULL)
    {
        return Token::encodeJwt($value, $key);
    }
}

if (!function_exists('jwt_decode_token')) {
	/**
	 * [jwt_decode_token description]
	 * @param  string $token [description]
	 * @param  [type] $key   [description]
	 * @return [type]        [description]
	 */
	function jwt_decode_token(string $token, $key = NULL)
	{
		return Token::decodeJwt($token, $key);
	}
}

if (!function_exists('create_sign')) {
	/**
	 * [create_sign description]
	 * @param  array  $sign_data [description]
	 * @return [type]            [description]
	 */
	function create_sign(array $sign_data)
	{
		return Token::createSign($sign_data);
	}
}

if (!function_exists('check_sign')) {
	/**
	 * [check_sign description]
	 * @param  array  $sign_data [description]
	 * @return [type]            [description]
	 */
	function check_sign(array $sign_data)
	{
		try {
			return Token::checkSign($sign_data);
		} catch (\Exception $e) {
			return false;
		}		
	}
}