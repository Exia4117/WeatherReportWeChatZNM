<?php
require_once "jssdk.php";
$jssdk = new JSSDK("wx9057905bce2a4dd1", "1dd056eb578c1f786999f4c529688aa0");
$signPackage = $jssdk->GetSignPackage();
?>
<!DOCTYPE html>
<html>
<head>
 <title>天气预报</title>
 <meta charset="UTF-8">
<meta name=viewport content="width=device-width, user-scalable=no, initial-scale=1">
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css" />
    <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>>
</head>
<body>
	<div data-role="page" id="page1">
        <div data-role="content">
            <div data-role="collapsible" data-theme="b" data-content-theme="d" data-collapsed-icon="arrow-d" data-expanded-icon="arrow-u">
                <h4><span id="city"></span>天气</h4>
                <ul data-role="listview" data-inset="false">
		    <li><span id="update_time"></span>发布</li>
                    <li>当前气温：<span id="wendu"></span>℃</li>
                    <li>天气：<span id="type"></span></li>
                    <li>湿度：<span id="shidu"></span></li>
                    <li>风向：<span id="fx"></span></li>
                    <li>最高气温：<span id="high1"></span></li>
                    <li>最低气温：<span id="low1"></span></li>
                </ul>
            </div>
        </div>
    </div>
</body>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
  /*
   * 注意：
   * 1. 所有的JS接口只能在公众号绑定的域名下调用，公众号开发者需要先登录微信公众平台进入“公众号设置”的“功能设置”里填写“JS接口安全域名”。
   * 2. 如果发现在 Android 不能分享自定义内容，请到官网下载最新的包覆盖安装，Android 自定义分享接口需升级至 6.0.2.58 版本及以上。
   * 3. 常见问题及完整 JS-SDK 文档地址：http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html
   *
   * 开发中遇到问题详见文档“附录5-常见错误及解决办法”解决，如仍未能解决可通过以下渠道反馈：
   * 邮箱地址：weixin-open@qq.com
   * 邮件主题：【微信JS-SDK反馈】具体问题
   * 邮件内容说明：用简明的语言描述问题所在，并交代清楚遇到该问题的场景，可附上截屏图片，微信团队会尽快处理你的反馈。
   */
  wx.config({
    debug: true,
    appId: '<?php echo $signPackage["appId"];?>',
    timestamp: <?php echo $signPackage["timestamp"];?>,
    nonceStr: '<?php echo $signPackage["nonceStr"];?>',
    signature: '<?php echo $signPackage["signature"];?>',
    jsApiList: [
      // 所有要调用的 API 都要加到这个列表中
	//'scanQRCode'
	'getLocation'
    ]
  });
  wx.ready(function () {
    // 在这里调用 API
//	wx.scanQRCode({
//	needResult: 0, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
//	scanType: ["qrCode","barCode"], // 可以指定扫二维码还是一维码，默认二者都有
//	success: function (res) {
//	var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
//	}
//	});
	wx.getLocation({
	type: 'wgs84', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
	success: function (res) {
	var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
	var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
	var speed = res.speed; // 速度，以米/每秒计
	var accuracy = res.accuracy; // 位置精度
	$.ajax({
                type: 'post',
                url: 'http://154.8.210.161/weixin/Ajax/read',
                data: { latitude: latitude, longitude: longitude },
                dataType: 'json',
                success: function(data) {
                    if (data.status == 0) {
                        alert(data.msg);
                    } else {
                        $("#city").text(data.city);
                        $("#update_time").text(data.update_time);
                        $("#shidu").text(data.shidu);
                        $("#wendu").text(data.wendu);
                        $("#high1").text(data.high1);
                        $("#low1").text(data.low1);
                        $("#fx").text(data.fx);
                        $("#type").text(data.type);
                    }
                },
                error: function() {
                    alert("程序异常");
                }
            });
	}
	});
  });
</script>
</html>
