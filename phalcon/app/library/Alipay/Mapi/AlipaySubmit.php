<?php
// +----------------------------------------------------------------------
// | Submit.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Library\Alipay\Mapi;

class AlipaySubmit
{

    protected $alipay_config;
    /**
     *支付宝网关地址（新）
     */
    protected $alipay_gateway_new = 'https://mapi.alipay.com/gateway.do?';

    public function __construct($alipay_config)
    {
        $this->alipay_config = $alipay_config;
    }

    /**
     * 生成签名结果
     * @param $para_sort 已排序要签名的数组
     *                   return 签名结果字符串
     */
    public function buildRequestMysign($para_sort)
    {
        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = Core::createLinkstring($para_sort);

        $mysign = "";
        switch (strtoupper(trim($this->alipay_config['sign_type']))) {
            case "MD5" :
                $mysign = Md5::md5Sign($prestr, $this->alipay_config['key']);
                break;
            default :
                $mysign = "";
        }

        return $mysign;
    }

    /**
     * 生成要请求给支付宝的参数数组
     * @param $para_temp 请求前的参数数组
     * @return 要请求的参数数组
     */
    function buildRequestPara($para_temp)
    {
        //除去待签名参数数组中的空值和签名参数
        $para_filter = Core::paraFilter($para_temp);

        //对待签名参数数组排序
        $para_sort = Core::argSort($para_filter);

        //生成签名结果
        $mysign = $this->buildRequestMysign($para_sort);

        //签名结果与签名方式加入请求提交参数组中
        $para_sort['sign'] = $mysign;
        $para_sort['sign_type'] = strtoupper(trim($this->alipay_config['sign_type']));

        return $para_sort;
    }

    /**
     * 生成要请求给支付宝的参数数组
     * @param $para_temp 请求前的参数数组
     * @return 要请求的参数数组字符串
     */
    public function buildRequestParaToString($para_temp)
    {
        //待请求参数数组
        $para = $this->buildRequestPara($para_temp);

        //把参数组中所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串，并对字符串做urlencode编码
        $request_data = Core::createLinkstringUrlencode($para);

        return $request_data;
    }

    /**
     * 建立请求，以表单HTML形式构造（默认）
     * @param $para_temp   请求参数数组
     * @param $method      提交方式。两个值可选：post、get
     * @param $button_name 确认按钮显示文字
     * @return 提交表单HTML文本
     */
    public function buildRequestForm($para_temp)
    {
        //待请求参数数组
        $para = $this->buildRequestPara($para_temp);
        $sHtml = $this->alipay_gateway_new;
        while (list ($key, $val) = each($para)) {
            $sHtml .= $key . "=" . urlencode($val) . "&";
        }
        $sHtml = substr($sHtml, 0, strripos($sHtml, "&"));
        return $sHtml;
    }

    /**
     * 建立请求，以模拟远程HTTP的POST请求方式构造并获取支付宝的处理结果
     * @param $para_temp 请求参数数组
     * @return 支付宝处理结果
     */
    public function buildRequestHttp($para_temp)
    {
        $sResult = '';

        //待请求参数数组字符串
        $request_data = $this->buildRequestPara($para_temp);

        //远程获取数据
        $sResult = Core::getHttpResponsePOST($this->alipay_gateway_new, $this->alipay_config['cacert'], $request_data, trim(strtolower($this->alipay_config['input_charset'])));

        return $sResult;
    }

    /**
     * 建立请求，以模拟远程HTTP的POST请求方式构造并获取支付宝的处理结果，带文件上传功能
     * @param $para_temp      请求参数数组
     * @param $file_para_name 文件类型的参数名
     * @param $file_name      文件完整绝对路径
     * @return 支付宝返回处理结果
     */
    public function buildRequestHttpInFile($para_temp, $file_para_name, $file_name)
    {

        //待请求参数数组
        $para = $this->buildRequestPara($para_temp);
        $para[$file_para_name] = "@" . $file_name;

        //远程获取数据
        $sResult = Core::getHttpResponsePOST($this->alipay_gateway_new, $this->alipay_config['cacert'], $para, trim(strtolower($this->alipay_config['input_charset'])));

        return $sResult;
    }

    /**
     * 用于防钓鱼，调用接口query_timestamp来获取时间戳的处理函数
     * 注意：该功能PHP5环境及以上支持，因此必须服务器、本地电脑中装有支持DOMDocument、SSL的PHP配置环境。建议本地调试时使用PHP开发软件
     * return 时间戳字符串
     */
    public function query_timestamp()
    {
        $url = $this->alipay_gateway_new . "service=query_timestamp&partner=" . trim(strtolower($this->alipay_config['partner'])) . "&_input_charset=" . trim(strtolower($this->alipay_config['input_charset']));
        $encrypt_key = "";

        $doc = new \DOMDocument();
        $doc->load($url);
        $itemEncrypt_key = $doc->getElementsByTagName("encrypt_key");
        $encrypt_key = $itemEncrypt_key->item(0)->nodeValue;

        return $encrypt_key;
    }

    public function getShortLink($longlink, $partner)
    {
        date_default_timezone_set('PRC');
        // 把请求参数打包成数组
        $parameter = array(
            "service" => "alipay.mobile.short.link.apply",
            "partner" => $partner,
            "timestamp" => date('Y-m-d H:i:s'),
            "real_url" => $longlink,
            "open_way" => "WEB",
            "valid_time" => "30",
            "_input_charset" => "utf-8"
        );

        dump($parameter);
        //建立请求
        //注意：该功能PHP5环境及以上支持，需开通curl、SSL等PHP配置环境。建议本地调试时使用PHP开发软件
        $xmlstr = $this->buildRequestHttp($parameter);
        //计算得出返回xml验证结果
        dump($xmlstr);
        $alipayNotify = new AlipayNotify($this->alipay_config);
        $verify_result = $alipayNotify->verifySign($xmlstr);
        dump($verify_result);

        if ($verify_result) {//验证成功
            //解析XML
            $resParameter = $alipayNotify->getRspFromXML($xmlstr);
            //判断是否成功
            if ($resParameter["is_success"] == "T") {
                return $resParameter["short_link_url"];
                //暂停二维码成功
            }

        }
        return null;
    }
}