<?php echo $header; ?>
<style type="text/css">
	.coupon_bg{
		background: rgba(0, 0, 0, 0) url('catalog/view/theme/optimus/images/coupon_bg.png') 326px 104px;
		width: 326px;
		height:104px;
		margin-bottom: 15px;
		margin-top: 30px;
	}

	.coupon_price{
		display: block;
		text-align: center;
		float: left;
		padding-top: 10px;
		padding-left: 60px;
		color: #E55111;
	}

	.coupon_price .price{
		font-size: 58px;
		font-weight: bold;
	}

	.coupon_price .type{
		font-size: 20px;
	}

	.rule-title-line{
		background-color:#EB706F;
		width: 100%;
		height: 3px;
		
	}
	.rule-title-content{
		font-size:16px;
		background-color:#EB706F;
		padding:5px 15px;
		vertical-align: middle;
		color: #fff;
	}


</style>
<body>

<div class="container">
<div class="panel panel-warning" style="margin-top:15px;">
  <div class="panel-body">
  	恭喜您获得x元红包，去分享红包，给您的小伙伴带去福利吧~
  </div>
</div>
<p class="text-center" style="margin:15px 0;color:red;font-size:16px;">红包可用于购买香薰，成功消费还能继续获得红包哦~！</p>
<div class="panel panel-warning" style="margin-top:15px;">
  <div class="panel-body img-rounded text-center" style="background-color:#EB706F;">
  	<div class="coupon_bg center-block">
  		<div class="coupon_price">
  			<span class="type">￥</span><span class="price">8</span>
  		</div>
  	</div>
  	<a href="" class="btn" style="background-color:#9A2727;color:#fff;width:326px;padding:15px;font-size:16px;">买香薰去</a>
  	<p style="margin-top:30px;color:#fff;font-size:18px;">*点击右上角分享红包</p>
  </div>
</div>
</div>
<div class="text-center">
	<hr>
	<span class="rule-title-content img-rounded">活动细则</span>
</div>


</body>
<script type="text/javascript">
wx.config({
    debug: false,
    appId: '<?php echo $signPackage["appId"];?>',
    timestamp: <?php echo $signPackage["timestamp"];?>,
    nonceStr: '<?php echo $signPackage["nonceStr"];?>',
    signature: '<?php echo $signPackage["signature"];?>',
    jsApiList: [
      // 所有要调用的 API 都要加到这个列表中
      'onMenuShareAppMessage',
      'onMenuShareTimeline'
    ]
  });

 wx.ready(function(){
    wx.onMenuShareAppMessage({
        title: '美国进口香氛', // 分享标题
        desc: '分享立刻获取10元优惠券', // 分享描述
        link: 'http://120.24.157.131/yankee/', // 分享链接
        imgUrl: 'http://120.24.157.131/yankee/image/catalog/logo.png', // 分享图标
        type: '', // 分享类型,music、video或link，不填默认为link
        dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
        success: function () { 
            // 用户确认分享后执行的回调函数
            alert("分享成功");
        },
        cancel: function () { 
            // 用户取消分享后执行的回调函数
        }
    });
    wx.onMenuShareTimeline({
        title: '美国进口香氛', // 分享标题
        link: 'http://120.24.157.131/yankee/', // 分享链接
        imgUrl: 'http://120.24.157.131/yankee/image/catalog/logo.png', // 分享图标
        success: function () { 
            // 用户确认分享后执行的回调函数
        },
        cancel: function () { 
            // 用户取消分享后执行的回调函数
        }
    });
  });
 </script>
</html>