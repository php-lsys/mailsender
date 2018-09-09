<?php
/**
 * lsys mail
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
namespace LSYS\MailSender\Handler\Render;
use LSYS\MailSender\Handler\Render;
/**
 * 一个邮件内容渲染的简单实现示例 
 * @author lonely
 */
class Simple extends Render{
	public function render_altbody(){
		return null;
	}
	function render_body(){
		$title=$this->_eml->get_title();
		$body='';
		
		foreach ($this->_eml->get_body_vars() as $k=>$v){
			$body.=(is_string($k)?($k.":"):'').$v."<br/>";
		}
		return $this->_body($title, $body);
	}
	private function _body($title,$body){
		return <<<BODY
		<!DOCTYPE HTML>
		<html>
				<title>{$title}</title>
		<body>
				{$body}
		</body>
		</html>
BODY;
	}
	
}