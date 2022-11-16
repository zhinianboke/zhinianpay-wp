<?php
/*
Plugin Name: 执念付费阅读
Plugin URI: https://github.com/zhinianboke/zhinianpay-wp
Description: 实现wordprwss文章的付费阅读功能
Version: 1.0.0
Author: 执念博客
Author URI: https://zhinianboke.com
Text Domain: zhinianpay-wp
*/
if ( ! defined( 'zhinianpay_wp_PLUGIN_FILE' ) ) {
	define( 'zhinianpay_wp_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'zhinianpay_wp_PLUGIN_PATH' ) ) {
	define( 'zhinianpay_wp_PLUGIN_PATH', plugin_dir_path( zhinianpay_wp_PLUGIN_FILE ) );
}

if ( ! defined( 'zhinianpay_wp_PLUGIN_URL' ) ) {
	define( 'zhinianpay_wp_PLUGIN_URL', plugin_dir_url( zhinianpay_wp_PLUGIN_FILE ) );
}
define('zhinianpay_wp_VERSION', '1.0.0');



/**
 * 加载enlighter js
 */
function zhinianpay_wp_run() {
    wp_enqueue_style( 'ZhinianPay', plugins_url('/css/ZhinianPay.css', __FILE__ ), false, zhinianpay_wp_VERSION );
    wp_enqueue_script( 'ZhinianPay', plugins_url('/js/ZhinianPay.js', __FILE__ ), false, zhinianpay_wp_VERSION );
    
}
add_action('wp_enqueue_scripts',  'zhinianpay_wp_run' );

add_action('admin_menu','pwtw_submit_menu');
function pwtw_submit_menu() {
    add_submenu_page('options-general.php','付费阅读设置','付费阅读设置','manage_options','zhinianpay','pwtw_submit_options');
}

function pwtw_submit_options() {
    $formData = $_REQUEST;
    $options = get_option( 'zhinianpay_option');
    if(isset($options) && isset($formData['ffyd_qq'])) {
        update_option('zhinianpay_option', $formData);
    }
    if(!isset($options) && isset($formData['ffyd_qq'])) {
        add_option('zhinianpay_option', $formData);
    }
    if(isset($formData['ffyd_qq'])) {
        $options = $formData;
    }
    $cookietime = $options['ffyd_cookietime'];
    if(empty($cookietime)) {
        $cookietime = 1;
    }
    
    echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';
        echo '<h1>付费阅读设置</h1>';
        echo '<form action="" method=post>';
            echo '<p><label>授权码(只需3元-申请地址：<a href="https://dy.zhinianboke.com" target="_BLANK">https://dy.zhinianboke.com</a><br>
        <span style="color:red;">下面的支付宝和微信配置不填写也可以使用，不过要到 https://dy.zhinianboke.com 的支付管理菜单中进行提现，请知悉<br/>不填写也可以自行到账，填写下方易支付或者是码支付商户id和秘钥即可</span><br>
        )：<br/></label><input name="ffyd_shouquanma" value="'.$options['ffyd_shouquanma'].'"/></p>';
            echo '<p><label>QQ号码：<br/></label><input name="ffyd_qq" value="'.$options['ffyd_qq'].'"/></p>';
            echo '<p><label>免登录Cookie保存时间，即付费后几天内可以重复阅读(天)：<br/>付费后多长时间内可以重复阅读，默认为1天<br/></label><input name="ffyd_cookietime" value="'.$cookietime.'"/></p>';
            
            echo '<p><label>是否开启支付宝支付：<br/></label></p>';
            echo '<select name="ffyd_zhifubao_zhifu">';
		    echo '<option value=""' . ($options['ffyd_zhifubao_zhifu'] == '' ? ' selected="selected"' : '') . '>关闭(默认)</option>';
	    	echo '<option value="01"' . ($options['ffyd_zhifubao_zhifu'] == '01' ? ' selected="selected"' : '') . '>开启</option>';
	    	echo '</select>';
	    	
	    	echo '<p><label>是否开启微信支付：<br/></label></p>';
            echo '<select name="ffyd_weixin_zhifu">';
		    echo '<option value=""' . ($options['ffyd_weixin_zhifu'] == '' ? ' selected="selected"' : '') . '>关闭(默认)</option>';
	    	echo '<option value="01"' . ($options['ffyd_weixin_zhifu'] == '01' ? ' selected="selected"' : '') . '>开启</option>';
	    	echo '</select>';
	    	
	    	echo '<p><label>是否开启QQ支付：<br/></label></p>';
            echo '<select name="ffyd_qq_zhifu">';
		    echo '<option value=""' . ($options['ffyd_qq_zhifu'] == '' ? ' selected="selected"' : '') . '>关闭(默认)</option>';
	    	echo '<option value="01"' . ($options['ffyd_qq_zhifu'] == '01' ? ' selected="selected"' : '') . '>开启</option>';
	    	echo '</select>';
            
			echo '<p><label>支付宝当面付appid：<br/></label><input name="ffyd_zhifubaodangmianfu_appid" value="'.$options['ffyd_zhifubaodangmianfu_appid'].'"/></p>';
            echo '<p><label>支付宝应用appid：<br/></label><input name="ffyd_zhifubao_appid" value="'.$options['ffyd_zhifubao_appid'].'"/></p>';
            echo '<p><label>支付宝应用私钥：<br/></label><input name="ffyd_zhifubao_private_key" value="'.$options['ffyd_zhifubao_private_key'].'"/></p>';
            echo '<p><label>支付宝公钥：<br/></label><input name="ffyd_zhifubao_public_key" value="'.$options['ffyd_zhifubao_public_key'].'"/></p>';
            
            echo '<p><label>微信公众号appid：<br/></label><input name="ffyd_weixin_appid" value="'.$options['ffyd_weixin_appid'].'"/></p>';
            echo '<p><label>微信商户号：<br/></label><input name="ffyd_weixin_mchId" value="'.$options['ffyd_weixin_mchId'].'"/></p>';
            echo '<p><label>微信商户密钥：<br/></label><input name="ffyd_weixin_mchKey" value="'.$options['ffyd_weixin_mchKey'].'"/></p>';
            
            echo '<p><label>易支付API接口支付地址：<br/>介绍：填写对应易支付网站中的API接口支付地址,注意后面有 mapi.php <br>
		例如：https://suyan.qqdsw8.cn/mapi.php <br/></label><input name="ffyd_yizhifu_interfUrl" value="'.$options['ffyd_yizhifu_interfUrl'].'"/></p>';
            echo '<p><label>易支付商户ID：<br/>介绍：申请地址如下 <a href="https://suyan.qqdsw8.cn/user/reg.php" target="_BLANK">https://suyan.qqdsw8.cn/user/reg.php</a> <br/>
		<span style="color:red;">本站不对该地址资金结算做保证，只是提供一个渠道</span><br/></label><input name="ffyd_yizhifu_pid" value="'.$options['ffyd_yizhifu_pid'].'"/></p>';
            echo '<p><label>易支付商户密钥：<br/></label><input name="ffyd_yizhifu_miyao" value="'.$options['ffyd_yizhifu_miyao'].'"/></p>';
            
            echo '<p><label>码支付API接口支付地址：<br/>介绍：填写对应码支付网站中的支付请求支付地址,注意后面可能有 submit.php <br>
		例如：https://pay.ococn.cn/submit.php<br/></label><input name="ffyd_mazhifu_interfUrl" value="'.$options['ffyd_mazhifu_interfUrl'].'"/></p>';
            echo '<p><label>码支付商户ID：<br/>介绍：申请地址如下 <a href="https://pay.ococn.cn/User/Login.php?invite_user=199451637" target="_BLANK">https://pay.ococn.cn/User/Login.php?invite_user=199451637</a> <br/>
		<span style="color:red;">本站不对该地址资金结算做保证，只是提供一个渠道</span><br/></label><input name="ffyd_mazhifu_pid" value="'.$options['ffyd_mazhifu_pid'].'"/></p>';
            echo '<p><label>码支付商户密钥：<br/></label><input name="ffyd_mazhifu_miyao" value="'.$options['ffyd_mazhifu_miyao'].'"/></p>';
            echo '<p><input class="button-primary" type="submit" name="Submit" value="保存更改" /></p>';
        echo '</form>';
        
        
    echo '</div>';
}

