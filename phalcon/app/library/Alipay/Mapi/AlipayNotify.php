<?php
// +----------------------------------------------------------------------
// | Notify.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Library\Alipay\Mapi;
class AlipayNotify
{
    /**
     * HTTPS形式消息验证地址
     */
    var $https_verify_url = 'https://mapi.alipay.com/gateway.do?service=notify_verify&';
    /**
     * HTTP形式消息验证地址
     */
    var $http_verify_url = 'http://notify.alipay.com/trade/notify_query.do?';
    var $alipay_config;

    public function __construct($alipay_config)
    {
        $this->alipay_config = $alipay_config;
    }

    /**
     * 针对notify_url验证消息是否是支付宝发出的合法消息
     * @return 验证结果
     */
    public function verifyNotify()
    {
        if (empty($_POST)) {//判断POST来的数组是否为空
            return false;
        } else {
            //生成签名结果
            $isSign = $this->getSignVeryfy($_POST, $_POST["sign"]);
            //获取支付宝远程服务器ATN结果（验证是否是支付宝发来的消息）
            $responseTxt = 'false';
            if (!empty($_POST["notify_id"])) {
                $responseTxt = $this->getResponse($_POST["notify_id"]);
            }

            if (preg_match("/true$/i", $responseTxt) && $isSign) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * 针对return_url验证消息是否是支付宝发出的合法消息
     * @return 验证结果
     */
    public function verifyReturn()
    {
        if (empty($_GET)) {//判断POST来的数组是否为空
            return false;
        } else {
            //生成签名结果
            $isSign = $this->getSignVeryfy($_GET, $_GET["sign"]);
            //获取支付宝远程服务器ATN结果（验证是否是支付宝发来的消息）
            $responseTxt = 'false';
            if (!empty($_GET["notify_id"])) {
                $responseTxt = $this->getResponse($_GET["notify_id"]);
            }

            //写日志记录
            //if ($isSign) {
            //	$isSignStr = 'true';
            //}
            //else {
            //	$isSignStr = 'false';
            //}
            //$log_text = "responseTxt=".$responseTxt."\n return_url_log:isSign=".$isSignStr.",";
            //$log_text = $log_text.createLinkString($_GET);
            //logResult($log_text);

            //验证
            //$responsetTxt的结果不是true，与服务器设置问题、合作身份者ID、notify_id一分钟失效有关
            //isSign的结果不是true，与安全校验码、请求时的参数格式（如：带自定义参数等）、编码格式有关
            if (preg_match("/true$/i", $responseTxt) && $isSign) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * 获取返回时的签名验证结果
     * @param $para_temp 通知返回来的参数数组
     * @param $sign      返回的签名结果
     * @return 签名验证结果
     */
    public function getSignVeryfy($para_temp, $sign)
    {
        //除去待签名参数数组中的空值和签名参数
        $para_filter = Core::paraFilter($para_temp);

        //对待签名参数数组排序
        $para_sort = Core::argSort($para_filter);

        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = Core::createLinkstring($para_sort);

        $isSgin = false;
        switch (strtoupper(trim($this->alipay_config['sign_type']))) {
            case "MD5" :
                $isSgin = Md5::md5Verify($prestr, $sign, $this->alipay_config['key']);
                break;
            default :
                $isSgin = false;
        }

        return $isSgin;
    }

    /**
     * 获取远程服务器ATN结果,验证返回URL
     * @param $notify_id 通知校验ID
     * @return 服务器ATN结果
     *                   验证结果集：
     *                   invalid命令参数不对 出现这个错误，请检测返回处理中partner和key是否为空
     *                   true 返回正确信息
     *                   false 请检查防火墙或者是服务器阻止端口问题以及验证时间是否超过一分钟
     */
    public function getResponse($notify_id)
    {
        $transport = strtolower(trim($this->alipay_config['transport']));
        $partner = trim($this->alipay_config['partner']);
        $veryfy_url = '';
        if ($transport == 'https') {
            $veryfy_url = $this->https_verify_url;
        } else {
            $veryfy_url = $this->http_verify_url;
        }
        $veryfy_url = $veryfy_url . "partner=" . $partner . "&notify_id=" . $notify_id;
        $responseTxt = Core::getHttpResponseGET($veryfy_url, $this->alipay_config['cacert']);

        return $responseTxt;
    }

    /**
     * 针对notify_url验证消息是否是支付宝发出的合法消息
     * @return 验证结果
     *设置原始内容，确保PHP环境支持simplexml_load_string函数才可以
     *一般PHP5环境下没问题，PHP4需要检测一下环境是否安装了simplexml模块
     */
    public function verifySign($xmlStr)
    {
        if (empty($xmlStr)) {//判断POST来的数组是否为空
            return false;
        } else {
            $arrays = array();   //集合类型map
            $xml = simplexml_load_string($xmlStr);
            $v = '';
            $sign = '';
            if ($xml && $xml->children()) {
                foreach ($xml->children() as $node) {
                    //有子节点
                    if ($node->getName() == "response" && $node->children()) {
                        $k = $node->getName();
                        $nodeXml = $node->asXML();
                        $v = substr($nodeXml, strlen($k) + 2, strlen($nodeXml) - 2 * strlen($k) - 5);
                        //$xmlStr = $v;
                    } else if ($node->getName() == "sign") {
                        $sign = (string)$node;
                    }
                }
            }

            $xml = simplexml_load_string($v);
            if ($xml && $xml->children()) {
                foreach ($xml->children() as $node) {
                    //有子节点
                    if ($node->children()) {
                        $k = $node->getName();
                        $nodeXml = $node->asXML();
                        $v = substr($nodeXml, strlen($k) + 2, strlen($nodeXml) - 2 * strlen($k) - 5);

                    } else {
                        $k = $node->getName();
                        $v = (string)$node;
                    }
                    $arrays[$k] = $v;

                }
            }
            //生成签名结果
            $isSign = $this->getSignVeryfy($arrays, $sign);

            if ($isSign) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * @param 支付宝同步返回的xml信息
     * @return array集合
     *设置原始内容，确保PHP环境支持simplexml_load_string函数才可以
     *一般PHP5环境下没问题，PHP4需要检测一下环境是否安装了simplexml模块
     */
    public function getRspFromXML($xmlStr)
    {
        if (empty($xmlStr)) {//判断POST来的数组是否为空
            return false;
        }
        $arrays = array();   //集合类型map
        $xml = simplexml_load_string($xmlStr);
        if ($xml && $xml->children()) {
            foreach ($xml->children() as $node) {
                //有子节点
                if ($node->getName() == "response" && $node->children()) {
                    $k = $node->getName();
                    $nodeXml = $node->asXML();
                    $v = substr($nodeXml, strlen($k) + 2, strlen($nodeXml) - 2 * strlen($k) - 5);
                    //$xmlStr = $v;
                } else if ($node->getName() == "is_success") {
                    $arrays[$node->getName()] = (string)$node;
                }
            }
        }
        $xml = simplexml_load_string($v);
        if ($xml && $xml->children()) {
            foreach ($xml->children() as $node) {
                //有子节点
                if ($node->children()) {
                    $k = $node->getName();
                    $nodeXml = $node->asXML();
                    $v = substr($nodeXml, strlen($k) + 2, strlen($nodeXml) - 2 * strlen($k) - 5);

                } else {
                    $k = $node->getName();
                    $v = (string)$node;
                }
                $arrays[$k] = $v;

            }
        }
        return $arrays;
    }
}