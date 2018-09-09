<?php
/**
 * lsys mail
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
namespace LSYS\MailSender;
/**
 * 邮件内容
 * @author lonely
 */
class Eml{
	protected $name;
	/**
	 * @param mixed $tpl
	 */
	public function __construct($name){
		$this->name=$name;
	}
	/**
	 * set mail tpl
	 * @return mixed
	 */
	public function get_name(){
		return $this->name;
	}
	protected $title='';
	/**
	 * set eml title vars
	 * @param array $data
	 * @return \LSYS\MailSender\Eml
	 */
	public function set_title($title){
		$this->title=$title;
		return $this;
	}
	/**
	 * get eml title vars
	 * @return array
	 */
	public function get_title(){
		return $this->title;
	}
	protected  $body_vars=array();
	/**
	 * set eml body vars
	 * @param array $data
	 * @return \LSYS\MailSender\Eml
	 */
	public function set_body_vars(array $data){
		$this->body_vars=$data;
		return $this;
	}
	/**
	 * get eml body vars
	 * @return array
	 */
	public function get_body_vars(){
		return $this->body_vars;
	}
	protected $atta=array();
	/**
	 * set eml attach vars
	 * @param array $data
	 * @return \LSYS\MailSender\Eml
	 */
	public function set_attachment(array $data){
		foreach ($data as $k=>$v){
			if (!is_file($v))unset($data[$k]);
		}
		$this->atta=array_values($data);
		return $this;
	}
	/**
	 * get eml attach vars
	 * @return array
	 */
	public function get_attachment(){
		return $this->atta;
	}
}