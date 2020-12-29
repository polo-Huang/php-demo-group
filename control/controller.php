<?php
/*
 * @Author: polo
 * @Date: 2020-12-29 11:37:35
 * @LastEditTime: 2020-12-29 12:00:50
 * @LastEditors: Please set LastEditors
 * @Description: Controller
 * @FilePath: \php-demo-group\control\ctl_index.php
 */

if( !defined('CORE') ) exit('Request Error!');

class control
{

    public function __construct()
    {
        
    }

	private static function response($code, $data, $fails_msg = '')
    {
        $response = [
            'code' => $code,
            'data' => $data
        ];

        // 错误时返回错误信息
        if (self::get_state_hs($code) != HS_SUCCESS) {
            $response['fails_msg'] = $fails_msg;
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response);
        die();
    }

    public static function request($argstr, $default='')
    {
        return isset($_REQUEST[$argstr])?$_REQUEST[$argstr]:$default;
    }

    private static function abort($code, $fails_msg = '')
    {
        self::response($code, [], $fails_msg);
    }
}

