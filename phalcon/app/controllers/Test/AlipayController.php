<?php

namespace App\Controllers\Test;

use App\Library\Alipay\AlipayClient;
use App\Library\Alipay\ZhimaClient;
use App\Utils\Cache;
use App\Utils\Log;
use function GuzzleHttp\Psr7\parse_query;
use limx\Support\Str;

class AlipayController extends Controller
{
    protected $redirectUrl;

    protected $appUrl;

    public function initialize()
    {
        $this->redirectUrl = env("MONSTER_ALIPAY_REDIRECT_URI");
        $this->appUrl = env('APP_URL');
        parent::initialize();
    }

    /**
     * @desc   用户授权信息 个人信息
     * @author limx
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function userInfoAction()
    {
        $client = AlipayClient::getInstance();
        $code = $this->request->get('auth_code');
        if (empty($code)) {
            $redirect_url = $this->redirectUrl . "/test/alipay/userInfo";
            $url = $client->getOauthCodeUrl($redirect_url);
            return $this->response->redirect($url);
        }

        $oauth_info = $client->getOauthInfo($code);

        dump($this->request->get());
        dump($oauth_info);

        $access_token = $oauth_info->access_token;
        $user_id = $oauth_info->user_id;
        $userinfo = $client->getUserInfo($access_token);

        dump($userinfo);
    }

    /**
     * @desc   支付
     * @author limx
     */
    public function paymentAction()
    {
        $client = AlipayClient::getInstance();
        $notify_url = $this->redirectUrl . "/test/alipay/notify";
        $return_url = $this->redirectUrl . "/test/alipay/return";
        $res = $client->getPaymentOrder("ORDER" . Str::random(12), 0.01, $notify_url, $return_url);

        echo $res;
    }

    /**
     * @desc   支付宝代扣 首次签约并扣款
     * @author limx
     */
    public function withholdingAction()
    {
        $client = AlipayClient::getInstance();

        $return_url = $this->appUrl . "/test/alipay/return";
        $cancel_url = $this->appUrl . "/test/alipay/cancel";
        $notify_url = $this->appUrl . "/test/alipay/notify";
        $out_trade_no = "ORDER" . uniqid();

        $result = $client->withholdingCreateAndPay(
            $out_trade_no, 0.01, $return_url, $notify_url, $cancel_url
        );

        dump($result);
        return $this->response->redirect($result);
    }

    /**
     * @desc   代扣签约
     * @author limx
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function signAction()
    {
        $client = AlipayClient::getInstance();

        $return_url = $this->appUrl . "/test/alipay/return";
        $cancel_url = $this->appUrl . "/test/alipay/cancel";
        $notify_url = $this->appUrl . "/test/alipay/signNotify";
        $out_trade_no = "ORDER" . uniqid();

        $result = $client->withholdingSign($return_url, $notify_url);

        dump($result);
        return $this->response->redirect($result);
    }

    /**
     * @desc   代扣签约回调
     * @author limx
     */
    public function signNotifyAction()
    {
        $verify_result = AlipayClient::getInstance()->mapiVerify();

        if ($verify_result) {//验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代
            $data = $this->request->get();
            Log::info(json_encode($data));

            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            $partner_id = $this->request->get('partner_id');
            $notify_time = $this->request->get('notify_time');
            $sign_modify_time = $this->request->get('sign_modify_time');
            $status = $this->request->get('status');
            $agreement_no = $this->request->get('agreement_no'); // 签约ID 后面代扣时需要此参数
            $sign_type = $this->request->get('sign_type');
            $alipay_user_id = $this->request->get('alipay_user_id'); // 用户支付宝ID
            $notify_type = $this->request->get('notify_type');
            $invalid_time = $this->request->get('invalid_time'); // 签约失效时间
            $sign = $this->request->get('sign');
            $valid_time = $this->request->get('valid_time'); // 签约时间
            $product_code = $this->request->get('product_code');
            $scene = $this->request->get('scene');
            $notify_id = $this->request->get('notify_id');
            $sign_time = $this->request->get('sign_time');

            // 存储签约ID
            Cache::save('agreement_no', $agreement_no);


            //判断是否在商户网站中已经做过了这次通知返回的处理
            //如果没有做过处理，那么执行商户的业务程序
            //如果有做过处理，那么不执行商户的业务程序

            echo "success";        //请不要修改或删除

            //调试用，写文本函数记录程序运行情况是否正常
            //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");

            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        } else {
            //验证失败
            echo "fail";

            //调试用，写文本函数记录程序运行情况是否正常
            //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
        }
    }

