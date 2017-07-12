<?php
// +----------------------------------------------------------------------
// | Alipay.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Library\Alipay;

use AopClient;
use AlipaySystemOauthTokenRequest;
use AlipayTradeWapPayRequest;
use ZhimaCreditScoreGetRequest;
use AlipayAcquireCreateandpayRequest;
use App\Library\Alipay\Mapi\AlipayNotify;
use App\Library\Alipay\Mapi\AlipaySubmit;
use App\Library\Alipay\Mapi\Config;
use App\Utils\Log;
use limx\Support\Str;

class AlipayClient
{
    public $appId;

    public $redirectUri;

    public $aliPublicKey;

    public $appPrivateKey;

    public $sellerId;

    public $parterId;

    protected $gatewayUrl = 'https://openapi.alipay.com/gateway.do';

    protected $signType = 'RSA2';

    protected $postCharset = 'UTF-8';

    protected $apiVersion = '1.0';

    protected $format = 'json';

    protected $aopClient;

    public static $instances;


    public function __construct()
    {
        $this->appId = env("MONSTER_ALIPAY_APPID");
        $this->redirectUri = env("MONSTER_ALIPAY_REDIRECT_URI");
        $this->aliPublicKey = env("MONSTER_ALIPAY_ALI_PUBLIC_KEY");
        $this->appPrivateKey = env("MONSTER_ALIPAY_APP_PRIVATE_KEY");
        $this->sellerId = env("MONSTER_ALIPAY_SELLERID");
        $this->parterId = env("MONSTER_ALIPAY_PID");

        include_once __DIR__ . '/AopSdk.php';

        $this->aopClient = $this->getAopClient();
    }

    public static function getInstance()
    {
        if (!isset(self::$instances) || !(self::$instances instanceof AlipayClient)) {
            self::$instances = new AlipayClient();
        }
        return self::$instances;
    }

    public function getAopClient()
    {
        $aop = new AopClient();
        $aop->gatewayUrl = $this->gatewayUrl;
        $aop->appId = $this->appId;
        $aop->rsaPrivateKey = $this->appPrivateKey;
        $aop->alipayrsaPublicKey = $this->aliPublicKey;
        $aop->signType = $this->signType;
        $aop->postCharset = $this->postCharset;
        $aop->apiVersion = $this->apiVersion;
        $aop->format = $this->format;

        return $aop;
    }

    public function getOauthCodeUrl($redirect_uri, $scope = 'auth_user')
    {
        $url = 'https://openauth.alipay.com/oauth2/publicAppAuthorize.htm?';
        $params = [
            'app_id' => $this->appId,
            'scope' => $scope,
            'redirect_uri' => $redirect_uri
        ];
        return $url . http_build_query($params);
    }

    /**
     * @desc   获取授权信息
     * @author limx
     * @param $authCode
     * @return \SimpleXMLElement[]
     */
    public function getOauthInfo($authCode)
    {
        $request = new AlipaySystemOauthTokenRequest();
        $request->setGrantType("authorization_code");
        $request->setCode($authCode);
        $result = $this->aopClient->execute($request);
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";

        return $result->$responseNode;

    }

    public function getUserInfo($accessToken)
    {
        $request = new \AlipayUserInfoShareRequest();
        $result = $this->aopClient->execute($request, $accessToken);
        return $result;
    }

    /**
     * @desc   创建支付订单
     * @author limx
     * @return string|\提交表单HTML文本
     */
    public function getPaymentOrder($order_no, $real_price, $notifyUrl, $returnUrl)
    {
        $req = new AlipayTradeWapPayRequest();
        $data['out_trade_no'] = $order_no;
        $data['total_amount'] = $real_price;
        $data['subject'] = '测试支付';
        $data['seller_id'] = $this->sellerId;
        $data['product_code'] = 'QUICK_WAP_PAY';
        $bizContent = json_encode($data);
        $req->setBizContent($bizContent);
        $req->setNotifyUrl($notifyUrl);
        $req->setReturnUrl($returnUrl);
        Log::info($notifyUrl);
        // return $this->aopClient->pageExecute($req, "GET");
        return $this->aopClient->pageExecute($req, "POST");
    }

    public function getCreditScore($accessToken)
    {
        $request = new ZhimaCreditScoreGetRequest();
        $data['transaction_id'] = Str::random(64);
        $data['product_code'] = 'w1010100100000000001';
        $request->setBizContent(json_encode($data));
        $result = $this->aopClient->execute($request, $accessToken);
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        return $result->$responseNode;
    }

