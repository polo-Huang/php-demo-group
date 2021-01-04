<?php
/*
 * @Author: your name
 * @Date: 2021-01-04 15:26:08
 * @LastEditTime: 2021-01-04 15:44:43
 * @LastEditors: Please set LastEditors
 * @Description: 阿里云sms
 * @FilePath: \php-demo-group\utils\sms\ali.php
 */


if( !defined('CORE') ) exit('Request Error!');

/**
 * @description: 公用常量
 */
const ACCESSKEY_ID = "LTAInHTLLM1z6MDX";
const ACCESS_SECRET = "LEIOMk12WtWMETc5U9ada16zp2QXBx";
const SIGN_NAME = '"方块互娱"';

class alisms
{
    /**
     * @description: 特殊转译
     * @param {*} $str
     * @return {*}
     */ 
    public static function special_url_encode($str) {
        $str = str_replace("%s", "", $str);
        $str = urlencode($str);
        $str = str_replace("+", "%20", $str);
        $str = str_replace("*", "%2A", $str);
        $str = str_replace("%%7E", "~", $str);
        $str = str_replace("%%5F", "_", $str);
        return $str;
    }

    /**
     * @description: 发送短信
     * @param {*} $phones
     * @param {*} $subject
     * @return {*}
     */ 
    public static function send($phones, $subject)
    {
        log::debug(sprintf("phones: %s, subject: %s", json_encode($phones), $subject));
        
        $AccessKeyId = ACCESSKEY_ID;
        $accessSecret = ACCESS_SECRET;
        $cust_alarm_phones = $phones;
        $PhoneNumberJson = json_encode($cust_alarm_phones);
        $SignName = SIGN_NAME;
        $SignNameJson = sprintf('[%s%s]', $SignName, str_repeat($SignName.',', count($cust_alarm_phones)-1));
        $TemplateParam = sprintf('{"msg":"%s"}', $subject);
        $TemplateParamJson = sprintf('[%s%s]', $TemplateParam, str_repeat($TemplateParam.',', count($cust_alarm_phones)-1));
        $time = time();
        $SignatureNonce = sprintf("%s-%s", $time, common::str_random(21));
        $utctime = $time - 60*60*8;
        $Timestamp = sprintf("%sT%sZ", date("Y-m-d", $utctime), date("H:i:s", $utctime));
        $sortedQueryString = "";
        $sortedQueryString = sprintf("%s=%s", self::special_url_encode('AccessKeyId'), self::special_url_encode($AccessKeyId));
        $sortedQueryString = sprintf("%s&%s=%s", $sortedQueryString, self::special_url_encode('Action'), self::special_url_encode('SendBatchSms'));
        $sortedQueryString = sprintf("%s&%s=%s", $sortedQueryString, self::special_url_encode('Format'), self::special_url_encode('JSON'));
        $sortedQueryString = sprintf("%s&%s=%s", $sortedQueryString, self::special_url_encode('OutId'), self::special_url_encode('12345'));
        $sortedQueryString = sprintf("%s&%s=%s", $sortedQueryString, self::special_url_encode('PhoneNumberJson'), self::special_url_encode($PhoneNumberJson));
        $sortedQueryString = sprintf("%s&%s=%s", $sortedQueryString, self::special_url_encode('RegionId'), self::special_url_encode('cn-hangzhou'));
        $sortedQueryString = sprintf("%s&%s=%s", $sortedQueryString, self::special_url_encode('SignNameJson'), self::special_url_encode($SignNameJson));
        $sortedQueryString = sprintf("%s&%s=%s", $sortedQueryString, self::special_url_encode('SignatureMethod'), self::special_url_encode('HMAC-SHA1'));
        $sortedQueryString = sprintf("%s&%s=%s", $sortedQueryString, self::special_url_encode('SignatureNonce'), self::special_url_encode($SignatureNonce));
        $sortedQueryString = sprintf("%s&%s=%s", $sortedQueryString, self::special_url_encode('SignatureVersion'), self::special_url_encode('1.0'));
        $sortedQueryString = sprintf("%s&%s=%s", $sortedQueryString, self::special_url_encode('SmsUpExtendCode'), self::special_url_encode('1234567'));
        $sortedQueryString = sprintf("%s&%s=%s", $sortedQueryString, self::special_url_encode('TemplateCode'), self::special_url_encode('SMS_163439193'));
        $sortedQueryString = sprintf("%s&%s=%s", $sortedQueryString, self::special_url_encode('TemplateParamJson'), self::special_url_encode($TemplateParamJson));
        $sortedQueryString = sprintf("%s&%s=%s", $sortedQueryString, self::special_url_encode('Timestamp'), self::special_url_encode($Timestamp));
        $sortedQueryString = sprintf("%s&%s=%s", $sortedQueryString, self::special_url_encode('Version'), self::special_url_encode('2017-05-25'));
        log::debug(sprintf("PhoneNumberJson: %s", $PhoneNumberJson));
        log::debug(sprintf("SignNameJson: %s", $SignNameJson));
        log::debug(sprintf("TemplateParamJson: %s", $TemplateParamJson));
        log::debug(sprintf("sortedQueryString: %s", $sortedQueryString));
        $stringToSign = sprintf("GET&%s&%s", self::special_url_encode('/'), self::special_url_encode($sortedQueryString));
        $sign = base64_encode(hash_hmac('sha1', $stringToSign, sprintf("%s&", $accessSecret), true));
        $Signature = self::special_url_encode($sign);

        $url = sprintf("http://dysmsapi.aliyuncs.com/?Signature=%s&%s", $Signature, $sortedQueryString);
        // log::debug(sprintf("url: %s", $url));
        $res = common::http_request($url);
        $res = ['data'=>$url];
        $data = $res['data'];
        if (isset($res['err'])) {
            log::debug(sprintf("请求失败 error :%s", $res['err']));
        }
        return $data;
    }
}

