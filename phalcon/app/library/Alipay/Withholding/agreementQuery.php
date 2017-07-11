<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>支付宝个人协议查询接口</title>
</head>
<?php
/* *
 * 功能：支付宝二维码管理接口接入页
 * 版本：3.3
 * 修改日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。

 *************************注意*************************
 * 如果您在接口集成过程中遇到问题，可以按照下面的途径来解决
 * 1、商户服务中心（https://b.alipay.com/support/helperApply.htm?action=consultationApply），提交申请集成协助，我们会有专业的技术工程师主动联系您协助解决
 * 2、商户帮助中心（http://help.alipay.com/support/232511-16307/0-16307.htm?sh=Y&info_type=9）
 * 3、支付宝论坛（http://club.alipay.com/read-htm-tid-8681712.html）
 * 如果不想使用扩展功能请把扩展功能参数赋空值。
 */

require_once("alipay.config.php");
require_once("lib/alipay_submit.class.php");
require_once("lib/alipay_notify.class.php");
require_once("lib/qrcodeUtil.class.php");
require_once("lib/phpqrcode.class.php");

//构造要请求的参数数组，无需改动
	$parameter = array(
		"service" => "alipay.dut.customer.agreement.query",
		"partner" => trim($alipay_config['partner']),
		"_input_charset" => trim(strtolower($alipay_config['input_charset'])),
		"sign_type" => trim($alipay_config['sign_type']),
		"product_code"	=> "GENERAL_WITHHOLDING_P",
		
		//用户支付宝id（2088开头）alipay_user_id 和 用户支付宝账号（手机号或邮箱）alipay_logon_id不能同时为空，若两个都填写，取alipay_user_id的值
		//alipay_user_id 在签约回调notifyUrl中返回
		"alipay_user_id"	=> "2088xxxxxxxxxxxx",
		//"alipay_logon_id"	=> "xxxxxxxxxxxx",
		
		//协议产品场景scene 和 商户签约号external_sign_no可为空，如果填了external_sign_no，scene不能为空
		"scene"	=> "INDUSTRY|DIGITAL_MEDIA",
		"external_sign_no"	=> "2016032022522950418453-11-r"
	);

	//建立请求
	//注意：该功能PHP5环境及以上支持，需开通curl、SSL等PHP配置环境。建议本地调试时使用PHP开发软件
	$alipaySubmit = new AlipaySubmit($alipay_config);
	$xmlstr = $alipaySubmit->buildRequestHttp($parameter);
	echo $xmlstr;
	//计算得出返回xml验证结果
	$alipayNotify = new AlipayNotify($alipay_config);
	$verify_result = $alipayNotify->verifySign($xmlstr);

	if($verify_result) {//验证成功
			//解析XML
		$resParameter = $alipayNotify->getRspFromXML($xmlstr);
		$is_success = $resParameter["is_success"];
		//判断是否成功
		//result_code：
		//		ORDER_FAIL	下单失败
		//		ORDER_SUCCESS_PAY_SUCCESS	下单成功并且支付成功
		//		ORDER_SUCCESS_PAY_FAIL	下单成功支付失败
		//		ORDER_SUCCESS_PAY_INPROCESS	下单成功支付处理中
		if($is_success == "T" ){
			//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
			$agreement_no = $resParameter["agreement_no"];
			$external_sign_no = $resParameter["external_sign_no"];
		}else {
				//——请根据您的业务逻辑来编写程序——
		}
		
	}



?>
</body>
</html>