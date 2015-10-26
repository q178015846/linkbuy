<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
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
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
    	<div class="panel panel-primary">
		  <div class="panel-body">
		  	<div class="media">
			  <div class="pull-left">
			   <img class="media-object" src="image/express/yto.png" style="width:50px;height:50px;" alt="快递">
			  </div>
			  <div>
			    快递公司：<?php echo $shipping_order_company; ?><br>
			    物流状态：<?php if($shipping_array_data != null || isset($shipping_array_data)){?><?php echo $shipping_order_status; ?><?php }else{?>快递单号已过期或者不存在<?php }?><br>
		   		运单编号：<?php echo $shipping_order_code; ?>
			  </div>
			</div>
		   
		  </div>
		</div>
		<section class="panel panel-primary">
			 <!-- Default panel contents -->
		  	<div class="panel-heading">物流信息</div>
			 <?php if($shipping_array_data != null || isset($shipping_array_data)){?>
			<ul class="express_list_bg">
				<?php  foreach ($shipping_array_data as $v) { ?>
				<li>
					<?php $ftime = explode(" ", $v->ftime);?>
					<h3><?php echo $ftime[0]?>
						<span class="subtime"><?php echo $ftime[1]?></span>
					</h3>

					<dl><dt>
						<span><?php echo $v->context?></span>
					</dt></dl>
					
				</li>
				<?php }?>
			</ul>
			<?php }else{?>
		  		<div class="panel-body">
			    	暂无相关物流信息
			  	</div>
		  	<?php }?>
		</section>
     </div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