function A2A_SHARE_SAVE_add_to_content1( $content ) {
    $zhiniantempContent = $content;
    $zhiniantempContent = preg_replace('/{ZhinianPay[^}]*}/', 'ZhinianPayStart', $zhiniantempContent);
    $zhiniantempContent = preg_replace('/{\/ZhinianPay}/', 'ZhinianPayEnd', $zhiniantempContent);
    
    $start = 'ZhinianPayStart';
    $end = 'ZhinianPayEnd';
    
    $hideContent = substr($zhiniantempContent, strlen($start)+strpos($zhiniantempContent, $start),(strlen($zhiniantempContent) - strpos($zhiniantempContent, $end))*(-1));
	
	$start = 'money=';
    $end = '}';
    $input = $content;
    $money = substr($input, strlen($start)+strpos($input, $start), strpos($input, '}', strpos($input, 'money='))-(strlen($start)+strpos($input, $start)));
    $options = get_option( 'zhinianpay_option');
    
    // 获取支付参数
	
    $dangmianfuAppid = $options['ffyd_zhifubaodangmianfu_appid'];
    $alipay_appid = $options['ffyd_zhifubao_appid'];
    $app_private_key = $options['ffyd_zhifubao_private_key'];
    $alipay_public_key = $options['ffyd_zhifubao_public_key'];
    
    $appId = $options['ffyd_weixin_appid'];
    $mchId = $options['ffyd_weixin_mchId'];
    $mchKey = $options['ffyd_weixin_mchKey'];
    
    $yizhif_interfUrl = $options['ffyd_yizhifu_interfUrl'];
    $yizhifu_pid = $options['ffyd_yizhifu_pid'];
    $yizhifu_miyao = $options['ffyd_yizhifu_miyao'];
    
    $mazhifu_interfUrl = $options['ffyd_mazhifu_interfUrl'];
    $mazhifu_pid = $options['ffyd_mazhifu_pid'];
    $mazhifu_miyao = $options['ffyd_mazhifu_miyao'];
    
    
    $alipay = $options['ffyd_zhifubao_zhifu'];
    $wxpay = $options['ffyd_weixin_zhifu'];
    $qqpay = $options['ffyd_qq_zhifu'];
    
    $qqNum = $options['ffyd_qq'];
    $cardId = $options['ffyd_shouquanma'];
    $cookietime = $options['ffyd_cookietime'];
    if(empty($cookietime)) {
        $cookietime = 1;
    }
    
    $returnUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    
    $cid = $returnUrl;
    $str = $cid;
    $new = '';
    if ($str[strlen($str) - 1] != '0') {
        for ($i = 0; $i < strlen($str); ++$i) {
            $new .= chr(ord('a') + intval($str[$i]) - 1);
        }
    }
    $cookieName =  'ZhinianPayCookie'.$new;
    if(!isset($_COOKIE[$cookieName])) {
		$randomCode = md5(uniqid(microtime(true),true));
		setcookie($cookieName, $randomCode, time()+3600*24*$cookietime);
	}
	$bussId = $_COOKIE[$cookieName];
    
    $form = '<form style="display:none;" target="_blank" action="https://dy.zhinianboke.com/pay/zhifu/ZhiFu001/init" method="post" id="subscribe_form"><input type="hidden" name="qqNum" value="'.$qqNum.'"><input type="hidden" name="alipay" value="'.$alipay.'"><input type="hidden" name="wxpay" value="'.$wxpay.'"><input type="hidden" name="qqpay" value="'.$qqpay.'"><input type="hidden" name="appId" value="'.$appId.'"><input type="hidden" name="mchId" value="'.$mchId.'"><input type="hidden" name="mchKey" value="'.$mchKey.'"><input type="hidden" id="ZhinianPay_cardId" name="cardId" value="'.$cardId.'"><input type="hidden" id="ZhinianPay_cookietime" value="'.$cookietime.'"><input type="hidden" name="orderName" value="文章付费阅读"><input type="hidden" id="ZhinianPay_cookieName" value="'.$cookieName.'"><input type="hidden" id="ZhinianPay_bussId" name="bussId" value="'.$bussId.'"><input type="hidden" name="orderDes" value="文章付费阅读"><input type="hidden" name="dangmianfuAppid" value="'.$dangmianfuAppid.'"><input type="hidden" name="alipayAppid" value="'.$alipay_appid.'"><input type="hidden" name="alipayAppPrivateKey" value="'.$app_private_key.'"><input type="hidden" name="alipayPublicKey" value="'.$alipay_public_key.'"><input type="hidden" id="ZhinianPay_orderFee" name="orderFee" value="'.$money.'"><input type="hidden" name="returnUrl" value="'.$returnUrl.'"><input type="hidden" name="interfUrl" value="'.$yizhif_interfUrl.'"><input type="hidden" name="pid" value="'.$yizhifu_pid.'"><input type="hidden" name="miyao" value="'.$yizhifu_miyao.'"><input type="hidden" name="mazhifuInterfUrl" value="'.$mazhifu_interfUrl.'"><input type="hidden" name="mazhifuPid" value="'.$mazhifu_pid.'"><input type="hidden" name="mazhifuMiyao" value="'.$mazhifu_miyao.'"><input type="submit" value="" id="submit"></form>';
    
    
    $replaceEnd = '<div id="zhinianpay_content" style="display: none;">'.$hideContent.'</div>';
    $replaceEnd = $replaceEnd . '<span id="zhinian_hide">此处内容作者设置了 <i id="zhinian_hide__button">付费'.$money . ' 元(点击此处支付，付费后请刷新界面) </i>可见，付费后 '. $cookietime . ' 天内有效</span>'.$form;
    $content = preg_replace('/{ZhinianPay[^}]*}([\s\S]*?){\/ZhinianPay}/', $replaceEnd, $content);
	return $content;
}


function A2A_SHARE_SAVE_pre_get_posts1( $query ) {
		add_filter( 'the_content', 'A2A_SHARE_SAVE_add_to_content1' );
}

add_action( 'pre_get_posts', 'A2A_SHARE_SAVE_pre_get_posts1' );