    /**
     * @desc   代扣签约并扣款
     * @author limx
     * @param $outTradeNo     订单号
     * @param $totalFee       费用
     * @param $returnUrl      签约成功后会把签约结果同步返回给客户端。
     * @param $notifyUrl
     * @param $requestFromUrl 如果用户中途取消支付会返回该地址(唤起app)。
     */
    public function withholdingCreateAndPay($outTradeNo, $totalFee, $returnUrl, $notifyUrl, $requestFromUrl)
    {
        //构造要请求的参数数组，无需改动
        $data['service'] = 'alipay.acquire.page.createandpay';
        $data['partner'] = $this->parterId;
        $data['seller_id'] = $this->sellerId;
        $data['_input_charset'] = strtolower($this->postCharset);

        $data['out_trade_no'] = $outTradeNo;
        $data['subject'] = '签约并扣款测试';
        $data['product_code'] = 'GENERAL_WITHHOLDING';
        $data['total_fee'] = $totalFee;
        $data['return_url'] = $returnUrl;
        $data['notify_url'] = $notifyUrl;
        $data['request_from_url'] = $requestFromUrl;
        $data['integration_type'] = "ALIAPP";
        $data['agreement_sign_parameters'] = json_encode([
            'productCode' => 'GENERAL_WITHHOLDING_P',
            'scene' => 'INDUSTRY|DIGITAL_MEDIA',
            'externalAgreementNo' => $outTradeNo,
            'notifyUrl' => $notifyUrl,
        ]);

        $config = new Config();
        $alipaySubmit = new AlipaySubmit($config);
        $html_text = $alipaySubmit->buildRequestForm($data);
        return $html_text;
    }

    /**
     * @desc   代扣签约
     * @author limx
     * @param $returnUrl
     * @param $notifyUrl
     * @return Mapi\提交表单HTML文本
     */
    public function withholdingSign($returnUrl, $notifyUrl)
    {
        //构造要请求的参数数组，无需改动
        $data['service'] = 'alipay.dut.customer.agreement.page.sign';
        $data['partner'] = $this->parterId;
        $data['_input_charset'] = strtolower($this->postCharset);
        $data['return_url'] = $returnUrl;
        $data['notify_url'] = $notifyUrl;

        $data['product_code'] = 'GENERAL_WITHHOLDING_P';
        $data['access_info'] = json_encode(['channel' => 'WAP']);
        $data['sign_validity_period'] = '1d'; // 签约有效期 可空

        $config = new Config();
        $alipaySubmit = new AlipaySubmit($config);
        $html_text = $alipaySubmit->buildRequestForm($data);
        return $html_text;
    }

    /**
     * @desc   代扣支付
     * @author limx
     * @param $aggrementNo
     * @param $outTradeNo
     * @param $totalFee
     * @param $returnUrl
     * @param $notifyUrl
     * @return Mapi\提交表单HTML文本
     */
    public function withholdingPay($aggrementNo, $outTradeNo, $totalFee, $returnUrl, $notifyUrl)
    {
        //构造要请求的参数数组，无需改动
        $data['service'] = 'alipay.acquire.createandpay';
        $data['partner'] = $this->parterId;
        $data['_input_charset'] = strtolower($this->postCharset);
        $data['return_url'] = $returnUrl;
        $data['notify_url'] = $notifyUrl;

        $data['out_trade_no'] = $outTradeNo;
        $data['subject'] = '签约并扣款测试';
        $data['product_code'] = 'GENERAL_WITHHOLDING';
        $data['total_fee'] = $totalFee;
        // $data['buyer_id'] = $buyerId;
        $data['agreement_info'] = json_encode(['agreement_no' => $aggrementNo]);

        $config = new Config();
        $alipaySubmit = new AlipaySubmit($config);
        $xmlstr = $alipaySubmit->buildRequestHttp($data);

        $alipayNotify = new AlipayNotify($config);
        $verify_result = $alipayNotify->verifySign($xmlstr);

        if ($verify_result) {
            //解析XML
            $resParameter = $alipayNotify->getRspFromXML($xmlstr);
            return $resParameter;
        }
        return null;
    }

    public function mapiVerify()
    {
        $config = new Config();
        //计算得出通知验证结果
        $alipayNotify = new AlipayNotify($config);
        $verify_result = $alipayNotify->verifyNotify();
        return $verify_result;
    }

    public function verify($data)
    {
        $result = $this->aopClient->rsaCheckV1($data, $this->aliPublicKey, $this->signType);
        return $result;
    }
}