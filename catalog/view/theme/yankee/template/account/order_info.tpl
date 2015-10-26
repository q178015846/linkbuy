<?php echo $header; ?>
<body style="padding-bottom:50px;">
<nav class="navbar navbar-default navbar-static-top">
<div class="container">
  <a href="#" >
        <i class="fa fa-chevron-left"></i>
      </a>
</div>
</nav>
<div class="container">
 
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="row">
    <div id="content"  class="col-sm-12"><?php echo $content_top; ?>
      <div class="panel panel-default">
         <div class="panel-heading"><?php echo $text_order_detail; ?></div>
         <ul class="list-group">
            <li class="list-group-item">
           <div class="row">
            <div class="col-xs-6">
               <ul style="margin-right:5px;" class="list-unstyled">
                <li><b><?php echo $text_order_id; ?></b> #<?php echo $order_id; ?></li>
                <li><b><?php echo $text_date_added; ?></b> <?php echo $date_added; ?></li>
              </ul>
            </div>
            <div class="col-xs-6">
              <ul style="margin-right:5px;" class="list-unstyled">
                <li><?php if ($payment_method) { ?>
              <b><?php echo $text_payment_method; ?></b> <?php echo $payment_method; ?><br />
              <?php } ?></li>
                <li><?php if ($shipping_method) { ?>
              <b><?php echo $text_shipping_method; ?></b> <?php echo $shipping_method; ?>
              <?php } ?></li>
              </ul>
            </div>
           </div>
          </li>
          <li class="list-group-item">
             <ul style="margin-right:5px;" class="list-unstyled">
                <li><b><?php echo $text_shipping_address; ?></b></li>
                <li><?php echo $shipping_address; ?></li>
              </ul>
          </li>
          </ul>
      </div>
       <?php if ($histories) { ?>
       <div class="panel panel-default">
         <div class="panel-heading"><?php echo $text_history; ?></div>
          <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <td class="text-left"><?php echo $column_date_added; ?></td>
            <td class="text-left"><?php echo $column_status; ?></td>
            <td class="text-left"><?php echo $column_comment; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($histories as $history) { ?>
          <tr>
            <td class="text-left"><?php echo $history['date_added']; ?></td>
            <td class="text-left"><?php echo $history['status']; ?></td>
            <td class="text-left"><?php echo $history['comment']; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      </div>
      <?php } ?>

      
        <div class="panel panel-default">
            
             <ul class="list-group">
                   <?php foreach ($products as $product) { ?>
                  <li class="list-group-item">
                    <a href="#" style="border:none;padding:0px;">
                    <div class="row">
                      <div class="col-xs-3 text-center">
                        
                          <img alt="64x64" data-src="holder.js/64x64" src="image/<?php echo $product['image']?>" style="width:64px;height:64px;margin:5px;">
                       
                      </div>
                      <div class="col-xs-6  text-left">
                        <p><?php echo $product['name']; ?></p>
                        <p>商品编号：<?php echo $product['model']; ?></p>

                      </div>
                      <div class="col-xs-3  text-right">
                        <ul style="margin-right:5px;" class="list-unstyled">
                          <li>￥ <?php echo $product['price']; ?></li>
                          <li>×<?php echo $product['quantity']; ?></li>
                        </ul>
                      </div>
                    </div>
                     </a>
                </li>
               <?php } ?>
              </ul>
            <div class="panel-footer text-left">
             <?php foreach ($totals as $total) { ?>
              <b><?php echo $total['title']; ?></b><?php echo $total['text']; ?></br>
               <?php if ($products) { ?>
              
              <?php } ?>
            <?php } ?>
              
            </div>
          </div>
      
     
     
      <?php if ($comment) { ?>
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <td class="text-left"><?php echo $text_comment; ?></td>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-left"><?php echo $comment; ?></td>
          </tr>
        </tbody>
      </table>
      <?php } ?>
     </div>
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
      'onMenuShareTimeline',
      'onMenuShareAppMessage',
      'scanQRCode'
    ]
  });

  wx.ready(function(){
    wx.onMenuShareAppMessage({
        title: '晒单立返10元', // 分享标题
        desc: '我在xxx购买了xxx，宝贝很好用', // 分享描述
        link: 'http://www.beyankee.com/yankee/', // 分享链接
        imgUrl: 'http://120.24.157.131/yankee/image/catalog/logo.png', // 分享图标
        type: '', // 分享类型,music、video或link，不填默认为link
        dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
        success: function () { 
            // 用户确认分享后执行的回调函数
            $.ajax({
              url: 'index.php?route=account/transaction/addTransaction&customer_id=<?php echo $customer_id; ?>',
              type: 'post',
              dataType: 'html',
              data: 'description=' + encodeURIComponent("晒单立返10元") + '&amount=10',
              beforeSend: function() {
                
              },
              complete: function() {
                
              },
              success: function(html) {
               
              }
            });
        },
        cancel: function () { 
            // 用户取消分享后执行的回调函数
        }
    });
  });
 /* function sharetimeline () {
    // body...
    wx.scanQRCode({
        needResult: 0, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
        scanType: ["qrCode","barCode"], // 可以指定扫二维码还是一维码，默认二者都有
        success: function (res) {
        var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
        },
        fail:function (res) {
            // body...
        }
    });
  }*/
  
</script>
<footer class="navbar-fixed-bottom">
  <div class="container">
  <div class="row">
    <div class="col-xs-12">
   <ul class="list-inline text-right">
        <?php if($order_status == 3){ ?>
        <li><a href="<?php echo $express; ?>" class="btn-sm btn-default">查看物流</a></li>
        <li><button class="btn-sm btn-default" title="确认收货">确认收货</button></li>
        <?php }else if($order_status == 5) {?>
        <li><button class="btn-sm btn-default" title="#">追加评价</button></li>
        <?php }else{ ?>
        <li><a href="<?php echo $express; ?>" class="btn-sm btn-info">取消订单</a></li>
        <?php } ?>
      </ul>
    </div>
    </div>
  </div>
</footer>
</body></html>