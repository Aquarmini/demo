<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>支付宝统一下单并支付页面接口接口</title>
</head>
<?php
/* *
 * 功能：统一下单并支付页面接口接入页
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

/**************************请求参数**************************/

        //商户订单号
        $out_trade_no = $_POST['out_trade_no'];
        //必填
        //订单名称
        $subject = $_POST['subject'];
        //必填
        //订单业务类型
        $product_code = "GENERAL_WITHHOLDING";
        //必填
        //订单金额
        $total_fee = $_POST['total_fee'];
        //必填
        //必填
		
		$return_url =  "http://liaojc.ngrok.sapronlee.com/createandpay_page/return_url.jsp";
		$notify_url =  "http://liaojc.ngrok.sapronlee.com/createandpay_page/notify_url.jsp";
		
		$request_from_url = "didipasnger://xxx";
		$integration_type = "ALIAPP";
		//"{\"productCode\":\"GENERAL_WITHHOLDING_P\",\"scene\":\"INDUSTRY|DIGITAL_MEDIA\",\"externalAgreementNo\":\""+out_trade_no+"_11"+"\",\"notifyUrl\":\"http://liaojc.ngrok.sapronlee.com/createandpay_page/notify_url.jsp\",\"signValidityPeriod\":\"12m\"}"
		//此处商家签约号externalAgreementNo填的是out_trade_no，商户可自己设定值
		$agreement_sign_parameters = "{\"productCode\":\"GENERAL_WITHHOLDING_P\",\"scene\":\"INDUSTRY|DIGITAL_MEDIA\",\"externalAgreementNo\":\"" . $out_trade_no  . "\",\"notifyUrl\":\"http://liaojc.ngrok.sapronlee.com/createandpay_page/notifyUrl.jsp\"}";


/************************************************************/

		//构造要请求的参数数组，无需改动
		$parameter = array(
				"service" => "alipay.acquire.page.createandpay",
				"partner" => trim($alipay_config['partner']),
				"out_trade_no"	=> $out_trade_no,
				"subject"	=> $subject,
				"product_code"	=> $product_code,
				"total_fee"	=> $total_fee,
				"seller_id"	=> trim($alipay_config['seller_id']),
				"_input_charset"	=> trim(strtolower($alipay_config['input_charset'])),
				"return_url"	=> $return_url,
				"notify_url"	=> $notify_url,
				"request_from_url"	=> $request_from_url,
				"integration_type"	=> $integration_type,
				"agreement_sign_parameters"	=> $agreement_sign_parameters
		);

		//建立请求
		$alipaySubmit = new AlipaySubmit($alipay_config);
		$html_text = $alipaySubmit->buildRequestForm($parameter);
		echo $html_text;

		$short_link_url = $alipaySubmit->getShortLink($html_text,trim($alipay_config['partner']));
		echo "</br>" .$short_link_url ."</br>";
		

		
	//方法一，利用php qrcode库生成二维码图片（带logo），并保存在本地img目录下	
	//推荐使用此方法
		$qrimg = 'img/qrcode.png';
		$alipaylogo = 'img/alipaylogo.png';
		QRcode::pngWithLogo($short_link_url,$qrimg ,$alipaylogo);
		echo '<img src="'.$qrimg.'" /></br></br>';
		
	//方法二，利用google api生成二维码图片，本地不保存，无logo
		echo QrcodeUtil::create_erweima($short_link_url);
?>
</body>
</html>