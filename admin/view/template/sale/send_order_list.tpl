
<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<script type="text/javascript" src="view/javascript/jquery/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap.min.js"></script>
<link href="view/javascript/bootstrap/opencart/opencart.css" type="text/css" rel="stylesheet" />
<link href="view/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript"></script>
<link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
<script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
<script src="view/javascript/jquery/datetimepicker/moment.js" type="text/javascript"></script>
<script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
<link type="text/css" href="view/stylesheet/stylesheet.css" rel="stylesheet" media="screen" />

</head>
<body>
<div id="container">
<header id="header" class="navbar navbar-static-top">

</header>
<div id="content">
<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <caption class="text-center h2"><?php echo $title; ?><div style="float:right;margin-right:50px;"><a href="<?php echo $deliveryOrderList?>" class="btn btn-info">发送</a></div></caption>
    <thead>
      <tr>
        <td class="text-right">订单ID</td>
        <td class="text-left">会员</td>
        <td class="text-left">状态</td>
        <td class="text-right">单品小计</td>
        <td class="text-left">添加日期</td>
        <td class="text-left">修改日期</td>
        <td class="text-right">操作</td>
      </tr>
    </thead>
    <tbody>
      <?php if ($orders) { ?>
      <?php foreach ($orders as $order) { ?>
      <tr>
        <td class="text-right"><?php echo $order['order_id']; ?></td>
        <td class="text-left"><?php echo $order['customer']; ?></td>
        <td class="text-left"><?php echo $order['status']; ?></td>
        <td class="text-right"><?php echo $order['total']; ?></td>
        <td class="text-left"><?php echo $order['date_added']; ?></td>
        <td class="text-left"><?php echo $order['date_modified']; ?></td>
        <td class="text-right"><a href="<?php echo $order['deliveryMsg']; ?>" data-toggle="tooltip" title="发货通知" class="btn btn-info"><i class="fa fa-send"></i></a> <a href="<?php echo $order['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i></a> </td>
      </tr>
      <?php } ?>
      <?php } else { ?>
      <tr>
        <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<footer id="footer"></footer></div>
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
      'scanQRCode'
    ]
  });

  function scanQRCode (order_id) {
    // body...
     wx.scanQRCode({
        needResult: 1, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
        scanType: ["qrCode","barCode"], // 可以指定扫二维码还是一维码，默认二者都有
        success: function (res) {
        var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
        alert(result);
        },
        fail:function (res) {
            // body...
        }
      });
  }

  /*$("#scanQR65").click(function () {
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
  });*/
  
  
</script>
</html>