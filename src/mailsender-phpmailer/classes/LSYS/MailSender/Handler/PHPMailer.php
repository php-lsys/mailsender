<?php
/**
 * lsys mail
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
namespace LSYS\MailSender\Handler;
use LSYS\MailSender\Handler;
use LSYS\MailSender\Eml;
use LSYS\Exception;
use LSYS\MailSender\Handler\Render\Simple;
use LSYS\Config;
use function LSYS\MailSender\__phpmailer as __;
/**
 * PHPMailer 发送邮件实现
* @author lonely
*/
class PHPMailer extends Handler{
	/**
	 * @var \PHPMailer
	 */
	protected $_mailer;
	/**
	 * @var array
	 */
	protected $_renders;
	/**
	 * @param Config $config
	 * @throws Exception
	 */
	public function __construct(Config $config){
		parent::__construct($config);
		$mailer = new \PHPMailer(true);
		$driver=$this->_config->get("driver");
		switch ($driver){
			case "smtp":
				$smtp=$this->_config->get("smtp",array());
				$smtp+=array(
					'host'=>'127.0.0.1',
					'port'=>25,
					'timeout'=>60,
					'username'=>'',
					'password'=>'',
					'pre_pop3'=>false,
					'pre_host'=>'127.0.0.1',
					'pre_port'=>'3306',
					'auth'=>false,
					'secure'=>false,
					'debug'=>false,
				);
				if ($smtp['pre_pop3']){
					$pop = new \POP3();
					$pop->Authorise(
						$smtp['pre_host'],
						$smtp['pre_port'],
						$smtp['timeout'],
						$smtp['username'],
						$smtp['password'],
						$smtp['debug']
					);
					unset($pop);
				}
				$mailer->isSMTP();
				$mailer->Host       = $smtp['host'];
				$mailer->Port       = $smtp['port'];
				$mailer->SMTPDebug  = $smtp['debug'];
				$mailer->SMTPAuth   = $smtp['auth'];
				$mailer->Username   = $smtp['username'];
				$mailer->Password   = $smtp['password'];
				$mailer->Timeout	  = $smtp['timeout'];
				$mailer->SMTPSecure = $smtp['secure'];
				break;
			case 'sendmail':
				$mailer->IsSendmail();
				break;
			case 'qmail':
				$mailer->IsQmail();
				break;
			case 'mail':
				$mailer->IsMail();
			default:
				throw new Exception(__("phpmailer config driver wrong [:driver]",array(":driver"=>$driver)));
		}
		$this->_mailer=$mailer;
		
		$this->_renders=(array)$this->_config->get("render",array());
		
	}
	/**
	 * {@inheritDoc}
	 * @see \LSYS\MailSender\Handler::send()
	 */
	public function send(Eml $eml){
		$mail=$this->_mailer;
		//Set who the message is to be sent from
		if (!empty($this->_from_mail)) $mail->setFrom($this->_from_mail,$this->_from_name);
		//Set an alternative reply-to address
		if (!empty($this->_reply_to_mail)) $mail->addReplyTo($this->_reply_to_mail, $this->_reply_to_name);
		foreach ($this->_to as $v){
			call_user_func_array(array($mail,'addAddress'),$v);
		}
		if (is_array($this->_cc)){
			foreach ($this->_cc as $v){
				call_user_func_array(array($mail,'addCC'),$v);
			}
		}
		if (is_array($this->_bcc)){
			foreach ($this->_bcc as $v){
				call_user_func_array(array($mail,'addBCC'),$v);
			}
		}
		$tpl=$eml->getName();
		if (isset($this->_renders[$tpl])){
			$class = $this->_renders[$tpl];
			if (!class_exists($class)||!in_array('LSYS\MailSender\Handler\Render', class_parents($class))){
				throw new Exception(
					__("phpmailer config wrong,render :class not extends \LSYS\MailSender\Handler\Render",
					array(":class"=>$class))
				);
			}
			$render=new $this->_renders[$tpl]($eml);
		}else $render=$this->defaultRender($eml);
		//Set the subject line
		$mail->Subject = $eml->getTitle();
		$alt_body=$render->renderAltbody();
		if (!empty($alt_body)) $mail->AltBody =$alt_body;
		$mail->msgHTML($render->renderBody());
		foreach ($eml->getAttachment() as $v)$mail->addAttachment($v);
		try{
			return $mail->send();
		}catch (\phpmailerException $e){
			throw new Exception($e->getMessage(),$e->getCode(),$e);
		}
	}
	/**
	 * get default render
	 * @param Eml $eml
	 * @return \LSYS\MailSender\Handler\Render\Simple
	 */
	public function defaultRender($eml){
		return new Simple($eml);
	}
}
