<?php
/*
 * @Author: your name
 * @Date: 2020-12-30 14:30:53
 * @LastEditTime: 2020-12-30 14:31:07
 * @LastEditors: Please set LastEditors
 * @Description: 状态码百位数配置
 * @FilePath: \php-demo-group\configs\code.php
 */

// 返回失败
define('HS_FAILS_SERVER', 5); // 服务器错误
define('HS_FAILS_HTTP', 4); // 业务错误
define('HS_FAILS_IGNORE', 3); // 忽略性错误
// 返回成功
define('HS_SUCCESS', 2);

// 数据库配置变量
$GLOBALS['config']['code'] = [
    422 => [
        'message' => "请求参数校验失败",
        'fails_code' => [
            '000001' =>  "",
        ]
    ]
];

