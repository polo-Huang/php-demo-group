<?php
/*
 * @Author: polo
 * @Date: 2020-12-29 11:28:46
 * @LastEditTime: 2020-12-29 11:29:10
 * @LastEditors: Please set LastEditors
 * @Description: 调试日志类
 * @FilePath: \php-demo-group\core\log.php
 */

if( !defined('CORE') ) exit('Request Error!');

class log
{
    // 
    public static function error($message, $mode = 'debug')
    {
        $content = sprintf("[%s] %s:%d %s(): %s", $mode, __FILE__, __LINE__, __FUNCTION__, $message);
        error_log($content, 0);
    }
}

