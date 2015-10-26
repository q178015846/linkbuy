
      <div class="row">
        <div id="content" class="col-sm-12"><?php echo $content_top; ?>

      <?php if ($orders) { ?>
     <!--  <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <td class="text-right"><?php echo $column_order_id; ?></td>
              <td class="text-left"><?php echo $column_status; ?></td>
              <td class="text-left"><?php echo $column_date_added; ?></td>
              <td class="text-right"><?php echo $column_product; ?></td>
             
              <td class="text-right"><?php echo $column_total; ?></td>
              <td></td>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($orders as $order) { ?>
            <tr>
              <td class="text-right">#<?php echo $order['order_id']; ?></td>
              <td class="text-left"><?php echo $order['status']; ?></td>
              <td class="text-left"><?php echo $order['date_added']; ?></td>
              <td class="text-right"><?php echo $order['products']; ?></td>
             
              <td class="text-right"><?php echo $order['total']; ?></td>
              <td class="text-right"><?php if($order['status_id'] == 3){?><a href="<?php echo $order['express']; ?>" class="btn btn-info"><i class="fa fa-truck"></i></a ><?php }?>  <a href="<?php echo $order['href']; ?>" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i></a></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div> -->
       <?php foreach ($orders as $order) { ?>
        <div class="panel panel-default">
            <div class="panel-heading">订单编号：<?php echo $order['order_id']; ?><span style="float:right;"><?php echo $order['status']; ?></span></div>
             <ul class="list-group">
                 <?php foreach ($order['products'] as $product_info) { ?>
                  <li class="list-group-item">
                    <a href="<?php echo $product_info['product_link']; ?>" style="border:none;padding:0px;">
                    <div class="row">
                      <div class="col-xs-3 text-center">
                        
                          <img alt="64x64" data-src="holder.js/64x64" src="image/<?php echo $product_info['image']?>" style="width:64px;height:64px;margin:5px;">
                       
                      </div>
                      <div class="col-xs-6  text-left">
                        <p><?php echo $product_info['name']; ?></p>
                        <p>商品编号：<?php echo $product_info['model']; ?></p>

                      </div>
                      <div class="col-xs-3  text-right">
                        <ul style="margin-right:5px;" class="list-unstyled">
                          <li>￥ <?php echo $product_info['price']; ?></li>
                          <li>×<?php echo $product_info['quantity']; ?></li>
                        </ul>
                      </div>
                    </div>
                     </a>
                </li>
                <?php } ?>
              </ul>
            <div class="panel-footer text-right">
             
              <?php if($order['status_id'] == 3){?><a href="<?php echo $order['express']; ?>" class="btn btn-info">查看物流</a> <a href="<?php echo $order['confirm_order']; ?>" title="<?php echo $button_view; ?>" class="btn btn-success">确认收货</a><?php }else if($order['status_id'] == 5){?><a href="<?php echo $order['express']; ?>" class="btn btn-info">评价有送</a><?php } ?>  <a href="<?php echo $order['href']; ?>" title="<?php echo $button_view; ?>" class="btn btn-default">订单详情</a>
            </div>
          </div>
           <?php } ?>
      <div class="text-right"><?php echo $pagination; ?></div>
      <?php } else { ?>
      <p><?php echo $text_empty; ?></p>
      <?php } ?>
      <div class="buttons clearfix">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
      </div>
      </div>
       </div>
