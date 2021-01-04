<?php
/*
 * @Author: polo
 * @Date: 2020-12-29 11:24:52
 * @LastEditTime: 2021-01-04 15:44:30
 * @LastEditors: Please set LastEditors
 * @Description: 公共类
 * @FilePath: \php-demo-group\utils\common\common.php
 */

if( !defined('CORE') ) exit('Request Error!');

/**
 * @description: 公用常量
 */
const SHORTEN_STR = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
const SHORTEN_STR_LEN = 61;
const ENCRYPT_AES_KEY = 'privacy-datainfo';

class common
{
    /**
     * @description: 打印日志
     * @param {*} $message
     * @param {*} $mode
     * @return {*}
     */
    public static function log($message, $mode = 'debug')
    {
        $content = sprintf("[%s] %s:%d %s(): %s", $mode, __FILE__, __LINE__, __FUNCTION__, $message);
        error_log($content, 0);
    }

    /**
     * @description: 文件相关函数
     * image
     */

    /**
     * @description: 图片转base64
     * @param {*} $icon_path
     * @return {*}
     */
    public static function imgtobase64($icon_path)
    {
        $fp = fopen($icon_path, "r");
        $filesize = filesize($icon_path);
        $content = fread($fp, $filesize);
        $file_content = chunk_split(base64_encode($content));
        fclose($fp);
        return $file_content;
    }

    /**
     * @description: 数据加密相关函数
     * AES-128-CBC
     */

    /**
     * @description: 隐私数据加密
     * @param {*} $sinfo
     * @return {*}
     */
    public static function sinfo_encrypt($sinfo)
    {
        $key = md5(ENCRYPT_AES_KEY, true);
        $iv = md5($key.ENCRYPT_AES_KEY, true);
        $encrypted = openssl_encrypt($sinfo, "AES-128-CBC", $key, OPENSSL_RAW_DATA, $iv);
        log::error(ENCRYPT_AES_KEY."\n");
        return strtoupper(bin2hex($encrypted));
    }
    /**
     * @description: 隐私数据解密
     * @param {*} $encrypted
     * @return {*}
     */
    public static function sinfo_decrypt($encrypted)
    {
        $key = md5(ENCRYPT_AES_KEY, true);
        $iv = md5($key.ENCRYPT_AES_KEY, true);
        $decrypted = openssl_decrypt(hex2bin($encrypted), "AES-128-CBC", $key, OPENSSL_RAW_DATA, $iv);
        return $decrypted;
    }

    
    /**
     * @description: http相关函数
     * curl, ip
     */

    /**
     * @description: curl
     * @param {*} $url
     * @param {*} $data
     * @param {*} $method
     * @param {*} $headers
     * @return {*}
     */ 
    public static function http_request($url, $data = [], $method = 'GET', $headers=[])
    {
        //初始化
        $curl = curl_init();
        array_push($headers, "Content-type:application/json;charset='utf-8'", "Accept:application/json");
        if($method == 'GET'){
            if($data){
                $querystring = http_build_query($data);
                $url = $url.'?'.$querystring;
            }
        }
        // 请求头，可以传数组
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $data = json_encode($data);
        if($method == 'POST'){
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST,'POST');     // 请求方式
            curl_setopt($curl, CURLOPT_POST, true);               // post提交
            curl_setopt($curl, CURLOPT_POSTFIELDS,$data);                 // post的变量
        }
        if($method == 'DELETE'){
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
            curl_setopt($curl, CURLOPT_POSTFIELDS,$data);
        }
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,FALSE);
        $output = curl_exec($curl);
        $err = curl_error($curl);
        if ($err) {
            utils::log(sprintf("curl fails error: %s", $err));
            return [
                'data' => null,
                'err' => $err
            ];
        }
        curl_close($curl);
        utils::log($output);
        $data = json_decode($output, true);
        return [
            'data' => $data,
            'err' => null
        ];
    }
    /**
     * @description: post请求
     * @param {*} $url
     * @param {*} $data
     * @param {*} $headers
     * @param {*} $options
     * @return {*}
     */
    public static function post_request($url, $data = [], $headers = [], $options = [])
    {
        $options['body_type'] = isset($options['body_type']) ? $options['body_type'] : 'json';
        if ($options['body_type'] == 'json') {
            $data = json_encode($data);
        }
        array_push($headers, "Content-type:application/json;charset='utf-8'", "Accept:application/json");
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,FALSE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        utils::log($output);
        return json_decode($output, true);
    }

    /**
     * @description: 获取客户端ip
     * @param {*}
     * @return {*}
     */
    public static function get_ip()
    {
        $ip = "";
        if (!empty($_SERVER["HTTP_CLIENT_IP"]))
        $ip = $_SERVER["HTTP_CLIENT_IP"];
        else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
        $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        else if(!empty($_SERVER["REMOTE_ADDR"]))
        $ip = $_SERVER["REMOTE_ADDR"];
        else $ip = "Unknow";
        return $ip;
    }

    /**
     * @description: 数据格式转化相关函数
     * plist
     */

    /**
     * @description: plist->array
     * @param {*} $data
     * @return {*}
     */
    public static function plist_parse($data)
    {
        require CORE.'/PlistParser.php';
        $plist = new PlistParser();
        return $plist->plistToArrayForXML($data);
    }

    /**
     * @description: id(int)->string
     * @param {*} $id
     * @return {*}
     */
    public static function shortenOfId($id) {
        $val = 100000000000000 + $id*10000000;
        $ret = "";
        while ($val >= SHORTEN_STR_LEN) {
            $ret = substr(SHORTEN_STR, $val % SHORTEN_STR_LEN, 1) . $ret;
            $val = floor($val / SHORTEN_STR_LEN);
        }
        $ret = substr(SHORTEN_STR, $val, 1) . $ret;

        return $ret;
    }

    
    /**
     * @description: 字符串相关函数
     * random
     */

    /**
     * @description: 生成随机字符串
     * @param {*} $len
     * @param {*} $type
     * @return {*}
     */
    public static function str_random($len, $type = "token")
    {
        $nums = [ "0", "1", "2","3", "4", "5", "6", "7", "8", "9" ];

        $lls = [
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
            "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
            "w", "x", "y", "z"
        ];

        $uls = [
            "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K",
            "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V",
            "W", "X", "Y", "Z"
        ];

        $special = [
            "!", "@", "#", "$", "?", "|", "{", "/", ":", ";", "%",
            "^", "&", "*", "(", ")", "-", "_", "[", "]", "}", "<",
            ">", "~", "+", "=", ",", "."
        ];

        switch ($type) {
            case "token":
                $chars = array_merge($nums, $lls, $uls, $special);
                break;
            case "nums":
                $chars = $nums;
                break;
            case "key":
                $chars = array_merge($nums, $lls);
                break;
            case "secret":
                $chars = array_merge($nums, $uls);
                break;
            case "string":
                $chars = array_merge($nums, $lls, $uls);
                break;
            default:
                $chars = array_merge($nums, $lls, $uls, $special);
        }

        $charsLen = count($chars) - 1;
        shuffle($chars);                            //打乱数组顺序
        $str = '';
        for ($i = 0; $i < $len; $i++) {
            $str .= $chars[mt_rand(0, $charsLen)];    //随机取出一位
        }
        return $str;
    }
}

