<?php echo $header; ?>


    <ul class="nav nav-tabs" style="margin-top:10px;">
  
    <li class="col-xs-3 text-center">
      <a href="#tab-all" data-toggle="tab">全部</a>
    </li>
    <li class="col-xs-3 text-center">
      <a href="#tab-wait" data-toggle="tab">待付款</a>
    </li>
   
    <li class="active col-xs-3 text-center">
     <a href="#tab-check" data-toggle="tab">待收货</a>
    </li>
    <li class="col-xs-3 text-center">
     <a href="#tab-comment" data-toggle="tab">待评价</a>
    </li>
  </ul>
   

<div id="myTabContent" class="tab-content" style="padding:0px;">
   <div class="tab-pane fade" id="tab-all">
      <div class="container" id="all">
      </div>
    </div>
  
   <div class="tab-pane fade" id="tab-wait">
      <div class="container" id="wait">
      </div>
   </div>
   <div class="tab-pane fade in active" id="tab-check">
      <div class="container" id="check">
      </div>
   </div>
   <div class="tab-pane fade" id="tab-comment">
      <div class="container" id="comment">
      </div>
   </div>
</div>
<script type="text/javascript">
$('#all').delegate('.pagination a', 'click', function(e) {
  e.preventDefault();

  $('#all').load(this.href);
});
$('#all').load('index.php?route=account/order/order_all');

$('#wai').delegate('.pagination a', 'click', function(e) {
  e.preventDefault();

  $('#wait').load(this.href);
});
$('#wait').load('index.php?route=account/order/order_all&order_status_id=15');

$('#check').delegate('.pagination a', 'click', function(e) {
  e.preventDefault();

  $('#check').load(this.href);
});
$('#check').load('index.php?route=account/order/order_all&order_status_id=3');

$('#comment').delegate('.pagination a', 'click', function(e) {
  e.preventDefault();

  $('#comment').load(this.href);
});
$('#comment').load('index.php?route=account/order/order_all&order_status_id=5');
</script>
<?php echo $footer; ?>