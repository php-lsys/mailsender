<?php
/**
 * lsys mail
 * 示例配置 未引入
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
return array(
	"phpmailer"=>array(
		"handler"=>\LSYS\MailSender\Handler\PHPMailer::class,
		"from"=>array("admim@doucao.cn","doucao admin"),
		"driver"=>'smtp',//smtp sendmail qmail mail
		"smtp"=>array(//only smtp driver
			'host'=>'127.0.0.1',
			'port'=>25,
			'timeout'=>60,
			'username'=>'',
			'password'=>'',
			'pre_pop3'=>false,
 			'pre_host'=>'127.0.0.1',
 			'pre_port'=>3306,
			'auth'=>false,
			'secure'=>false,
			'debug'=>false,
		),
		"render"=>array(//name -> render map
			//'dome_mail'=>\LSYS\MailSender\Handler\Render\Simple::class
		)
	),
);