    /**
     * @desc   代扣
     * @author limx
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function withholdingPayAction()
    {
        $client = AlipayClient::getInstance();

        $return_url = $this->appUrl . "/test/alipay/return";
        $cancel_url = $this->appUrl . "/test/alipay/cancel";
        $notify_url = $this->appUrl . "/test/alipay/payNotify";
        $out_trade_no = "ORDER" . uniqid();
        $aggrement_no = Cache::get('agreement_no'); // 签约返回的NO
        $result = $client->withholdingPay($aggrement_no, $out_trade_no, 0.01, $return_url, $notify_url);

        dump($result);
    }

    /**
     * @desc   代扣回调
     * @author limx
     */
    public function payNotifyAction()
    {
        $verify_result = AlipayClient::getInstance()->mapiVerify();

        if ($verify_result) {
            //验证成功
            //请在这里加上商户的业务逻辑程序代

            $data = $this->request->get();
            Log::info(json_encode($data));

            $trade_no = $this->request->get('trade_no');
            $subject = $this->request->get('subject');
            $paytools_pay_amount = $this->request->get('paytools_pay_amount');
            $buyer_email = $this->request->get('buyer_email');
            $gmt_create = $this->request->get('gmt_create');
            $notify_type = $this->request->get('notify_type');
            $quantity = $this->request->get('quantity');
            $out_trade_no = $this->request->get('out_trade_no'); // 商户订单号 根据此字段修改订单状态
            $seller_id = $this->request->get('seller_id');
            $notify_time = $this->request->get('notify_time');
            $trade_status = $this->request->get('trade_status'); // 支付状态 TRADE_SUCCESS支付成功
            $total_fee = $this->request->get('total_fee');
            $gmt_payment = $this->request->get('gmt_payment');
            $seller_email = $this->request->get('seller_email');
            $notify_action_type = $this->request->get('notify_action_type');
            $price = $this->request->get('price');
            $buyer_id = $this->request->get('buyer_id');
            $notify_id = $this->request->get('notify_id');
            $sign_type = $this->request->get('sign_type');
            $sign = $this->request->get('sign');
            if ($trade_status == 'TRADE_SUCCESS') {
                // 订单逻辑处理
                Log::info("代扣成功！！！");
            }

            echo "success";


        } else {

            echo "fail";
        }
    }

    public function zhimaAuthAction()
    {
        $mobile = $this->request->get('mobile');
        // 芝麻设置回调地址为 zhimaAuthRet
        $client = ZhimaClient::getInstance();
        $auth_url = $client->getAuthInfoByMobile($mobile);

        dump($this->request->get());
        dump($auth_url);

        return $this->response->redirect($auth_url);
    }

    public function zhimaAuthRetAction()
    {
        $params = $this->request->get('params');
        $sign = $this->request->get('sign');

        $client = ZhimaClient::getInstance();
        $result_str = $client->getAuthInfoResult($params, $sign);
        $result = [];
        if ($result_str) {
            parse_str($result_str, $result);
            $open_id = $result['open_id'];
            $error_message = $result['error_message'];
            $status = $result['status'];
            $error_code = $result['error_code'];
            $app_id = $result['app_id'];
            $success = $result['success'];
            dump($result);

            if ($error_code == 'SUCCESS') {
                // 获取信用分
                $result = $client->getCreditScore($open_id);
                dump($result);
            }
        }
    }

    public function cancelAction()
    {
        $data = $this->request->get();
        $data['ret'] = "CANCEL";
        dump($data);
    }

    public function returnAction()
    {
        $data = $this->request->get();
        $data['ret'] = "SUCCESS";
        dump($data);
    }

    public function notifyAction()
    {
        Log::info("DEBUG ALIPAY NOTIFY");
        $data = $this->request->get();
        unset($data['_url']);
        Log::info("DEBUG ALIPAY " . json_encode($data));
        $result = AlipayClient::getInstance()->verify($data);

        /* 实际验证过程建议商户添加以下校验。
         1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
         2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
         3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
         4、验证app_id是否为该商户本身。
        */
        if ($result) {//验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代
            Log::info("DEBUG ALIPAY VERIFIED " . json_encode($data));

            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——

            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表

            //商户订单号

            $out_trade_no = $this->request->get('out_trade_no');

            //支付宝交易号

            $trade_no = $this->request->get('trade_no');

            //交易状态
            $trade_status = $this->request->get('trade_status');

            // 时间
            $now = time();


            if ($trade_status == 'TRADE_FINISHED') {

                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                //如果有做过处理，不执行商户的业务程序

                //注意：
                //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
            } else if ($trade_status == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                //如果有做过处理，不执行商户的业务程序
                //注意：
                //付款完成后，支付宝系统发送该交易状态通知

                // TODO:修改支付宝支付订单状态
                Log::info('DEBUG ALIPAY ORDER TRADE_SUCCESS ' . $out_trade_no);

            } else {

            }
            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

            echo "success";        //请不要修改或删除

        } else {
            //验证失败
            echo "fail";    //请不要修改或删除

        }
    }

}

