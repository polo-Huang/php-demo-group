<?php
/*
 * @Author: your name
 * @Date: 2020-12-30 14:22:01
 * @LastEditTime: 2020-12-30 14:29:25
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \php-demo-group\core\helper.php
 */

/**
 * HTTP类函数
 */
// 判断状态码
function get_state_hs($code)
{
    $hs = ceil(intval($code) / 100);
    return $hs;
}

function response($code, $data, $fails_msg = '')
{
    $response = [
        'code' => $code,
        'data' => $data
    ];

    // 错误时返回错误信息
    if (get_state_hs($code) != HS_SUCCESS) {
        $response['fails_msg'] = $fails_msg;
    }

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($response);
    die();
}

function request($argstr, $default='')
{
    return isset($_REQUEST[$argstr])?$_REQUEST[$argstr]:$default;
}

function abort($code, $fails_msg = '')
{
    response($code, [], $fails_msg);
}

