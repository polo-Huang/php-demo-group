<?php
/*
 * @Author: polo
 * @Date: 2020-12-29 11:17:20
 * @LastEditTime: 2020-12-29 17:51:43
 * @LastEditors: Please set LastEditors
 * @Description: 框架核心入口文件
 * @FilePath: \php-demo-group\core\init.php
 */


// 核心库目录
define('CORE', dirname(__FILE__));

// 系统配置
require CORE.'/../configs/app.php';
require CORE.'/../configs/database.php';
require CORE.'/../configs/routes.php';

// 加载核心类库
require CORE.'/log.php';
require CORE.'/roles.php';

// 加载组件类库
require CORE.'/../utils/database/db.php';
require CORE.'/../utils/common/common.php';

// 加载模型类
require CORE.'/../model/mod_user.php';

require CORE.'/../control/controller.php';

function into_controller()
{
    // 获取路由信息
    $uri = str_replace($GLOBALS['config']['channel'], '', $_SERVER['REQUEST_URI']);
    $routes = explode("/", $uri);
    try {
        // 获取控制器信息
        $ctl = preg_replace("/[^0-9a-z_]/i", "", isset($routes[1]) ? $routes[1] : "");
        $ctl = "ctl_".($ctl == "" ? $ctl = 'index' : $ctl);
        // 获取方法名
        $func = preg_replace("/[^0-9a-z_]/i", "", isset($routes[2]) ? $routes[2] : "");
        $func = $func == "" ? $func = 'index' : $func;
        // 拼接出控制器文件完整信息
        $path_file = PATH_CONTROL.'/'.$ctl.'.php';

        // 判断控制器文件是否存在，否throw错误信息
        if (file_exists($path_file)) {
            // 引入控制器文件
            require $path_file;
        } else {
            throw new Exception("Contrl {$ctl}--{$path_file} is not exists!");
        }
        // 判断控制器文件中声明的类与方法是否存在
        if (method_exists($ctl, $func) === true) {
            // 权限控制
            roles::auth($ctl, $func);
            // 调用类与方法
            $instance = new $ctl();
            $instance->$func();
        } else {
            // throw new Exception("Method {$ctl}::{$func}() is not exists!");
            log::error("init.php run_controller() error: Method {$ctl}::{$func}() is not exists! url: {$_SERVER['REQUEST_URI']}");
            utils::abort(404);
        }
    } catch (Exception $e) {
        // 捕获到错误时打印日志
        log::error("init.php run_controller() error: {$e->getMessage()} url: {$_SERVER['REQUEST_URI']}");
        // 中止程序并向客户端返回状态码
        utils::abort(500);
    }
}
