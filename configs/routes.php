<?php
/*
 * @Author: polo
 * @Date: 2020-12-29 11:51:01
 * @LastEditTime: 2020-12-29 11:51:21
 * @LastEditors: Please set LastEditors
 * @Description: 路由权限控制
 * @FilePath: \php-demo-group\configs\routes.php
 */

$GLOBALS['config']['routes'] = [
    'ctl' => [
        'ctl_user' => ['login'],
    ],
    'func' => [
        'ctl_index/index' => ['login'],
        // 'ctl_test/submit' => ['login'],
    ]
];