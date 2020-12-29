<?php
/*
 * @Author: polo
 * @Date: 2020-12-29 11:32:04
 * @LastEditTime: 2020-12-29 11:33:41
 * @LastEditors: Please set LastEditors
 * @Description: 项目配置
 * @FilePath: \php-demo-group\configs\app.php
 */
/**
 * 定义常量
 */
define('OPEN_DEBUG', true);

define('PATH_CONTROL', './control');


/* 定义全局变量
    */
$GLOBALS = [];
global $GLOBALS;

// 项目频道: 用于单端口多项目时区分项目的前缀
$GLOBALS['config']['channel'] = '/demo';


