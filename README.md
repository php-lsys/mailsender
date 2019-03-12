# 邮件发送接口
> 目的:使你的系统发送邮件不依赖与某个邮件发送方式,从而实现邮件发送系统的无缝切换
> 代码里只设置邮件内容变量,邮件内容剥离出代码层,并支持附件发送


> 本库未实现任何邮件发送实现,请根据实际需求引入以下包:

	"lsys/mailsender-phpmailer":"~2.0.0", #支持smtp等常用邮件协议发送邮件
	"lsys/mailsender-sendcloud":"~2.0.0" #sohu的sendcloud服务


使用示例:
```
//配置文件:dome/config/mail.php
//邮件渲染:dome/Mail/MyMail.php
//设置默认发送配置
//创建一个邮件内容
$simple=new Eml("eml_name");//eml_name 为对应配置值
$simple->set_title("aaa");//设置标题
$simple->set_body_vars(array("link"=>"http://baidu.com"));//设置内容变量
$simple->set_attachment(array(__DIR__."/test.png"));//设置附件
//创建一个发送邮件元素
$item=new Item($simple);
//添加收件人
$item->add_to("97148830@qq.com");//LSLSlsls1212312312
try{
	//执行发送
	$send=MailSender::factory()->send($item);
	var_dump($send);
}catch (\LSYS\Exception $e){
	print_r($e->getMessage());
}
```