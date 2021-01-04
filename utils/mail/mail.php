<?php
/*
 * @Author: your name
 * @Date: 2021-01-04 15:26:08
 * @LastEditTime: 2021-01-04 17:17:24
 * @LastEditors: Please set LastEditors
 * @Description: qq邮件
 * @FilePath: \php-demo-group\utils\mail\mail.php
 */


if( !defined('CORE') ) exit('Request Error!');


class mail
{
    protected $config;

    function __construct($config)
    {
        $this->config = $config;
    }

    public function send($to_user,$subject,$content)
    {
        
        // 判断是否使用html类型
        $type = $this->config['html'] ? 'Content-type: text/html;' : 'Content-type: text/plain;';

        $cmd = [
            "EHLO {$this->config['smtp_name']}\r\n",
            "AUTH LOGIN\r\n",
            base64_encode($this->config['smtp_user'])."\r\n",
            base64_encode($this->config['smtp_pass'])."\r\n",
            "MAIL FROM: <{$this->config['smtp_user']}>\r\n",
            "RCPT TO: <{$to_user}>\r\n",
            "DATA\r\n",
            "From: \"{$this->config['smtp_name']}\"<{$this->config['smtp_user']}>\r\n",
            "To: <{$to_user}>\r\n",
            "Subject:{$subject}\r\n",
            $type."\r\n",
            "\r\n",
            $content." \r\n",
            ".\r\n",
            "QUIT\r\n",
        ];

        $this->connect($cmd);

        return true;
    }

    // 链接 发送
    protected function connect($cmd)
    {
        //打开smtp服务器端口
        $fp = @pfsockopen($this->config['smtp_host'], $this->config['smtp_port']);
        $fp or die("Error: Cannot conect to ".$smtp_host);

        // 执行命令
        foreach ($cmd as $k => $v) {
            @fputs($fp, $v );

            // ************ 打印 *********** 
            $res= fgets($fp);
            log::debug("\n {$v} {$res} \n");
            // *****************************

            // sleep(1);
            // 延迟 0.5秒
            usleep(500000);
        }
    }
}

