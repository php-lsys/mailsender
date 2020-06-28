<?php
return array(
	"phpmailer"=>array(//phpmailer 设配器设置
		"handler"=>\LSYS\MailSender\Handler\PHPMailer::class,
		"from"=>array("2420135034@qq.com","2420135034"),
		"driver"=>'smtp',//smtp sendmail qmail mail
		"smtp"=>array(//only smtp driver
			'host'=>'smtp.qq.com',
			'port'=>25,
			'timeout'=>60,
			'username'=>'2420135034@qq.com',
			'password'=>'...',
			'auth'=>true,
			'secure'=>false,
			'debug'=>false,
		),
		"render"=>array(//name -> render map
			'eml_name'=>\Mail\MyMailRender::class//渲染器,eml_name对应为eml的name
		)
	),
	"sendcloud"=>array(//sohu sendcoud 按模板发送配置
		"handler"=>\LSYS\MailSender\Handler\SendCloud::class,
		"from"=>array("support@doucao.cn","support"),
		"api_user"=>'qq2420135034',
		"api_key"=>'00',
		//"time_out"=>60,//default 60
		"tpls"=>array(//name ->  map
			'eml_name'=>array(
				//'label_id'=>'1111',//可以不要
// 				'headers'=>array('header1'=>'value1'),//可以不要
				'template_invoke_name'=>'ifaxin_bill'
			)
		)
	),
);
