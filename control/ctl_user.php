<?php
/*
 * @Author: polo
 * @Date: 2020-12-29 11:37:35
 * @LastEditTime: 2021-01-04 15:22:46
 * @LastEditors: Please set LastEditors
 * @Description: index
 * @FilePath: \php-demo-group\control\ctl_user.php
 */

if( !defined('CORE') ) exit('Request Error!');

class ctl_user extends controller
{

    public function __construct()
    {
        
    }

    public function list()
    {
        $data = [
            'list' => db::select("select * from users")
        ];
        response(200, $data);
    }
}

