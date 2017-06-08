<?php

namespace App\Controllers\Test;

use App\Utils\Log;
use Sms\Request\V20160927 as Sms;

class AliController extends Controller
{

    public function indexAction()
    {
        /** 接入alipay后台SDK */
        library('alipay/AopSdk.php');
        $c = new \AopClient();
        $c->gatewayUrl = "https://openapi.alipay.com/gateway.do";
        $c->appId = env("ALIPAY_APPID");
        $c->rsaPrivateKey = env('ALIPAY_APP_PRIVATE_KEY');
        // $c->signType = 'RSA2';
        $c->postCharset = 'UTF-8';
        $c->format = "json";
        $c->alipayrsaPublicKey = env('ALIPAY_ALI_PUBLIC_KEY');
        $req = new \AlipayTradeWapPayRequest();
        $data['out_trade_no'] = time();
        $data['total_amount'] = 0.01;
        $data['subject'] = 'test';
        $data['seller_id'] = env('ALIPAY_SELLERID');
        $data['product_code'] = 'QUICK_WAP_PAY';
        $bizContent = json_encode($data);
        $req->setBizContent($bizContent);
        $req->setNotifyUrl('https://demo.phalcon.lmx0536.cn/test/ali/notify');

        $form = $c->pageExecute($req);
        echo $form;
    }

    public function notifyAction()
    {
        $data = $this->request->get();
        Log::debug(json_encode($data));
    }

    public function checkSignAction()
    {
        library('alipay/AopSdk.php');
        $aop = new \AopClient();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = env('ALIPAY_APPID');
        $aop->rsaPrivateKey = env('ALIPAY_PRIKEY');
        $aop->alipayrsaPublicKey = env('ALIPAY_PUBKEY');
        $aop->apiVersion = '1.0';
        $aop->format = 'json';

        $request = new \MonitorHeartbeatSynRequest();
        $request->setBizContent("{任意值}");
        $result = $aop->execute($request);
        dump($result);
    }

    /**
     * @desc   支付宝内WAP获取用户信息
     * @author limx
     * @return mixed
     * @throws \Exception
     */
    public function infoAction()
    {
        $code = $this->request->get('auth_code');
        $appid = env('MONSTER_ALIPAY_APPID');
        $redirect_uri = env('MONSTER_ALIPAY_REDIRECT_URI');
        if (empty($code)) {
            // 获取code
            $url = 'https://openauth.alipay.com/oauth2/publicAppAuthorize.htm?';
            $params = [
                'app_id' => $appid,
                'scope' => 'auth_user',
                'redirect_uri' => $redirect_uri
            ];
            return $this->response->redirect($url . http_build_query($params));
        }
        library('alipay/AopSdk.php');
        $aop = new \AopClient();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = env('MONSTER_ALIPAY_APPID');
        $aop->rsaPrivateKey = env('MONSTER_ALIPAY_APP_PRIVATE_KEY');
        $aop->alipayrsaPublicKey = env('MONSTER_ALIPAY_ALI_PUBLIC_KEY');
        $aop->signType = 'RSA2';
        $aop->postCharset = 'UTF-8';
        $aop->apiVersion = '1.0';
        $aop->format = 'json';

        $request = new \AlipaySystemOauthTokenRequest();
        $request->setGrantType("authorization_code");
        $request->setCode($code);
        //        $request->setRefreshToken("201208134b203fe6c11548bcabd8da5bb087a83b");
        $result = $aop->execute($request);
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $user_id = $result->$responseNode->user_id;
        $access_token = $result->$responseNode->access_token;

        dump($result->$responseNode);
        $request = new \AlipayUserInfoShareRequest();
        $result = $aop->execute($request, $access_token);

        dump($result);

        dump("USERID" . $user_id);

    }

    /**
     * @desc   APP 获取用户信息
     * @author limx
     * @throws \Exception
     */
    public function userinfoAction()
    {
        $code = $this->request->get('auth_code');
        library('alipay/AopSdk.php');
        $aop = new \AopClient();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = env('ALIPAY_APPID');
        $aop->rsaPrivateKey = env('ALIPAY_PRIKEY');
        $aop->alipayrsaPublicKey = env('ALIPAY_PUBKEY');
        $aop->apiVersion = '1.0';
        $aop->format = 'json';

        $request = new \AlipaySystemOauthTokenRequest();
        $request->setGrantType("authorization_code");
        $request->setCode($code);
        //        $request->setRefreshToken("201208134b203fe6c11548bcabd8da5bb087a83b");
        $result = $aop->execute($request);
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $user_id = $result->$responseNode->user_id;
        $access_token = $result->$responseNode->access_token;

        dump($result->$responseNode);
        $request = new \AlipayUserUserinfoShareRequest();
        $result = $aop->execute($request, $access_token);

        dump($result);

    }

