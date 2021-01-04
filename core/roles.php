<?php
/*
 * @Author: polo
 * @Date: 2020-12-29 11:48:48
 * @LastEditTime: 2020-12-30 14:29:16
 * @LastEditors: Please set LastEditors
 * @Description: 权限控制类
 * @FilePath: \php-demo-group\core\roles.php
 */

if( !defined('CORE') ) exit('Request Error!');

class roles
{
    public static function auth($ctl, $func)
    {
        $routes = $GLOBALS['config']['routes'];
        // 先判断控制器层是否有权限控制
        if (isset($routes['ctl'][$ctl])) {
            self::role_verify($routes['ctl'][$ctl]);
        }
        // 再判断具体方法
        $func_uri = "{$ctl}/{$func}";
        if (isset($routes['func'][$func_uri])) {
            self::role_verify($routes['func'][$func_uri]);
        }
    }

    /**
     * 权限控制
     */
    private static function role_verify($roles)
    {
        $forbidden = true;
        foreach ($roles as $role) {
            switch ($role) {
                case 'login':
                    // 判断是否有登录权限
                    $has_role = self::login_role();
                    if ($has_role) {
                        $forbidden = false;
                    }
                    break;
            }
            // 只要有一条权限不足直接跳出循环
            if ($forbidden) break;
        }
        if ($forbidden) {
            abort(403);
        }
    }

    private static function login_role()
    {
        $has_role = true;
        $openid = request('openid');
        $user = mod_user::user_for_openid($openid);
        if (!isset($user['id'])) {
            $has_role = false;
        }
        return $has_role;
    }
    /**
     * End
     */
}

