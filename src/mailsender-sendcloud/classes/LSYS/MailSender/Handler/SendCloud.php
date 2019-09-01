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
use LSYS\Config;
/**
 * Sohu SendColud 模板发送邮件实现
 * 非模板发送邮件可以调用 smtp 服务来发送
* @author lonely
*/
class SendCloud extends Handler{
	//sohu sendcolud url
	protected $_label_id;
	public static $url='http://sendcloud.sohu.com/webapi/mail.send_template.json';
	/**
	 * 获得模板,你可以重写此方法动态设置模板
	 * @param Eml $eml
	 * @throws Exception
	 * @return string
	 */
	protected function _getTpl(Eml $eml){
		$tpls=(array)$this->_config->get("tpls",array());
		$tpl=$eml->getName();
		if (!$tpls[$tpl]||!isset($tpls[$tpl]['template_invoke_name'])){
			throw new Exception(strtr("can't find :tpl in config tpls or not find template_invoke_name",array(":tpl"=>$tpl)));
		}
		return $tpls[$tpl]['template_invoke_name'];
	}
	/**
	 * 获得标签ID,你可以重写此方法来动态设置标签ID
	 * @param Eml $eml
	 * @return int
	 */
	protected function _getLabelId(Eml $eml){
		$tpls=(array)$this->_config->get("tpls",array());
		$tpl=$eml->getName();
		if (!$tpls[$tpl]||!isset($tpls[$tpl]['label_id'])) return null;
		return $tpls[$tpl]['label_id'];
	}
	/**
	 * 获得头消息,你可以重写此方法动态设置头消息
	 * @param Eml $eml
	 * @return string
	 */
	protected function _getHeaders(Eml $eml){
		$tpls=(array)$this->_config->get("tpls",array());
		$tpl=$eml->getName();
		if (!$tpls[$tpl]||!isset($tpls[$tpl]['headers'])) return null;
		return json_encode($tpls[$tpl]['headers']);
	}
	/**
	 * {@inheritDoc}
	 * @see \LSYS\MailSender\Handler::send()
	 */
	public function send(Eml $eml){
		$tpl=$this->_getTpl($eml);
		$body=$eml->getBodyVars();
		$item=array(
			'to'=>array(),
			'sub'=>array(),
		);
		$vars=array();
		foreach ($this->_to as $v){
			$item['to'][]=array_shift($v);
			foreach ($body as $k=>$v){
				$vars[$k][]=$v;
			}
		}
		$item['sub']=$vars;
		$post=array(
				'api_user'=>$this->_config->get('api_user'),
				'api_key'=>$this->_config->get('api_key'),
				'from'=>$this->_from_mail,
				'subject'=>$eml->getTitle(),
				'template_invoke_name'=>$tpl,
				'substitution_vars'=>json_encode($item),
				'resp_email_id'=>'false'
		);
		$label_id=$this->_getLabelId($eml);
		if ($label_id) $post['label']=$label_id;
		$header=$this->_getHeaders($eml);
		if ($header) $post['headers']=$header;
		if (!empty($this->_from_name)){
			$post['fromname']=$this->_from_name;
		}
		if (!empty($this->_reply_to_mail)){
			$post['replyto']=$this->_reply_to_mail;
		}
		if (is_array($this->_cc)){
			$cc=array();
			foreach ($this->_cc as $v){
				$cc[]=array_shift($v);
			}
			if (count($cc)>0) $post['cc']=implode(";",$cc);
		}
		if (is_array($this->_bcc)){
			$cc=array();
			foreach ($this->_bcc as $v){
				$cc[]=array_shift($v);
			}
			if (count($cc)>0) $post['bcc']=implode(";",$cc);
		}
		foreach ($eml->getAttachment() as $k=>$v){
			$post['files['.$k.']']=new \CURLFile($v);
			//$post['files']=new \CURLFile($v);
		}
		$timeout=$this->_config->get("time_out",60);
		// 1. 初始化
		$ch = curl_init();
		// 2. 设置选项，包括URL
		curl_setopt($ch,CURLOPT_URL,self::$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_HEADER,0);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POST, true );
		curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
		curl_setopt($ch, CURLOPT_TIMEOUT,$timeout);
		$output = curl_exec($ch);
		if($output === FALSE ){
			$msg=curl_error($ch);
			$no=curl_errno($ch);
		}
		// 4. 释放curl句柄
		curl_close($ch);
		if($output === FALSE ) throw new Exception($msg,$no);
		$data=json_decode($output,true);
		if ($data==null) throw new Exception($output);
		if(isset($data['message'])&&$data['message']=='success') return true;
		
		$msg=array_pop($msg);
		if (is_array($msg))$msg=array_pop($msg);
		if (is_array($msg))$msg=json_encode($msg);
		throw new Exception($msg);
	}
}