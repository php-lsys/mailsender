<?php
namespace Mail;
use LSYS\MailSender\Handler\Render;
/**
 * 自定义邮件内容渲染
 * @author lonely
 */
class MyMailRender extends Render{
	public function renderAltbody(){
		return null;
	}
	function renderBody(){
		$title=$this->_eml->getTitle();
		$body='';
		foreach ($this->_eml->getBodyVars() as $k=>$v){
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
				this is email body
				{$body}
		</body>
		</html>
BODY;
	}
}