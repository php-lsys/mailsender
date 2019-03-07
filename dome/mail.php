<?php
use LSYS\MailSender;
use LSYS\MailSender\Eml;
use LSYS\MailSender\Item;

include __DIR__."/Bootstarp.php";
include __DIR__."/Mail/MyMail.php";
//设置默认发送配置
// MailSender::$config="mail.sendcloud";
//得到一个邮件内容
$simple=new Eml("eml_name");//eml_name 为对应配置值
$simple->setTitle("aaa");//设置标题
$simple->setBodyVars(array("link"=>"http://baidu.com"));//设置内容变量
$simple->setAttachment(array(__DIR__."/test.png"));//设置附件
//创建一个发送邮件元素
$item=new Item($simple);
//添加收件人
$item->addTo("2420135034@qq.com");//LSLSlsls1212312312
try{
	//执行发送
	$send=MailSender::factory(\LSYS\Config\DI::get()->config("mail.phpmailer"))->send($item);
	//$send=\LSYS\MailSender\DI::get()->mailSender()->send($item);
	var_dump($send);
}catch (\LSYS\Exception $e){
	print_r($e);
}
