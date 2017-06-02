<?php

namespace App\Controllers\Test;

use EasyWeChat\Foundation\Application;
use limx\tools\wx\OAuth;
/** 微信支付 S */
use limx\tools\wx\pay\JsApiPay;
use limx\tools\wx\pay\data\WxPayUnifiedOrder;
use limx\tools\wx\pay\lib\WxPayApi;

/** 微信支付 E */
class WxController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {
        $name = "";
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')) {
            // 微信内打开
            $name .= "微信";
        }
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') || strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')) {
            $name .= "IOS";
        } else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Android')) {
            $name .= "Android";
        }
        $this->view->name = $name;
        return $this->view->render('test/wx', 'index');
    }

    /**
     * [infoAction desc]
     * @desc     微信获取授权OPENID的测试
     * @composer require limingxinleo/wx-api
     * @author   limx
     */
    public function infoAction()
    {
        $code = $this->request->get('code');
        $appid = env('APPID');
        $appsec = env('APPSECRET');
        $api = new OAuth($appid, $appsec);
        $api->code = $code;// 微信官方回调回来后 会携带code
        $url = env('APP_URL') . '/test/wx/info';//当前的URL
        $api->setRedirectUrl($url);
        $res = $api->getUserInfo();
        dump($res);
    }

    public function pcLoginAction()
    {
        $appid = env("WECHAT_PC_OPENID");
        $secret = env("WECHAT_PC_SECRET");
        $redirect_uri = 'http://wap.peppertv.cn/wx.php';
        $redirect_uri = urlencode($redirect_uri);
        $url = "https://open.weixin.qq.com/connect/qrconnect?appid=" . $appid . "&redirect_uri=" . $redirect_uri . "&response_type=code&scope=snsapi_login&state=STATE#wechat_redirect";

        return $this->response->redirect($url);
    }

    public function pcUserInfoAction()
    {
        $code = $this->request->get('code');
        $state = $this->request->get('state');
        $appid = env("WECHAT_PC_OPENID");
        $secret = env("WECHAT_PC_SECRET");

        $data = [];
        $data['appid'] = $appid;
        $data['secret'] = $secret;
        $data['code'] = $code;
        $data['grant_type'] = 'authorization_code';
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token";
        $res = $this->httpGet($url, $data);

        $access_token = $res['access_token'];
        $refresh_token = $res['refresh_token'];
        $openid = $res['openid'];
        $unionid = $res['unionid'];

        $data = [];
        $data['access_token'] = $access_token;
        $data['openid'] = $openid;
        $url = "https://api.weixin.qq.com/sns/userinfo";
        $res = $this->httpGet($url, $data);
        dump($res);
    }

    public function pcInfoAction()
    {
        $code = $this->request->get('code');
        print_r($code);
    }

    /**
     * [payAction desc]
     * @desc     微信JsApiPay支付
     * @composer require limingxinleo/wx-api
     * @author   limx
     * @return mixed
     */
    public function payAction()
    {
        //①、获取用户openid
        $tools = new JsApiPay();
        $tools->setBaseUrl(env('APP_URL') . '/test/wx/pay');
        $openId = $tools->GetOpenid();

        //②、统一下单
        $input = new WxPayUnifiedOrder();
        $input->SetBody("test");
        $input->SetAttach("test");
        $input->SetOut_trade_no(date("YmdHis"));
        $input->SetTotal_fee("1");
        $input->SetTime_start(date("YmdHis"));
        //$input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("test");
        $input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openId);
        $order = WxPayApi::unifiedOrder($input);
        echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
        dump($order);
        $jsApiParameters = $tools->GetJsApiParameters($order);
        dump($jsApiParameters);

        //获取共享收货地址js函数参数
        $editAddress = $tools->GetEditAddressParameters();
        dump($editAddress);

        //③、在支持成功回调通知中处理成功之后的事宜，见 notify.php
        /**
         * 注意：
         * 1、当你的回调地址不可访问的时候，回调通知会失败，可以通过查询订单来确认支付是否成功
         * 2、jsapi支付时需要填入用户openid，WxPay.JsApiPay.php中有获取openid流程 （文档可以参考微信公众平台“网页授权接口”，
         * 参考http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html）
         */
        $this->view->jsApiParameters = $jsApiParameters;
        return $this->view->render('test/wx', 'pay');
    }

    public function wechatAction()
    {
        $config = app('easywechat');
        dump($config);
        $app = new Application($config);
        $response = $app->oauth->scopes(['snsapi_userinfo'])->redirect();
        dump($response);
        $response->send();
    }

    public function wechatCallbackAction()
    {
        $config = app('easywechat');
        $app = new Application($config);
        $user = $app->oauth->user();
        dump($user);
    }

    private function httpGet($url, $params)
    {
        $body = http_build_query($params);
        $url .= "?" . $body;
        $ch = curl_init();
        // 设置抓取的url
        curl_setopt($ch, CURLOPT_URL, $url);
        // 启用时会将头文件的信息作为数据流输出。
        curl_setopt($ch, CURLOPT_HEADER, false);
        // 启用时将获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // 启用时会将服务器服务器返回的"Location: "放在header中递归的返回给服务器，使用CURLOPT_MAXREDIRS可以限定递归返回的数量。
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        //执行命令
        $result = curl_exec($ch);
        if ($result === false) {
            echo 'Curl error: ' . curl_error($ch);
            return false;
        }
        //关闭URL请求
        curl_close($ch);
        $res = json_decode($result, true);
        return $res;
    }

}

