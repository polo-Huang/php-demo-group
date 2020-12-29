<?php
/*
 * @Author: polo
 * @Date: 2020-12-29 11:37:35
 * @LastEditTime: 2020-12-29 17:41:02
 * @LastEditors: Please set LastEditors
 * @Description: index
 * @FilePath: \php-demo-group\control\ctl_index.php
 */

if( !defined('CORE') ) exit('Request Error!');
require CORE.'/../control/controller.php';
new control();

class ctl_index extends control
{

    public function __construct()
    {
        
    }

	public function index()
	{
        var_dump("/index/index");
    }

    public function test()
    {
        $data = [];
        $data['token'] = utils::str_random(32);
        $data['key'] = utils::str_random(32,'key');
        $data['secret'] = utils::str_random(32,'secret');
        utils::response(200, $data);
    }
}

