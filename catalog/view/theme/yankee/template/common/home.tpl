<?php echo $header; ?>
<div class="container">
  <div class="container">
    
    <?php foreach ($products as $k => $v) {?>
      <div class="panel panel-default">
      <div class="panel-body">
        <a href="#">
        <img src="http://www.beyankee.com/yankee/image/<?php echo $v['image']?>" class="img-responsive" alt="<?php echo $v['model'] ?>">
        </a>
        <div class="pull-left">
            <?php echo $v['model'] ?>
        </div>
         <div class="btn-toolbar pull-right">
            <div class="btn-group">
              <a class="btn btn-primary" href="#fakelink"><span class="fui-heart"></span></a>
              <a class="btn btn-primary" href="#fakelink"><span class="fui-export"></span></a>
            </div>
          </div>
      </div>
    </div>
    <?php }?>
    
  
     
</div>
</div>
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
<?php echo $footer; ?>