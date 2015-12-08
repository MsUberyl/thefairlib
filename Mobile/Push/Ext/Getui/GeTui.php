<?php
/**
 * GeTui.php
 *
 * @author ZhangHan <zhanghan@thefair.net.cn>
 * @version 1.0
 * @copyright 2015-2025 TheFair
 */
namespace TheFairLib\Mobile\Push\Ext\GeTui;

use TheFairLib\Config\Config;
use TheFairLib\Mobile\Push\Ext\PushInterface;
use Yaf\Exception;

require_once(dirname(__FILE__) . '/' . 'IGt.Push.php');
require_once(dirname(__FILE__) . '/' . 'igetui/IGt.AppMessage.php');
require_once(dirname(__FILE__) . '/' . 'igetui/IGt.APNPayload.php');
require_once(dirname(__FILE__) . '/' . 'igetui/template/IGt.BaseTemplate.php');
require_once(dirname(__FILE__) . '/' . 'IGt.Batch.php');
require_once(dirname(__FILE__) . '/' . 'igetui/utils/AppConditions.php');

class GeTui implements PushInterface
{
    //http的域名
    private $_httpHost = 'http://sdk.open.api.igexin.com/apiex.htm';
    private $_httpsHost = 'https://api.getui.com/apiex.htm';

    private $_appID = null;
    private $_appSecret = null;
    private $_appKey = null;
    private $_masterSecret = null;

    private $_iGeTui = null;

    public function __construct(){
        //获取个推配置
        $config = Config::get_notification_push_getui('system_conf');
        if(empty($config) || empty($config['app_id']) || empty($config['app_secret']) || empty($config['app_key']) || empty($config['master_secret'])){
            throw new Exception('getui conf error');
        }
        $this->_appID = $config['app_id'];
        $this->_appSecret = $config['app_secret'];
        $this->_appKey = $config['app_key'];
        $this->_masterSecret = $config['master_secret'];

        $this->_iGeTui = new \IGeTui($this->_httpHost, $this->_appKey, $this->_masterSecret, false);
        return $this;
    }

    public function sendPushToSingleDevice($deviceToken, $platform, $message){
        $template = new \IGtAPNTemplate();
        $template->set_pushInfo("actionLocKey", 6, $message, "", "payload", "locKey", "locArgs", "launchImage",1);
        $messageObj = new \IGtSingleMessage();
        $messageObj->set_data($template);
        $ret = $this->_iGeTui->pushAPNMessageToSingle($this->_appID, $deviceToken, $messageObj);

        return $ret;
    }

    public function sendPushToDeviceList($deviceToken, $platform, $message){

    }
}