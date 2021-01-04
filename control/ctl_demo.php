<?php
/*
 * @Author: polo
 * @Date: 2020-12-29 11:37:35
 * @LastEditTime: 2021-01-04 17:16:18
 * @LastEditors: Please set LastEditors
 * @Description: index
 * @FilePath: \php-demo-group\control\ctl_demo.php
 */

if( !defined('CORE') ) exit('Request Error!');

class ctl_demo extends controller
{
    public function random()
    {
        $len = request('len', 5);
        $type = request('type', "token");
        // var_dump($len, $type);
        $str = common::str_random($len, $type);
        $data = [
            'str' => $str,
        ];
        response(200, $data);
    }

    public function sinfo()
    {
        $sdata = request('data', '');
        $type = request('type', "e");
        if ($sdata == '' || !in_array($type, [ "e", "d" ])) {
            response(404, [], '参数错误');
        }
        $str = $type == "e" ? common::sinfo_encrypt($sdata) : common::sinfo_decrypt($sdata);
        $data = [
            'str' => $str,
        ];
        response(200, $data);
    }

    public function sms_send()
    {
        $phones = request('phones', '');
        $subject = request('subject', '');
        $mode = request('mode', 'ali');
        if ($subject == '' || $phones == '') {
            response(404, [], '参数错误');
        }
        $phones = explode(",", $phones);
        $result = null;
        switch ($mode) {
            case 'ali':
                $result = alisms::send($phones, $subject);
                break;
            default:
                response(404, [], '参数错误');
        }
        $data = [
            'result' => $result,
        ];
        response(200, $data);
    }

    public function mail_send()
    {
        $to = request('to', '');
        $title = request('title', '');
        $content = request('content', '');
        $mode = request('mode', 'qq');
        if ($title == '' || $content == '' || $to == '' || !in_array($mode, [ "qq" ])) {
            response(404, [], '参数错误');
        }
        $result = null;
        $config = $GLOBALS['config']['mail'][$mode];
        $mail = new mail($config);
        $result = $mail->send('polo_07@163.com','subject123',' <h1>33</h1> <a href="http://www.baidu.com"> baidu</a> <hr>');
        $data = [
            'result' => $result,
        ];
        response(200, $data);
    }
}

