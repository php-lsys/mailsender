<?php
/**
 * lsys mail
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
namespace LSYS\MailSender;
use LSYS\Config;
/**
 * 邮件发送抽象接口
 * @author lonely
 */
abstract class Handler{
	/**
	 * @var Config
	 */
	protected $_config;
	protected $_from_mail;
	protected $_from_name;
	protected $_reply_to_mail;
	protected $_reply_to_name;
	protected $_to;
	protected $_cc;
	protected $_bcc;
	public function __construct(Config $config){
		$this->_config=$config;
	}
	/**
	 * 设置发送来源地址
	 * @param string $from_mail
	 * @param string $mail_name
	 * @return \LSYS\MailSender\Handler
	 */
	public function setFrom($from_mail,$mail_name=null){
		$this->_from_mail=$from_mail;
		$this->_from_name=$mail_name;
		return $this;
	}
	/**
	 * 设置回复地址
	 * @param string $reply_to_mail
	 * @param string $mail_name
	 * @return \LSYS\MailSender\Handler
	 */
	public function setReply($reply_to_mail,$mail_name=null){
		$this->_reply_to_mail=$reply_to_mail;
		$this->_reply_to_name=$mail_name;
		return $this;
	}
	/**
	 * 设置接受者
	 * @param array $tos
	 * @return \LSYS\MailSender\Handler
	 */
	public function setTo(array $tos){
		$this->_to=$tos;
		return $this;
	}
	/**
	 * 设置抄送
	 * @param array $ccs
	 * @return \LSYS\MailSender\Handler
	 */
	public function setCc(array $ccs){
		$this->_cc=$ccs;
		return $this;
	}
	/**
	 * 设置密送
	 * @param array $bccs
	 * @return \LSYS\MailSender\Handler
	 */
	public function setBcc(array $bccs){
		$this->_bcc=$bccs;
		return $this;
	}
	/**
	 * 进行发送邮件
	 * @param Eml $eml
	 */
	abstract public function send(Eml $eml);
}