<?php
namespace LSYS\MailSender;
/**
 * @method \LSYS\MailSender mailer($config=null)
 */
class DI extends \LSYS\DI{
    /**
     *
     * @var string default config
     */
    public static $config = 'mail.phpmailer';
    /**
     * @return static
     */
    public static function get(){
        $di=parent::get();
        !isset($di->mailer)&&$di->mailer(new \LSYS\DI\ShareCallback(function($config=null){
            return $config?$config:self::$config;
        },function($config=null){
            $config=\LSYS\Config\DI::get()->config($config?$config:self::$config);
            return \LSYS\MailSender::factory($config);
        }));
        return $di;
    }
}