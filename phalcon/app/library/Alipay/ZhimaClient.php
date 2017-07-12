<?php
// +----------------------------------------------------------------------
// | ZhimaClient.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Library\Alipay;

use ZmopClient;

class ZhimaClient
{
    public $appId;

    public $redirectUri;

    public $aliPublicKeyFile;

    public $appPrivateKeyFile;

    public $aliPublicKey;

    public $appPrivateKey;

    public $sellerId;

    public $parterId;

    protected $gatewayUrl = 'https://zmopenapi.zmxy.com.cn/openapi.do';

    protected $signType = 'RSA';

    protected $postCharset = 'UTF-8';

    protected $apiVersion = '1.0';

    protected $format = 'json';

    protected $client;

    public static $instances;


    public function __construct()
    {
        $this->appId = env("MONSTER_ZHIMA_APPID");
        $this->aliPublicKeyFile = env('MONSTER_ZHIMA_ALI_PUBLIC_KEY');
        $this->appPrivateKeyFile = env('MONSTER_ZHIMA_APP_PRIVATE_KEY');
        $this->aliPublicKey = env('MONSTER_ZHIMA_ALI_PUBLIC_KEY');
        $this->appPrivateKey = env('MONSTER_ZHIMA_APP_PRIVATE_KEY');

        include_once __DIR__ . '/ZmopSdk.php';

        $this->client = $this->getZmopClient();
    }

    public static function getInstance()
    {
        if (!isset(self::$instances) || !(self::$instances instanceof AlipayClient)) {
            self::$instances = new ZhimaClient();
        }
        return self::$instances;
    }

    public function getZmopClient()
    {
        $client = new ZmopClient(
            $this->gatewayUrl,
            $this->appId,
            $this->postCharset,
            $this->appPrivateKeyFile,
            $this->aliPublicKeyFile
        );

        return $client;
    }

    public function getAuthInfoByMobile($mobile)
    {
        $request = new \ZhimaAuthInfoAuthorizeRequest();
        $request->setChannel("apppc");
        $request->setPlatform("zmop");
        $request->setIdentityType("1");// 必要参数
        $request->setIdentityParam(json_encode([
            'mobileNo' => $mobile
        ]));
        $request->setBizParams(json_encode([
            'auth_code' => 'M_H5',
            'channelType' => 'app',
        ]));
        $url = $this->client->generatePageRedirectInvokeUrl($request);
        return $url;
    }

    public function getAuthInfoResult($params, $sign)
    {
        // 判断串中是否有%，有则需要decode
        $params = strstr($params, '%') ? urldecode($params) : $params;
        $sign = strstr($sign, '%') ? urldecode($sign) : $sign;

        $result = $this->client->decryptAndVerifySign($params, $sign);
        return $result;
    }

    public function getCreditScore($openId)
    {
        $num = rand(1, 9999999999999);
        $transaction_id = date("YmdHis") . round(microtime() * 1000) . str_pad($num, 13, '0', STR_PAD_LEFT);

        $request = new \ZhimaCreditScoreGetRequest();
        $request->setChannel("apppc");
        $request->setPlatform("zmop");
        $request->setTransactionId($transaction_id);// 必要参数
        $request->setProductCode("w1010100100000000001");// 必要参数
        $request->setOpenId($openId);// 必要参数
        $response = $this->client->execute($request);
        return $response;
    }

}