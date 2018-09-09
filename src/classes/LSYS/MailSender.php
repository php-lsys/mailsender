<?php
/**
 * lsys mail
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
namespace LSYS;
use function LSYS\MailSender\__;
use LSYS\MailSender\Item;
use LSYS\MailSender\Handler;
use LSYS\MailSender\Eml;
/**
 * 邮件发送对外接口
 * @author lonely
 */
class MailSender{
	/**
	 * @param Config
	 * @return self
	 */
    public static function factory(Config $config) {
        $handler=$config->get("handler",NULL);
        if ($handler==null){
            throw new Exception(__('Mail handler not defined in [:name] configuration',array("name"=>$config->name()) ));
        }
        if (!class_exists($handler)||!in_array(\LSYS\MailSender\Handler::class,class_parents($handler))){
            throw new Exception(__("mail handler [:handler] wong,not extends \LSYS\MailSender\Handler",array("handler"=>$handler)));
        }
        $handler=new $handler($config);
        return new static($handler,$config);
	}
	/**
	 * @var Handler
	 */
	protected $_handler;
	/**
	 * @var Config
	 */
	protected $_config;
	/**
	 * @param Config $config
	 * @param Handler $handler
	 */
	public function __construct(Handler $handler,Config $config){
		$this->_config=$config;
		$this->_handler=$handler;
	}
	/**
	 * filter email list array
	 * @param mixed $address
	 * @return array
	 */
	private function _filter_emails($address){
		if (!is_array($address))return array();
		foreach ($address as $k=>&$v){
			if(empty($v))continue;
			if (is_string($v)) $v=array($v,null);
			
			if (count($v)!=2||!$this->_valid_email(@$v[0]))unset($address[$k]);
			
			if (@$v[1]==null) $v[1]=substr($v[0], 0,strrpos($v[0], '@'));
		}
		return $address;
	}
	/**
	 * send mail
	 * @param Item $item
	 * @throws Exception
	 */
	public function send(Item $item){
		$to=$item->get_tos();
		$to=$this->_filter_emails($to);
		
		if(count($to)==0)throw new Exception(__("send mail need to email address"));//需要收件人地址
		$this->_handler->set_to($to);
		
		$cc=$this->_filter_emails($item->get_ccs());
		if (count($cc)>0) $this->_handler->set_cc($cc);
		
		$bcc=$this->_filter_emails($item->get_bccs());
		if (count($bcc)>0) $this->_handler->set_bcc($bcc);
		
		$reply=$this->_filter_emails(array($item->get_reply()));
		if (count($reply)>0){
			list($email,$name)=array_shift($reply);
			$this->_handler->set_reply($email,$name);
		}
		
		$from=(array)$this->_config->get("from",array());
		if (count($from)==2){
			list($email,$name)=$from;
			$this->_valid_email($email)&&$this->_handler->set_from($email,$name);
		}
		
		$eml=$item->get_eml();
		if (!$eml instanceof  Eml) throw new Exception(__("send mail need eml"));//需要发送的邮件
		
		return $this->_handler->send($eml);
	}
	protected function _valid_email($email){
		if (mb_strlen($email,Core::$charset) > 254)
		{
			return FALSE;
		}
		$expression = '/^[-_a-z0-9\'+*$^&%=~!?{}]++(?:\.[-_a-z0-9\'+*$^&%=~!?{}]+)*+@(?:(?![-.])[-a-z0-9.]+(?<![-.])\.[a-z]{2,6}|\d{1,3}(?:\.\d{1,3}){3})$/iD';
		return (bool) preg_match($expression, (string) $email);
	}
	
}