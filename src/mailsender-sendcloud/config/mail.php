<?php
/**
 * lsys mail
 * 示例配置 未引入
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
return array(
	"sendcloud"=>array(
		"handler"=>\LSYS\MailSender\Handler\SendCloud::class,
		"from"=>array("support@doucao.cn","support"),
		"api_user"=>'test',
		"api_key"=>'b9j8UWZHG4ci7ZIs',
		//"time_out"=>60,//default 60
		"tpls"=>array(//name ->  map
			'test'=>array(
				//'label_id'=>'1111',//可以不要
				//'headers'=>array('header1'=>'value1'),//可以不要
				'template_invoke_name'=>'ifaxin_bill'
			)
		)
	),
);
