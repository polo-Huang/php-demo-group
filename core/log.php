<?php
/*
 * @Author: polo
 * @Date: 2020-12-29 11:28:46
 * @LastEditTime: 2021-01-04 15:42:25
 * @LastEditors: Please set LastEditors
 * @Description: 调试日志类
 * @FilePath: \php-demo-group\core\log.php
 */

if( !defined('CORE') ) exit('Request Error!');

class log
{
    // 
    public static function error($message)
    {
        $content = sprintf("[%s] %s:%d %s(): %s", 'error', __FILE__, __LINE__, __FUNCTION__, $message);
        error_log($content, 0);
    }

    // 
    public static function debug($message)
    {
        $content = sprintf("[%s] %s:%d %s(): %s", 'debug', __FILE__, __LINE__, __FUNCTION__, $message);
        error_log($content, 0);
    }
}