    public function echoAction()
    {
        $res = $this->request->get();
        dump($res);
    }

    /**
     * [smsAction desc]
     * @desc   下载阿里短信官方sdk
     * @author limx
     */
    public function smsAction()
    {
        library('alisms/aliyun-php-sdk-core/Config.php');

        $iClientProfile = \DefaultProfile::getProfile("cn-hangzhou", env('ALIYUN_ACCESS_KEY'), env('ALIYUN_ACCESS_SECRET'));
        $client = new \DefaultAcsClient($iClientProfile);
        $request = new Sms\SingleSendSmsRequest();
        $request->setSignName("祎昕测试");/*签名名称*/
        $request->setTemplateCode("SMS_33520803");/*模板code*/
        $request->setRecNum("13250874521");/*目标手机号*/
        $request->setParamString("{\"name\":\"李铭昕\"}");/*模板变量，数字一定要转换为字符串*/
        try {
            $response = $client->getAcsResponse($request);
            print_r($response);
        } catch (ClientException  $e) {
            print_r($e->getErrorCode());
            print_r($e->getErrorMessage());
        } catch (ServerException  $e) {
            print_r($e->getErrorCode());
            print_r($e->getErrorMessage());
        }
    }

    /**
     * [loginAction desc]
     * @desc   支付宝登录 签名
     * @author limx
     */
    public function loginAction()
    {
        library('alipay/AopSdk.php');
        $aop = new \AopClient();
        $aop->rsaPrivateKey = env('ALIPAY_PRIKEY');
        $aop->alipayrsaPublicKey = env('ALIPAY_PUBKEY');

        /** 待签名数组 */
        $data['apiname'] = 'com.alipay.account.auth';
        $data['method'] = 'alipay.open.auth.sdk.code.get';
        $data['app_id'] = env('ALIPAY_APPID');
        $data['app_name'] = 'mc';
        $data['biz_type'] = 'openservice';
        $data['pid'] = env('ALIPAY_PID');
        $data['product_id'] = 'APP_FAST_LOGIN';
        $data['scope'] = 'kuaijie';
        $data['target_id'] = uniqid();
        $data['auth_type'] = 'AUTHACCOUNT';
        $data['sign_type'] = 'RSA';
        $data['sign'] = urlencode($aop->rsaSign($data));

        return success($data);

    }

    public function alimobileAction()
    {
        return $this->view->render("test/ali", "alimobile");
    }

    // *********************** 开放搜索 v3.0 **************************

    private function openSearchClient()
    {
        library('OpenSearch/Autoloader/Autoloader.php');

        //替换为对应的access key id
        $accessKeyId = env('ALIYUN_ACCESS_KEY');
        //替换为对应的access secret
        $secret = env('ALIYUN_ACCESS_SECRET');
        //替换为对应区域api访问地址，可参考应用控制台,基本信息中api地址
        $endPoint = env("ALIYUN_OPENSEARCH_API");
        //替换为下拉提示名称
        $suggestName = '什么鬼';
        //开启调试模式
        $options = ['debug' => true];
        //创建OpenSearchClient客户端对象
        $client = new \OpenSearch\Client\OpenSearchClient($accessKeyId, $secret, $endPoint, $options);
        return $client;
    }

    public function openSearchUpdateAction()
    {
        //替换为应用名
        $appName = 'test_lbs';

        $client = $this->openSearchClient();
        //设置数据需推送到对应应用表中
        $tableName = 'test';
        //创建文档操作client
        $documentClient = new \OpenSearch\Client\DocumentClient($client);
        //添加数据
        $docs_to_upload = array();
        for ($i = 0; $i < 10; $i++) {
            $item = array();
            $item['cmd'] = 'ADD';
            $item["fields"] = array(
                "id" => rand(1, 1000),
                "name" => "搜索" . $i,
                "longitude" => 121 + floatval(rand(0, 10000)) / 10000,
                "latitude" => 31 + floatval(rand(0, 10000)) / 10000,
            );
            $docs_to_upload[] = $item;
        }
        //将文档编码成json格式
        $json = json_encode($docs_to_upload);
        //提交推送文档
        $ret = $documentClient->push($json, $appName, $tableName);
        dump($ret);
    }

