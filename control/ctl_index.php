<?php
/*
 * @Author: polo
 * @Date: 2020-12-29 11:37:35
 * @LastEditTime: 2021-01-04 11:56:55
 * @LastEditors: Please set LastEditors
 * @Description: index
 * @FilePath: \php-demo-group\control\ctl_index.php
 */

if( !defined('CORE') ) exit('Request Error!');

class ctl_index extends controller
{

    public function __construct()
    {
        
    }

	public function index()
	{
        $data = [];
        response(200, $data, '');
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

