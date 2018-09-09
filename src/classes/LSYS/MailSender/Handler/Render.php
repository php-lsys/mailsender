<?php
/**
 * lsys mail
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
namespace LSYS\MailSender\Handler;
use LSYS\MailSender\Eml;
/**
 * 邮件渲染抽象接口
 * @author lonely
 */
abstract class Render{
	protected $_eml;
	public function __construct(Eml $eml){
		$this->_eml=$eml;
	}
	/**
	 * return email alt body
	 * @return string
	 */
	public function render_altbody(){
		return null;
	}
	/**
	 * return email body
	 * return string
	 */
	abstract function render_body();
}