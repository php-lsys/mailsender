<?php
/**
 * lsys mail
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
namespace LSYS\MailSender;
/**
 * 邮件元素
 * @author lonely
 */
class Item{
	/**
	 * @var Eml
	 */
	protected $_eml;
	protected $_reply;
	protected $_to=array();
	protected $_cc=array();
	protected $_bcc=array();
	public function __construct(Eml $eml){
		$this->_eml=$eml;
	}
	/**
	 * 获取邮件内容
	 * @return \LSYS\MailSender\Eml
	 */
	public function get_eml(){
		return $this->_eml;
	}
	/**
	 * 设置回复
	 * @param string $email
	 * @param string $name
	 * @return \LSYS\MailSender\Item
	 */
	public function set_reply($email,$name=null){
		$this->_reply=array($email,$name);	
		return $this;
	}
	/**
	 * 设置接收
	 * @param string $email
	 * @param string $name
	 * @return \LSYS\MailSender\Item
	 */
	public function add_to($email,$name=null){
		$this->_to[]=array($email,$name);
		return $this;
	}
	/**
	 * 添加抄送
	 * @param string $email
	 * @param string $name
	 * @return \LSYS\MailSender\Item
	 */
	public function add_cc($email,$name=null){
		$this->_cc[]=array($email,$name);
		return $this;
	}
	/**
	 * 添加密送
	 * @param string $email
	 * @param string $name
	 * @return \LSYS\MailSender\Item
	 */
	public function add_bcc($email,$name=null){
		$this->_bcc[]=array($email,$name);
		return $this;
	}
	/**
	 * 获取接收者
	 * @return array
	 */
	public function get_tos(){
		return $this->_to;
	}
	/**
	 * 获取抄送
	 * @return array
	 */
	public function get_ccs(){
		return $this->_cc;
	}
	/**
	 * 获取密送
	 * @return array
	 */
	public function get_bccs(){
		return $this->_bcc;
	}
	/**
	 * 设置回复
	 * @return string
	 */
	public function get_reply(){
		return $this->_reply;
	}
}