    public function openSearchNameAction()
    {
        $client = $this->openSearchClient();
        $appName = 'test_lbs';

        // 实例化一个搜索类
        $searchClient = new \OpenSearch\Client\SearchClient($client);
        // 实例化一个搜索参数类
        $params = new \OpenSearch\Util\SearchParamsBuilder();
        //设置config子句的start值
        $params->setStart(0);
        //设置config子句的hit值
        $params->setHits(20);
        // 指定一个应用用于搜索
        $params->setAppName($appName);
        // 指定搜索关键词
        $params->setQuery("name:'搜索'");
        // 指定返回的搜索结果的格式为json
        $params->setFormat("fulljson");
        //添加排序字段
        $params->addSort('RANK', \OpenSearch\Util\SearchParamsBuilder::SORT_DECREASE);
        // 执行搜索，获取搜索结果
        $ret = $searchClient->execute($params->build())->result;
        dump(json_decode($ret, true));
    }

    public function openSearchNearAction()
    {
        $client = $this->openSearchClient();
        $appName = 'test_lbs';
        $longitude = 121.0;
        $latitude = 31.0;

        // 实例化一个搜索类
        $searchClient = new \OpenSearch\Client\SearchClient($client);
        // 实例化一个搜索参数类
        $params = new \OpenSearch\Util\SearchParamsBuilder();
        //设置config子句的start值
        $params->setStart(0);
        //设置config子句的hit值
        $params->setHits(20);
        // 指定一个应用用于搜索
        $params->setAppName($appName);
        // 指定搜索关键词
        $params->setQuery("name:'搜索'");
        $distance = sprintf('distance(longitude,latitude,"%s","%s")<100', $longitude, $latitude);
        $params->setFilter($distance);
        // 精排表达式
        $kvpairs = sprintf("longitude_input:%s,latitude_input:%s", $longitude, $latitude);
        $params->setKvPairs($kvpairs);
        // 指定返回的搜索结果的格式为json
        $params->setFormat("fulljson");
        //添加排序字段
        // $params->addSort($distance, \OpenSearch\Util\SearchParamsBuilder::SORT_INCREASE);
        // 执行搜索，获取搜索结果
        $ret = $searchClient->execute($params->build());
        dump($distance);
        dump($kvpairs);
        dump($ret);
        dump(json_decode($ret->result, true));
    }

    // *********************** 开放搜索 v2 **************************
    private function openSearchV2Client()
    {
        //替换为对应的access key id
        $accessKeyId = env('ALIYUN_ACCESS_KEY');
        //替换为对应的access secret
        $secret = env('ALIYUN_ACCESS_SECRET');
        //替换为对应区域api访问地址，可参考应用控制台,基本信息中api地址
        $host = env("ALIYUN_OPENSEARCH_API");

        $key_type = "aliyun";  //固定值，不必修改
        $opts = array('host' => $host);
        $client = new \App\Logics\OpenSearch\CloudsearchClient($accessKeyId, $secret, $opts, $key_type);
        return $client;
    }

    public function openSearchV2NearAction()
    {
        $client = $this->openSearchV2Client();
        $index_name = 'test_lbs';
        $longitude = 121.0;
        $latitude = 31.0;
        $query = "name:'搜索'";
        $fileter = sprintf('distance(longitude,latitude,"%s","%s")<100', $longitude, $latitude);
        $kvpairs = sprintf("longitude_input:%s,latitude_input:%s", $longitude, $latitude);

        $search = new \App\Logics\OpenSearch\CloudsearchSearch($client);
        $search->addIndex($index_name);
        $search->setQueryString($query);
        $search->addFilter($fileter);
        $search->setFormat('fulljson');
        $search->setFormulaName('distance2');
        $search->setPair($kvpairs);
        $search->setStartHit(0);
        $search->setHits(20);
        $ret = json_decode($search->search(), true);
        dump($ret);
        dump($ret['result']);
    }
}

