<?php
/*
 * @Author: polo
 * @Date: 2020-12-29 11:41:30
 * @LastEditTime: 2020-12-29 11:41:51
 * @LastEditors: Please set LastEditors
 * @Description: user model
 * @FilePath: \php-demo-group\model\mod_user.php
 */

if (!defined('CORE')) exit('Request Error!');

class mod_user
{
    public static function user($select = '*', $where = '', $binds = [])
    {
        $users = db::select("select {$select} from wx_users {$where} limit 1", $binds);
        $user = count($users) > 0 ? $users[0] : [];
        return $user;
    }

    public static function user_for_openid($openid)
    {
        $user = self::user(
            'id, openid, session_key, unionId, nickname, avatar_url, phone, gender, province, city, counciltry, balance',
            'where openid = ?',
            [$openid]
        );
        return $user;
    }
}
