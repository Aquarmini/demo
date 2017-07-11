<?php
/* *
 * QrcodeUtil
 * 功能： 把string生成二维码图片
 */

class QrcodeUtil {

	//1.封装生成二维码图片的函数（方法）
	 /** *利用google api生成二维码图片
	 * $content：二维码内容参数
	 * $size：生成二维码的尺寸，宽度和高度的值
	 * $lev：可选参数，纠错等级
	 *               L-默认：可以识别已损失的7%的数据
	 *               M-可以识别已损失15%的数据
	 *               Q-可以识别已损失25%的数据
	 *               H-可以识别已损失30%的数据
	 * $margin：生成的二维码离边框的距离
	 */
	public static function create_erweima($content) {
		
		 $size = '250';
		 $lev = 'L';
		 $margin= '0';
		$content = urlencode($content);
		$image = '<img src="http://chart.apis.google.com/chart?chs='.$size.'x'.$size.
			'&amp;cht=qr&chld='.$lev.'|'.$margin.'&amp;chl='.$content.'"  widht="'.$size.'" height="'.$size.'" />';
		return $image;
	}
}
?>